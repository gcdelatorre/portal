<?php
session_start();

// Check if user is logged in and is admin
if (!isset($_SESSION['account_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../portal/login.php");
    exit();
}

include __DIR__ . '/../database.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id <= 0) {
    die('Invalid id');
}

$stmt = $conn->prepare("SELECT renewal_id, scholar_id, name, certificate_of_birth, certificate_of_indigency, message FROM renewals WHERE renewal_id = ?");
$stmt->bind_param('i', $id);
$stmt->execute();
$res = $stmt->get_result();
if (!$res || $res->num_rows === 0) {
    die('Not found');
}
$row = $res->fetch_assoc();
$stmt->close();
?>
<!DOCTYPE html>
<html>
<head>
    <title>View Renewal #<?php echo htmlspecialchars($row['renewal_id']); ?></title>
    <link rel="stylesheet" href="../styles.css">
    <style>
        .renewal-card{background:#f9f9f9;padding:20px;border-radius:8px;margin:20px 0;border:1px solid #e5e7eb;box-shadow:0 2px 4px rgba(0,0,0,0.05)}
        .renewal-card img{max-width:100%;height:auto;border-radius:6px;display:block;margin:12px 0}
        .renewal-card h3{margin:16px 0 8px 0;color:#111;font-size:16px}
        .renewal-card p{margin:8px 0;line-height:1.5}
        .back-link{display:inline-block;margin-top:20px;padding:8px 12px;text-decoration:none;color:var(--accent);border:1px solid var(--accent);border-radius:6px;transition:all .2s}
        .back-link:hover{background:var(--accent);color:#fff}
        .renewal-header{border-bottom:2px solid #e5e7eb;padding-bottom:12px;margin-bottom:20px}
        .renewal-header h2{margin:0 0 4px 0}
        .renewal-header .meta{font-size:14px;color:var(--muted)}
    </style>
</head>
<body>
    <?php include 'header.php'; ?>
    <div class="container">
        <div class="renewal-header">
            <h2>Renewal #<?php echo htmlspecialchars($row['renewal_id']); ?></h2>
            <div class="meta">Scholar: <strong><?php echo htmlspecialchars($row['name']); ?></strong> | Scholar ID: <strong><?php echo htmlspecialchars($row['scholar_id']); ?></strong></div>
        </div>

        <?php if ($row['message']): ?>
        <div class="renewal-card">
            <h3>Message</h3>
            <p><?php echo nl2br(htmlspecialchars($row['message'])); ?></p>
        </div>
        <?php endif; ?>

        <div class="renewal-card">
            <h3>📋 Certificate of Birth</h3>
            <img src="serve_file.php?id=<?php echo $row['renewal_id']; ?>&amp;type=birth" alt="Certificate of Birth">
            <p><a href="serve_file.php?id=<?php echo $row['renewal_id']; ?>&amp;type=birth" target="_blank" class="btn">Open full size</a></p>
        </div>

        <div class="renewal-card">
            <h3>📋 Certificate of Indigency</h3>
            <img src="serve_file.php?id=<?php echo $row['renewal_id']; ?>&amp;type=indigency" alt="Certificate of Indigency">
            <p><a href="serve_file.php?id=<?php echo $row['renewal_id']; ?>&amp;type=indigency" target="_blank" class="btn">Open full size</a></p>
        </div>

        <a href="renewals.php" class="back-link">← Back to Renewals</a>
    </div>

<?php
session_start();

// Check if user is logged in and is scholar
if (!isset($_SESSION['account_id']) || $_SESSION['role'] !== 'scholar') {
    header("Location: ../portal/login.php");
    exit();
}

include("../database.php");

if($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["submit_renewal"])) {

        $message = mysqli_real_escape_string($conn, $_POST['message']);
        $scholar_id = $_SESSION['scholar_id'] ?? 0;

            // Make sure user is logged in
        $account_id = $_SESSION['account_id'] ?? 0;
        if ($account_id == 0) {
            die("You must be logged in.");
        }

        // Fetch the name from scholars table
        // Use a proper prepared statement with a placeholder
        $sql_name = "SELECT name FROM scholars WHERE scholar_id = ?";
        $stmt_name = $conn->prepare($sql_name);
        if (!$stmt_name) {
            die("Prepare failed: " . htmlspecialchars(mysqli_error($conn)));
        }
        $stmt_name->bind_param("i", $scholar_id);
        $stmt_name->execute();
        $result_name = $stmt_name->get_result();

        if ($result_name && $result_name->num_rows > 0) {
            $row = $result_name->fetch_assoc();
            $name = $row['name'];
        } else {
            $stmt_name->close();
            die("No scholar found for this account.");
        }
        $stmt_name->close();

        // File upload
        $upload_dir = "../uploads/renewals/";
        if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);

        $birth_file = $_FILES['certificate_of_birth']['name'];
        $indigency_file = $_FILES['certificate_of_indigency']['name'];

        $birth_path = $upload_dir . basename($birth_file);
        $indigency_path = $upload_dir . basename($indigency_file);

        // Save uploads with a unique prefix to avoid collisions and check success
        $birth_tmp = $_FILES['certificate_of_birth']['tmp_name'] ?? '';
        $indigency_tmp = $_FILES['certificate_of_indigency']['tmp_name'] ?? '';

        $birth_saved = false;
        $indigency_saved = false;

        if ($birth_tmp && is_uploaded_file($birth_tmp)) {
            $birth_path = $upload_dir . time() . '_birth_' . basename($birth_file);
            $birth_saved = move_uploaded_file($birth_tmp, $birth_path);
        }

        if ($indigency_tmp && is_uploaded_file($indigency_tmp)) {
            $indigency_path = $upload_dir . time() . '_indigency_' . basename($indigency_file);
            $indigency_saved = move_uploaded_file($indigency_tmp, $indigency_path);
        }

        // Prepare insert using placeholders to avoid quoting issues
        $sql = "INSERT INTO renewals (account_id, scholar_id, name, certificate_of_birth, certificate_of_indigency, message) 
                VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            die("Prepare failed: " . htmlspecialchars(mysqli_error($conn)));
        }

        // Bind parameters: account_id (i), scholar_id (i), name (s), birth path (s), indigency path (s), message (s)
        $stmt->bind_param('iissss', $account_id, $scholar_id, $name, $birth_path, $indigency_path, $message);
        $ok = $stmt->execute();
        if ($ok) {
            echo "Renewal submitted successfully!";
        } else {
            echo "Error: " . htmlspecialchars($stmt->error);
        }
        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Renewal Submission</title>
    <link rel="stylesheet" href="../styles.css">
    <style>
        .form-group{margin-bottom:16px}
        .form-group label{display:block;font-weight:600;margin-bottom:6px;color:#111}
        .form-group input[type=file]{padding:8px;border:2px solid #e5e7eb;border-radius:6px;cursor:pointer}
        .form-group input[type=file]:hover{border-color:var(--accent)}
        .form-group textarea{font-family:inherit;padding:10px;border:1px solid #e5e7eb;border-radius:6px;resize:vertical}
        .success-msg{background:#d1fae5;color:#065f46;padding:12px;border-radius:6px;margin-bottom:16px;border-left:4px solid #10b981}
        .error-msg{background:#fee2e2;color:#991b1b;padding:12px;border-radius:6px;margin-bottom:16px;border-left:4px solid #ef4444}
    </style>
</head>
<body>
    <?php include 'scholarnav.php'; ?>
    <div class="container">
        <h2>Scholarship Renewal Submission</h2>
        <p>Submit your documents and message below.</p>

        <?php if (isset($ok) && $ok): ?>
            <div class="success-msg">✓ Renewal submitted successfully!</div>
        <?php elseif (isset($ok) && !$ok): ?>
            <div class="error-msg">✗ Error: submission failed. Please try again.</div>
        <?php endif; ?>

        <form method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="birth">Certificate of Birth <span style="color:red">*</span></label>
                <input type="file" id="birth" name="certificate_of_birth" accept="image/*,.pdf" required>
                <small style="color:var(--muted)">Image or PDF file</small>
            </div>

            <div class="form-group">
                <label for="indigency">Certificate of Indigency <span style="color:red">*</span></label>
                <input type="file" id="indigency" name="certificate_of_indigency" accept="image/*,.pdf" required>
                <small style="color:var(--muted)">Image or PDF file</small>
            </div>

            <div class="form-group">
                <label for="message">Message (optional)</label>
                <textarea id="message" name="message" rows="4" placeholder="Add any additional information..."></textarea>
            </div>

            <button class="btn" type="submit" name="submit_renewal">Submit Renewal</button>
        </form>
    </div>
</body>
</html>

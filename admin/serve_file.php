<?php
session_start();

// Check if user is logged in and is admin
if (!isset($_SESSION['account_id']) || $_SESSION['role'] !== 'admin') {
    http_response_code(403);
    echo 'Forbidden';
    exit;
}

include __DIR__ . '/../database.php';

$renewal_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$type = $_GET['type'] ?? '';

if ($renewal_id <= 0 || !in_array($type, ['birth', 'indigency'])) {
    http_response_code(400);
    echo 'Bad request';
    exit;
}

$col = $type === 'birth' ? 'certificate_of_birth' : 'certificate_of_indigency';

$stmt = $conn->prepare("SELECT $col FROM renewals WHERE renewal_id = ?");
if (!$stmt) {
    http_response_code(500);
    echo 'Prepare failed';
    exit;
}
$stmt->bind_param('i', $renewal_id);
$stmt->execute();
$res = $stmt->get_result();
if (!$res || $res->num_rows === 0) {
    http_response_code(404);
    echo 'Not found';
    exit;
}
$row = $res->fetch_assoc();
$path = $row[$col];
$stmt->close();

if (!$path) {
    http_response_code(404);
    echo 'File not found';
    exit;
}

// Try to resolve the path. Stored paths may be absolute, relative, or just a basename.
$uploadsDir = realpath(__DIR__ . '/../uploads/renewals/');

// Try multiple candidate paths to resolve the stored path value.
$candidates = [];
$candidates[] = $path;
$candidates[] = __DIR__ . '/' . $path;           // script-relative
$candidates[] = __DIR__ . '/../' . $path;        // project-relative
if ($uploadsDir) {
    $candidates[] = $uploadsDir . DIRECTORY_SEPARATOR . basename($path); // uploads basename
}

$realPath = false;
foreach ($candidates as $cand) {
    $rp = realpath($cand);
    if ($rp !== false && is_file($rp)) {
        $realPath = $rp;
        break;
    }
}

// Diagnostics for debugging path issues
error_log("serve_file: stored_path=" . var_export($path, true));
error_log("serve_file: candidates=" . var_export($candidates, true));
error_log("serve_file: resolved_realPath=" . var_export($realPath, true));
error_log("serve_file: uploadsDir=" . var_export($uploadsDir, true));

if ($realPath === false) {
    http_response_code(404);
    echo 'File not found';
    exit;
}

// Ensure the file lives under uploads/renewals (if uploadsDir is available)
if ($uploadsDir && strpos($realPath, $uploadsDir) !== 0) {
    error_log('serve_file: resolved path outside uploadsDir, denying access');
    http_response_code(403);
    echo 'Forbidden';
    exit;
}

$finfo = finfo_open(FILEINFO_MIME_TYPE);
$mime = finfo_file($finfo, $realPath) ?: 'application/octet-stream';
finfo_close($finfo);
$basename = basename($realPath);

header('Content-Type: ' . $mime);
header('Content-Disposition: inline; filename="' . $basename . '"');
header('Content-Length: ' . filesize($realPath));
readfile($realPath);
exit;

?>

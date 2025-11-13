<?php
session_start(); // MUST be first

// Check if user is logged in and is admin
if (!isset($_SESSION['account_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../portal/login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
</head>
<body>

    <?php include 'header.php'; ?>

    <div class="container">
        <h2>Admin Dashboard</h2>
        <p>Overview and quick links.</p>
    </div>

</body>
</html>

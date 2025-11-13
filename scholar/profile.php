<?php
session_start(); // MUST be first

// Check if user is logged in and is scholar
if (!isset($_SESSION['account_id']) || $_SESSION['role'] !== 'scholar') {
    header("Location: ../portal/login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Scholars profile</title>
    <link rel="stylesheet" href="../styles.css">
</head>
<body>

    <?php include 'scholarnav.php'; ?>
    <div class="container">
        <h2>Scholar Profile</h2>
        <p>Welcome to your scholar profile. Manage your information and view your scholarship status here.</p>

        <p>Scholar can view announcements, renewals, and edit profile details.</p>
    </div>

</body>
</html>

<?php
session_start();

// Check if user is logged in and is admin
if (!isset($_SESSION['account_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../portal/login.php");
    exit();
}

include("../database.php");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin: Renewals</title>
    <link rel="stylesheet" href="../styles.css">
</head>
<body>
    <?php include 'header.php'; ?>
    <div class="container">
        <h2>Renewal Applications</h2>
        <table cellpadding="5">
        <tr>
            <th>Renewal ID</th>
            <th>Account ID</th>
            <th>Scholar ID</th>
            <th>Scholar Name</th>
            <th>Message</th>
            <th>Certificate of Birth</th>
            <th>Certificate of Indigency</th>
            <th>Action</th>
        </tr>
        <?php
        $result = mysqli_query($conn, "SELECT * FROM renewals");
        while($row = mysqli_fetch_assoc($result)){
            echo "<tr>";
            echo "<td>".htmlspecialchars($row['renewal_id'])."</td>";
            echo "<td>".htmlspecialchars($row['account_id'] ?? '') . "</td>";
            echo "<td>".htmlspecialchars($row['scholar_id'] ?? '') . "</td>";
            echo "<td>".htmlspecialchars($row['name'])."</td>";
            echo "<td>".htmlspecialchars($row['message'])."</td>";
            echo "<td><a class='btn' href='view_renewal.php?id=".urlencode($row['renewal_id'])."' target='_blank'>View</a></td>";
            echo "<td><a class='btn' href='view_renewal.php?id=".urlencode($row['renewal_id'])."' target='_blank'>View</a></td>";
            echo "<td><a class='btn' href='approve_renewal.php?id=".urlencode($row['renewal_id'])."'>Approve</a> ";
            echo " <a class='btn secondary' href='reject_renewal.php?id=".urlencode($row['renewal_id'])."'>Reject</a></td>";
            echo "</tr>";
        }
        ?>
        </table>
    </div>
</body>
</html>

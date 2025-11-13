<?php
    session_start(); // MUST be first

    // Check if user is logged in and is admin
    if (!isset($_SESSION['account_id']) || $_SESSION['role'] !== 'admin') {
        header("Location: ../portal/login.php");
        exit();
    }

    include("../database.php");

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>accounts page</title>
    <link rel="stylesheet" href="../styles.css">
</head>
<body>

    <?php include 'header.php'; ?>  
    <div class="container">
        <h2>Accounts</h2>
        <p>Manage user accounts here (admin / scholars).</p>

    <?php

        $sql = "SELECT * FROM accounts";
        $result = mysqli_query($conn, $sql);

        if (!$result) {
            die("Query failed: " . mysqli_error($conn));
        }

        echo "<table>
        <tr>
            <th>Account ID</th>
            <th>Scholar ID</th>
            <th>Username</th>
            <th>Role</th>
            <th>Actions</th>
        </tr>";
        
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($row['account_id']) . "</td>";
                echo "<td>" . htmlspecialchars($row['scholar_id']) . "</td>";
                echo "<td>" . htmlspecialchars($row['username']) . "</td>";
                echo "<td>" . htmlspecialchars($row['role']) . "</td>";
                echo "<td>
                        <form method='POST'>
                        <input type='hidden' name='account_id' value='" . htmlspecialchars($row['account_id']) . "'>
                        <button class=\"btn\" type='submit' name='deactivate'>Deactivate</button>
                        <button class=\"btn secondary\" type='submit' name='idk'>Other</button>
                        </form>
                    </td>";
                // single actions column already rendered above; no duplicate block
                echo "</tr>";
            }
        } else {    
            echo "<tr><td colspan='5'>No applications found</td></tr>";
        }
        echo "</table>";
    ?>
        </div>

    </body>
    </html>
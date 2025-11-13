<?php
    session_start(); // MUST be first

    // Check if user is logged in and is admin
    if (!isset($_SESSION['account_id']) || $_SESSION['role'] !== 'admin') {
        header("Location: ../portal/login.php");
        exit();
    }

    include("../database.php"); // Connects to the database

    // This file handles displaying the list of all approved scholars.
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Scholars Page</title>
    <link rel="stylesheet" href="../styles.css">
</head>
<body>
    
    <?php include 'header.php'; ?>
    <div class="container">
        <h2>Scholars</h2>
        <p>Manage scholar records here.</p>

    <?php

        $sql = "SELECT * FROM scholars";
        $result = mysqli_query($conn, $sql);

        if (!$result) {
            die("Query failed: " . mysqli_error($conn));
        }

        echo "<table>
        <tr>
            <th>Scholar ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Date</th>
        </tr>";
        
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($row['scholar_id']) . "</td>";
                echo "<td>" . htmlspecialchars($row['name']) . "</td>";
                echo "<td>" . htmlspecialchars($row['email']) . "</td>";
                echo "<td>" . htmlspecialchars($row['date']) . "</td>";
                // Adding an action column for future management
                echo "</tr>";
            }
        } else {    
            echo "<tr><td colspan='5'>No scholars found</td></tr>";
        }
        echo "</table>";
    ?>
    </div>

</body>
</html>

<?php
    mysqli_close($conn); // Close the connection
?>
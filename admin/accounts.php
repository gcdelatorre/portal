<?php
    session_start(); // MUST be first
    include("../database.php");

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>accounts page</title>
</head>
<body>

    <?php include 'header.php'; ?>  
    
    <h2>accounts page</h2>
    <p>Manage user accounts here (admin/scholars).</p>

    print or fetch the list of accounts from the database and print here
    admin can active or activate account here

    <?php

        $sql = "SELECT * FROM accounts";
        $result = mysqli_query($conn, $sql);

        if (!$result) {
            die("Query failed: " . mysqli_error($conn));
        }

        echo "<table border='1'>
        <tr>
            <th>account ID</th>
            <th>Scholar ID</th>
            <th>username</th>
            <th>password</th>
            <th>role</th>
            <th>unhashedPassword</th>
            <th>actions</th>
        </tr>";
        
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($row['account_id']) . "</td>";
                echo "<td>" . htmlspecialchars($row['scholar_id']) . "</td>";
                echo "<td>" . htmlspecialchars($row['username']) . "</td>";
                echo "<td>" . htmlspecialchars($row['password']) . "</td>";
                echo "<td>" . htmlspecialchars($row['role']) . "</td>";
                echo "<td>" . htmlspecialchars($row['unhashedPassword']) . "</td>";
                echo "<td>
                        <form method='POST'>
                        <input type='hidden' name='account_id' value='" . htmlspecialchars($row['account_id']) . "'>
                        <input type='hidden' name='applicant_id' value='" . htmlspecialchars($row['scholar_id']) . "'>
                        <input type='hidden' name='name' value='" . htmlspecialchars($row['username']) . "'>
                        <input type='hidden' name='email' value='" . htmlspecialchars($row['password']) . "'>
                        <input type='hidden' name='name' value='" . htmlspecialchars($row['role']) . "'>
                        <input type='hidden' name='unhashedPassword' value='" . htmlspecialchars($row['unhashedPassword']) . "'>
                        <button type='submit' name='deactivate'>deactivate</button>
                        <button type='submit' name='idk'>idk</button>
                        </form>
                    </td>";
                echo "</tr>";
            }
        } else {    
            echo "<tr><td colspan='5'>No applications found</td></tr>";
        }
        echo "</table>";
    ?>

    
    

</body>
</html>
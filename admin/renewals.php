<?php
session_start();
include("../database.php");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin: Renewals</title>
</head>
<body>
    <h2>Renewal Applications</h2>
    <table border="1" cellpadding="5">
        <tr>
            <th>Renewal ID</th>
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
            echo "<td>".$row['renewal_id']."</td>";
            echo "<td>".$row['name']."</td>";
            echo "<td>".$row['message']."</td>";
            echo "<td><a href='".$row['certificate_of_birth']."' target='_blank'>View</a></td>";
            echo "<td><a href='".$row['certificate_of_indigency']."' target='_blank'>View</a></td>";
            echo "<td><a href='approve_renewal.php?id=".$row['renewal_id']."'>Approve</a> | 
                      <a href='reject_renewal.php?id=".$row['renewal_id']."'>Reject</a></td>";
            echo "</tr>";
        }
        ?>
    </table>
</body>
</html>

<?php
    session_start(); // MUST be first
    include("../database.php"); // Connects to the database
    include("functions.php"); // Includes the basic functions

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        
        // Use basic cleanup (sanitization) for security, but note the function is still vulnerable.
        $applicant_id = (int)($_POST['applicant_id'] ?? 0);
        $name = mysqli_real_escape_string($conn, $_POST['name'] ?? '');
        $email = mysqli_real_escape_string($conn, $_POST['email'] ?? '');

        if (isset($_POST['approve']) && $applicant_id > 0) {
            addToScholars($name, $email, $conn);
            removeApplication($applicant_id, $conn);
            
        } elseif (isset($_POST['reject']) && $applicant_id > 0) {
            removeApplication($applicant_id, $conn); 
            $_SESSION['message'] = "<p style='color:blue;'>Applicant rejected and application removed.</p>";
        }

        // CRITICAL FIX: Post/Redirect/Get to prevent duplication on refresh
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Applications Page</title>
</head>
<body>
    
    <?php include 'header.php'; ?>
    
    <h2>Applications Page</h2>
    <p>Manage scholarship applications here.</p>

    <?php
        // Display status message from the session and then clear it
        if (isset($_SESSION['message'])) {
            echo $_SESSION['message'];
            unset($_SESSION['message']);
        }
    ?>
    
    <?php
        $sql = "SELECT * FROM applications";
        $result = mysqli_query($conn, $sql);

        if (!$result) {
            die("Query failed: " . mysqli_error($conn));
        }

        echo "<table border='1'>
        <tr>
            <th>Applicant ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Date</th>
            <th>Action</th>
        </tr>";

        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($row['applicant_id']) . "</td>";
                echo "<td>" . htmlspecialchars($row['name']) . "</td>";
                echo "<td>" . htmlspecialchars($row['email']) . "</td>";
                echo "<td>" . htmlspecialchars($row['date']) . "</td>";
                echo "<td>
                        <form method='POST'>
                        <input type='hidden' name='applicant_id' value='" . htmlspecialchars($row['applicant_id']) . "'>
                        <input type='hidden' name='name' value='" . htmlspecialchars($row['name']) . "'>
                        <input type='hidden' name='email' value='" . htmlspecialchars($row['email']) . "'>
                        <input type='hidden' name='date' value='" . htmlspecialchars($row['date']) . "'>
                        <button type='submit' name='approve' onClick=\"return confirmAction('approve_application')\">Approve</button>
                        <button type='submit' name='reject' onClick=\"return confirmAction('reject_application')\">Reject</button>
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

<?php
    mysqli_close($conn);
?>

<script src="./confirmation.js"></script>
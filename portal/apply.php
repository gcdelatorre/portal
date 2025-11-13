<?php
    include("../database.php");

    
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Application Form</title>

    <!-- link the external CSS -->
    <link rel="stylesheet" href="apply.css">
    <link rel="stylesheet" href="../styles.css">
</head>
<body>

    <div class="container">
        <p>Apply page — fill up the form and send your request</p>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post"
        onsubmit="return confirmAction('submit_application')">
            <input type="text" name="fullname" placeholder="Full Name" required>
            <input type="email" name="email" placeholder="Email Address" required>
            <input class="btn" type="submit" name="submit_application" value="Submit Application">
        </form>

        <?php
            if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit_application'])) {
                // In a real system you could save to DB or redirect here

                $applicant_name = mysqli_real_escape_string($conn, $_POST['fullname']);
                $applicant_email = mysqli_real_escape_string($conn, $_POST['email']);

                $sql = "INSERT INTO `applications` (`applicant_id`, `name`, `email`, `date`)
                    VALUES (NULL, '$applicant_name' , '$applicant_email', current_timestamp())";

                if (mysqli_query($conn, $sql)) {
                    echo "<div class='success'>Application submitted successfully!</div>";
                    echo "<div class='success'>Please wait until we review your application! We will send an email.</div>";
                } else {
                    echo "<div class='error'>Could not register: " . mysqli_error($conn) . "</div>";
                }
            }
        ?>
    </div>

</body>
</html>

<?php
    mysqli_close($conn);


?>

<script src="../admin/confirmation.js"></script>
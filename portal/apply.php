<?php
    include("../database.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Application Form</title>

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
            <select name="religion" id="religion">
                <option value="Roman Catholic">Roman Catholic</option>
                <option value="Christian Asido">Christian Asido</option>
                <option value="INC">INC</option>
            </select>
            <select name="civil_status" id="civil_status">
                <option value="Single">Single</option>
                <option value="Married">Married</option>
                <option value="Divorced">Divorced</option>
            </select>
            <input class="btn" type="submit" name="submit_application" value="Submit Application">
        </form>

        <?php
            if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit_application'])) {

                $applicant_name = mysqli_real_escape_string($conn, $_POST['fullname']);
                $applicant_email = mysqli_real_escape_string($conn, $_POST['email']);
                $applicant_religion = mysqli_real_escape_string($conn, $_POST['religion']);
                $applicant_civil_status = mysqli_real_escape_string($conn, $_POST['civil_status']);

                $sql = "INSERT INTO `applications` (`applicant_id`, `name`, `email`, `religion`, `civil_status`, `date`)
                    VALUES (NULL, '$applicant_name' , '$applicant_email', '$applicant_religion', '$applicant_civil_status', current_timestamp())";

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
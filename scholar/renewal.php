<?php
session_start();
include("../database.php");

if($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["submit_renewal"])) {

        $message = mysqli_real_escape_string($conn, $_POST['message']);
        $account_id = $_SESSION['account_id']; // assume logged-in user
        $scholar_id = $_SESSION['scholar_id'];
        $name = $_SESSION['name'];

        // File upload
        $upload_dir = "../uploads/renewals/";
        if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);

        $birth_file = $_FILES['certificate_of_birth']['name'];
        $indigency_file = $_FILES['certificate_of_indigency']['name'];

        $birth_path = $upload_dir . basename($birth_file);
        $indigency_path = $upload_dir . basename($indigency_file);

        move_uploaded_file($_FILES['certificate_of_birth']['tmp_name'], $birth_path);
        move_uploaded_file($_FILES['certificate_of_indigency']['tmp_name'], $indigency_path);

        $sql = "INSERT INTO renewals (account_id, scholar_id, name, certificate_of_birth, certificate_of_indigency, message) 
                VALUES ('$account_id', '$scholar_id', '$name', '$birth_path', '$indigency_path', '$message')";

        if(mysqli_query($conn, $sql)){
            echo "Renewal submitted successfully!";
        } else {
            echo "Error: " . mysqli_error($conn);
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Renewal Submission</title>
</head>
<body>
    <h2>Renewal Submission Page</h2>
    <form method="post" enctype="multipart/form-data" onsubmit="return confirm('submit_renewal');">
        <label>Certificate of Birth:</label>
        <input type="file" name="certificate_of_birth" required><br>
        <label>Certificate of Indigency:</label>
        <input type="file" name="certificate_of_indigency" required><br>
        <label>Message:</label>
        <input type="text" name="message"><br>
        <input type="submit" name="submit_renewal" value="Submit Renewal">
    </form>
</body>
</html>

<?php
    session_start(); // MUST be first
    include("../database.php");
    gLobal $conn;
    
    if($_SERVER["REQUEST_METHOD"] == "POST") {
        if (isset($_POST["submit_renewal"])) {

            $message = $_POST['message'];

            $sql = "INSERT INTO `renewals` (`renewal_id`, `account_id`,  `scholar_id`, `name` ,`message`) 
                    VALUES ('$message')";

            try {
                mysqli_query($conn, $sql);
                echo 'submitted';
            } 
            catch (mysqli_sql_exception) {
                echo "Error submitting renewal.";   
            }
        }
    }

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>renewal submission page</title>
</head>
<body>
   
    <?php include 'scholarnav.php'; ?>
    
    <h2>renewal submission page</h2>
    <p>Submit your renewal application here.</p>
    <p>renewal submission will be every semester, not renewing will make your acc deactivate</p>

    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <label for="certificate_of_birth">Certificate of Birth</label>
        <input type="file" name="certificate_of_birth" required><br>
        <label for="certificate_of_indigency">Certificate of Indigency</label>
        <input type="file" name="certificate_of_indigency" required><br>
        <label for="message">message</label>
        <input type="message" name="message">
        <input type="submit" name="submit_renewal" value="Submit Renewal">
    </form>

</body>
</html>
<?php


    mysqli_close($conn);
?>
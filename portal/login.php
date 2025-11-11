<?php
session_start();
include("../database.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
</head>
<body>

<h2>Login</h2>
<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
    <input type="text" name="username" placeholder="Username" required><br><br>
    <input type="password" name="password" placeholder="Password" required><br><br>
    <input type="submit" name="login_submit" value="Log In">
</form>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['login_submit'])) {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    
    // check if user exists in the accounts table
    $sql = "SELECT * FROM accounts WHERE username='$username'";
    $result = mysqli_query($conn, $sql);


    if (!$result) {
        die("Query failed: " . mysqli_error($conn));
    }

    // step 2: if user found, verify password
    if (mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_assoc($result);
        if (password_verify($password, $row['password'])) {
            // Password is correct, start a session
            $_SESSION['account_id'] = $row['account_id'];
            $_SESSION['username'] = $row['username'];

            // Redirect based on role
            if ($row['role'] == 'admin') {
                header("Location: ../admin/dashboard.php");
            } elseif ($row['role'] == 'scholar') {
                header("Location: ../scholar/profile.php");
            } else {
                echo "Unknown user role.";
            }
            exit();
        } else {
            echo "Invalid password.";
        }
    } else {
        echo "No user found with that username.";
    }
}


?>

</body>
</html>

<?php
// This file should be included in applications.php

function addToScholars($name, $email, $conn) {
    // Basic SQL concatenation - VULNERABLE TO SQL INJECTION

    $username = generateUsername($name);
    $password = generateRandomPassword(7);
    addtoScholars($username, $password, $conn);

    $sql = "INSERT INTO `scholars` (`name`, `email`, `date`) 
            VALUES ('$name', '$email' , current_timestamp())";

    try {
        mysqli_query($conn, $sql);
        $_SESSION['message'] = "<p style='color:green;'>Scholar added successfully!</p>";
    }
    catch (mysqli_sql_exception $e) {
        $_SESSION['message'] = "<p style='color:red;'>Could not add scholar: " . $e->getMessage() . "</p>";
    }
}

function addScholarAccount($scholar_id, $username, $password) {
    global $conn;

    $sql = "INSERT INTO `accounts` (`scholar_id`, `username`, `password`)
            VALUES ('$scholar_id', '$username', '$password')";

    try {
        mysqli_query($conn, $sql);
    }
    catch (mysqli_sql_exception) {
        // Handle error (e.g., log it)
        echo "Error adding scholar account.";   
    }
}


function generateRandomPassword ($length = 8) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomPassword = '';
    for ($i = 0; $i < $length; $i++) {
        $randomPassword .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomPassword;
}

    echo generateRandomPassword(6);

function generateUsername ($name) {
    $nameParts = explode(' ', strtolower($name));
    $username = '';

    if (count($nameParts) >= 2) {
        $username = $nameParts[0][0] . $nameParts[1]; // first initial + last name
    } else {
        $username = $nameParts[0]; // single name
    }

    // Append random number to ensure uniqueness
    $username .= rand(100, 999);

    return $username;
}


function removeApplication($applicant_id, $conn) {
    // Basic SQL concatenation - VULNERABLE TO SQL INJECTION
    $sql = "DELETE FROM `applications` WHERE `applicant_id` = $applicant_id";

    try {
        mysqli_query($conn, $sql);
        $_SESSION['message'] .= "<p style='color:green;'>Application removed successfully!</p>";
    }
    catch (mysqli_sql_exception $e) {
        $_SESSION['message'] .= "<p style='color:red;'>Could not remove application: " . $e->getMessage() . "</p>";
    }
}
?>
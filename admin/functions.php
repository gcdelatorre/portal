<?php
// This file should be included in applications.php

function generateRandomPassword ($length = 8) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomPassword = '';
    for ($i = 0; $i < $length; $i++) {
        $randomPassword .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomPassword;
}

function generateUsername ($name) {
    // Defensive: ensure we have a string and limit its size to avoid memory issues
    $name = (string)$name;
    $name = trim($name);

    if ($name === '') {
        return 'user' . rand(100, 999);
    }

    // Limit the input length to something reasonable (200 chars)
    if (strlen($name) > 200) {
        $name = substr($name, 0, 200);
    }

    // Split into at most 3 parts to avoid creating huge arrays
    $parts = preg_split('/\s+/', $name, 3, PREG_SPLIT_NO_EMPTY);
    // Lowercase only the small parts (avoids lowercasing an enormous string)
    $parts = array_map('strtolower', $parts);

    $username = '';
    if (count($parts) >= 2) {
        $username = ($parts[0][0] ?? '') . $parts[1]; // first initial + last name
    } else {
        $username = $parts[0]; // single name
    }

    // Append random number to ensure uniqueness and strip unsafe chars
    $username .= rand(100, 999);
    // Keep only alphanumeric and underscore
    $username = preg_replace('/[^a-z0-9_]/', '', $username);

    // Fallback if resulting username is empty
    if ($username === '') {
        $username = 'user' . rand(100, 999);
    }

    return $username;
}

function addToScholars($name, $email, $conn) {
    // Basic SQL concatenation - VULNERABLE TO SQL INJECTION

    // Basic but improved: escape inputs and insert scholar, then create account
    $name_safe = mysqli_real_escape_string($conn, (string)$name);
    $email_safe = mysqli_real_escape_string($conn, (string)$email);

    $username = generateUsername($name_safe);
    $password = generateRandomPassword(7);

    $sql = "INSERT INTO `scholars` (`name`, `email`, `date`) 
            VALUES ('{$name_safe}', '{$email_safe}', current_timestamp())";

    try {
        mysqli_query($conn, $sql);
        // Get the inserted scholar id and create an account for them
        $scholar_id = mysqli_insert_id($conn);
        if ($scholar_id) {
            // add account record (separate function)
            addScholarAccount($scholar_id, $username, $password);
        }
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
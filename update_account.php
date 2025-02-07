<?php
session_start();
require 'db_connect.php'; // Include your database connection file

// Check if the user is logged in
if (!isset($_SESSION['email'])) {
    header('Location: signIn.php');
    exit();
}

// Process form data when the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the new data from the form
    $username = isset($_POST['username']) ? htmlspecialchars(trim($_POST['username'])) : '';
    $name = isset($_POST['name']) ? htmlspecialchars(trim($_POST['name'])) : '';
    $password = isset($_POST['password']) ? trim($_POST['password']) : '';

    // Split the full name into first and last name
    $nameParts = explode(' ', $name);
    $firstName = $nameParts[0];
    $lastName = isset($nameParts[1]) ? $nameParts[1].' '.$nameParts[2].' '.$nameParts[3] : '';

    // Hash the password if it’s provided
    $passwordHash = !empty($password) ? password_hash($password, PASSWORD_DEFAULT) : null;

    // Prepare SQL update statement
    $updateQuery = "UPDATE users SET username = ?, firstname = ?, lastname = ?";
    $params = [$username, $firstName, $lastName];
    $types = "sss";

    if ($passwordHash) {
        $updateQuery .= ", password = ?";
        $params[] = $passwordHash;
        $types .= "s";
    }

    $updateQuery .= " WHERE email = ?";
    $params[] = $_SESSION['email'];
    $types .= "s";

    // Prepare and execute the update query
    if ($stmt = $connection->prepare($updateQuery)) {
        $stmt->bind_param($types, ...$params);
        if ($stmt->execute()) {
            $_SESSION['username'] = $username;
            $_SESSION['firstname'] = $firstName;
            $_SESSION['lastname'] = $lastName;

            echo "<script>alert('Account information updated successfully!'); window.location.href = 'accountsettings.php';</script>";
        } else {
            echo "<script>alert('Error updating information.');</script>";
        }
        $stmt->close();
    } else {
        echo "<script>alert('Failed to prepare statement.');</script>";
    }
}

$connection->close();
?>

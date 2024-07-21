<?php
session_start();
if (!isset($_SESSION['SESSION_EMAIL'])) {
    header("Location: login.php");
    die();
}

// Ensure only admin can access this page
if ($_SESSION['SESSION_ROLE'] !== 'admin') {
    header("Location: index.php");
    die();
}

include 'connection/config.php';

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = mysqli_real_escape_string($conn, $_POST['userName']);
    $email = mysqli_real_escape_string($conn, $_POST['userEmail']);
    $role = mysqli_real_escape_string($conn, $_POST['userRole']);
    $password = mysqli_real_escape_string($conn, password_hash($_POST['userPassword'], PASSWORD_BCRYPT));

    // Check if the email already exists
    $check_email_query = mysqli_query($conn, "SELECT * FROM users WHERE email='$email'");
    if (mysqli_num_rows($check_email_query) > 0) {
        // Email already exists, redirect back with an error message
        $_SESSION['error_message'] = 'Email already exists';
        header("Location: admin_manage_users.php");
        die();
    }

    // Insert new user into the database
    $query = "INSERT INTO users (name, email, password, role, is_verified) VALUES ('$name', '$email', '$password', '$role', 1)";
    if (mysqli_query($conn, $query)) {
        $_SESSION['success_message'] = 'User added successfully';
    } else {
        $_SESSION['error_message'] = 'Error adding user: ' . mysqli_error($conn);
    }

    header("Location: admin_manage_users.php");
    die();
}
?>

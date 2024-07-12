<?php
    session_start();
    if (!isset($_SESSION['SESSION_EMAIL'])) {
        header("Location: login.php");
        die();
    }

// Include admin_dashboard.php from user/user_dashboard.php
// include('admin/admin_dashboard.php');

include('connection/config.php');



    $query = mysqli_query($conn, "SELECT * FROM users WHERE email='{$_SESSION['SESSION_EMAIL']}'");

    if (mysqli_num_rows($query) > 0) {
        $row = mysqli_fetch_assoc($query);

        // Redirect user based on role
        if ($row['role'] === 'admin') {
            header("Location: admin_dashboard.php");
            die();
        } else {
            header("Location: user_dashboard.php");
            die();
        }
    }
?>

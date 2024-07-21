<?php
include 'connection/config.php';

if (isset($_GET['id'])) {
    $userId = $_GET['id'];
    $query = "DELETE FROM users WHERE id='$userId'";
    if (mysqli_query($conn, $query)) {
        header("Location: admin_manage_users.php?success=User deleted successfully");
    } else {
        echo "Error deleting user: " . mysqli_error($conn);
    }
}
?>

<?php
include 'connection/config.php';

if (isset($_GET['id'])) {
    $userId = $_GET['id'];
    $query = "SELECT * FROM users WHERE id = '$userId'";
    $result = mysqli_query($conn, $query);
    if ($result) {
        $user = mysqli_fetch_assoc($result);
        echo "<p><strong>ID:</strong> " . $user['id'] . "</p>";
        echo "<p><strong>Name:</strong> " . $user['name'] . "</p>";
        echo "<p><strong>Email:</strong> " . $user['email'] . "</p>";
        echo "<p><strong>Role:</strong> " . $user['role'] . "</p>";
    } else {
        echo "<p>User not found.</p>";
    }
}
?>

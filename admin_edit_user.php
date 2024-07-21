<?php
session_start();
include 'connection/config.php';

// Redirect to login page if user is not logged in
if (!isset($_SESSION['SESSION_EMAIL'])) {
    header("Location: login.php");
    exit();
}

// Redirect non-admin users to index page
if ($_SESSION['SESSION_ROLE'] !== 'admin') {
    header("Location: index.php");
    exit();
}

// Handle form submission for updating user
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userId = mysqli_real_escape_string($conn, $_POST['userId']);
    $userName = mysqli_real_escape_string($conn, $_POST['userName']);
    $userEmail = mysqli_real_escape_string($conn, $_POST['userEmail']);
    $userRole = mysqli_real_escape_string($conn, $_POST['userRole']);

    // Update query
    $update_query = "UPDATE users SET name = '$userName', email = '$userEmail', role = '$userRole' WHERE id = '$userId'";

    if (mysqli_query($conn, $update_query)) {
        // Redirect to manage_users.php with a success message
        header("Location: admin_manage_users.php?msg=updated");
        exit();
    } else {
        // Handle update error
        $_SESSION['error_message'] = "Error updating user: " . mysqli_error($conn);
        header("Location: admin_manage_users.php");
        exit();
    }
}

// Display form to edit user details
if (isset($_GET['id'])) {
    $userId = mysqli_real_escape_string($conn, $_GET['id']);
    
    // Retrieve user details based on user ID
    $user_query = mysqli_query($conn, "SELECT * FROM users WHERE id = '$userId'");

    if (mysqli_num_rows($user_query) > 0) {
        $user = mysqli_fetch_assoc($user_query);
?>
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Edit User</title>
            <link href="css/bootstrap.min.css" rel="stylesheet">
            <link rel="stylesheet" href="fontawesome/css/all.min.css">
            <style>
                /* Your custom styles here */
            </style>
        </head>
        <body>
            <div class="container mt-5">
                <h1>Edit User</h1>
                <div class="card">
                    <div class="card-body">
                        <form action="admin_edit_user.php" method="POST">
                            <input type="hidden" name="userId" value="<?php echo $user['id']; ?>">
                            <div class="mb-3">
                                <label for="userName" class="form-label">Name</label>
                                <input type="text" class="form-control" id="userName" name="userName" value="<?php echo $user['name']; ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="userEmail" class="form-label">Email address</label>
                                <input type="email" class="form-control" id="userEmail" name="userEmail" value="<?php echo $user['email']; ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="userRole" class="form-label">Role</label>
                                <select class="form-select" id="userRole" name="userRole" required>
                                    <option value="admin" <?php echo ($user['role'] === 'admin') ? 'selected' : ''; ?>>Admin</option>
                                    <option value="user" <?php echo ($user['role'] === 'user') ? 'selected' : ''; ?>>User</option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary">Update User</button>
                        </form>
                    </div>
                </div>
            </div>
            <script src="js/bootstrap.bundle.min.js"></script>
        </body>
        </html>
<?php
    } else {
        $_SESSION['error_message'] = "User not found.";
        header("Location: admin_manage_users.php");
        exit();
    }
} else {
    $_SESSION['error_message'] = "User ID not provided.";
    header("Location: admin_manage_users.php");
    exit();
}
?>

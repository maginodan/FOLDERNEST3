<?php
    session_start();
    if (!isset($_SESSION['SESSION_EMAIL'])) {
        header("Location: login.php");
        die();
    }

    // Assuming role check has already been done in index.php
    // If this page is directly accessed, ensure role is admin
    if ($_SESSION['SESSION_ROLE'] !== 'admin') {
        header("Location: index.php"); // Redirect to index if not admin
        die();
    }

    // Include necessary files and configurations
    include 'connection/config.php';

    // Retrieve user data based on session email
    $query = mysqli_query($conn, "SELECT * FROM users WHERE email='{$_SESSION['SESSION_EMAIL']}'");

    if (mysqli_num_rows($query) > 0) {
        $row = mysqli_fetch_assoc($query);
        $name = $row['name'];
    } else {
        // Handle case where user data is not found (although this should not happen if user is logged in)
        header("Location: login.php");
        die();
    }
?>

















<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Settings</title>
    <!-- Bootstrap CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"> -->
    <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"> -->
    <link rel="stylesheet" href="fontawesome/css/all.min.css">
    <style>
        body {
            display: flex;
            min-height: 100vh;
            font-family: Arial, sans-serif;
            margin: 0;
        }
        .sidebar {
            width: 250px;
            background-color: #0aa0bf;
            color: #fff;
            position: fixed;
            height: 100%;
            padding-top: 20px;
            z-index: 1;
        }
        .sidebar a {
            color: #fff;
            text-decoration: none;
            display: block;
            padding: 10px 20px;
            margin: 10px 0;
        }
        .sidebar a:hover {
            background-color: #3cb8c9;
        }
        .sidebar .active {
            background-color: #48d1cc;
        }
        .content {
            margin-left: 250px;
            padding: 20px;
            flex: 1;
        }
        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }
        .card-header {
            background-color: #48d1cc;
            color: #fff;
            border-radius: 10px 10px 0 0;
            padding: 15px;
            font-size: 20px;
        }
        .card-body {
            padding: 20px;
        }
        .form-control {
            margin-bottom: 15px;
        }
        .btn-save {
            color: #fff;
            background-color: #3cb8c9;
            border-color: #3cb8c9;
        }
        .btn-save:hover {
            color: #fff;
            background-color: #48d1cc;
            border-color: #48d1cc;
        }
        @media (max-width: 768px) {
            .sidebar {
                width: 100%;
                position: static;
                height: auto;
            }
            .content {
                margin-left: 0;
                width: 100%;
                padding: 10px;
            }
        }
    </style>
</head>
<body>
    <?php include('admin_sidebar.php'); ?>
    <div class="content">
        <h1>Settings</h1>
        <div class="row">
            <!-- Profile Settings -->
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <i class="fas fa-user-cog"></i> Profile Settings
                    </div>
                    <div class="card-body">
                        <form>
                            <div class="mb-3">
                                <label for="profileName" class="form-label">Name</label>
                                <input type="text" class="form-control" id="profileName" value="John Doe">
                            </div>
                            <div class="mb-3">
                                <label for="profileEmail" class="form-label">Email</label>
                                <input type="email" class="form-control" id="profileEmail" value="john.doe@example.com">
                            </div>
                            <div class="mb-3">
                                <label for="profilePhone" class="form-label">Phone</label>
                                <input type="text" class="form-control" id="profilePhone" value="+1 234 567 890">
                            </div>
                            <button type="submit" class="btn btn-save"><i class="fas fa-save"></i> Save Changes</button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Password Settings -->
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <i class="fas fa-key"></i> Password Settings
                    </div>
                    <div class="card-body">
                        <form>
                            <div class="mb-3">
                                <label for="currentPassword" class="form-label">Current Password</label>
                                <input type="password" class="form-control" id="currentPassword">
                            </div>
                            <div class="mb-3">
                                <label for="newPassword" class="form-label">New Password</label>
                                <input type="password" class="form-control" id="newPassword">
                            </div>
                            <div class="mb-3">
                                <label for="confirmPassword" class="form-label">Confirm New Password</label>
                                <input type="password" class="form-control" id="confirmPassword">
                            </div>
                            <button type="submit" class="btn btn-save"><i class="fas fa-save"></i> Save Changes</button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Notification Settings -->
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <i class="fas fa-bell"></i> Notification Settings
                    </div>
                    <div class="card-body">
                        <form>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="emailNotifications" checked>
                                <label class="form-check-label" for="emailNotifications">Email Notifications</label>
                            </div>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="smsNotifications">
                                <label class="form-check-label" for="smsNotifications">SMS Notifications</label>
                            </div>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="pushNotifications" checked>
                                <label class="form-check-label" for="pushNotifications">Push Notifications</label>
                            </div>
                            <button type="submit" class="btn btn-save mt-3"><i class="fas fa-save"></i> Save Changes</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap Bundle with Popper -->
    <!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script> -->
    <script src="js/bootstrap.bundle.min.js"></script>
    <!-- Font Awesome -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/js/all.min.js"></script>
</body>
</html>

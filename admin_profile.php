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
    <title>Admin Profile</title>
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
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .card {
            width: 100%;
            max-width: 800px;
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
        .card-body p {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .card-body .small-text {
            font-size: 14px;
            color: #666;
        }
        .btn-edit {
            color: #fff;
            background-color: #3cb8c9;
            border-color: #3cb8c9;
        }
        .btn-edit:hover {
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
            .card {
                max-width: 100%;
            }
        }

         /* Adjusting the font size for the edit profile modal */
         .modal-body label {
            font-size: 14px; /* Adjust as needed */
        }
        .modal-body input,
        .modal-body textarea {
            font-size: 14px; /* Adjust as needed */
        }
        .modal-title {
            font-size: 18px; /* Adjust as needed */
        }
        .modal-dialog {
            max-width: 600px; /* Adjust as needed */
        }
    </style>
</head>
<body>
    <?php include('admin_sidebar.php'); ?>
    <div class="content">
        <div class="card mb-3">
            <div class="row g-0">
                <div class="col-md-4 text-center">
                    <img src="images/avatar.jpg" class="img-fluid rounded-circle my-4" alt="Admin Avatar">
                </div>
                <div class="col-md-8">
                    <div class="card-body">
                        <!-- sql///////////////////////////////// -->
                        <h5 class="card-title"><?php echo $name; ?></h5> 
                        <p class="card-text"><i class="fas fa-envelope"></i> john.doe@example.com</p>
                        <p class="card-text"><i class="fas fa-user-shield"></i> Role: Admin</p>
                        <p class="card-text"><i class="fas fa-phone"></i> Phone: +1 234 567 890</p>
                        <p class="card-text"><i class="fas fa-map-marker-alt"></i> Office: Room 402, Building A</p>
                        <p class="card-text"><i class="fas fa-info-circle"></i> Bio: John has been with the company for over 10 years, leading the admin team to ensure smooth operations across all departments.</p>
                        <button type="button" class="btn btn-edit" data-bs-toggle="modal" data-bs-target="#editAdminModal">
                            <i class="fas fa-edit"></i> Edit Profile
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Edit Admin Modal -->
        <div class="modal fade" id="editAdminModal" tabindex="-1" aria-labelledby="editAdminModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editAdminModalLabel">Edit Admin Profile</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <!-- Form for editing admin profile -->
                        <form><h1>
                            <div class="mb-3">
                                <label for="adminName" class="form-label">Name</label>
                                <input type="text" class="form-control" id="adminName" name="adminName" value="john doe" required>
                            </div>
                            <div class="mb-3">
                                <label for="adminEmail" class="form-label">Email address</label>
                                <input type="email" class="form-control" id="adminEmail" name="adminEmail" value="john.doe@example.com" required>
                            </div>
                            <div class="mb-3">
                                <label for="adminPhone" class="form-label">Phone</label>
                                <input type="text" class="form-control" id="adminPhone" name="adminPhone" value="+1 234 567 890" required>
                            </div>
                            <div class="mb-3">
                                <label for="adminOffice" class="form-label">Office</label>
                                <input type="text" class="form-control" id="adminOffice" name="adminOffice" value="Room 402, Building A" required>
                            </div>
                            <div class="mb-3">
                                <label for="adminRole" class="form-label">Role</label>
                                <input type="text" class="form-control" id="adminRole" name="adminRole" value="Admin" readonly>
                            </div>
                            <div class="mb-3">
                                <label for="adminBio" class="form-label">Bio</label>
                                <textarea class="form-control" id="adminBio" name="adminBio" rows="3" required>John has been with the company for over 10 years, leading the admin team to ensure smooth operations across all departments.</textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">Save Changes</button>
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

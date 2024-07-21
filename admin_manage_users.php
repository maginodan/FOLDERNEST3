<?php
session_start();
include 'connection/config.php';

if (!isset($_SESSION['SESSION_EMAIL'])) {
    header("Location: login.php");
    exit();
}

if ($_SESSION['SESSION_ROLE'] !== 'admin') {
    header("Location: index.php");
    exit();
}

// Fetch all users
$users_query = mysqli_query($conn, "SELECT * FROM users");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
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
        .img-fluid {
            max-width: 50px;
            height: auto;
            border-radius: 50%;
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
        .card-body p {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .card-body .small-text {
            font-size: 14px;
            color: #666;
        }
        .table-responsive {
            overflow-x: auto;
        }
        .btn-add {
            color: #fff;
            background-color: #3cb8c9;
            border-color: #3cb8c9;
            margin-bottom: 20px;
        }
        .btn-add:hover {
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
            }
        }
        @media (max-width: 576px) {
            .card-header, .card-body {
                padding: 10px;
            }
            .btn-add {
                width: 100%;
                margin-bottom: 10px;
            }
        }
    </style>
</head>
<body>
    <?php include('admin_sidebar.php'); ?>
    <div class="content">
        <h1>Manage Users</h1>
        <div class="mb-3">
            <button type="button" class="btn btn-add" data-bs-toggle="modal" data-bs-target="#addUserModal">
                <i class="fas fa-plus"></i> Add User
            </button>
        </div>
        <div class="card">
            <div class="card-header">
                <i class="fas fa-table"></i> User Table
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>User ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            while ($user = mysqli_fetch_assoc($users_query)) {
                                echo "<tr>";
                                echo "<td>{$user['id']}</td>";
                                echo "<td>{$user['name']}</td>";
                                echo "<td>{$user['email']}</td>";
                                echo "<td>{$user['role']}</td>";
                                echo "<td>
                                    <a href='#viewUserModal' class='btn btn-primary btn-sm view-user' data-bs-toggle='modal' data-bs-userid='{$user['id']}'><i class='fas fa-eye'></i> View</a>
                                    <a href='#editUserModal' class='btn btn-info btn-sm edit-user' data-bs-toggle='modal' data-bs-userid='{$user['id']}'><i class='fas fa-edit'></i> Edit</a>
                                    <a href='admin_delete_user.php?id={$user['id']}' class='btn btn-danger btn-sm' onclick='return confirm(\"Are you sure you want to delete this user?\");'><i class='fas fa-trash'></i> Delete</a>
                                  </td>";
                                echo "</tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Add User Modal -->
    <div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="addUserModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addUserModalLabel">Add User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Form for adding a new user -->
                    <form action="add_user.php" method="POST">
                        <div class="mb-3">
                            <label for="userName" class="form-label">Name</label>
                            <input type="text" class="form-control" id="userName" name="userName" required>
                        </div>
                        <div class="mb-3">
                            <label for="userEmail" class="form-label">Email address</label>
                            <input type="email" class="form-control" id="userEmail" name="userEmail" required>
                        </div>
                        <div class="mb-3">
                            <label for="userRole" class="form-label">Role</label>
                            <select class="form-select" id="userRole" name="userRole" required>
                                <option value="admin">Admin</option>
                                <option value="user">User</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="userPassword" class="form-label">Password</label>
                            <input type="password" class="form-control" id="userPassword" name="userPassword" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Add User</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- View User Modal -->
    <div class="modal fade" id="viewUserModal" tabindex="-1" aria-labelledby="viewUserModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="viewUserModalLabel">View User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- View user details will be loaded dynamically using Ajax -->
                    <div id="viewUserDetails"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit User Modal -->
    <div class="modal fade" id="editUserModal" tabindex="-1" aria-labelledby="editUserModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editUserModalLabel">Edit User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Edit user form will be loaded dynamically using Ajax -->
                    <div id="editUserForm"></div>
                </div>
            </div>
        </div>
    </div>

    <script src="js/bootstrap.bundle.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script>
        // Ajax request to load user details in the view user modal
        $(document).on("click", ".view-user", function () {
            var userId = $(this).data('bs-userid');
            $.ajax({
                url: 'admin_view_user.php',
                type: 'GET',
                data: { id: userId },
                success: function(response) {
                    $('#viewUserDetails').html(response);
                }
            });
        });

        // Ajax request to load edit user form in the edit user modal
        $(document).on("click", ".edit-user", function () {
            var userId = $(this).data('bs-userid');
            $.ajax({
                url: 'admin_edit_user.php',
                type: 'GET',
                data: { id: userId },
                success: function(response) {
                    $('#editUserForm').html(response);
                }
            });
        });
    </script>
</body>
</html>

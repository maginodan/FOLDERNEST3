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
    <title>Manage Documents</title>
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
            width: 100%;
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
                padding: 10px;
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
        <h1>Manage Documents</h1>
        <button class="btn btn-add"><i class="fas fa-plus"></i> Add Document</button>
        <div class="card">
            <div class="card-header">
                <i class="fas fa-table"></i> Document Table
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Document ID</th>
                                <th>Title</th>
                                <th>Author</th>
                                <th>Date Created</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>1</td>
                                <td>Document Title 1</td>
                                <td>Author 1</td>
                                <td>2024-01-01</td>
                                <td>
                                    <a href="#" class="btn btn-primary btn-sm"><i class="fas fa-eye"></i> View</a>
                                    <a href="#" class="btn btn-info btn-sm"><i class="fas fa-edit"></i> Edit</a>
                                    <a href="#" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i> Delete</a>
                                </td>
                            </tr>
                            <tr>
                                <td>2</td>
                                <td>Document Title 2</td>
                                <td>Author 2</td>
                                <td>2024-02-01</td>
                                <td>
                                    <a href="#" class="btn btn-primary btn-sm"><i class="fas fa-eye"></i> View</a>
                                    <a href="#" class="btn btn-info btn-sm"><i class="fas fa-edit"></i> Edit</a>
                                    <a href="#" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i> Delete</a>
                                </td>
                            </tr>
                            <!-- Add more rows dynamically as needed -->
                        </tbody>
                    </table>
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

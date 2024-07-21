<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>
    <!-- Bootstrap CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="fontawesome/css/all.min.css">
    
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f0f0f0;
            margin: 0;
            padding: 0;
            overflow-x: hidden;
        }

        /* Sidebar */
        .sidebar {
            background: linear-gradient(45deg, #0cc0df, #48d1cc);
            color: #fff;
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            width: 250px;
            padding-top: 20px;
            overflow-y: auto;
            transition: all 0.3s;
            box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1);
        }
        .sidebar a {
            display: block;
            padding: 12px 20px;
            color: #fff;
            text-decoration: none;
            transition: background-color 0.3s, padding-left 0.3s;
            border-radius: 5px;
        }
        .sidebar a:hover {
            background-color: #5ce1e6;
            padding-left: 30px;
        }
        .sidebar .active {
            background-color: #48d1cc;
            font-weight: bold;
        }
        .sidebar h3 {
            font-size: 1.5rem;
            margin-bottom: 20px;
            text-transform: capitalize;
            text-align: center;
            color: #fff;
        }
        .sidebar .welcome-message {
            padding: 15px 20px;
            border-radius: 10px;
            margin-bottom: 20px;
            font-weight: bold;
            text-align: center;
            background-color: rgba(255, 255, 255, 0.1);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.5);
        }
        .sidebar .welcome-message span {
            color: #fff;
            font-size: 1.2rem;
        }
        .content {
            margin-left: 250px;
            padding: 20px;
        }
        .folder i {
            font-size: 48px;
            margin-bottom: 10px;
            color: #0cc0df;
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="text-center">
            <div class="welcome-message">
                <span>Welcome,</span>
                <h3><?php echo $name; ?></h3>
            </div>
        </div>
        <a href="user_dashboard.php" class="<?php echo ($current_page == 'user_dashboard.php') ? 'active' : ''; ?>"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
        <a href="user_upload.php" class="<?php echo ($current_page == 'user_upload.php') ? 'active' : ''; ?>"><i class="fas fa-upload"></i> Upload Documents</a>
        <a href="user_documents.php" class="<?php echo ($current_page == 'user_documents.php') ? 'active' : ''; ?>"><i class="fas fa-file-alt"></i> Documents</a>
        <a href="user_folders.php" class="<?php echo ($current_page == 'user_folders.php') ? 'active' : ''; ?>"><i class="fas fa-folder"></i> Folders</a>
        <a href="user_friends.php" class="<?php echo ($current_page == 'user_friends.php') ? 'active' : ''; ?>"><i class="fas fa-user-friends"></i> Friends</a>
        <a href="user_shared.php" class="<?php echo ($current_page == 'user_shared.php') ? 'active' : ''; ?>"><i class="fas fa-share-alt"></i> Shared Folders</a>
        <a href="user_pinsetup.php" class="<?php echo ($current_page == 'user_pinsetup.php') ? 'active' : ''; ?>"><i class="fas fa-key"></i> PIN Setup</a>
        <a href="user_logout.php" class="<?php echo ($current_page == 'user_logout.php') ? 'active' : ''; ?>"><i class="fas fa-sign-out-alt"></i> Logout</a>
    </div>

    <!-- Main content -->
    <div class="content">
        <!-- Your page content goes here -->
    </div>

    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Font Awesome -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/js/all.min.js"></script>
</body>
</html>

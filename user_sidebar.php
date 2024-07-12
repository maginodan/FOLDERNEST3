

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">


    <style>
        /* Custom styles here */
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f0f0f0;
            margin: 0;
            padding: 0;
        }
        .sidebar {
            background-color: #0cc0df; /* Sidebar background color */
            color: #fff;
            height: 100vh; /* Full height */
            position: fixed;
            top: 0;
            left: 0;
            width: 250px;
            padding-top: 20px;
            border-radius: 0 15px 15px 0;
            overflow-y: auto; /* Enable scrolling if content exceeds height */
            transition: all 0.3s; /* Smooth transition for better user experience */
        }
        .sidebar a {
            display: block;
            padding: 12px 20px;
            color: #fff;
            text-decoration: none;
            transition: background-color 0.3s; /* Smooth transition for hover effect */
        }
        .sidebar a:hover {
            background-color: #5ce1e6; /* Highlight on hover */
        }
        .sidebar .active {
            background-color: #48d1cc; /* Active link background color */
            font-weight: bold;
        }
        .sidebar h3 {
            font-size: 1.5rem;
            margin-bottom: 20px;
            text-transform: capitalize; /* Capitalize admin name */
            text-align: center;
            color: #fff; /* Text color for admin name */
        }
        .sidebar .welcome-message {
            padding: 15px 20px;
            background-color: #48d1cc; /* Welcome message background color */
            border-radius: 10px;
            margin-bottom: 20px;
            font-weight: bold;
            text-align: center;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .sidebar .welcome-message span {
            color: #fff;
            font-size: 1.2rem;
        }
        .content {
            margin-left: 250px;
            padding: 20px;
        }
        .card {
            margin-bottom: 20px;
            border: none;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .card-header {
            background-color: #48d1cc; /* Card header background color */
            color: #fff;
            border-radius: 10px 10px 0 0;
        }
        .card-body {
            padding: 20px;
        }
        .card-body h5 {
            color: #0cc0df; /* Heading color */
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
    <a href="user_upload.php" class="<?php echo ($current_page == 'user_upload.php') ? 'active' : ''; ?>"><i class="fas fa-users"></i> Upload Documents</a>
    <a href="user_documents.php" class="<?php echo ($current_page == 'user_documents.php') ? 'active' : ''; ?>"><i class="fas fa-file-alt"></i> Documents</a>
    <a href="user_folders.php" class="<?php echo ($current_page == 'user_folders.php') ? 'active' : ''; ?>"><i class="fas fa-cogs"></i> Folders</a>
    <a href="user_friends.php" class="<?php echo ($current_page == 'user_friends.php') ? 'active' : ''; ?>"><i class="fas fa-user"></i> Friends</a>
    <a href="user_shared.php" class="<?php echo ($current_page == 'user_shared.php') ? 'active' : ''; ?>"><i class="fas fa-user"></i> Shared Folders</a>
    <a href="user_pinsetup.php" class="<?php echo ($current_page == 'user_pinsetup.php') ? 'active' : ''; ?>"><i class="fas fa-sign-out-alt"></i> PIN Setup</a>
    <a href="user_logout.php" class="<?php echo ($current_page == 'user_logout.php') ? 'active' : ''; ?>"><i class="fas fa-sign-out-alt"></i> Logout</a>
</div>



<!-- Bootstrap Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<!-- Font Awesome -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/js/all.min.js"></script>
<script src="js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
    session_start();
    if (!isset($_SESSION['SESSION_EMAIL'])) {
        header("Location: login.php");
        die();
    }

    // Assuming role check has already been done in index.php
    // If this page is directly accessed, ensure role is not admin
    if ($_SESSION['SESSION_ROLE'] === 'admin') {
        header("Location: admin_dashboard.php"); // Redirect admin to admin dashboard
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

    // Get the current page filename
$current_page = basename($_SERVER['PHP_SELF']);
?>


<!-- ////////////////////////// -->
<?php
// Example: Replace this with your actual logic to fetch the admin's name
$admin_name = "John Doe"; // Example name, replace with actual logic

// Example: Simulated database interaction for folders
$folders = [
    ['id' => 1, 'name' => 'Folder A', 'created_by' => 'John Doe', 'created_on' => '2024-07-15 10:00 AM'],
    ['id' => 2, 'name' => 'Folder B', 'created_by' => 'Jane Smith', 'created_on' => '2024-07-15 11:30 AM']
    // Add more folders as needed
];

// Handle form submission for creating a new folder
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Assuming form input is named 'folder_name'
    $folder_name = $_POST['folder_name'];

    // Simulated logic to add folder to database or array (replace with actual logic)
    $new_folder = [
        'id' => count($folders) + 1,
        'name' => $folder_name,
        'created_by' => $admin_name,
        'created_on' => date('Y-m-d H:i:s') // Current timestamp
    ];

    // Add new folder to the list
    $folders[] = $new_folder;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>System Dashboard</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <!-- local files -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="fontawesome/css/all.min.css">

    <style>
        /* Custom styles for content */
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
            background-color: #48d1cc;
            color: #fff;
            border-radius: 10px 10px 0 0;
        }
        .card-body {
            padding: 20px;
        }
        .card-body h5 {
            color: #0cc0df;
        }
        .table-responsive {
            margin-top: 20px;
        }
    </style>
</head>
<body>

<!-- Sidebar -->
<?php include('user_sidebar.php'); ?>

<!-- Page Content -->
<div class="content">
    <h1>Welcome to the System Dashboard, <?php echo $admin_name; ?></h1>

    <!-- Create Folder Form -->
    <div class="card">
        <div class="card-header">Create New Folder</div>
        <div class="card-body">
            <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                <div class="mb-3">
                    <label for="folder_name" class="form-label">Folder Name</label>
                    <input type="text" class="form-control" id="folder_name" name="folder_name" required>
                </div>
                <button type="submit" class="btn btn-primary">Create Folder</button>
            </form>
        </div>
    </div>

    <!-- Display Folders -->
    <div class="card">
        <div class="card-header">Folders</div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Folder Name</th>
                            <th>Created By</th>
                            <th>Created On</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($folders as $folder): ?>
                        <tr>
                            <td><?php echo $folder['id']; ?></td>
                            <td><?php echo $folder['name']; ?></td>
                            <td><?php echo $folder['created_by']; ?></td>
                            <td><?php echo $folder['created_on']; ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<!-- Font Awesome -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/js/all.min.js"></script>

<script src="js/bootstrap.bundle.min.js"></script>
</body>
</html>

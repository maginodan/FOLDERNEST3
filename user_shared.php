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
?>


<!-- ///////////////////////// -->




<?php
// Example: Replace this with your actual logic to fetch the admin's name
$admin_name = "John Doe"; // Example name, replace with actual logic

// Example: Simulated database interaction for folders and shared folders
$folders = [
    ['id' => 1, 'name' => 'Folder 1'],
    ['id' => 2, 'name' => 'Folder 2']
    // Add more folders as needed
];

$shared_folders = [
    ['id' => 3, 'name' => 'Shared Folder A', 'shared_with' => 'Alice Johnson'],
    ['id' => 4, 'name' => 'Shared Folder B', 'shared_with' => 'David Wilson']
    // Add more shared folders as needed
];

// Handle form submission for sharing a folder
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['folder_id'])) {
    // Simulated logic to handle sharing folder
    $folder_id = $_POST['folder_id'];
    $folder_name = $_POST['folder_name'];
    $shared_with = $_POST['shared_with'];

    // Simulated confirmation message
    $message = "Folder '$folder_name' shared with $shared_with.";
}

// Get the current page filename
$current_page = basename($_SERVER['PHP_SELF']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shared</title>
    <!-- Bootstrap CSS -->

    <!-- local files -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="fontawesome/css/all.min.css">

    <style>
        /* Custom styles for content */
        .content {
            margin-left: 250px;
            padding: 20px;
        }

        .content h1{
            color: #5ce1e6;
        }
        .card {
            margin-bottom: 20px;
            border: none;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .card .card-header {
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
<!-- nav bar -->
<?php include 'navbar.php'; ?>
<!-- Sidebar -->
<?php include('user_sidebar.php'); ?>

<!-- Page Content -->
<div class="content">
    <h1>shared</h1>

    <!-- Share Folder Form -->
    <div class="card">
        <div class="card-header">Share Folder</div>
        <div class="card-body">
            <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                <div class="mb-3">
                    <label for="folder_id" class="form-label">Select Folder</label>
                    <select class="form-select" id="folder_id" name="folder_id" required>
                        <option value="">Select folder...</option>
                        <?php foreach ($folders as $folder): ?>
                        <option value="<?php echo $folder['id']; ?>"><?php echo $folder['name']; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="shared_with" class="form-label">Share with</label>
                    <input type="text" class="form-control" id="shared_with" name="shared_with" required>
                </div>
                <button type="submit" class="btn btn-primary">Share Folder</button>
            </form>
            <?php if (isset($message)): ?>
            <div class="mt-3 alert alert-success" role="alert">
                <?php echo $message; ?>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Display Shared Folders -->
    <div class="card">
        <div class="card-header">Shared Folders</div>
        <div class="card-body">
            <?php if (empty($shared_folders)): ?>
            <p>No folders shared yet.</p>
            <?php else: ?>
            <ul class="list-group">
                <?php foreach ($shared_folders as $shared_folder): ?>
                <li class="list-group-item">
                    <?php echo $shared_folder['name']; ?> (Shared with <?php echo $shared_folder['shared_with']; ?>)
                    <!-- Include option to manage shared folders -->
                    <form method="POST" action="process_share_folder.php" style="display: inline;">
                        <input type="hidden" name="shared_folder_id" value="<?php echo $shared_folder['id']; ?>">
                        <input type="hidden" name="action" value="remove">
                        <button type="submit" class="btn btn-danger btn-sm mx-2">Remove</button>
                    </form>
                </li>
                <?php endforeach; ?>
            </ul>
            <?php endif; ?>
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

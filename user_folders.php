<?php
session_start();
if (!isset($_SESSION['SESSION_EMAIL'])) {
    header("Location: login.php");
    die();
}

// Include necessary files and configurations
include 'connection/config.php';

// Retrieve user data based on session email
$query = mysqli_query($conn, "SELECT * FROM users WHERE email='{$_SESSION['SESSION_EMAIL']}'");

if (mysqli_num_rows($query) > 0) {
    $row = mysqli_fetch_assoc($query);
    $user_id = $row['id']; // User's ID
    $name = $row['name']; // User's name
} else {
    // Handle case where user data is not found (although this should not happen if user is logged in)
    header("Location: login.php");
    die();
}

// Handle form submission for creating a new folder
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['create_folder'])) {
    // Validate and sanitize input (you can add more validation as needed)
    $folder_name = mysqli_real_escape_string($conn, $_POST['folder_name']);
    
    // SQL query to insert new folder into database with creator's name
    $insert_query = "INSERT INTO folder (folder_name, created_by_name, created_at) 
                     VALUES ('$folder_name', '$name', current_timestamp())";

    if (mysqli_query($conn, $insert_query)) {
        // Redirect after successful folder creation
        header("Location: {$_SERVER['PHP_SELF']}");
        die();
    } else {
        echo "Error: " . mysqli_error($conn); // Handle database error
    }
}

// Handle deletion of folders and associated documents
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_folder'])) {
    $folder_id = mysqli_real_escape_string($conn, $_POST['delete_folder']);
    
    // First, delete associated documents
    $delete_documents_query = "DELETE FROM documents WHERE folder_id = '$folder_id'";
    if (mysqli_query($conn, $delete_documents_query)) {
        // Now, delete the folder itself
        $delete_folder_query = "DELETE FROM folder WHERE folder_id = '$folder_id'";
        if (mysqli_query($conn, $delete_folder_query)) {
            // Redirect after successful deletion
            header("Location: {$_SERVER['PHP_SELF']}");
            die();
        } else {
            echo "Error deleting folder: " . mysqli_error($conn); // Handle folder deletion error
        }
    } else {
        echo "Error deleting documents: " . mysqli_error($conn); // Handle documents deletion error
    }
}

// Handle renaming/editing of folders
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['edit_folder'])) {
    $folder_id = mysqli_real_escape_string($conn, $_POST['edit_folder']);
    $new_folder_name = mysqli_real_escape_string($conn, $_POST['new_folder_name']);
    
    // SQL query to update folder name
    $update_query = "UPDATE folder SET folder_name = '$new_folder_name' WHERE folder_id = '$folder_id'";

    if (mysqli_query($conn, $update_query)) {
        // Redirect after successful update
        header("Location: {$_SERVER['PHP_SELF']}");
        die();
    } else {
        echo "Error updating folder: " . mysqli_error($conn); // Handle database error
    }
}

// Fetch all folders associated with the logged-in user, including folder size calculation
$fetch_query = mysqli_query($conn, "SELECT f.*, SUM(d.file_size) AS folder_size FROM folder f LEFT JOIN documents d ON f.folder_id = d.folder_id WHERE f.created_by_name = '$name' GROUP BY f.folder_id");

// Get the current page filename
$current_page = basename($_SERVER['PHP_SELF']);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Folders</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

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
        .folder-icon {
            color: #48d1cc; /* Folder icon color */
            font-size: 24px; /* Larger folder icon size */
            margin-right: 10px;
        }
        .action-buttons {
            display: flex; /* Use flexbox for inline buttons */
            align-items: center; /* Align items vertically */
        }
        .action-buttons form {
            margin-right: 10px; /* Add spacing between buttons */
        }
        @media (max-width: 576px) {
            .action-buttons {
                display: block; /* Stack buttons on small screens */
                margin-top: 5px; /* Add spacing between stacked buttons */
            }
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
    <h1>folders</h1>

    <!-- Create Folder Form -->
    <div class="card">
        <div class="card-header">Create New Folder</div>
        <div class="card-body">
            <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                <div class="mb-3">
                    <label for="folder_name" class="form-label">Folder Name</label>
                    <input type="text" class="form-control" id="folder_name" name="folder_name" required>
                </div>
                <button type="submit" name="create_folder" class="btn btn-primary">Create Folder</button>
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
                            <th>Size</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($folder = mysqli_fetch_assoc($fetch_query)): ?>
                            <tr>
                                <td><?php echo $folder['folder_id']; ?></td>
                                <td><i class="fas fa-folder folder-icon"></i><?php echo $folder['folder_name']; ?></td>
                                <td><?php echo $folder['created_by_name']; ?></td>
                                <td><?php echo $folder['created_at']; ?></td>
                                <td><?php echo $folder['folder_size'] ? $folder['folder_size'] . ' KB' : '0 KB'; ?></td>
                                <td class="action-buttons">
                                    <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                                        <input type="hidden" name="delete_folder" value="<?php echo $folder['folder_id']; ?>">
                                        <button type="submit" class="btn btn-outline-danger btn-sm" onclick="return confirm('Are you sure you want to delete this folder and all its documents?')">Delete</button>
                                    </form>
                                    <button type="button" class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#editFolderModal<?php echo $folder['folder_id']; ?>">Edit</button>
                                </td>
                            </tr>
                            <!-- Edit Folder Modal -->
                            <div class="modal fade" id="editFolderModal<?php echo $folder['folder_id']; ?>" tabindex="-1" aria-labelledby="editFolderModalLabel<?php echo $folder['folder_id']; ?>" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="editFolderModalLabel<?php echo $folder['folder_id']; ?>">Edit Folder Name</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                                                <input type="hidden" name="edit_folder" value="<?php echo $folder['folder_id']; ?>">
                                                <div class="mb-3">
                                                    <label for="new_folder_name<?php echo $folder['folder_id']; ?>" class="form-label">New Folder Name</label>
                                                    <input type="text" class="form-control" id="new_folder_name<?php echo $folder['folder_id']; ?>" name="new_folder_name" required>
                                                </div>
                                                <button type="submit" class="btn btn-primary">Save changes</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endwhile; ?>
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

</body>
</html>

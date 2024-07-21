<?php
session_start();

// Redirect to login page if session email is not set
if (!isset($_SESSION['SESSION_EMAIL'])) {
    header("Location: login.php");
    die();
}

// Include database connection
include 'connection/config.php';

// Retrieve user data based on session email
$query = mysqli_query($conn, "SELECT * FROM users WHERE email='{$_SESSION['SESSION_EMAIL']}'");

if (mysqli_num_rows($query) > 0) {
    $row = mysqli_fetch_assoc($query);
    $user_id = $row['id'];
    $name = $row['name'];
} else {
    // Redirect to login if user data is not found (although this should not happen if user is logged in)
    header("Location: login.php");
    die();
}

// Handle file upload logic
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES['file']) && isset($_POST['folder_id'])) {
    // Directory where uploads will be stored
    $upload_dir = 'uploads/';

    // Ensure the upload directory exists
    if (!file_exists($upload_dir)) {
        mkdir($upload_dir, 0777, true); // Create the directory recursively
    }

    // File details
    $file_name = $_FILES['file']['name'];
    $file_tmp = $_FILES['file']['tmp_name'];
    $file_size = $_FILES['file']['size'];
    $folder_id = $_POST['folder_id'];
    $file_path = $upload_dir . $file_name; // Assuming the file path in server storage

    // Check if the selected folder exists
    $folder_query = mysqli_query($conn, "SELECT * FROM folder WHERE folder_id = '$folder_id' AND is_deleted = 0");
    if (mysqli_num_rows($folder_query) == 0) {
        $error = "The selected folder does not exist or has been deleted.";
    } else {
        // Attempt to move uploaded file
        if (move_uploaded_file($file_tmp, $file_path)) {
            // File upload successful, insert file details into database
            $insert_query = "INSERT INTO documents (folder_id, document_name, file_path, file_size, created_by_name) 
                            VALUES ('$folder_id', '$file_name', '$file_path', '$file_size', '$name')";

            if (mysqli_query($conn, $insert_query)) {
                // Update the folder size
                $update_folder_query = "UPDATE folder SET folder_size = folder_size + $file_size WHERE folder_id = '$folder_id'";
                if (mysqli_query($conn, $update_folder_query)) {
                    $message = "File successfully uploaded: " . $file_name;
                } else {
                    $error = "Error updating folder size: " . mysqli_error($conn);
                }
            } else {
                $error = "Error uploading file. Please try again.";
            }
        } else {
            // Error moving file
            $error = "Error uploading file: Unable to move uploaded file.";
        }
    }
}

// Fetch all folders associated with the logged-in user
$fetch_folders_query = mysqli_query($conn, "SELECT * FROM folder WHERE created_by_name = '$name' AND is_deleted = 0");

// Get the current page filename
$current_page = basename($_SERVER['PHP_SELF']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Documents</title>
    <!-- Bootstrap CSS -->
    <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"> -->
    <!-- Font Awesome -->
    <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"> -->
    <!-- Local Styles -->

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
       .card .card-header {
            background-color: #48d1cc;
            color: #fff;
            border-radius: 10px 10px 0 0;
        }

        .content h1{
            color: #5ce1e6;
        }

        .card-body {
            padding: 20px;
        }
        .card-body h5 {
            color: #0cc0df;
        }
        .dropzone {
            border: 2px dashed #ccc;
            padding: 20px;
            text-align: center;
            color: #666;
            cursor: pointer;
        }
        .dropzone.hover {
            border-color: #48d1cc;
            color: #48d1cc;
        }
        #fileInput {
            display: none;
        }
        .custom-file-upload {
            display: inline-block;
            padding: 10px 20px;
            cursor: pointer;
            background-color: #0cc0df;
            color: #fff;
            border: none;
            border-radius: 5px;
            text-decoration: none;
        }
        .custom-file-upload:hover {
            background-color: #48d1cc;
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
    <h1>Upload your files from here</h1>

    <!-- Upload Document Section -->
    <div class="card">
        <div class="card-header">Upload Documents</div>
        <div class="card-body">
            <div class="mb-3">
                <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" enctype="multipart/form-data">
                    <label for="fileInput" class="custom-file-upload">
                        <i class="fas fa-cloud-upload-alt"></i> Select Files
                    </label>
                    <input type="file" id="fileInput" name="file" style="display: none;">
                    <div class="dropzone mt-3" id="dropzone">
                        <i class="fas fa-cloud-upload-alt fa-3x mb-3"></i>
                        <p>Drag & Drop files here</p>
                    </div>
                    <div class="mb-3">
                        <label for="folderSelect" class="form-label">Choose Folder:</label>
                        <select class="form-select" id="folderSelect" name="folder_id">
                            <?php while ($folder_row = mysqli_fetch_assoc($fetch_folders_query)): ?>
                                <option value="<?php echo $folder_row['folder_id']; ?>"><?php echo $folder_row['folder_name']; ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary mt-3">Upload</button>
                </form>
            </div>
            <?php if (isset($message)): ?>
            <div class="alert alert-success" role="alert">
                <?php echo $message; ?>
            </div>
            <?php endif; ?>
            <?php if (isset($error)): ?>
            <div class="alert alert-danger" role="alert">
                <?php echo $error; ?>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Bootstrap Bundle with Popper -->
<script src="js/bootstrap.bundle.min.js"></script>
<!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script> -->
<!-- Font Awesome -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/js/all.min.js"></script>
<!-- Local Scripts -->
<script>
    // JavaScript for drag and drop functionality
    const dropzone = document.getElementById('dropzone');

    dropzone.addEventListener('dragover', (event) => {
        event.preventDefault();
        dropzone.classList.add('hover');
    });

    dropzone.addEventListener('dragleave', () => {
        dropzone.classList.remove('hover');
    });

    dropzone.addEventListener('drop', (event) => {
        event.preventDefault();
        dropzone.classList.remove('hover');

        const files = event.dataTransfer.files;
        document.getElementById('fileInput').files = files;
    });
</script>

</body>
</html>

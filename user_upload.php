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

// Handle file upload logic
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES['file'])) {
    // Example: Handle file upload here, validate, and save to server
    $upload_dir = 'uploads/'; // Directory where uploads will be stored
    $uploaded_file = $upload_dir . basename($_FILES['file']['name']);

    if (move_uploaded_file($_FILES['file']['tmp_name'], $uploaded_file)) {
        // Example: Handle description
        $description = $_POST['description'] ?? ''; // Retrieve description from form
        $message = "File successfully uploaded: " . htmlspecialchars(basename($_FILES['file']['name'])) . " with description: " . htmlspecialchars($description);
    } else {
        $error = "Sorry, there was an error uploading your file.";
    }
}
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

<!-- Sidebar -->
<?php include('user_sidebar.php'); ?>

<!-- Page Content -->
<div class="content">
    <h1>Welcome to the System Dashboard, <?php echo $name; ?></h1>

    <!-- Upload Document Section -->
    <div class="card">
        <div class="card-header">Upload Documents</div>
        <div class="card-body">
            <div class="mb-3">
                <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" enctype="multipart/form-data">
                    <label for="fileInput" class="custom-file-upload">
                        <i class="fas fa-cloud-upload-alt"></i> Select Files
                    </label>
                    <input type="file" id="fileInput" name="file" style="display: none;" multiple>
                    <div class="dropzone mt-3" id="dropzone">
                        <i class="fas fa-cloud-upload-alt fa-3x mb-3"></i>
                        <p>Drag & Drop files here</p>
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Description (optional)</label>
                        <textarea class="form-control" id="description" name="description" rows="3"></textarea>
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
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

<!-- Font Awesome -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/js/all.min.js"></script>

<!-- local files -->
<script src="js/bootstrap.bundle.min.js"></script>
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

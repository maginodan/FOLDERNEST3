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

// Fetch all folders associated with the logged-in user
$fetch_folders_query = mysqli_query($conn, "SELECT * FROM folder WHERE created_by_name = '$name' AND is_deleted = 0");

// Function to get file icon and color based on extension
function getFileIcon($file_name) {
    $ext = pathinfo($file_name, PATHINFO_EXTENSION);
    switch ($ext) {
        case 'pdf':
            return ['icon' => 'far fa-file-pdf', 'color' => '#e84118']; // Red color for PDF
        case 'doc':
        case 'docx':
            return ['icon' => 'far fa-file-word', 'color' => '#3498db']; // Blue color for Word documents
        case 'xls':
        case 'xlsx':
            return ['icon' => 'far fa-file-excel', 'color' => '#27ae60']; // Green color for Excel files
        case 'ppt':
        case 'pptx':
            return ['icon' => 'far fa-file-powerpoint', 'color' => '#e74c3c']; // Red color for PowerPoint presentations
        case 'png':
        case 'jpg':
        case 'jpeg':
        case 'gif':
            return ['icon' => 'far fa-file-image', 'color' => '#f39c12']; // Yellow color for image files
        default:
            return ['icon' => 'far fa-file', 'color' => '#7f8c8d']; // Default color for other file types
    }
}

// Handle file upload
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["fileUpload"])) {
    $folder_id = $_POST['folderId'];
    $fileName = $_POST['fileName'];
    $fileTmp = $_FILES["fileUpload"]["tmp_name"];
    $fileType = pathinfo($_FILES["fileUpload"]["name"], PATHINFO_EXTENSION);
    $fileSize = $_FILES["fileUpload"]["size"];
    $uploadDir = "uploads/"; // Adjust as per your setup

    // Generate unique file name
    $fileNewName = uniqid() . '_' . $fileName . '.' . $fileType;
    $targetFilePath = $uploadDir . $fileNewName;

    // Upload file
    if (move_uploaded_file($fileTmp, $targetFilePath)) {
        // Insert file details into database
        $insertFileQuery = "INSERT INTO documents (folder_id, document_name, file_path, file_size, created_by_name)
                            VALUES ('$folder_id', '$fileName', '$targetFilePath', '$fileSize', '$name')";
        if (mysqli_query($conn, $insertFileQuery)) {
            // Redirect to current page to avoid form resubmission on refresh
            header("Location: {$_SERVER['PHP_SELF']}");
            exit();
        } else {
            echo "Error: " . mysqli_error($conn);
        }
    } else {
        echo "File upload failed.";
    }
}

// Handle file deletion
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['deleteDocumentId'])) {
    $documentId = $_POST['deleteDocumentId'];

    // Retrieve file path from database
    $getFilePathQuery = "SELECT file_path FROM documents WHERE document_id = '$documentId'";
    $result = mysqli_query($conn, $getFilePathQuery);

    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $filePath = $row['file_path'];

        // Delete file from server
        if (unlink($filePath)) {
            // Delete file record from database
            $deleteFileQuery = "DELETE FROM documents WHERE document_id = '$documentId'";
            if (mysqli_query($conn, $deleteFileQuery)) {
                // Redirect to current page to avoid form resubmission on refresh
                header("Location: {$_SERVER['PHP_SELF']}");
                exit();
            } else {
                echo "Error deleting file record: " . mysqli_error($conn);
            }
        } else {
            echo "Error deleting file from server.";
        }
    } else {
        echo "File not found or already deleted.";
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
    <title>documents</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!-- Local Styles -->
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
        .card-header {
            background-color: #48d1cc; /* System's primary color */
            color: #fff;
            border-radius: 10px 10px 0 0;
            padding: 10px 20px; /* Added padding */
        }
        .card-body {
            padding: 20px;
        }
        .card-body h5 {
            color: #0cc0df; /* System's secondary color */
        }
        .folder {
            background-color: #ffffff; /* System's folder background color */
            border: 1px solid #ccc;
            border-radius: 10px;
            padding: 10px;
            margin-bottom: 20px;
            cursor: pointer;
        }
        .folder:hover {
            background-color: #f0f0f0; /* Lighter shade for hover */
        }
        .folder-title {
            display: flex;
            align-items: center;
        }
        .folder-title i {
            font-size: 24px;
            margin-right: 10px;
            /* Color set dynamically */
        }
        .folder-content {
            display: none; /* Initially hide folder content */
            padding-left: 20px;
        }
        .table {
            width: 100%;
            margin-bottom: 1rem;
            color: #212529;
            border-collapse: collapse;
        }
        .table th,
        .table td {
            padding: 0.75rem;
            vertical-align: top;
            border-top: 1px solid #dee2e6;
        }
        .table th {
            background-color: #f8f9fa; /* Light gray for table header */
            font-weight: bold;
            text-align: left;
        }
        .table tbody+tbody {
            border-top: 2px solid #dee2e6;
        }
        .table-hover tbody tr:hover {
            background-color: rgba(0, 0, 0, 0.075);
        }
        .table-sm th,
        .table-sm td {
            padding: 0.3rem;
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
    <h1>documents</h1>

    <!-- Display Folders -->
    <div class="row">
        <?php while ($folder_row = mysqli_fetch_assoc($fetch_folders_query)): ?>
            <div class="col-md-12">
                <div class="folder" onclick="toggleFolder(this)">
                    <div class="folder-title">
                        <i class="fas fa-folder fa-2x" style="color: #0cc0df;"></i>
                        <h3><?php echo htmlspecialchars($folder_row['folder_name']); ?></h3>
                    </div>
                    <div class="folder-content">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>File Name</th>
                                    <th>File Size</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $folder_id = $folder_row['folder_id'];
                                $fetch_documents_query = mysqli_query($conn, "SELECT * FROM documents WHERE folder_id = '$folder_id'");

                                while ($document_row = mysqli_fetch_assoc($fetch_documents_query)):
                                    $document_name = htmlspecialchars($document_row['document_name']);
                                    $file_icon = getFileIcon($document_name);
                                    $file_path = htmlspecialchars($document_row['file_path']);
                                    $file_size = htmlspecialchars($document_row['file_size']);
                                ?>
                                <tr>
                                    <td><?php echo $document_name; ?></td>
                                    <td><?php echo $file_size; ?> KB</td>
                                    <td>
                                        <a href="<?php echo $file_path; ?>" class="btn btn-sm btn-primary" target="_blank">View</a>
                                        <button type="button" class="btn btn-sm btn-danger" onclick="confirmDelete(<?php echo $document_row['document_id']; ?>)">Delete</button>
                                    </td>
                                </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        <?php endwhile; ?>
    </div>
</div>

<!-- Upload Modal -->
<div class="modal fade" id="uploadModal" tabindex="-1" aria-labelledby="uploadModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="uploadModalLabel">Upload File</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Upload Form -->
                <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label for="fileUpload" class="form-label">Choose File</label>
                        <input type="file" class="form-control" id="fileUpload" name="fileUpload" required>
                    </div>
                    <div class="mb-3">
                        <label for="fileName" class="form-label">File Name</label>
                        <input type="text" class="form-control" id="fileName" name="fileName" required>
                    </div>
                    <input type="hidden" name="folderId" value="<?php echo $folder_id; ?>">
                    <button type="submit" class="btn btn-primary">Upload</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Confirm Delete</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete this file?
            </div>
            <div class="modal-footer">
                <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" id="deleteForm">
                    <input type="hidden" name="deleteDocumentId" id="deleteDocumentId">
                    <button type="submit" class="btn btn-danger">Delete</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<!-- Font Awesome -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/js/all.min.js"></script>
<!-- Local Scripts -->
<script>
    function toggleFolder(folder) {
        // Toggle the display of folder content
        const folderContent = folder.querySelector('.folder-content');
        folderContent.style.display = folderContent.style.display === 'block' ? 'none' : 'block';
    }

    function confirmDelete(documentId) {
        document.getElementById('deleteDocumentId').value = documentId;
        var deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'), {
            keyboard: false
        });
        deleteModal.show();
    }
</script>

</body>
</html>

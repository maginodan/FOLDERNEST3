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
<?php
// Example: Replace with actual database connection and document retrieval logic
$documents = [
    ['id' => 1, 'name' => 'Document 1', 'uploaded_by' => 'John Doe', 'uploaded_on' => '2024-07-15 10:00 AM'],
    ['id' => 2, 'name' => 'Document 2', 'uploaded_by' => 'Jane Smith', 'uploaded_on' => '2024-07-15 11:30 AM'],
    // Add more documents as needed
];

// Function to simulate sharing functionality
function shareDocument($documentId, $recipient) {
    // Add logic to share document
    return true; // Example success
}

// Function to simulate deleting functionality
function deleteDocument($documentId) {
    // Add logic to delete document
    return true; // Example success
}

// Function to simulate editing functionality
function editDocument($documentId) {
    // Add logic to edit document
    return true; // Example success
}

// Handle actions if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action']) && isset($_POST['document_id'])) {
        $documentId = $_POST['document_id'];
        
        switch ($_POST['action']) {
            case 'delete':
                if (deleteDocument($documentId)) {
                    // Document deleted successfully, handle success or redirect
                    // Example: header('Location: documents.php');
                } else {
                    // Handle deletion failure
                }
                break;
            case 'edit':
                if (editDocument($documentId)) {
                    // Document edited successfully, handle success or redirect
                    // Example: header('Location: edit_document.php?id=' . $documentId);
                } else {
                    // Handle editing failure
                }
                break;
            case 'share':
                // Example of handling sharing
                $recipient = $_POST['recipient']; // Example: get recipient from form input
                if (shareDocument($documentId, $recipient)) {
                    // Document shared successfully, handle success or redirect
                    // Example: header('Location: documents.php');
                } else {
                    // Handle sharing failure
                }
                break;
            default:
                // Handle unknown action
                break;
        }
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
    <title>Documents</title>
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
        .btn-action {
            margin-right: 5px;
        }
    </style>
</head>
<body>

<!-- Sidebar -->
<?php include('user_sidebar.php'); ?>

<!-- Page Content -->
<div class="content">
    <h1>Welcome to the Documents Page</h1>

    <div class="card">
        <div class="card-header">Documents</div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Document Name</th>
                            <th>Uploaded By</th>
                            <th>Uploaded On</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($documents as $document): ?>
                        <tr>
                            <td><?php echo $document['id']; ?></td>
                            <td><?php echo $document['name']; ?></td>
                            <td><?php echo $document['uploaded_by']; ?></td>
                            <td><?php echo $document['uploaded_on']; ?></td>
                            <td>
                                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                                    <input type="hidden" name="document_id" value="<?php echo $document['id']; ?>">
                                    <button type="submit" name="action" value="view" class="btn btn-info btn-action"><i class="fas fa-eye"></i> View</button>
                                    <button type="submit" name="action" value="edit" class="btn btn-primary btn-action"><i class="fas fa-edit"></i> Edit</button>
                                    <button type="submit" name="action" value="delete" class="btn btn-danger btn-action"><i class="fas fa-trash"></i> Delete</button>
                                    <button type="submit" name="action" value="share" class="btn btn-success btn-action" data-bs-toggle="modal" data-bs-target="#shareModal"><i class="fas fa-share"></i> Share</button>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Share Modal -->
<div class="modal fade" id="shareModal" tabindex="-1" aria-labelledby="shareModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="shareModalLabel">Share Document</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <div class="modal-body">
                    <input type="hidden" name="document_id" value="">
                    <div class="mb-3">
                        <label for="recipient" class="form-label">Recipient Email:</label>
                        <input type="email" class="form-control" id="recipient" name="recipient" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" name="action" value="share" class="btn btn-success"><i class="fas fa-share"></i> Share</button>
                </div>
            </form>
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

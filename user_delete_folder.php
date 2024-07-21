<?php
session_start();

// Include database connection
include 'connection/config.php';

// Check if folder_id is set
if (isset($_POST['folder_id'])) {
    $folder_id = $_POST['folder_id'];

    // Begin a transaction
    mysqli_begin_transaction($conn);

    try {
        // Delete files associated with the folder
        $delete_files_query = "DELETE FROM documents WHERE folder_id = '$folder_id'";
        if (!mysqli_query($conn, $delete_files_query)) {
            throw new Exception("Error deleting files: " . mysqli_error($conn));
        }

        // Delete the folder itself
        $delete_folder_query = "DELETE FROM folder WHERE folder_id = '$folder_id'";
        if (!mysqli_query($conn, $delete_folder_query)) {
            throw new Exception("Error deleting folder: " . mysqli_error($conn));
        }

        // Commit transaction
        mysqli_commit($conn);
        $message = "Folder and its files successfully deleted.";
    } catch (Exception $e) {
        // Rollback transaction on error
        mysqli_rollback($conn);
        $error = $e->getMessage();
    }
} else {
    $error = "Folder ID not provided.";
}

header("Location: user_dashboard.php"); // Redirect back to the user dashboard
?>

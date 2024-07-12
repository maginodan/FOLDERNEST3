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

<!-- ///////////////////////////// -->


<?php
// Example: Replace with actual logic to handle PIN setup and verification
$pin_set = false;
$pin_changed = false;
$current_pin = ''; // Example: Fetch current PIN from database or session

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'];

    if ($action === 'set') {
        $pin = $_POST['pin'];

        // Example: Validate and save PIN logic (replace with actual implementation)
        if (!empty($pin)) {
            // Save PIN to database or session
            $pin_set = true; // Example success
            $current_pin = $pin;
        } else {
            // Handle validation errors or empty PIN
        }
    } elseif ($action === 'change') {
        $new_pin = $_POST['new_pin'];

        // Example: Validate and update PIN logic (replace with actual implementation)
        if (!empty($new_pin)) {
            // Update PIN in database or session
            $pin_changed = true; // Example success
            $current_pin = $new_pin;
        } else {
            // Handle validation errors or empty new PIN
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
    <title>PIN Setup</title>
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
            max-width: 600px; /* Increase the maximum width */
            margin: auto;
            padding: 20px;
        }
        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            margin-top: 50px; /* Add margin to the top for spacing */
        }
        .card-header {
            background-color: #48d1cc;
            color: #fff;
            border-radius: 10px 10px 0 0;
        }
        .card-body {
            padding: 30px; /* Increase padding for better appearance */
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
    <div class="card">
        <div class="card-header"><?php echo ($pin_set || $pin_changed) ? 'Change PIN' : 'Set PIN'; ?></div>
        <div class="card-body">
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <?php if ($pin_set || $pin_changed): ?>
                    <div class="mb-3">
                        <label for="current_pin" class="form-label">Current PIN:</label>
                        <input type="password" class="form-control" id="current_pin" name="current_pin" value="<?php echo htmlspecialchars($current_pin); ?>" required>
                    </div>
                <?php endif; ?>
                <div class="mb-3">
                    <label for="pin" class="form-label"><?php echo ($pin_set || $pin_changed) ? 'New PIN:' : 'Enter PIN:'; ?></label>
                    <input type="password" class="form-control" id="pin" name="<?php echo ($pin_set || $pin_changed) ? 'new_pin' : 'pin'; ?>" required>
                </div>
                <input type="hidden" name="action" value="<?php echo ($pin_set || $pin_changed) ? 'change' : 'set'; ?>">
                <button type="submit" class="btn btn-primary btn-action"><i class="fas fa-lock"></i> <?php echo ($pin_set || $pin_changed) ? 'Change PIN' : 'Set PIN'; ?></button>
            </form>
            <?php if ($pin_set || $pin_changed): ?>
                <a href="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" class="btn btn-secondary btn-action"><i class="fas fa-times"></i> Cancel</a>
            <?php endif; ?>
            <?php if ($pin_set || $pin_changed): ?>
                <div class="alert alert-success mt-3" role="alert">
                    PIN <?php echo ($pin_set) ? 'set' : 'changed'; ?> successfully!
                </div>
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

<?php
session_start();
if (!isset($_SESSION['SESSION_EMAIL'])) {
    header("Location: login.php");
    die();
}

// Ensure user is not an admin
if ($_SESSION['SESSION_ROLE'] === 'admin') {
    header("Location: admin_dashboard.php");
    die();
}

// Include necessary files and configurations
include 'connection/config.php';

// Retrieve user data
$query = mysqli_query($conn, "SELECT * FROM users WHERE email='{$_SESSION['SESSION_EMAIL']}'");

if (mysqli_num_rows($query) > 0) {
    $row = mysqli_fetch_assoc($query);
    $name = $row['name'];
} else {
    header("Location: login.php");
    die();
}
?>

<?php
// PIN setup logic
$pin_set = false;
$pin_changed = false;
$current_pin = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'];

    if ($action === 'set') {
        $pin = $_POST['pin'];
        if (!empty($pin)) {
            $pin_set = true;
            $current_pin = $pin;
        }
    } elseif ($action === 'change') {
        $new_pin = $_POST['new_pin'];
        if (!empty($new_pin)) {
            $pin_changed = true;
            $current_pin = $new_pin;
        }
    }
}

$current_page = basename($_SERVER['PHP_SELF']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PIN Setup</title>
    <!-- Bootstrap CSS -->

    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="fontawesome/css/all.min.css">

    <style>
        .content {
            max-width: 600px;
            /* margin: auto;  */
            padding: 20px;
         } 
        



        .content h1{
            color: #5ce1e6;
        }
        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            margin-top: 50px;
        }
        .card .card-header {
            background-color: #48d1cc;
            color: #fff;
            border-radius: 10px 10px 0 0;
        }
        .card-body {
            padding: 30px;
        }
        .btn-action {
            margin-right: 5px;
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
<h1>set up your pin here, <?php echo $name; ?>!</h1>
    <div class="card">
        <div class="card-header">
            <?php echo ($pin_set || $pin_changed) ? 'Change PIN' : 'Set PIN'; ?>
        </div>
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
                <button type="submit" class="btn btn-primary btn-action">
                    <i class="fas fa-lock"></i> <?php echo ($pin_set || $pin_changed) ? 'Change PIN' : 'Set PIN'; ?>
                </button>
            </form>
            <?php if ($pin_set || $pin_changed): ?>
                <a href="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" class="btn btn-secondary btn-action">
                    <i class="fas fa-times"></i> Cancel
                </a>
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
<!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script> -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/js/all.min.js"></script>
<script src="js/bootstrap.bundle.min.js"></script>
</body>
</html>

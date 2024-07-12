
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

// Example: Simulated database interaction for friends and friend requests
$friends = [
    ['id' => 1, 'name' => 'Jane Smith'],
    ['id' => 2, 'name' => 'Michael Brown']
    // Add more friends as needed
];

$friend_requests = [
    ['id' => 3, 'name' => 'Alice Johnson'],
    ['id' => 4, 'name' => 'David Wilson']
    // Add more pending friend requests as needed
];

// Handle form submission for sending a friend request
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['friend_id'])) {
    // Simulated logic to handle sending friend request
    $friend_id = $_POST['friend_id'];
    $friend_name = $_POST['friend_name'];

    // Simulated confirmation message
    $message = "Friend request sent to $friend_name.";
}

// Get the current page filename
$current_page = basename($_SERVER['PHP_SELF']);
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

    <!-- Send Friend Request Form -->
    <div class="card">
        <div class="card-header">Send Friend Request</div>
        <div class="card-body">
            <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                <div class="mb-3">
                    <label for="friend_id" class="form-label">Select Friend</label>
                    <select class="form-select" id="friend_id" name="friend_id" required>
                        <option value="">Select friend...</option>
                        <?php foreach ($friends as $friend): ?>
                        <option value="<?php echo $friend['id']; ?>"><?php echo $friend['name']; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Send Request</button>
            </form>
            <?php if (isset($message)): ?>
            <div class="mt-3 alert alert-success" role="alert">
                <?php echo $message; ?>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Display Friends -->
    <div class="card">
        <div class="card-header">Friends</div>
        <div class="card-body">
            <?php if (empty($friends)): ?>
            <p>No friends added yet.</p>
            <?php else: ?>
            <ul class="list-group">
                <?php foreach ($friends as $friend): ?>
                <li class="list-group-item"><?php echo $friend['name']; ?></li>
                <?php endforeach; ?>
            </ul>
            <?php endif; ?>
        </div>
    </div>

    <!-- Display Friend Requests -->
    <div class="card">
        <div class="card-header">Friend Requests</div>
        <div class="card-body">
            <?php if (empty($friend_requests)): ?>
            <p>No pending friend requests.</p>
            <?php else: ?>
            <ul class="list-group">
                <?php foreach ($friend_requests as $request): ?>
                <li class="list-group-item">
                    <?php echo $request['name']; ?>
                    <form method="POST" action="process_friend_request.php" style="display: inline;">
                        <input type="hidden" name="request_id" value="<?php echo $request['id']; ?>">
                        <input type="hidden" name="action" value="accept">
                        <button type="submit" class="btn btn-success btn-sm mx-2">Accept</button>
                    </form>
                    <form method="POST" action="process_friend_request.php" style="display: inline;">
                        <input type="hidden" name="request_id" value="<?php echo $request['id']; ?>">
                        <input type="hidden" name="action" value="reject">
                        <button type="submit" class="btn btn-danger btn-sm">Reject</button>
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

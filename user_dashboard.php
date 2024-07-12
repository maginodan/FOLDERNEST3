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
// Example: Replace this with your actual logic to fetch the admin's name
$admin_name = "John Doe"; // Example name, replace with actual logic

// Get the current page filename
$current_page = basename($_SERVER['PHP_SELF']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

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
        .row {
            margin-top: 20px;
        }
        .quick-links .btn {
            display: block;
            width: 100%;
            margin-bottom: 10px;
        }
        .chart-container {
            height: 300px; /* Adjust as needed for consistent height */
        }
    </style>
</head>
<body>

<!-- Sidebar -->
<?php include('user_sidebar.php'); ?>

<!-- Page Content -->
<div class="content">
    <h1>Folder Nest welcomes you, <?php echo $name; ?></h1>

    <div class="row">
        <!-- Storage Usage Chart -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">Storage Usage</div>
                <div class="card-body">
                    <div class="chart-container">
                        <canvas id="storageChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Links -->
        <div class="col-md-6">
            <div class="card quick-links">
                <div class="card-header">Quick Links</div>
                <div class="card-body">
                    <a href="user_upload.php" class="btn btn-primary"><i class="fas fa-cloud-upload-alt"></i> Upload Documents</a>
                    <a href="user_documents.php" class="btn btn-secondary"><i class="fas fa-file-alt"></i> View Documents</a>
                    <a href="user_folders.php" class="btn btn-success"><i class="fas fa-folder"></i> Manage Folders</a>
                    <a href="user_friends.php" class="btn btn-info"><i class="fas fa-user-friends"></i> Manage Friends</a>
                    <a href="User_pinsetup.php" class="btn btn-warning"><i class="fas fa-lock"></i> PIN Setup</a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<!-- Font Awesome -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/js/all.min.js"></script>

<script src="js/bootstrap.bundle.min.js"></script>

<script>
    // Chart.js Script
    var ctx = document.getElementById('storageChart').getContext('2d');
    var storageChart = new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: ['Used', 'Free'],
            datasets: [{
                label: 'Storage Usage',
                data: [70, 30], // Example data (replace with actual usage data)
                backgroundColor: ['#0cc0df', '#48d1cc'],
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'top',
                }
            }
        }
    });
</script>
</body>
</html>

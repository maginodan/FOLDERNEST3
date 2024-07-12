<?php
    session_start();
    if (!isset($_SESSION['SESSION_EMAIL'])) {
        header("Location: login.php");
        die();
    }

    // Assuming role check has already been done in index.php
    // If this page is directly accessed, ensure role is admin
    if ($_SESSION['SESSION_ROLE'] !== 'admin') {
        header("Location: index.php"); // Redirect to index if not admin
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



<!-- //////////////////////////////// -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Panel</title>
    <!-- Bootstrap CSS -->
    <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"> -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"> -->
    <link rel="stylesheet" href="fontawesome/css/all.min.css">
    <!-- Chart.js -->
    <!-- <script src="https://cdn.jsdelivr.net/npm/chart.js"></script> -->
    <script src="js/chart.js"></script>
    <style>
        body {
            display: flex;
            min-height: 100vh;
            font-family: Arial, sans-serif;
            margin: 0;
            background-color: #f8f9fa; /* Light gray background */
        }
        .sidebar {
            width: 250px;
            background-color: #0aa0bf; /* Blue sidebar background */
            color: #fff;
            position: fixed;
            height: 100%;
            padding-top: 20px;
            z-index: 1;
        }
        .sidebar a {
            color: #fff;
            text-decoration: none;
            display: block;
            padding: 10px 20px;
            margin: 10px 0;
        }
        .sidebar a:hover {
            background-color: #3cb8c9; /* Light blue hover */
        }
        .sidebar .active {
            background-color: #48d1cc; /* Aqua active */
        }
        .content {
            margin-left: 250px;
            padding: 20px;
            flex: 1;
            background-color: #fff; /* White content background */
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
        }
        .content h1 {
            margin-top: 5px;
            color: #0aa0bf; /* Blue heading */
        }

    
    
        .img-fluid {
            max-width: 50px;
            height: auto;
            border-radius: 35%;
            filter: brightness(97%);
        }
        .card {
            margin-bottom: 20px;
            border: none;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .card-header {
            background-color: #48d1cc; /* Aqua card header */
            color: #fff;
            border-radius: 10px 10px 0 0;
        }
        .card-body {
            padding: 20px;
        }
        .card-body p {
            font-size: 18px; /* Smaller font size for card body */
            font-weight: bold;
            margin-bottom: 5px;
        }
        .card-body .small-text {
            font-size: 14px;
            color: #666;
        }
        .chart-container {
            position: relative;
            height: 200px; /* Adjust height as needed */
            max-width: 100%; /* Ensure charts are responsive */
            margin: 0 auto;
        }
        @media (max-width: 768px) {
            .sidebar {
                width: 100%;
                position: static;
                height: auto;
            }
            .content {
                margin-left: 0;
                width: 100%;
                padding: 10px;
            }
        }
    </style>
</head>
<body>

<?php include('admin_sidebar.php'); ?>

<div class="content">
<h1> Folder Nest Dashboard</h1>

    
    

    <div class="row row-cols-1 row-cols-md-2 g-4">
        <!-- Card 1: Total Users -->
        <div class="col">
            <div class="card">
                <div class="card-header">
                    <i class="fas fa-users"></i> Total Users
                </div>
                <div class="card-body">
                    <div class="chart-container">
                        <canvas id="totalUsersChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card 2: Total Documents -->
        <div class="col">
            <div class="card">
                <div class="card-header">
                    <i class="fas fa-file-alt"></i> Total Documents
                </div>
                <div class="card-body">
                    <div class="chart-container">
                        <canvas id="totalDocumentsChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card 3: Recently Added Users -->
        <div class="col">
            <div class="card">
                <div class="card-header">
                    <i class="fas fa-user-plus"></i> Recently Added Users
                </div>
                <div class="card-body">
                    <div class="chart-container">
                        <canvas id="recentUsersChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card 4: Shared Documents -->
        <div class="col">
            <div class="card">
                <div class="card-header">
                    <i class="fas fa-share-alt"></i> Shared Documents
                </div>
                <div class="card-body">
                    <div class="chart-container">
                        <canvas id="sharedDocumentsChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card 5: User Table -->
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <i class="fas fa-table"></i> User Table
                </div>
                <div class="card-body">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>User ID</th>
                                <th>Name</th>
                                <th>Email</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>1</td>
                                <td>John Doe</td>
                                <td>john.doe@example.com</td>
                            </tr>
                            <tr>
                                <td>2</td>
                                <td>Jane Smith</td>
                                <td>jane.smith@example.com</td>
                            </tr>
                            <!-- Add more rows as needed -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap Bundle with Popper -->
<script src="js/bootstrap.bundle.min.js"></script>
<!-- Font Awesome -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/js/all.min.js"></script>
<script>
    // Total Users Chart
    const totalUsersCtx = document.getElementById('totalUsersChart').getContext('2d');
    const totalUsersChart = new Chart(totalUsersCtx, {
        type: 'doughnut',
        data: {
            labels: ['Active', 'Inactive'],
            datasets: [{
                data: [280, 40],
                backgroundColor: ['#48d1cc', '#f1f1f1']
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false // Ensure the chart is not cut off
        }
    });

    // Total Documents Chart
    const totalDocumentsCtx = document.getElementById('totalDocumentsChart').getContext('2d');
    const totalDocumentsChart = new Chart(totalDocumentsCtx, {
        type: 'doughnut',
        data: {
            labels: ['Shared', 'Private'],
            datasets: [{
                data: [750, 500],
                backgroundColor: ['#48d1cc', '#f1f1f1']
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false // Ensure the chart is not cut off
        }
    });

    // Recently Added Users Chart
    const recentUsersCtx = document.getElementById('recentUsersChart').getContext('2d');
    const recentUsersChart = new Chart(recentUsersCtx, {
        type: 'bar',
        data: {
            labels: ['7 days ago', '6 days ago', '5 days ago', '4 days ago', '3 days ago', '2 days ago', 'Yesterday'],
            datasets: [{
                label: 'New Users',
                data: [2, 3, 4, 5, 2, 1, 15],
                backgroundColor: '#48d1cc'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false // Ensure the chart is not cut off
        }
    });

    // Shared Documents Chart
    const sharedDocumentsCtx = document.getElementById('sharedDocumentsChart').getContext('2d');
    const sharedDocumentsChart = new Chart(sharedDocumentsCtx, {
        type: 'doughnut',
        data: {
            labels: ['Public', 'Private'],
            datasets: [{
                data: [500, 250],
                backgroundColor: ['#48d1cc', '#f1f1f1']
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false // Ensure the chart is not cut off
        }
    });
</script>
</body>
</html>

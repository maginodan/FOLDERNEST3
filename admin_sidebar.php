


<?php
// Get the current page filename
$current_page = basename($_SERVER['PHP_SELF']);
?>

<div class="sidebar">
    <div class="text-center mb-4">
    <img src="images/logo3.png" alt="Company Logo" class="img-fluid rounded-circle" style="max-width: 50px; filter: brightness(97%); box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);">

    </div>
    <h3 class="text-center">Admin Panel</h3>
    <a href="admin_dashboard.php" class="<?php echo ($current_page == 'admin_dashboard.php') ? 'active' : ''; ?>"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
    <a href="admin_manage_users.php" class="<?php echo ($current_page == 'admin_manage_users.php') ? 'active' : ''; ?>"><i class="fas fa-users"></i> Manage Users</a>
    <a href="admin_manage_content.php" class="<?php echo ($current_page == 'admin_manage_content.php') ? 'active' : ''; ?>"><i class="fas fa-file-alt"></i> Manage Content</a>
    <a href="admin_settings.php" class="<?php echo ($current_page == 'admin_settings.php') ? 'active' : ''; ?>"><i class="fas fa-cogs"></i> Settings</a>
    <a href="admin_profile.php" class="<?php echo ($current_page == 'admin_profile.php') ? 'active' : ''; ?>"><i class="fas fa-user"></i> Profile</a>
    <a href="admin_logout.php" class="<?php echo ($current_page == 'admin_logout.php') ? 'active' : ''; ?>"><i class="fas fa-sign-out-alt"></i> Logout</a>
</div>


<?php
$msg = "";

include 'connection/config.php';

if (isset($_GET['reset'])) {
    $reset_code = mysqli_real_escape_string($conn, $_GET['reset']);
    $query = "SELECT * FROM users WHERE code='{$reset_code}' AND is_verified=1";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        if (isset($_POST['submit'])) {
            $password = mysqli_real_escape_string($conn, $_POST['password']);
            $confirm_password = mysqli_real_escape_string($conn, $_POST['confirm-password']);

            if ($password === $confirm_password) {
                // Hash the password
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);

                // Update password and clear code
                $update_query = "UPDATE users SET password='{$hashed_password}', code='' WHERE code='{$reset_code}'";
                $update_result = mysqli_query($conn, $update_query);

                if ($update_result) {
                    header("Location: login.php");
                    exit;
                } else {
                    $msg = "<div class='alert alert-danger'>Something went wrong. Please try again.</div>";
                }
            } else {
                $msg = "<div class='alert alert-danger'>Password and Confirm Password do not match.</div>";
            }
        }
    } else {
        $msg = "<div class='alert alert-danger'>Reset Link does not match or account is not verified.</div>";
    }
} else {
    header("Location: forgot-password.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="zxx">
<head>
    <title>FOLDER NEST</title>
    <!-- Meta tag Keywords -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta charset="UTF-8" />
    <meta name="keywords" content="Login Form" />
    <!-- //Meta tag Keywords -->

    <link href="//fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">

    <!--/Style-CSS -->
    <link rel="stylesheet" href="css/style.css" type="text/css" media="all" />
    <!--//Style-CSS -->

    <script src="https://kit.fontawesome.com/af562a2a63.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="fontawesome/css/all.min.css">

</head>
<body>

<!-- form section start -->
<section class="w3l-mockup-form">
    <div class="container">
        <!-- /form -->
        <div class="workinghny-form-grid">
            <div class="main-mockup">
                <div class="alert-close">
                    <span class="fas fa-times rounded-circle"></span>
                </div>
                <div class="w3l_form align-self">
                    <div class="left_grid_info">
                        <img src="images/reset2.png" alt="">
                    </div>
                </div>
                <div class="content-wthree">
                    <h2>Change Password</h2>
                    <p>Please enter your new password.</p>
                    <?php echo $msg; ?>
                    <form action="" method="post">
                        <input type="password" class="password" name="password" placeholder="Enter Your Password" required>
                        <input type="password" class="confirm-password" name="confirm-password" placeholder="Enter Your Confirm Password" required>
                        <button name="submit" class="btn" type="submit">Change Password</button>
                    </form>
                    <div class="social-icons">
                        <p>Back to! <a href="login.php">Login</a>.</p>
                    </div>
                </div>
            </div>
        </div>
        <!-- //form -->
    </div>
</section>
<!-- //form section start -->

<script src="js/jquery.min.js"></script>
<script>
    $(document).ready(function (c) {
        $('.alert-close').on('click', function (c) {
            $('.main-mockup').fadeOut('slow', function (c) {
                $('.main-mockup').remove();
            });
        });
    });
</script>

</body>
</html>

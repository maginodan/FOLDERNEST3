<?php
session_start();
if (isset($_SESSION['SESSION_EMAIL'])) {
    header("Location: index.php");
    die();
}

// Import PHPMailer classes into the global namespace
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// Load Composer's autoloader
require 'vendor/autoload.php';

include 'connection/config.php';
$msg = "";

if (isset($_POST['submit'])) {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $code = mysqli_real_escape_string($conn, md5(rand()));

    if (mysqli_num_rows(mysqli_query($conn, "SELECT * FROM users WHERE email='{$email}' AND is_verified=1")) > 0) {
        $query = mysqli_query($conn, "UPDATE users SET code='{$code}' WHERE email='{$email}'");

        if ($query) {        
            echo "<div style='display: none;'>";
            // Create an instance; passing `true` enables exceptions
            $mail = new PHPMailer(true);

            try {
                // Server settings
                $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      // Enable verbose debug output
                $mail->isSMTP();                                            // Send using SMTP
                $mail->Host       = 'smtp.gmail.com';                       // Set the SMTP server to send through
                $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
                $mail->Username   = 'danmagino64@gmail.com';                 // SMTP username
                $mail->Password   = 'xzcy zjns exzn bajh';                 // SMTP password
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            // Enable implicit TLS encryption
                $mail->Port       = 465;                                    // TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

                // Recipients
                $mail->setFrom('your-email@gmail.com', 'Folder Nest');
                $mail->addAddress($email);

                // Content
                $mail->isHTML(true);                                  // Set email format to HTML
                $mail->Subject = 'Password Reset Link';
                $mail->Body    = "Hi,<br><br>We received a request to reset your password. Please click the following link to reset your password: <br><br><a href='http://localhost/FOLDERNEST3/change-password.php?reset={$code}'>Reset Password</a>";

                // Send email
                $mail->send();
                echo 'Message has been sent';
            } catch (Exception $e) {
                echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
            }
            echo "</div>";        
            $msg = "<div class='alert alert-info'>We've sent a password reset link to your email address.</div>";
        }
    } else {
        $msg = "<div class='alert alert-danger'>$email - This email address was not found or the account is not verified.</div>";
    }
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
                        <img src="images/image3.png" alt="">
                    </div>
                </div>
                <div class="content-wthree">
                    <h2>Forgot Password</h2>
                    <p>Enter your email below, and we'll send you a reset link.</p>
                    <?php echo $msg; ?>
                    <form action="" method="post">
                        <input type="email" class="email" name="email" placeholder="Enter Your Email" required>
                        <button name="submit" class="btn" type="submit">Send Reset Link</button>
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

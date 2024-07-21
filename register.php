<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

session_start();
if (isset($_SESSION['SESSION_EMAIL'])) {
    header("Location: index.php");
    die();
}

require 'vendor/autoload.php';
include 'connection/config.php';
$msg = "";

if (isset($_POST['submit'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $confirm_password = mysqli_real_escape_string($conn, $_POST['confirm-password']);
    $code = md5(rand()); // Generate verification code
    
    // Validate password match
    if ($password !== $confirm_password) {
        $msg = "<div class='alert alert-danger'>Passwords do not match.</div>";
    } else {
        // Check if email already exists
        $sql_check_email = "SELECT * FROM users WHERE email='{$email}'";
        $result_check_email = mysqli_query($conn, $sql_check_email);
        
        if (mysqli_num_rows($result_check_email) > 0) {
            $msg = "<div class='alert alert-danger'>Email already exists.</div>";
        } else {
            // Hash password
            $password_hashed = password_hash($password, PASSWORD_BCRYPT);
            
            // Insert user into database with unverified status
            $sql_insert_user = "INSERT INTO users (name, email, password, code, role, is_verified)
                                VALUES ('$name', '$email', '$password_hashed', '$code', 'user', 0)";
            $result_insert_user = mysqli_query($conn, $sql_insert_user);
            
            if ($result_insert_user) {
                // Send verification email
                $mail = new PHPMailer(true);

                try {
                    // SMTP settings
                    $mail->SMTPDebug = SMTP::DEBUG_OFF;
                    $mail->isSMTP();
                    $mail->Host       = 'smtp.gmail.com';
                    $mail->SMTPAuth   = true;
                    $mail->Username   = 'danmagino64@gmail.com'; // Your Gmail email
                    $mail->Password   = 'xzcy zjns exzn bajh';        // Your Gmail password
                    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                    $mail->Port       = 587;
                    
                    // Sender and recipient
                    $mail->setFrom('danmagino64@gmail.com', 'Folder Nest');
                    $mail->addAddress($email, $name);  // Add a recipient

                    // Email content
                    $mail->isHTML(true);
                    $mail->Subject = 'Account Verification';
                    $mail->Body    = "Hi $name,<br><br>Thank you for registering with Folder Nest. Please click the following link to verify your account: <br><br><a href='http://localhost/FOLDERNEST3/login.php?verification=$code'>Verify Account</a>";
                    
                    $mail->send();
                    $msg = "<div class='alert alert-success'>Registration successful. Please check your email to verify your account.</div>";
                } catch (Exception $e) {
                    $msg = "<div class='alert alert-danger'>Message could not be sent. Mailer Error: {$mail->ErrorInfo}</div>";
                }
            } else {
                $msg = "<div class='alert alert-danger'>Error: Could not register user.</div>";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Register - FOLDER NEST</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta charset="UTF-8" />
    <meta name="keywords" content="Registration Form" />
    <link href="//fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css" type="text/css" media="all" />
    <script src="https://kit.fontawesome.com/af562a2a63.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="fontawesome/css/all.min.css">
</head>
<body>
    <section class="w3l-mockup-form">
        <div class="container">
            <div class="workinghny-form-grid">
                <div class="main-mockup">
                    <div class="alert-close">
                        <span class="fas fa-times rounded-circle"></span>
                    </div>
                    <div class="w3l_form align-self">
                        <div class="left_grid_info">
                            <img src="images/gal.png" alt="">
                        </div>
                    </div>
                    <div class="content-wthree">
                        <div class="logo">
                            <a href="register.php" class="app-brand-link gap-2">
                                <img src="images/logo3.png" alt="Your Logo Alt Text" class="app-brand-logo">
                                <span class="app-brand-text">Folder Nest</span>
                                <p>"At your service" </p>
                            </a>
                        </div>
                        <p>Register Now</p>
                        <?php echo $msg; ?>
                        <form action="" method="post">
                            <input type="text" class="name" name="name" placeholder="Enter Your Name" value="<?php if (isset($_POST['submit'])) { echo htmlspecialchars($name); } ?>" required>
                            <input type="email" class="email" name="email" placeholder="Enter Your Email" value="<?php if (isset($_POST['submit'])) { echo htmlspecialchars($email); } ?>" required>
                            <input type="password" class="password" name="password" placeholder="Enter Your Password" required>
                            <input type="password" class="confirm-password" name="confirm-password" placeholder="Enter Your Confirm Password" required>
                            <button name="submit" class="btn" type="submit">Register</button>
                        </form>
                        <div class="social-icons">
                            <p>Have an account! <a href="login.php">Login</a>.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
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

<?php
session_start();

if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
    header("location: userdashboard.php");
    exit;
}

require_once "config.php";

$email = $password = "";
$email_err = $password_err = $login_err = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Check if email is provided
    if (empty(trim($_POST["email"]))) {
        $email_err = "Please enter your email.";
    } else {
        $email = trim($_POST["email"]);
    }

    // Check if password is provided
    if (empty(trim($_POST["password"]))) {
        $password_err = "Please enter your password.";
    } else {
        $password = trim($_POST["password"]);
    }

    // If no errors, proceed with login attempt
    if (empty($email_err) && empty($password_err)) {

        $sql = "SELECT id, email, password, full_name, failed_attempts, last_failed_attempt FROM users WHERE email = :email";

        if ($stmt = $pdo->prepare($sql)) {

            $stmt->bindParam(":email", $param_email, PDO::PARAM_STR);
            $param_email = $email;

            if ($stmt->execute()) {

                if ($stmt->rowCount() == 1) {
                    if ($row = $stmt->fetch()) {
                        $id = $row["id"];
                        $email = $row["email"];
                        $hashed_password = $row["password"];
                        $full_name = $row["full_name"];
                        $failed_attempts = $row["failed_attempts"];
                        $last_failed_attempt = $row["last_failed_attempt"];

                        // Lockout time is 3 minutes
                        $lockout_time = 60; // 3 minutes in seconds
                        $current_time = time();

                        // Check if the account is locked due to failed attempts
                        if ($failed_attempts >= 3) {
                            $time_since_last_failed = $current_time - strtotime($last_failed_attempt);

                            if ($time_since_last_failed < $lockout_time) {
                                // Calculate the remaining lockout time in minutes
                                $time_left = ($lockout_time - $time_since_last_failed) / 60;
                                $login_err = "Account locked. Please try again in " . round($time_left, 1) . " minutes.";
                            } else {
                                // Account is unlocked after 3 minutes, reset failed attempts count
                                $reset_attempts_sql = "UPDATE users SET failed_attempts = 0 WHERE id = :id";
                                $stmt_reset = $pdo->prepare($reset_attempts_sql);
                                $stmt_reset->bindParam(":id", $id, PDO::PARAM_INT);
                                $stmt_reset->execute();
                            }
                        }

                        // If account is not locked, check the password
                        if (empty($login_err)) {
                            if (password_verify($password, $hashed_password)) {
                                // Successful login, reset failed attempts count
                                $reset_attempts_sql = "UPDATE users SET failed_attempts = 0 WHERE id = :id";
                                $stmt_reset = $pdo->prepare($reset_attempts_sql);
                                $stmt_reset->bindParam(":id", $id, PDO::PARAM_INT);
                                $stmt_reset->execute();

                                // Start session and login user
                                session_start();
                                $_SESSION["loggedin"] = true;
                                $_SESSION["id"] = $id;
                                $_SESSION["email"] = $email;

                                if (empty($full_name)) {
                                    header("location: editprofile.php");
                                } else {
                                    header("location: userdashboard.php");
                                }
                            } else {
                                // Incorrect password, increment failed attempts
                                logFailedLogin($email, $id, $failed_attempts);
                                $login_err = "Invalid email or password.";
                            }
                        }
                    }
                } else {
                    $login_err = "Invalid email or password.";
                }
            } else {
                echo "Oops! Something went wrong. Please try again later.";
            }

            unset($stmt);
        }
    }

    unset($pdo);
}

// Function to log failed login attempt and update the failed attempts counter
function logFailedLogin($email, $id, $failed_attempts) {
    global $pdo;

    // Log the failed login attempt in failed_logins table
    $stmt = $pdo->prepare("INSERT INTO failed_logins (email, ip_address) VALUES (:email, :ip)");
    $stmt->execute([
        'email' => $email,
        'ip' => $_SERVER['REMOTE_ADDR']
    ]);

    // Increment failed attempts in users table and set last failed attempt timestamp
    $stmt = $pdo->prepare("UPDATE users SET failed_attempts = failed_attempts + 1, last_failed_attempt = NOW() WHERE id = :id");
    $stmt->execute(['id' => $id]);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="./css/login.css">
</head>
<body>
    <div class="wrapper">
        <h2>Login</h2>
        <p>Please fill in your credentials to login.</p>  

        <?php 
        if(!empty($login_err)){
            echo '<div class="alert alert-danger">' . $login_err . '</div>';
        }        
        ?>

        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" class="form-control <?php echo (!empty($email_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $email; ?>">
                <span class="invalid-feedback"><?php echo $email_err; ?></span>
            </div>    
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" class="form-control <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>">
                <span class="invalid-feedback"><?php echo $password_err; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary btn-block" value="Login">
            </div>
            <p>Don't have an account? <a href="register.php">Sign up now</a>.</p>
            <p>Or would you like to <a href="index.php">go back</a>?</p>

        </form>
    </div>
</body>
</html>

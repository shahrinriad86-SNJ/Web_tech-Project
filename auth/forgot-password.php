<?php

session_start();

include "../config/db.php";

$message = "";
$message_type = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = $_POST["email"];
    $new_password = $_POST["new_password"];

    $check_sql = "SELECT id FROM users WHERE email = ?";

    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param("s", $email);
    $check_stmt->execute();

    $result = $check_stmt->get_result();

    if ($result->num_rows == 1) {

        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

        $sql = "UPDATE users SET password = ? WHERE email = ?";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $hashed_password, $email);

        if ($stmt->execute()) {

            $message = "Password changed successfully!";
            $message_type = "success";

        } else {

            $message = "Failed to change password!";
            $message_type = "error";

        }

    } else {

        $message = "No account found with this email!";
        $message_type = "error";

    }
}

?>

<!DOCTYPE html>
<html>

<head>

    <title>Forgot Password - Smart Campus Parking</title>

    <link rel="stylesheet" href="../assets/css/style.css">

</head>

<body>

<div class="auth-page">

    <div class="auth-card">

        <div class="logo">
            Smart Parking
        </div>

        <p class="subtitle">
            Smart Campus Parking System
        </p>

        <h2>Reset Password</h2>

        <?php if ($message != "") { ?>

            <p class="<?php echo $message_type == 'success' ? 'success-message' : 'message'; ?>">
                <?php echo $message; ?>
            </p>

        <?php } ?>

        <form method="POST">

            <div class="form-group">

                <label>Email</label>

                <input
                    type="email"
                    name="email"
                    placeholder="Enter your registered email"
                    required
                >

            </div>

            <div class="form-group">

                <label>New Password</label>

                <input
                    type="password"
                    name="new_password"
                    placeholder="Enter your new password"
                    required
                >

            </div>

            <button type="submit" class="auth-btn">
                Change Password
            </button>

        </form>

        <div class="auth-link">

            Remember your password?

            <a href="login.php">
                Login
            </a>

        </div>

    </div>

</div>

</body>

</html>
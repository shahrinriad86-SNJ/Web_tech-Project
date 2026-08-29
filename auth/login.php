<?php

session_start();

include "../config/db.php";

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = $_POST["email"];
    $password = $_POST["password"];

    $sql = "SELECT * FROM users WHERE email = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();

    $result = $stmt->get_result();

    if ($result->num_rows == 1) {

        $user = $result->fetch_assoc();

        if (password_verify($password, $user["password"])) {

            $_SESSION["user_id"] = $user["id"];
            $_SESSION["name"] = $user["name"];
            $_SESSION["role"] = $user["role"];

            if ($user["role"] == "student") {

                header("Location: ../student/dashboard.php");
                exit();

            } elseif ($user["role"] == "staff") {

                header("Location: ../staff/dashboard.php");
                exit();

            } elseif ($user["role"] == "admin") {

                header("Location: ../admin/dashboard.php");
                exit();

            }

        } else {

            $message = "Incorrect password!";

        }

    } else {

        $message = "No account found with this email!";

    }
}

?>

<!DOCTYPE html>
<html>

<head>

    <title>Login - Smart Campus Parking</title>

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

        <h2>Welcome Back</h2>

        <?php if ($message != "") { ?>

            <p class="message">
                <?php echo $message; ?>
            </p>

        <?php } ?>

        <form method="POST">

            <div class="form-group">

                <label>Email</label>

                <input
                    type="email"
                    name="email"
                    placeholder="Enter your email"
                    required
                >

            </div>

            <div class="form-group">

                <label>Password</label>

                <input
                    type="password"
                    name="password"
                    placeholder="Enter your password"
                    required
                >

            </div>

            <button type="submit" class="auth-btn">
                Login
            </button>

        </form>

        <div class="auth-link">

            <a href="forgot-password.php">
                Forgot Password?
            </a>

        </div>

        <div class="auth-link">

            Don't have an account?

            <a href="register.php">
                Create Account
            </a>

        </div>

    </div>

</div>

</body>

</html>
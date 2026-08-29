<?php

session_start();

include "../config/db.php";

$message = "";
$message_type = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $name = $_POST["name"];
    $email = $_POST["email"];
    $password = $_POST["password"];
    $role = $_POST["role"];

    $check_sql = "SELECT id FROM users WHERE email = ?";

    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param("s", $email);
    $check_stmt->execute();

    $check_result = $check_stmt->get_result();

    if ($check_result->num_rows > 0) {

        $message = "Email already exists!";
        $message_type = "error";

    } else {

        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $sql = "INSERT INTO users (name, email, password, role)
                VALUES (?, ?, ?, ?)";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssss", $name, $email, $hashed_password, $role);

        if ($stmt->execute()) {

            $message = "Registration successful!";
            $message_type = "success";

        } else {

            $message = "Registration failed!";
            $message_type = "error";

        }
    }
}

?>

<!DOCTYPE html>
<html>

<head>

    <title>Create Account - Smart Campus Parking</title>

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

        <h2>Create Account</h2>

        <?php if ($message != "") { ?>

            <p class="<?php echo $message_type == 'success' ? 'success-message' : 'message'; ?>">
                <?php echo $message; ?>
            </p>

        <?php } ?>

        <form method="POST">

            <div class="form-group">

                <label>Name</label>

                <input
                    type="text"
                    name="name"
                    placeholder="Enter your name"
                    required
                >

            </div>

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
                    placeholder="Create a password"
                    required
                >

            </div>

            <div class="form-group">

                <label>Account Type</label>

                <select name="role" required>

                    <option value="student">Student</option>
                    <option value="staff">Staff</option>
                    <option value="admin">Admin</option>

                </select>

            </div>

            <button type="submit" class="auth-btn">
                Create Account
            </button>

        </form>

        <div class="auth-link">

            Already have an account?

            <a href="login.php">
                Login
            </a>

        </div>

    </div>

</div>

</body>

</html>
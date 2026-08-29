
<?php

session_start();

include "../config/db.php";

if (!isset($_SESSION["user_id"]) || $_SESSION["role"] != "admin") {
    header("Location: ../auth/login.php");
    exit();
}

$user_count = $conn->query("SELECT COUNT(*) AS total FROM users")->fetch_assoc()["total"];

$slot_count = $conn->query("SELECT COUNT(*) AS total FROM parking_slots")->fetch_assoc()["total"];

$booking_count = $conn->query("SELECT COUNT(*) AS total FROM bookings")->fetch_assoc()["total"];

$available_slots = $conn->query("SELECT COUNT(*) AS total FROM parking_slots WHERE status = 'available'")->fetch_assoc()["total"];

?>

<!DOCTYPE html>
<html>

<head>

    <title>Admin Dashboard</title>

    <style>

        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f5f7fa;
        }

        .header {
            background: #2563eb;
            color: white;
            padding: 18px 30px;
            display: flex;
            justify-content: space-between;
        }

        .header h2 {
            margin: 0;
        }

        .header a {
            color: white;
            text-decoration: none;
        }

        .container {
            width: 90%;
            max-width: 1100px;
            margin: 30px auto;
        }

        .welcome {
            background: white;
            padding: 25px;
            border-radius: 8px;
            margin-bottom: 25px;
        }

        .welcome h1 {
            margin-top: 0;
        }

        .stats {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 15px;
            margin-bottom: 30px;
        }

        .stat {
            background: white;
            padding: 20px;
            border-radius: 8px;
            text-align: center;
            border: 1px solid #ddd;
        }

        .stat h3 {
            margin: 0 0 10px;
            color: #555;
        }

        .stat p {
            margin: 0;
            font-size: 25px;
            font-weight: bold;
            color: #2563eb;
        }

        .section-title {
            margin-bottom: 15px;
        }

        .menu {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 15px;
        }

        .menu-item {
            background: white;
            padding: 25px;
            border-radius: 8px;
            border: 1px solid #ddd;
        }

        .menu-item h3 {
            margin-top: 0;
        }

        .menu-item p {
            color: #666;
            line-height: 1.5;
        }

        .button {
            display: inline-block;
            padding: 10px 16px;
            background: #2563eb;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }

        .button:hover {
            background: #1d4ed8;
        }

        @media (max-width: 700px) {

            .stats {
                grid-template-columns: repeat(2, 1fr);
            }

            .menu {
                grid-template-columns: 1fr;
            }

        }

    </style>

</head>

<body>

<div class="header">

    <h2>
        Smart Campus Parking
    </h2>

    <div>
        Welcome, <?php echo htmlspecialchars($_SESSION["name"]); ?>
        |
        <a href="../auth/logout.php">Logout</a>
    </div>

</div>

<div class="container">

    <div class="welcome">

        <h1>
            Admin Dashboard
        </h1>

        <p>
            You are logged in as Admin.
        </p>

    </div>

    <div class="stats">

        <div class="stat">

            <h3>
                Total Users
            </h3>

            <p>
                <?php echo $user_count; ?>
            </p>

        </div>

        <div class="stat">

            <h3>
                Parking Slots
            </h3>

            <p>
                <?php echo $slot_count; ?>
            </p>

        </div>

        <div class="stat">

            <h3>
                Available Slots
            </h3>

            <p>
                <?php echo $available_slots; ?>
            </p>

        </div>

        <div class="stat">

            <h3>
                Total Bookings
            </h3>

            <p>
                <?php echo $booking_count; ?>
            </p>

        </div>

    </div>

    <h2 class="section-title">
        Admin Options
    </h2>

    <div class="menu">

        <div class="menu-item">

            <h3>
                Manage Users
            </h3>

            <p>
                View and manage registered users.
            </p>

            <a href="users.php" class="button">
                Manage Users
            </a>

        </div>

        <div class="menu-item">

            <h3>
                Parking Slots
            </h3>

            <p>
                Add and manage parking slots.
            </p>

            <a href="slots.php" class="button">
                Manage Slots
            </a>

        </div>

        <div class="menu-item">

            <h3>
                Information
            </h3>

            <p>
                View parking and booking information.
            </p>

            <a href="information.php" class="button">
                View Information
            </a>

        </div>

    </div>

</div>

</body>

</html>


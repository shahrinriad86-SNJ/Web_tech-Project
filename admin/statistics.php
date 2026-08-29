<?php

session_start();

include "../config/db.php";

if (!isset($_SESSION["user_id"]) || $_SESSION["role"] != "admin") {
    header("Location: ../auth/login.php");
    exit();
}

$total_users = $conn->query("SELECT COUNT(*) AS total FROM users")->fetch_assoc()["total"];

$total_slots = $conn->query("SELECT COUNT(*) AS total FROM parking_slots")->fetch_assoc()["total"];

$total_bookings = $conn->query("SELECT COUNT(*) AS total FROM bookings")->fetch_assoc()["total"];

$pending = $conn->query("SELECT COUNT(*) AS total FROM bookings WHERE status = 'pending'")->fetch_assoc()["total"];

$approved = $conn->query("SELECT COUNT(*) AS total FROM bookings WHERE status = 'approved'")->fetch_assoc()["total"];

$rejected = $conn->query("SELECT COUNT(*) AS total FROM bookings WHERE status = 'rejected'")->fetch_assoc()["total"];

$cancelled = $conn->query("SELECT COUNT(*) AS total FROM bookings WHERE status = 'cancelled'")->fetch_assoc()["total"];

?>

<!DOCTYPE html>
<html>

<head>

    <title>Parking Statistics</title>

    <style>

        body {
            font-family: Arial, sans-serif;
            background: #f4f7fb;
            margin: 0;
            padding: 40px;
        }

        .container {
            max-width: 1000px;
            margin: auto;
        }

        h1 {
            text-align: center;
            margin-bottom: 30px;
        }

        .cards {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
        }

        .card {
            background: white;
            padding: 30px;
            border-radius: 15px;
            text-align: center;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        }

        .card h2 {
            font-size: 36px;
            margin: 10px 0;
        }

        .card p {
            font-size: 18px;
            margin: 0;
        }

        .back-btn {
            display: block;
            width: 180px;
            text-align: center;
            margin: 35px auto;
            padding: 12px;
            background: #2563eb;
            color: white;
            text-decoration: none;
            border-radius: 8px;
        }

    </style>

</head>

<body>

<div class="container">

    <h1>Parking System Information</h1>

    <div class="cards">

        <div class="card">

            <p>Total Users</p>

            <h2>
                <?php echo $total_users; ?>
            </h2>

        </div>

        <div class="card">

            <p>Total Parking Slots</p>

            <h2>
                <?php echo $total_slots; ?>
            </h2>

        </div>

        <div class="card">

            <p>Total Bookings</p>

            <h2>
                <?php echo $total_bookings; ?>
            </h2>

        </div>

        <div class="card">

            <p>Pending</p>

            <h2>
                <?php echo $pending; ?>
            </h2>

        </div>

        <div class="card">

            <p>Approved</p>

            <h2>
                <?php echo $approved; ?>
            </h2>

        </div>

        <div class="card">

            <p>Rejected</p>

            <h2>
                <?php echo $rejected; ?>
            </h2>

        </div>

        <div class="card">

            <p>Cancelled</p>

            <h2>
                <?php echo $cancelled; ?>
            </h2>

        </div>

    </div>

    <a href="dashboard.php" class="back-btn">
        Back to Dashboard
    </a>

</div>

</body>

</html>
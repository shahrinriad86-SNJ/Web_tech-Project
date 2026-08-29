<?php

session_start();

if (!isset($_SESSION["user_id"]) || $_SESSION["role"] != "staff") {
    header("Location: ../auth/login.php");
    exit();
}

?>

<!DOCTYPE html>
<html>

<head>

    <title>Staff Dashboard - Smart Parking</title>

    <link rel="stylesheet" href="../assets/css/style.css">

    <style>

        body {
            background: #f4f7fb;
        }

        .dashboard-container {
            max-width: 1100px;
            margin: auto;
            padding: 40px 20px;
        }

        .topbar {
            background: white;
            padding: 20px 25px;
            border-radius: 15px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 5px 20px rgba(0,0,0,0.08);
        }

        .brand {
            font-size: 24px;
            font-weight: bold;
            color: #2563eb;
        }

        .user-info {
            color: #475569;
        }

        .welcome {
            background: #2563eb;
            color: white;
            margin-top: 25px;
            padding: 35px;
            border-radius: 15px;
        }

        .welcome h1 {
            margin-bottom: 10px;
        }

        .features {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
            margin-top: 25px;
        }

        .feature-card {
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.08);
            text-align: center;
        }

        .feature-card h2 {
            margin-bottom: 10px;
            color: #1e293b;
        }

        .feature-card p {
            color: #64748b;
            margin-bottom: 20px;
        }

        .dashboard-btn {
            display: inline-block;
            padding: 12px 22px;
            background: #2563eb;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-weight: bold;
        }

        .logout-btn {
            background: #dc2626;
        }

        @media (max-width: 700px) {

            .features {
                grid-template-columns: 1fr;
            }

            .topbar {
                flex-direction: column;
                gap: 10px;
            }

        }

    </style>

</head>

<body>

<div class="dashboard-container">

    <div class="topbar">

        <div class="brand">
            Smart Parking
        </div>

        <div class="user-info">
            <?php echo $_SESSION["name"]; ?> · Staff
        </div>

    </div>

    <div class="welcome">

        <h1>
            Welcome, <?php echo $_SESSION["name"]; ?>!
        </h1>

        <p>
            Manage campus parking bookings from your dashboard.
        </p>

    </div>

    <div class="features">

        <div class="feature-card">

            <h2>Manage Bookings</h2>

            <p>
                View all student parking bookings and manage their status.
            </p>

            <a href="bookings.php" class="dashboard-btn">
                View Bookings
            </a>

        </div>

        <div class="feature-card">

            <h2>Account</h2>

            <p>
                You are currently logged in as Staff.
            </p>

            <a href="../auth/logout.php" class="dashboard-btn logout-btn">
                Logout
            </a>

        </div>

    </div>

</div>

</body>

</html>
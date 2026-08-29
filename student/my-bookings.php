<?php

session_start();

include "../config/db.php";

if (!isset($_SESSION["user_id"]) || $_SESSION["role"] != "student") {
    header("Location: ../auth/login.php");
    exit();
}

$user_id = $_SESSION["user_id"];

$sql = "SELECT
            bookings.*,
            parking_slots.slot_number,
            parking_slots.location
        FROM bookings
        JOIN parking_slots
        ON bookings.slot_id = parking_slots.id
        WHERE bookings.user_id = ?
        ORDER BY bookings.id DESC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();

$result = $stmt->get_result();

?>

<!DOCTYPE html>
<html>

<head>

    <title>My Bookings - Smart Parking</title>

    <link rel="stylesheet" href="../assets/css/style.css">

    <style>

        body {
            background: #f4f7fb;
        }

        .bookings-container {
            max-width: 1000px;
            margin: 40px auto;
            padding: 20px;
        }

        .bookings-card {
            background: white;
            padding: 30px;
            border-radius: 18px;
            box-shadow: 0 8px 25px rgba(0,0,0,0.08);
        }

        .page-title {
            text-align: center;
            color: #1e293b;
            margin-bottom: 10px;
        }

        .page-subtitle {
            text-align: center;
            color: #64748b;
            margin-bottom: 30px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th {
            background: #2563eb;
            color: white;
            padding: 14px;
            text-align: left;
        }

        td {
            padding: 14px;
            border-bottom: 1px solid #e2e8f0;
            color: #334155;
        }

        tr:hover {
            background: #f8fafc;
        }

        .status {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: bold;
        }

        .pending {
            background: #fef3c7;
            color: #92400e;
        }

        .approved {
            background: #dcfce7;
            color: #166534;
        }

        .rejected {
            background: #fee2e2;
            color: #991b1b;
        }

        .cancelled {
            background: #e2e8f0;
            color: #475569;
        }

        .back-link {
            display: inline-block;
            margin-top: 25px;
            color: #2563eb;
            text-decoration: none;
            font-weight: bold;
        }

        .no-booking {
            text-align: center;
            padding: 30px;
            color: #64748b;
        }

        @media (max-width: 700px) {

            .bookings-card {
                overflow-x: auto;
            }

            table {
                min-width: 700px;
            }

        }

    </style>

</head>

<body>

<div class="bookings-container">

    <div class="bookings-card">

        <h1 class="page-title">
            My Bookings
        </h1>

       

        <?php if ($result->num_rows > 0) { ?>

            <table>

                <tr>

                    <th>Slot</th>
                    <th>Location</th>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Status</th>

                </tr>

                <?php while ($booking = $result->fetch_assoc()) { ?>

                    <tr>

                        <td>
                            <?php echo $booking["slot_number"]; ?>
                        </td>

                        <td>
                            <?php echo $booking["location"]; ?>
                        </td>

                        <td>
                            <?php echo $booking["booking_date"]; ?>
                        </td>

                        <td>
                            <?php echo $booking["booking_time"]; ?>
                        </td>

                        <td>

                            <span class="status <?php echo $booking["status"]; ?>">

                                <?php echo ucfirst($booking["status"]); ?>

                            </span>

                        </td>

                    </tr>

                <?php } ?>

            </table>

        <?php } else { ?>

            <div class="no-booking">

                <h3>No bookings found</h3>

                <p>
                    You haven't booked any parking slot yet.
                </p>

            </div>

        <?php } ?>

        <a href="dashboard.php" class="back-link">
            ← Back to Dashboard
        </a>

    </div>

</div>

</body>

</html>
<?php

session_start();

include "../config/db.php";

if (!isset($_SESSION["user_id"]) || $_SESSION["role"] != "student") {
    header("Location: ../auth/login.php");
    exit();
}

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $slot_id = $_POST["slot_id"];
    $booking_date = $_POST["booking_date"];
    $booking_time = $_POST["booking_time"];
    $user_id = $_SESSION["user_id"];

    $check_sql = "SELECT id FROM bookings
                  WHERE slot_id = ?
                  AND booking_date = ?
                  AND booking_time = ?
                  AND status IN ('pending','approved')";

    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param("iss", $slot_id, $booking_date, $booking_time);
    $check_stmt->execute();

    $check_result = $check_stmt->get_result();

    if ($check_result->num_rows > 0) {

        $message = "This parking slot is already booked!";

    } else {

        $sql = "INSERT INTO bookings
                (user_id, slot_id, booking_date, booking_time, status)
                VALUES (?, ?, ?, ?, 'pending')";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param(
            "iiss",
            $user_id,
            $slot_id,
            $booking_date,
            $booking_time
        );

        if ($stmt->execute()) {

            $message = "Parking slot booked successfully!";

        } else {

            $message = "Booking failed!";

        }
    }
}

$slots = $conn->query("
    SELECT *
    FROM parking_slots
    WHERE status = 'available'
    ORDER BY slot_number ASC
");

?>

<!DOCTYPE html>
<html>

<head>

    <title>Book Parking - Smart Parking</title>

    <link rel="stylesheet" href="../assets/css/style.css">

    <style>

        body {
            background: #f4f7fb;
        }

        .booking-container {
            max-width: 700px;
            margin: 50px auto;
            padding: 20px;
        }

        .booking-card {
            background: white;
            padding: 35px;
            border-radius: 18px;
            box-shadow: 0 8px 25px rgba(0,0,0,0.08);
        }

        .booking-title {
            text-align: center;
            color: #1e293b;
            margin-bottom: 10px;
        }

        .booking-subtitle {
            text-align: center;
            color: #64748b;
            margin-bottom: 30px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
            color: #334155;
        }

        .form-group select,
        .form-group input {
            width: 100%;
            padding: 13px;
            border: 1px solid #cbd5e1;
            border-radius: 8px;
            font-size: 15px;
        }

        .booking-btn {
            width: 100%;
            padding: 14px;
            border: none;
            border-radius: 8px;
            background: #2563eb;
            color: white;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
        }

        .booking-btn:hover {
            background: #1d4ed8;
        }

        .success-message {
            text-align: center;
            color: #16a34a;
            font-weight: bold;
            margin-bottom: 20px;
        }

        .error-message {
            text-align: center;
            color: #dc2626;
            font-weight: bold;
            margin-bottom: 20px;
        }

        .back-link {
            display: block;
            text-align: center;
            margin-top: 20px;
            color: #2563eb;
            text-decoration: none;
            font-weight: bold;
        }

    </style>

</head>

<body>

<div class="booking-container">

    <div class="booking-card">

        <h1 class="booking-title">
            Book Parking Slot
        </h1>

        <p class="booking-subtitle">
            Select an available parking slot
        </p>

        <?php if ($message != "") { ?>

            <p class="<?php echo strpos($message, 'successfully') !== false ? 'success-message' : 'error-message'; ?>">
                <?php echo $message; ?>
            </p>

        <?php } ?>

        <form method="POST">

            <div class="form-group">

                <label>Parking Slot</label>

                <select name="slot_id" required>

                    <option value="">
                        Select a parking slot
                    </option>

                    <?php while ($slot = $slots->fetch_assoc()) { ?>

                        <option value="<?php echo $slot["id"]; ?>">

                            <?php echo $slot["slot_number"]; ?>
                            -
                            <?php echo $slot["location"]; ?>

                        </option>

                    <?php } ?>

                </select>

            </div>

            <div class="form-group">

                <label>Booking Date</label>

                <input
                    type="date"
                    name="booking_date"
                    required
                >

            </div>

            <div class="form-group">

                <label>Booking Time</label>

                <input
                    type="time"
                    name="booking_time"
                    required
                >

            </div>

            <button
                type="submit"
                class="booking-btn"
            >
                Confirm Booking
            </button>

        </form>

        <a
            href="dashboard.php"
            class="back-link"
        >
            ← Back to Dashboard
        </a>

    </div>

</div>

</body>

</html>
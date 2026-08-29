<?php

session_start();

include "../config/db.php";

if (!isset($_SESSION["user_id"]) || $_SESSION["role"] != "staff") {
    header("Location: ../auth/login.php");
    exit();
}

$message = "";

if (isset($_GET["action"]) && isset($_GET["id"])) {

    $id = $_GET["id"];
    $action = $_GET["action"];

    if ($action == "approve") {

        $status = "approved";

    } elseif ($action == "reject") {

        $status = "rejected";

    } else {

        $status = "";

    }

    if ($status != "") {

        $sql = "UPDATE bookings SET status = ? WHERE id = ?";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $status, $id);

        if ($stmt->execute()) {

            $message = "Booking status updated successfully!";

        }

    }

}

$sql = "SELECT
            bookings.*,
            users.name,
            users.email,
            parking_slots.slot_number,
            parking_slots.location
        FROM bookings
        JOIN users
        ON bookings.user_id = users.id
        JOIN parking_slots
        ON bookings.slot_id = parking_slots.id
        ORDER BY bookings.id DESC";

$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html>

<head>

    <title>Manage Bookings - Smart Parking</title>

    <link rel="stylesheet" href="../assets/css/style.css">

    <style>

        body {
            background: #f4f7fb;
        }

        .bookings-container {
            max-width: 1200px;
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
            margin-bottom: 25px;
        }

        .success-message {
            text-align: center;
            color: #16a34a;
            font-weight: bold;
            margin-bottom: 20px;
        }

        .table-container {
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            min-width: 900px;
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

        .action-btn {
            display: inline-block;
            padding: 7px 12px;
            border-radius: 6px;
            color: white;
            text-decoration: none;
            font-size: 13px;
            font-weight: bold;
            margin: 2px;
        }

        .approve-btn {
            background: #16a34a;
        }

        .reject-btn {
            background: #dc2626;
        }

        .back-link {
            display: inline-block;
            margin-top: 25px;
            color: #2563eb;
            text-decoration: none;
            font-weight: bold;
        }

    </style>

</head>

<body>

<div class="bookings-container">

    <div class="bookings-card">

        <h1 class="page-title">
            Manage Parking Bookings
        </h1>

       

        <?php if ($message != "") { ?>

            <p class="success-message">
                <?php echo $message; ?>
            </p>

        <?php } ?>

        <div class="table-container">

            <table>

                <tr>

                    <th>Student</th>
                    <th>Email</th>
                    <th>Slot</th>
                    <th>Location</th>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Status</th>
                    <th>Action</th>

                </tr>

                <?php if ($result->num_rows > 0) { ?>

                    <?php while ($booking = $result->fetch_assoc()) { ?>

                        <tr>

                            <td>
                                <?php echo htmlspecialchars($booking["name"]); ?>
                            </td>

                            <td>
                                <?php echo htmlspecialchars($booking["email"]); ?>
                            </td>

                            <td>
                                <?php echo htmlspecialchars($booking["slot_number"]); ?>
                            </td>

                            <td>
                                <?php echo htmlspecialchars($booking["location"]); ?>
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

                            <td>

                                <?php if ($booking["status"] == "pending") { ?>

                                    <a
                                        href="bookings.php?action=approve&id=<?php echo $booking["id"]; ?>"
                                        class="action-btn approve-btn"
                                    >
                                        Approve
                                    </a>

                                    <a
                                        href="bookings.php?action=reject&id=<?php echo $booking["id"]; ?>"
                                        class="action-btn reject-btn"
                                    >
                                        Reject
                                    </a>

                                <?php } else { ?>

                                    No Action

                                <?php } ?>

                            </td>

                        </tr>

                    <?php } ?>

                <?php } else { ?>

                    <tr>

                        <td colspan="8" style="text-align:center;">
                            No bookings found.
                        </td>

                    </tr>

                <?php } ?>

            </table>

        </div>

        <a href="dashboard.php" class="back-link">
            ← Back to Dashboard
        </a>

    </div>

</div>

</body>

</html>
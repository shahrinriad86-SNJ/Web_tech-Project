<?php

session_start();

include "../config/db.php";

if (!isset($_SESSION["user_id"]) || $_SESSION["role"] != "admin") {
    header("Location: ../auth/login.php");
    exit();
}

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $slot_number = $_POST["slot_number"];
    $location = $_POST["location"];

    $sql = "INSERT INTO parking_slots (slot_number, location, status)
            VALUES (?, ?, 'available')";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $slot_number, $location);

    if ($stmt->execute()) {

        $message = "Parking slot added successfully!";

    } else {

        $message = "Failed to add parking slot!";

    }
}

if (isset($_GET["delete"])) {

    $id = $_GET["delete"];

    $sql = "DELETE FROM parking_slots WHERE id = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {

        $message = "Parking slot deleted successfully!";

    }

}

if (isset($_GET["status"]) && isset($_GET["id"])) {

    $id = $_GET["id"];
    $status = $_GET["status"];

    if (
        $status == "available" ||
        $status == "occupied" ||
        $status == "maintenance"
    ) {

        $sql = "UPDATE parking_slots SET status = ? WHERE id = ?";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $status, $id);
        $stmt->execute();

        $message = "Parking slot status updated!";

    }

}

$result = $conn->query("
    SELECT *
    FROM parking_slots
    ORDER BY id ASC
");

?>

<!DOCTYPE html>
<html>

<head>

    <title>Parking Slots - Smart Parking</title>

    <link rel="stylesheet" href="../assets/css/style.css">

    <style>

        body {
            background: #f4f7fb;
        }

        .container {
            max-width: 1100px;
            margin: 40px auto;
            padding: 20px;
        }

        .card {
            background: white;
            padding: 30px;
            border-radius: 18px;
            box-shadow: 0 8px 25px rgba(0,0,0,0.08);
            margin-bottom: 25px;
        }

        h1 {
            text-align: center;
            color: #1e293b;
        }

        .subtitle {
            text-align: center;
            color: #64748b;
            margin-bottom: 30px;
        }

        .message {
            text-align: center;
            color: #16a34a;
            font-weight: bold;
            margin-bottom: 20px;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr auto;
            gap: 15px;
            align-items: end;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
            color: #334155;
        }

        input {
            width: 100%;
            padding: 12px;
            border: 1px solid #cbd5e1;
            border-radius: 8px;
            box-sizing: border-box;
        }

        .add-btn {
            padding: 12px 22px;
            background: #2563eb;
            color: white;
            border: none;
            border-radius: 8px;
            font-weight: bold;
            cursor: pointer;
        }

        .add-btn:hover {
            background: #1d4ed8;
        }

        .table-container {
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            min-width: 700px;
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
        }

        .status {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: bold;
        }

        .available {
            background: #dcfce7;
            color: #166534;
        }

        .occupied {
            background: #fee2e2;
            color: #991b1b;
        }

        .maintenance {
            background: #fef3c7;
            color: #92400e;
        }

        .action-btn {
            display: inline-block;
            padding: 7px 10px;
            border-radius: 6px;
            color: white;
            text-decoration: none;
            font-size: 12px;
            font-weight: bold;
            margin: 2px;
        }

        .available-btn {
            background: #16a34a;
        }

        .occupied-btn {
            background: #dc2626;
        }

        .maintenance-btn {
            background: #d97706;
        }

        .delete-btn {
            background: #991b1b;
        }

        .back-link {
            display: inline-block;
            margin-top: 20px;
            color: #2563eb;
            text-decoration: none;
            font-weight: bold;
        }

        @media (max-width: 700px) {

            .form-row {
                grid-template-columns: 1fr;
            }

        }

    </style>

</head>

<body>

<div class="container">

    <div class="card">

        <h1>
            Parking Slot Management
        </h1>

        <p class="subtitle">
            Add and manage campus parking slots
        </p>

        <?php if ($message != "") { ?>

            <p class="message">
                <?php echo $message; ?>
            </p>

        <?php } ?>

        <form method="POST">

            <div class="form-row">

                <div>

                    <label>
                        Slot Number
                    </label>

                    <input
                        type="text"
                        name="slot_number"
                        placeholder="Example: C1"
                        required
                    >

                </div>

                <div>

                    <label>
                        Location
                    </label>

                    <input
                        type="text"
                        name="location"
                        placeholder="Example: Ground Floor"
                        required
                    >

                </div>

                <div>

                    <button
                        type="submit"
                        class="add-btn"
                    >
                        Add Slot
                    </button>

                </div>

            </div>

        </form>

    </div>

    <div class="card">

        <div class="table-container">

            <table>

                <tr>

                    <th>ID</th>
                    <th>Slot Number</th>
                    <th>Location</th>
                    <th>Status</th>
                    <th>Action</th>

                </tr>

                <?php while ($slot = $result->fetch_assoc()) { ?>

                    <tr>

                        <td>
                            <?php echo $slot["id"]; ?>
                        </td>

                        <td>
                            <?php echo htmlspecialchars($slot["slot_number"]); ?>
                        </td>

                        <td>
                            <?php echo htmlspecialchars($slot["location"]); ?>
                        </td>

                        <td>

                            <span class="status <?php echo $slot["status"]; ?>">

                                <?php echo ucfirst($slot["status"]); ?>

                            </span>

                        </td>

                        <td>

                            <a
                                href="slots.php?status=available&id=<?php echo $slot["id"]; ?>"
                                class="action-btn available-btn"
                            >
                                Available
                            </a>

                            <a
                                href="slots.php?status=occupied&id=<?php echo $slot["id"]; ?>"
                                class="action-btn occupied-btn"
                            >
                                Occupied
                            </a>

                            <a
                                href="slots.php?status=maintenance&id=<?php echo $slot["id"]; ?>"
                                class="action-btn maintenance-btn"
                            >
                                Maintenance
                            </a>

                            <a
                                href="slots.php?delete=<?php echo $slot["id"]; ?>"
                                class="action-btn delete-btn"
                            >
                                Delete
                            </a>

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
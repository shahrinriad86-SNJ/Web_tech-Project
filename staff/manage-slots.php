<?php

session_start();

include "../config/db.php";

// Only staff can access this page
if (!isset($_SESSION["user_id"]) || $_SESSION["role"] != "staff") {
    header("Location: ../auth/login.php");
    exit();
}

$message = "";

// Add new parking slot
if (isset($_POST["add_slot"])) {

    $slot_number = $_POST["slot_number"];
    $location = $_POST["location"];

    $sql = "INSERT INTO parking_slots (slot_number, location)
            VALUES (?, ?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $slot_number, $location);

    if ($stmt->execute()) {
        $message = "Parking slot added successfully!";
    } else {
        $message = "Failed to add parking slot!";
    }
}

// Get all parking slots
$sql = "SELECT * FROM parking_slots ORDER BY id DESC";
$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html>

<head>

    <title>Manage Parking Slots</title>

</head>

<body>

<h1>Manage Parking Slots</h1>

<p>
    Welcome, <?php echo $_SESSION["name"]; ?>
</p>

<a href="dashboard.php">Dashboard</a>

<br><br>

<?php

if ($message != "") {
    echo "<p>$message</p>";
}

?>

<h2>Add New Parking Slot</h2>

<form method="POST">

    <label>Slot Number</label>
    <br>

    <input
        type="text"
        name="slot_number"
        placeholder="Example: C1"
        required
    >

    <br><br>

    <label>Location</label>
    <br>

    <input
        type="text"
        name="location"
        placeholder="Example: Ground Floor"
        required
    >

    <br><br>

    <button type="submit" name="add_slot">
        Add Slot
    </button>

</form>

<hr>

<h2>All Parking Slots</h2>

<table border="1" cellpadding="10">

    <tr>
        <th>ID</th>
        <th>Slot Number</th>
        <th>Location</th>
        <th>Status</th>
    </tr>

<?php

if ($result->num_rows > 0) {

    while ($row = $result->fetch_assoc()) {

?>

    <tr>

        <td>
            <?php echo $row["id"]; ?>
        </td>

        <td>
            <?php echo $row["slot_number"]; ?>
        </td>

        <td>
            <?php echo $row["location"]; ?>
        </td>

        <td>
            <?php echo $row["status"]; ?>
        </td>

    </tr>

<?php

    }

} else {

?>

    <tr>
        <td colspan="4">
            No parking slots found.
        </td>
    </tr>

<?php

}

?>

</table>

<br>

<a href="../auth/logout.php">Logout</a>

</body>

</html>
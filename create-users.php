<?php

include "config/db.php";

$staffPassword = password_hash("123456", PASSWORD_DEFAULT);
$adminPassword = password_hash("123456", PASSWORD_DEFAULT);

$sql1 = "INSERT INTO users (name, email, password, role)
         VALUES ('Parking Staff', 'staff@test.com', '$staffPassword', 'staff')";

$sql2 = "INSERT INTO users (name, email, password, role)
         VALUES ('System Admin', 'admin@test.com', '$adminPassword', 'admin')";

if ($conn->query($sql1) && $conn->query($sql2)) {
    echo "Staff and Admin accounts created successfully!";
} else {
    echo "Error creating accounts.";
}

?>
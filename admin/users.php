<?php

session_start();

include "../config/db.php";

if (!isset($_SESSION["user_id"]) || $_SESSION["role"] != "admin") {
    header("Location: ../auth/login.php");
    exit();
}

$sql = "SELECT id, name, email, role, created_at
        FROM users
        ORDER BY id DESC";

$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html>

<head>

    <title>Manage Users - Smart Parking</title>

    <link rel="stylesheet" href="../assets/css/style.css">

    <style>

        body {
            background: #f4f7fb;
        }

        .users-container {
            max-width: 1100px;
            margin: 40px auto;
            padding: 20px;
        }

        .users-card {
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
            color: #334155;
        }

        tr:hover {
            background: #f8fafc;
        }

        .role {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: bold;
        }

        .student {
            background: #dbeafe;
            color: #1e40af;
        }

        .staff {
            background: #fef3c7;
            color: #92400e;
        }

        .admin {
            background: #ede9fe;
            color: #6b21a8;
        }

        .back-link {
            display: inline-block;
            margin-top: 25px;
            color: #2563eb;
            text-decoration: none;
            font-weight: bold;
        }

        .no-users {
            text-align: center;
            padding: 30px;
            color: #64748b;
        }

    </style>

</head>

<body>

<div class="users-container">

    <div class="users-card">

        <h1 class="page-title">
            Manage Users
        </h1>

        <p class="page-subtitle">
            View all registered users in the system
        </p>

        <div class="table-container">

            <?php if ($result->num_rows > 0) { ?>

                <table>

                    <tr>

                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Created At</th>

                    </tr>

                    <?php while ($user = $result->fetch_assoc()) { ?>

                        <tr>

                            <td>
                                <?php echo $user["id"]; ?>
                            </td>

                            <td>
                                <?php echo htmlspecialchars($user["name"]); ?>
                            </td>

                            <td>
                                <?php echo htmlspecialchars($user["email"]); ?>
                            </td>

                            <td>

                                <span class="role <?php echo $user["role"]; ?>">

                                    <?php echo ucfirst($user["role"]); ?>

                                </span>

                            </td>

                            <td>
                                <?php echo $user["created_at"]; ?>
                            </td>

                        </tr>

                    <?php } ?>

                </table>

            <?php } else { ?>

                <div class="no-users">
                    No users found.
                </div>

            <?php } ?>

        </div>

        <a href="dashboard.php" class="back-link">
            ← Back to Dashboard
        </a>

    </div>

</div>

</body>

</html>
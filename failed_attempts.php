<?php
session_start();
include('config.php');

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: login.php");
    exit();
}

// Fetch the failed login attempts from the database
$failed_loginsStmt = $pdo->prepare("SELECT * FROM failed_logins ORDER BY created_at DESC"); // Use created_at or timestamp as per your DB
$failed_loginsStmt->execute();
$failed_logins = $failed_loginsStmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Failed Login Attempts</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f5f5f5;
        }
        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: black;
            color: green;
            padding: 10px 20px;
        }
        .navbar .logo h2 {
            margin: 0;
            color: green;
        }
        .nav-links a {
            color: green;
            text-decoration: none;
            margin-left: 20px;
        }
        .content {
            padding: 20px;
            margin: 20px auto;
            background: white;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            max-width: 90%;
        }
        h1, h2 {
            text-align: center;
            color: black;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: black;
            color: green;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
    </style>
</head>
<body>
    <div class="navbar">
        <div class="logo">
            <h2>Welcome, Group 6</h2>
        </div>
        <div class="nav-links">
            <a href="admin_dashboard.php">Dashboard</a>
            <a href="logout.php">Logout</a>
            <a href="feedbacks.php">Feedbacks</a>
        </div>
    </div>

    <div class="content">
        <h2>Failed Login Attempts</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Email</th>
                    <th>IP Address</th>
                    <th>Timestamp</th> <!-- You can replace 'created_at' if that's the column name -->
                </tr>
            </thead>
            <tbody>
                <?php foreach ($failed_logins as $attempt): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($attempt['id']); ?></td>
                        <td><?php echo htmlspecialchars($attempt['email']); ?></td>
                        <td><?php echo htmlspecialchars($attempt['ip_address']); ?></td>
                        <td><?php echo htmlspecialchars($attempt['created_at']); ?></td> <!-- Updated to match column name -->
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>

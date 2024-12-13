<?php
session_start();
include('config.php');
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: login.php");
    exit();
}

$feedbackStmt = $pdo->prepare("SELECT pf.id, pf.feedback, pf.created_at, p.name AS product_name, u.full_name AS user_full_name
                               FROM product_feedback pf
                               LEFT JOIN products p ON pf.product_id = p.id
                               LEFT JOIN users u ON pf.user_id = u.id");
$feedbackStmt->execute();
$feedbacks = $feedbackStmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Feedbacks</title>
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
<body>>
    <div class="navbar">
        <div class="logo">
            <h2>Welcome, Group 6</h2>
        </div>
        <div class="nav-links">
            <a href="logout.php">Logout</a>
            <a href="admin_dashboard.php">Dashboard</a>
        </div>
    </div>

    <div class="content">
        <h2>Product Feedbacks</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Product Name</th>
                    <th>User Full Name</th>
                    <th>Feedback</th>
                    <th>Date Submitted</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($feedbacks as $feedback): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($feedback['id']); ?></td>
                        <td><?php echo htmlspecialchars($feedback['product_name']); ?></td>
                        <td><?php echo htmlspecialchars($feedback['user_full_name']); ?></td>
                        <td><?php echo htmlspecialchars($feedback['feedback']); ?></td>
                        <td><?php echo htmlspecialchars($feedback['created_at']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>

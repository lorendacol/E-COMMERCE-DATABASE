<?php
session_start();
include('config.php');

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: login.php");
    exit();
}

if (isset($_GET['delete_id'])) {
    $deleteId = $_GET['delete_id'];
    $deleteStmt = $pdo->prepare("DELETE FROM products WHERE id = :id");
    $deleteStmt->execute(['id' => $deleteId]);
    header("Location: admin_dashboard.php");
    exit();
}

// Query to fetch the number of failed login attempts
$failedLoginCountStmt = $pdo->prepare("SELECT COUNT(*) AS failed_count FROM failed_logins");
$failedLoginCountStmt->execute();
$failedLoginCount = $failedLoginCountStmt->fetch(PDO::FETCH_ASSOC)['failed_count'];

// Fetch products as usual (already in your code)
$pumaStmt = $pdo->prepare("SELECT * FROM products WHERE company_name = 'Puma'");
$pumaStmt->execute();
$pumaProducts = $pumaStmt->fetchAll(PDO::FETCH_ASSOC);

$nikeStmt = $pdo->prepare("SELECT * FROM products WHERE company_name = 'Nike'");
$nikeStmt->execute();
$nikeProducts = $nikeStmt->fetchAll(PDO::FETCH_ASSOC);

$adidasStmt = $pdo->prepare("SELECT * FROM products WHERE company_name = 'Adidas'");
$adidasStmt->execute();
$adidasProducts = $adidasStmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
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
        .nav-links .failed-attempts {
            color: red;
            font-weight: bold;
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
        .delete-btn {
            color: white;
            background-color: red;
            border: none;
            padding: 5px 10px;
            border-radius: 4px;
            cursor: pointer;
        }
        .delete-btn:hover {
            background-color: darkred;
        }
    </style>
</head>
<body>
    <div class="navbar">
        <div class="logo">
            <h2>Welcome, Group 6</h2>
        </div>
        <div class="nav-links">
            <a href="logout.php">Logout</a>
            <a href="feedbacks.php">Feedbacks</a>
            <!-- Display the number of failed attempts -->
            <a href="failed_attempts.php" class="failed-attempts">
                Failed Attempts (<?php echo $failedLoginCount; ?>)
            </a>
        </div>
    </div>

    <div class="content">
        <h2>Puma Products</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Price</th>
                    <th>Stock</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($pumaProducts as $product): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($product['id']); ?></td>
                        <td><?php echo htmlspecialchars($product['name']); ?></td>
                        <td>₱<?php echo number_format($product['price'], 2); ?></td>
                        <td><?php echo htmlspecialchars($product['stock']); ?></td>
                        <td><a href="?delete_id=<?php echo $product['id']; ?>" class="delete-btn">Delete</a></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <div class="content">
        <h2>Nike Products</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Price</th>
                    <th>Stock</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($nikeProducts as $product): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($product['id']); ?></td>
                        <td><?php echo htmlspecialchars($product['name']); ?></td>
                        <td>₱<?php echo number_format($product['price'], 2); ?></td>
                        <td><?php echo htmlspecialchars($product['stock']); ?></td>
                        <td><a href="?delete_id=<?php echo $product['id']; ?>" class="delete-btn">Delete</a></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <div class="content">
        <h2>Adidas Products</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Price</th>
                    <th>Stock</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($adidasProducts as $product): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($product['id']); ?></td>
                        <td><?php echo htmlspecialchars($product['name']); ?></td>
                        <td>₱<?php echo number_format($product['price'], 2); ?></td>
                        <td><?php echo htmlspecialchars($product['stock']); ?></td>
                        <td><a href="?delete_id=<?php echo $product['id']; ?>" class="delete-btn">Delete</a></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>

<?php
session_start();
include('config.php');

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: login.php");
    exit();
}

$companyName = $_SESSION['company_name'];

$stmt = $pdo->prepare("SELECT id, name, price, stock FROM products WHERE company_name = :company_name");
$stmt->execute(['company_name' => $companyName]);
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

$historyStmt = $pdo->prepare("SELECT * FROM history WHERE company_name = :company_name ORDER BY timestamp DESC");
$historyStmt->execute(['company_name' => $companyName]);
$history = $historyStmt->fetchAll(PDO::FETCH_ASSOC);

$purchasesStmt = $pdo->prepare("
    SELECT p.id, p.user_id, p.product_id, p.quantity, p.total_price, p.payment_method, p.purchase_date, 
           p.amount_paid, p.change_due, pr.name AS product_name, pr.company_name 
    FROM purchases p
    JOIN products pr ON p.product_id = pr.id
    WHERE pr.company_name = :company_name
    ORDER BY p.purchase_date DESC
");
$purchasesStmt->execute(['company_name' => $companyName]);
$purchases = $purchasesStmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventory - <?php echo htmlspecialchars($companyName); ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="./css/companies.css">
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
            background-color: #333;
            color: white;
            padding: 10px 20px;
        }
        .navbar .logo h2 {
            margin: 0;
        }
        .nav-links a {
            color: white;
            text-decoration: none;
            margin-left: 20px;
        }
        .content {
            padding: 20px;
            margin: 20px auto;
            background: white;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            max-width: 80%;
        }
        h1 {
            margin-top: 0;
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
            background-color: #333;
            color: white;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        button {
            background-color: #333;
            color: white;
            border: none;
            padding: 5px 10px;
            cursor: pointer;
            border-radius: 4px;
        }
        button:hover {
            background-color: #555;
        }
        form input[type="number"],
        form input[type="text"] {
            width: 60px;
            padding: 5px;
            margin-right: 5px;
        }
        .message {
            margin: 10px 0;
            padding: 10px;
            border-radius: 5px;
        }
        .success {
            background-color: #d4edda;
            color: #155724;
        }
        .error {
            background-color: #f8d7da;
            color: #721c24;
        }
        .content h1 {
            margin-top: 20px;
        }

        table {
            margin-top: 10px;
        }

    </style>
</head>
<body>
    <!-- Navigation Bar -->
    <div class="navbar">
        <div class="logo">
            <h2><?php echo htmlspecialchars($companyName); ?> Dashboard</h2>
        </div>
        <div class="nav-links">
            <a href="companies_dashboard.php">Home</a>
            <a href="inventory.php">Inventory</a>
            <a href="logout.php">Logout</a>
        </div>
    </div>

    <div class="content">
        <h1>Products</h1>
        <table>
            <thead>
                <tr>
                    <th>Product ID</th>
                    <th>Name</th>
                    <th>Price</th>
                    <th>Total Stocks</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($products) > 0): ?>
                    <?php foreach ($products as $product): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($product['id']); ?></td>
                            <td><?php echo htmlspecialchars($product['name']); ?></td>
                            <td>₱<?php echo number_format($product['price'], 2); ?></td>
                            <td><?php echo htmlspecialchars($product['stock'] ?? 0); ?></td>
                            <td>
                                <!-- Add Stock Form -->
                                <form action="add_stock.php" method="POST" style="display: inline-block;">
                                    <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                                    <input type="number" name="add_stock" min="1" placeholder="Add stock" required>
                                    <button type="submit">Add Stock</button>
                                </form>
                                <form action="update_price.php" method="POST" style="display: inline-block; margin-top: 5px;">
                                    <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                                    <input type="text" name="new_price" placeholder="New price" required>
                                    <button type="submit">Change Price</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5">No products available for this company.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <div class="content">
        <h1>History</h1>
        <table>
            <thead>
                <tr>
                    <th>Action</th>
                    <th>Product</th>
                    <th>Details</th>
                    <th>Timestamp</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($history) > 0): ?>
                    <?php foreach ($history as $record): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($record['action']); ?></td>
                            <td><?php echo htmlspecialchars($record['product_name'] ?? 'N/A'); ?></td>
                            <td><?php echo htmlspecialchars($record['details']); ?></td>
                            <td><?php echo htmlspecialchars($record['timestamp']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4">No history available for this company.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Purchases Table -->
    <div class="content">
        <h1>Purchased Products</h1>
        <table>
            <thead>
                <tr>
                    <th>Purchase ID</th>
                    <th>User ID</th>
                    <th>Product</th>
                    <th>Quantity</th>
                    <th>Total Price</th>
                    <th>Payment Method</th>
                    <th>Purchase Date</th>
                    <th>Amount Paid</th>
                    <th>Change Due</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($purchases) > 0): ?>
                    <?php foreach ($purchases as $purchase): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($purchase['id']); ?></td>
                            <td><?php echo htmlspecialchars($purchase['user_id']); ?></td>
                            <td><?php echo htmlspecialchars($purchase['product_name']); ?></td>
                            <td><?php echo htmlspecialchars($purchase['quantity']); ?></td>
                            <td>₱<?php echo number_format($purchase['total_price'], 2); ?></td>
                            <td><?php echo htmlspecialchars($purchase['payment_method']); ?></td>
                            <td><?php echo htmlspecialchars($purchase['purchase_date']); ?></td>
                            <td>₱<?php echo number_format($purchase['amount_paid'], 2); ?></td>
                            <td>₱<?php echo number_format($purchase['change_due'], 2); ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="9">No purchases made for this company.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>

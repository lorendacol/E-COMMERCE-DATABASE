<?php
session_start();
include('config.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $productId = $_POST['product_id'];
    $addStock = intval($_POST['add_stock']);
    $companyName = $_SESSION['company_name'];

    $stmt = $pdo->prepare("SELECT name FROM products WHERE id = :id");
    $stmt->execute(['id' => $productId]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($product) {
       
        $updateStmt = $pdo->prepare("UPDATE products SET stock = stock + :add_stock WHERE id = :id");
        $updateStmt->execute(['add_stock' => $addStock, 'id' => $productId]);

        $historyStmt = $pdo->prepare("INSERT INTO history (company_name, action, product_name, details) VALUES (:company_name, :action, :product_name, :details)");
        $historyStmt->execute([
            'company_name' => $companyName,
            'action' => 'Stock Added',
            'product_name' => $product['name'],
            'details' => "Added $addStock stocks"
        ]);

        $_SESSION['success_message'] = 'Stock added successfully!';
    } else {
        $_SESSION['error_message'] = 'Product not found.';
    }
}
header("Location: inventory.php");
exit();
?>

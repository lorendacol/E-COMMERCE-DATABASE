<?php
session_start();
include('config.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $productId = $_POST['product_id'];
    $newPrice = floatval($_POST['new_price']);
    $companyName = $_SESSION['company_name'];

    $stmt = $pdo->prepare("SELECT name, price FROM products WHERE id = :id");
    $stmt->execute(['id' => $productId]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($product) {
        $oldPrice = $product['price'];
        $productName = $product['name'];

        $updateStmt = $pdo->prepare("UPDATE products SET price = :new_price WHERE id = :id");
        $updateStmt->execute(['new_price' => $newPrice, 'id' => $productId]);

        $historyStmt = $pdo->prepare("INSERT INTO history (company_name, action, product_name, details) VALUES (:company_name, :action, :product_name, :details)");
        $historyStmt->execute([
            'company_name' => $companyName,
            'action' => 'Price Changed',
            'product_name' => $productName,
            'details' => "Changed price from ₱" . number_format($oldPrice, 2) . " to ₱" . number_format($newPrice, 2)
        ]);

        $_SESSION['success_message'] = 'Price updated successfully!';
    } else {
        $_SESSION['error_message'] = 'Product not found.';
    }
}
header("Location: inventory.php");
exit();
?>

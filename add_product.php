<?php
session_start();
include('config.php');

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $companyName = $_SESSION['company_name'];
    $productName = $_POST['product_name'];
    $productDescription = $_POST['product_description'];
    $productPrice = floatval($_POST['product_price']);
    $productStock = intval($_POST['product_stock']);
    $productImageUrl = $_POST['product_image_url'];

    $stmt = $pdo->prepare("INSERT INTO products (company_name, name, description, price, stock, image) VALUES (:company_name, :name, :description, :price, :stock, :image)");
    $stmt->execute([
        'company_name' => $companyName,
        'name' => $productName,
        'description' => $productDescription,
        'price' => $productPrice,
        'stock' => $productStock,
        'image' => $productImageUrl
    ]);

    $historyStmt = $pdo->prepare("INSERT INTO history (company_name, action, product_name, details) VALUES (:company_name, :action, :product_name, :details)");
    $historyStmt->execute([
        'company_name' => $companyName,
        'action' => 'Product Added',
        'product_name' => $productName,
        'details' => "Added product with price ₱" . number_format($productPrice, 2) . " and stock quantity of " . $productStock
    ]);

    $_SESSION['success_message'] = 'Product added successfully!';
}

header("Location: companies_dashboard.php");
exit();
?>

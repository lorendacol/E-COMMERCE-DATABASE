<?php
session_start();
include('config.php'); 

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $productId = $_POST['product_id'];
    $addStock = (int)$_POST['add_stock'];

    $stmt = $pdo->prepare("UPDATE products SET stock = stock + :add_stock WHERE id = :id");
    $stmt->execute([
        'add_stock' => $addStock,
        'id' => $productId
    ]);

    header("Location: companies_dashboard.php");
    exit();
}
?>

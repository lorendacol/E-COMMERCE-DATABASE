<?php

session_start();

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php");
    exit;
}

require_once "config.php";

$user_id = $_SESSION["id"];
$cart_sql = "SELECT c.id AS cart_id, c.product_id, p.stock, p.price, c.quantity, p.name 
             FROM cart c 
             JOIN products p ON c.product_id = p.id 
             WHERE c.user_id = :user_id";
$cart_stmt = $pdo->prepare($cart_sql);
$cart_stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$cart_stmt->execute();
$cart_items = $cart_stmt->fetchAll();

if (count($cart_items) > 0) {
    try {
       
        $pdo->beginTransaction();
        
        foreach ($cart_items as $item) {
           
            if ($item['quantity'] > $item['stock']) {
                throw new Exception("Insufficient stock for " . $item['name']);
            }

            $new_stock = $item['stock'] - $item['quantity'];
            $update_sql = "UPDATE products SET stock = :stock WHERE id = :product_id";
            $update_stmt = $pdo->prepare($update_sql);
            $update_stmt->bindParam(':stock', $new_stock, PDO::PARAM_INT);
            $update_stmt->bindParam(':product_id', $item['product_id'], PDO::PARAM_INT);
            $update_stmt->execute();

            $transaction_sql = "INSERT INTO transactions (user_id, product_id, quantity, total_price) 
                                VALUES (:user_id, :product_id, :quantity, :total_price)";
            $transaction_stmt = $pdo->prepare($transaction_sql);
            $total_price = $item['quantity'] * $item['price'];
            $transaction_stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
            $transaction_stmt->bindParam(':product_id', $item['product_id'], PDO::PARAM_INT);
            $transaction_stmt->bindParam(':quantity', $item['quantity'], PDO::PARAM_INT);
            $transaction_stmt->bindParam(':total_price', $total_price, PDO::PARAM_STR);
            $transaction_stmt->execute();
        }

        $delete_cart_sql = "DELETE FROM cart WHERE user_id = :user_id";
        $delete_cart_stmt = $pdo->prepare($delete_cart_sql);
        $delete_cart_stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $delete_cart_stmt->execute();

        $pdo->commit();

        header("location: purchase_success.php");
        exit;
    } catch (Exception $e) {
       
        $pdo->rollBack();
        echo "Error: " . $e->getMessage();
    }
} else {
    echo "Your cart is empty.";
}
?>

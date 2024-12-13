<?php
session_start();

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php");
    exit;
}

require_once "config.php";

if (isset($_GET['product_id'])) {
    $product_id = $_GET['product_id'];
    $user_id = $_SESSION["id"]; 
    $sql = "SELECT quantity FROM cart WHERE user_id = :user_id AND product_id = :product_id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->bindParam(':product_id', $product_id, PDO::PARAM_INT);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
       
        $cart_item = $stmt->fetch();
        $new_quantity = $cart_item['quantity'] + 1; 

        $update_sql = "UPDATE cart SET quantity = :quantity WHERE user_id = :user_id AND product_id = :product_id";
        $update_stmt = $pdo->prepare($update_sql);
        $update_stmt->bindParam(':quantity', $new_quantity, PDO::PARAM_INT);
        $update_stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $update_stmt->bindParam(':product_id', $product_id, PDO::PARAM_INT);
        $update_stmt->execute();
    } else {
        $insert_sql = "INSERT INTO cart (user_id, product_id, quantity) VALUES (:user_id, :product_id, 1)";
        $insert_stmt = $pdo->prepare($insert_sql);
        $insert_stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $insert_stmt->bindParam(':product_id', $product_id, PDO::PARAM_INT);
        $insert_stmt->execute();
    }

    header("location: userdashboard.php");
    exit;
}
?>

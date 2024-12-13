<?php
session_start();

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php");
    exit;
}

require_once "config.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
   
    $user_id = $_SESSION["id"];
    $payment_method = $_POST["payment_method"];
    $amount_paid = floatval($_POST["amount_paid"]);

    try {
        $cart_sql = "SELECT c.id AS cart_id, p.id AS product_id, p.price, p.stock, c.quantity
                     FROM cart c
                     JOIN products p ON c.product_id = p.id
                     WHERE c.user_id = :user_id";
        $cart_stmt = $pdo->prepare($cart_sql);
        $cart_stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $cart_stmt->execute();
        $cart_items = $cart_stmt->fetchAll();

        if (count($cart_items) === 0) {
            $_SESSION['error_message'] = "Your cart is empty.";
            header("location: userdashboard.php");
            exit;
        }

        $total_amount = 0;
        foreach ($cart_items as $item) {
            $total_amount += $item['price'] * $item['quantity'];
        }

        if ($amount_paid < $total_amount) {
            $_SESSION['error_message'] = "Insufficient payment. Please enter an amount equal to or greater than the total.";
            header("location: userdashboard.php");
            exit;
        }

        $change = $amount_paid - $total_amount;

        $pdo->beginTransaction();

        $insert_purchase_sql = "INSERT INTO purchases (user_id, product_id, quantity, total_price, payment_method, amount_paid, change_due)
                                VALUES (:user_id, :product_id, :quantity, :total_price, :payment_method, :amount_paid, :change_due)";
        $purchase_stmt = $pdo->prepare($insert_purchase_sql);

        $update_product_sql = "UPDATE products SET stock = stock - :quantity WHERE id = :product_id AND stock >= :quantity";
        $update_product_stmt = $pdo->prepare($update_product_sql);

        foreach ($cart_items as $item) {
          
            if ($item['stock'] < $item['quantity']) {
                $_SESSION['error_message'] = "Not enough stock for product: " . $item['product_id'];
                header("location: userdashboard.php");
                exit;
            }

            $total_price = $item['price'] * $item['quantity'];
            $purchase_stmt->execute([
                ':user_id' => $user_id,
                ':product_id' => $item['product_id'],
                ':quantity' => $item['quantity'],
                ':total_price' => $total_price,
                ':payment_method' => $payment_method,
                ':amount_paid' => $amount_paid,
                ':change_due' => $change
            ]);

            $update_product_stmt->execute([
                ':quantity' => $item['quantity'],
                ':product_id' => $item['product_id']
            ]);
        }

        // Clear the cart
        $clear_cart_sql = "DELETE FROM cart WHERE user_id = :user_id";
        $clear_cart_stmt = $pdo->prepare($clear_cart_sql);
        $clear_cart_stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $clear_cart_stmt->execute();

        $pdo->commit();

        $_SESSION['purchase_success'] = "Purchase successful! Change: ₱" . number_format($change, 2);
        header("location: userdashboard.php");
        exit;

    } catch (Exception $e) {
      
        $pdo->rollBack();
        $_SESSION['error_message'] = "There was an error processing your purchase. Please try again.";
        error_log("Purchase error: " . $e->getMessage());
        header("location: userdashboard.php");
        exit;
    }
} else {
   
    header("location: userdashboard.php");
    exit;
}
?>

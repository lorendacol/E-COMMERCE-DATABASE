<?php
$product_sql = "SELECT id, name, price, stock FROM products WHERE id = :product_id";
$product_stmt = $pdo->prepare($product_sql);
$product_stmt->bindParam(':product_id', $product_id, PDO::PARAM_INT);
$product_stmt->execute();
$product = $product_stmt->fetch();

if ($product) {
    echo "<h2>" . htmlspecialchars($product['name']) . "</h2>";
    echo "<p>Price: ₱" . number_format($product['price'], 2) . "</p>";
    echo "<p>Stock: " . $product['stock'] . "</p>";

    if ($product['stock'] > 0) {
        echo '<form action="add_to_cart.php" method="POST">
                <input type="hidden" name="product_id" value="' . $product['id'] . '">
                <button type="submit">Add to Cart</button>
              </form>';
    } else {
        echo "<p>Out of Stock</p>";
    }
}
?>

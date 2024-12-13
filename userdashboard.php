<?php
session_start();

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php");
    exit;
}

require_once "config.php"; 

$sql = "SELECT id, company_name, name, description, price, image, stock FROM products";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$products = $stmt->fetchAll();

$user_id = $_SESSION["id"]; 
$cart_sql = "SELECT c.id AS cart_id, p.name, p.price, c.quantity 
             FROM cart c 
             JOIN products p ON c.product_id = p.id 
             WHERE c.user_id = :user_id";
$cart_stmt = $pdo->prepare($cart_sql);
$cart_stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$cart_stmt->execute();
$cart_items = $cart_stmt->fetchAll();

$total_amount = 0;
foreach ($cart_items as $item) {
    $total_amount += $item['price'] * $item['quantity'];
}
if (isset($_GET['decrease_cart_item'])) {
    $cart_item_id = $_GET['decrease_cart_item'];
    
    $check_quantity_sql = "SELECT quantity FROM cart WHERE id = :cart_item_id";
    $check_quantity_stmt = $pdo->prepare($check_quantity_sql);
    $check_quantity_stmt->bindParam(':cart_item_id', $cart_item_id, PDO::PARAM_INT);
    $check_quantity_stmt->execute();
    $item = $check_quantity_stmt->fetch();
    
    if ($item) {
        $current_quantity = $item['quantity'];
        
        if ($current_quantity > 1) {
           
            $update_sql = "UPDATE cart SET quantity = quantity - 1 WHERE id = :cart_item_id AND quantity > 1";
            $update_stmt = $pdo->prepare($update_sql);
            $update_stmt->bindParam(':cart_item_id', $cart_item_id, PDO::PARAM_INT);
            $update_stmt->execute();
        } else {
            $delete_sql = "DELETE FROM cart WHERE id = :cart_item_id";
            $delete_stmt = $pdo->prepare($delete_sql);
            $delete_stmt->bindParam(':cart_item_id', $cart_item_id, PDO::PARAM_INT);
            $delete_stmt->execute();
        }
    }
    header("location: userdashboard.php");
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Dashboard</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #121212;
            color: #fff;
            font-family: Arial, sans-serif;
        }
        .navbar {
            background-color: #1a1a1a;
        }
        .navbar-brand, .nav-link {
            color: #00ff00 !important;
        }
        .navbar-nav .nav-link:hover {
            color: #ff1a1a !important;
        }
        .card {
            background-color: #1a1a1a;
            border: 1px solid #333;
            color: #fff;
            border-radius: 10px;
            margin-bottom: 30px;
        }
        .card-title {
            font-size: 1.25rem;
            color: #00ff00;
        }
        .card-img-top {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }
        .btn-primary {
            background-color: #00ff00;
            border-color: #00ff00;
        }
        .btn-primary:hover {
            background-color: #00cc00;
            border-color: #00cc00;
        }
        .container {
            display: flex;
        }
        .products-container {
            width: 70%;
        }
        .cart-container {
            width: 30%;
            padding: 15px;
            background-color: #1a1a1a;
            border: 1px solid #333;
            border-radius: 10px;
            margin-left: 15px;
            margin-top: 15px;
        }
        .cart-item {
            display: flex;
            justify-content: space-between;
            padding: 10px;
            border-bottom: 1px solid #333;
        }
        .cart-item:last-child {
            border-bottom: none;
        }
        .btn-secondary {
    background-color: #6c757d; 
    border-color: #6c757d;    
}

.btn-secondary:disabled {
    background-color: #b5b5b5; 
    border-color: #b5b5b5;     
    color: #fff;              
}

    </style>
</head>
<body>
   
    <nav class="navbar navbar-expand-lg navbar-light">
        <a class="navbar-brand" href="#">Product Dashboard</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item active">
                    <a class="nav-link" href="userdashboard.php">Dashboard</a>
                </li>
                <li class="nav-item active">
                    <a class="nav-link" href="profile.php">Profile</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="logout.php">Logout</a>
                </li>
            </ul>
        </div>
    </nav>

    <div class="container">
    <div class="products-container">
    <h2>Products</h2>
    <div class="row">
        <?php foreach ($products as $product): ?>
            <div class="col-md-4">
                <div class="card">
                    <img src="<?= htmlspecialchars($product['image']); ?>" class="card-img-top" alt="<?= htmlspecialchars($product['name']); ?>">
                    <div class="card-body">
                        <h5 class="card-title"><?= htmlspecialchars($product['name']); ?></h5>
                        <p class="card-text">Price: ₱<?= number_format($product['price'], 2); ?></p>
                        <p class="card-text">Stock: <?= $product['stock']; ?> units</p>
                        
                        <?php if ($product['stock'] > 0): ?>
                            <a href="add_to_cart.php?product_id=<?= $product['id']; ?>" class="btn btn-primary">Add to Cart</a>
                        <?php else: ?>
                            <button class="btn btn-secondary" disabled>Sold Out</button>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

        <div class="cart-container">
            <h3>Your Cart</h3>
            <?php if (count($cart_items) > 0): ?>
                <?php foreach ($cart_items as $item): ?>
                    <div class="cart-item">
                        <span><?= htmlspecialchars($item['name']); ?> (x<?= $item['quantity']; ?>)</span>
                        <span>₱<?= number_format($item['price'] * $item['quantity'], 2); ?></span>
                        <a href="userdashboard.php?decrease_cart_item=<?= $item['cart_id']; ?>" class="btn btn-danger btn-sm">-</a>
                    </div>
                <?php endforeach; ?>
                <div class="cart-item" style="font-weight: bold;">
                    <span>Total:</span>
                    <span>₱<?= number_format($total_amount, 2); ?></span>
                </div>
                <button class="btn btn-primary mt-3" data-toggle="modal" data-target="#purchaseModal">Proceed to Purchase</button>
            <?php else: ?>
                <p>Your cart is empty.</p>
            <?php endif; ?>
        </div>
    </div>

    <?php if (isset($_SESSION['purchase_success'])): ?>
    <div class="alert alert-success" role="alert">
        <?= $_SESSION['purchase_success']; ?>
    </div>
    <?php unset($_SESSION['purchase_success']); ?>
    <?php endif; ?>

    <div class="modal fade" id="purchaseModal" tabindex="-1" role="dialog" aria-labelledby="purchaseModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content bg-dark text-white">
                <div class="modal-header">
                    <h5 class="modal-title" id="purchaseModalLabel">Complete Your Purchase</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="process_purchase.php" method="POST">
                <div class="modal-body">
                    <h5>Cart Summary:</h5>
                    <?php foreach ($cart_items as $item): ?>
                        <div class="cart-item">
                            <span><?= htmlspecialchars($item['name']); ?> (x<?= $item['quantity']; ?>)</span>
                            <span>₱<?= number_format($item['price'] * $item['quantity'], 2); ?></span>
                        </div>
                    <?php endforeach; ?>
                    <hr>
                    <div class="cart-item" style="font-weight: bold;">
                        <span>Total:</span>
                        <span id="totalAmount" data-total="<?= $total_amount; ?>">₱<?= number_format($total_amount, 2); ?></span>
                    </div>
                    <div class="form-group mt-3">
                        <label for="paymentMethod">Payment Method:</label>
                        <select class="form-control" name="payment_method" id="paymentMethod" required>
                            <option value="Cash on Delivery">Cash on Delivery</option>
                            <option value="Gcash">Gcash</option>
                            <option value="Credit Card">Credit Card</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="amountPaid">Amount Paid:</label>
                        <input type="number" class="form-control" name="amount_paid" id="amountPaid" placeholder="Enter the amount" required>
                        <small id="warningMessage" class="text-danger" style="display: none;">Insufficient amount! Please enter an amount equal to or greater than the total.</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary" id="confirmPurchaseButton" disabled>Confirm Purchase</button>
                </div>
                </form>
            </div>
        </div>
    </div>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const amountPaidInput = document.getElementById("amountPaid");
        const totalAmount = parseFloat(document.getElementById("totalAmount").dataset.total);
        const warningMessage = document.getElementById("warningMessage");
        const confirmPurchaseButton = document.getElementById("confirmPurchaseButton");

        amountPaidInput.addEventListener("input", function () {
            const amountPaid = parseFloat(amountPaidInput.value);

            if (isNaN(amountPaid) || amountPaid < totalAmount) {
                warningMessage.style.display = "block";
                amountPaidInput.classList.add("is-invalid");
                confirmPurchaseButton.disabled = true;

                amountPaidInput.style.backgroundColor = "#ffcccc";
                setTimeout(() => {
                    amountPaidInput.style.backgroundColor = "transparent";
                }, 1000);
            } else {
                warningMessage.style.display = "none";
                amountPaidInput.classList.remove("is-invalid");
                confirmPurchaseButton.disabled = false;
            }
        });
    });
</script>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.4.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>

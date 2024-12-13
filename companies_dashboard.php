<?php
session_start();
include('config.php'); 

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: login.php");
    exit();
}

$companyName = $_SESSION['company_name'];

$stmt = $pdo->prepare("SELECT logo_image FROM companies WHERE company_name = :company_name");
$stmt->execute(['company_name' => $companyName]);
$companyDetails = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$companyDetails) {
    echo "Company not found!";
    exit();
}

$logoImage = $companyDetails['logo_image'];

$productStmt = $pdo->prepare("SELECT * FROM products WHERE company_name = :company_name");
$productStmt->execute(['company_name' => $companyName]);
$products = $productStmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($companyName); ?> Dashboard</title>
   
    <link rel="stylesheet" href="./css/companies.css">
</head>
<body>
   
    <div class="navbar">
        <div class="logo">
            <img src="<?php echo htmlspecialchars($logoImage); ?>" alt="<?php echo htmlspecialchars($companyName); ?> Logo" height="50">
        </div>
        <div class="nav-links">
            <a href="companies_dashboard.php">Home</a>
            <a href="inventory.php">Inventory</a>
            <a href="logout.php">Logout</a>
        </div>
    </div>
    <div class="content">
    <h1>Welcome, <?php echo htmlspecialchars($companyName); ?>!</h1>

    <button id="addProductButton">Add Product</button>

<div id="popupForm" class="popup">
    <div class="popup-content">
        <span id="closePopup">&times;</span>
        <h2>Add New Product</h2>
        <form action="add_product.php" method="POST">
            <input type="text" name="product_name" placeholder="Product Name" required><br>
            <textarea name="product_description" placeholder="Product Description" required></textarea><br>
            <input type="number" name="product_price" step="0.01" placeholder="Product Price" required><br>
            <input type="number" name="product_stock" min="1" placeholder="Stock Quantity" required><br>
            <input type="text" name="product_image_url" placeholder="Image URL" required><br>
            <button type="submit">Save Product</button>
        </form>
    </div>
</div>


<div class="products">
    <?php if (count($products) > 0): ?>
        <?php foreach ($products as $product): ?>
            <div class="product-card">
                <img 
                    src="<?php echo htmlspecialchars($product['image']); ?>" 
                    alt="<?php echo htmlspecialchars($product['name']); ?>">
                <h2><?php echo htmlspecialchars($product['name']); ?></h2>
                <p><?php echo htmlspecialchars($product['description']); ?></p>
                <span>Price: ₱<?php echo number_format($product['price'], 2); ?></span><br>
                <span>Stock: <?php echo htmlspecialchars($product['stock']); ?></span>
               
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>No products available for this company.</p>
    <?php endif; ?>
</div>
</body>
<script>
    const addProductButton = document.getElementById('addProductButton');
    const popupForm = document.getElementById('popupForm');
    const closePopup = document.getElementById('closePopup');

    addProductButton.addEventListener('click', () => {
        popupForm.style.display = 'flex';
    });

    closePopup.addEventListener('click', () => {
        popupForm.style.display = 'none';
    });

    window.addEventListener('click', (e) => {
        if (e.target === popupForm) {
            popupForm.style.display = 'none';
        }
    });
</script>

</html>

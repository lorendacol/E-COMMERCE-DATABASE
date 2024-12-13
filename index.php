
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shoe Shope</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="./css/style.css">
    <link rel="stylesheet" href="./css/style1.css">
    <style>
        
</style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>  
    <div class="navbar">
        <div class="page-title">Shoe Shope</div>
        <div class="buttons">
            <button onclick="window.location.href='login.php'">
                <i class="fas fa-sign-in-alt"></i> Login
            </button>
            <a href="#" id="companyBtn">
                <button>
                    <i class="fas fa-building"></i> Companies
                </button>
            </a>
            <a href="#" id="adminBtn">
                <button>
                    <i class="fas fa-user-shield"></i> Admin
                </button>
            </a>
        </div>
    </div>

    <div class="modal-background" id="modalBackground"></div>

    <div class="modal" id="companyModal">
        <div class="modal-header">
            <h3>Company Login</h3>
        </div>
        <div class="modal-body">
            <p>Please fill in the credentials for the company login.</p>

            <?php
            session_start();
            include('config.php'); 

            $email = "";

            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $company = $_POST['company'];
                $email = $_POST['email'];
                $password = $_POST['password'];

                $stmt = $pdo->prepare("SELECT * FROM companies WHERE company_name = :company AND email = :email");
                $stmt->execute(['company' => $company, 'email' => $email]);
                $companyDetails = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($companyDetails && $password === $companyDetails['password']) {
                    $_SESSION['logged_in'] = true;
                    $_SESSION['company_name'] = ucfirst($company);
                    header("Location: companies_dashboard.php"); 
                    exit();
                } else {
                    $error = "Invalid email or password!";
                }
            }
            ?>

            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <div class="form-group">
                    <label for="company">Select Company:</label>
                    <select id="company" name="company" class="form-control" required>
                        <option value="nike">Nike</option>
                        <option value="adidas">Adidas</option>
                        <option value="puma">Puma</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" name="email" class="form-control" value="<?php echo htmlspecialchars($email); ?>" required>
                    <?php if(isset($error)) echo '<span class="text-danger">'.$error.'</span>'; ?>
                </div>

                <div class="form-group">
                    <label for="password">Password:</label>
                    <input type="password" name="password" class="form-control" required>
                    <?php if(isset($error)) echo '<span class="text-danger">'.$error.'</span>'; ?>
                </div>

                <div class="form-group">
                    <input type="submit" class="btn btn-primary btn-block" value="Login">
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <button onclick="closeModal()">Close</button>
        </div>
    </div>

    <div class="modal" id="adminModal">
        <div class="modal-header">
            <h3>Admin Login</h3>
        </div>
        <div class="modal-body">
            <p>Please enter the credentials for admin login.</p>

            <?php
         
            $adminEmail = "";
            $adminPassword = "";

            if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['adminLogin'])) {
                $adminEmail = $_POST['email'];
                $adminPassword = $_POST['password'];

                if ($adminEmail === 'group6@admin.com' && $adminPassword === 'ecommers') {
                    $_SESSION['logged_in'] = true;
                    $_SESSION['admin_email'] = $adminEmail;
                    header("Location: admin_dashboard.php");
                    exit();
                } else {
                    $adminError = "Invalid email or password!";
                }
            }
            ?>

            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" name="email" class="form-control" value="<?php echo htmlspecialchars($adminEmail); ?>" required>
                    <?php if(isset($adminError)) echo '<span class="text-danger">'.$adminError.'</span>'; ?>
                </div>

                <div class="form-group">
                    <label for="password">Password:</label>
                    <input type="password" name="password" class="form-control" required>
                    <?php if(isset($adminError)) echo '<span class="text-danger">'.$adminError.'</span>'; ?>
                </div>

                <div class="form-group">
                    <input type="submit" name="adminLogin" class="btn btn-primary btn-block" value="Login as Admin">
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <button onclick="closeAdminModal()">Close</button>
        </div>
    </div>
    <?php
$stmt = $pdo->prepare("SELECT * FROM products WHERE company_name = 'Nike' LIMIT 3");
$stmt->execute();
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<div class="companiename">Nike</div>
<div class="product-container">
    <div class="row">
        <?php foreach($products as $product): ?>
        <div class="col">
            <div class="product-card">
                <img src="<?php echo $product['image']; ?>" alt="<?php echo $product['name']; ?>" class="product-image">
                <h4 class="product-name"><?php echo $product['name']; ?></h4>
                <p class="product-description"><?php echo $product['description']; ?></p>
                <p class="product-price">₱<?php echo number_format($product['price'], 2); ?></p>
                <button class="buy-btn" onclick="showLoginPrompt()">Check</button>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>
<?php
$stmt = $pdo->prepare("SELECT * FROM products WHERE company_name = 'Puma' LIMIT 3");
$stmt->execute();
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<div class="companiename">Puma</div>
<div class="product-container">
    <div class="row">
        <?php foreach($products as $product): ?>
        <div class="col">
            <div class="product-card">
                <img src="<?php echo $product['image']; ?>" alt="<?php echo $product['name']; ?>" class="product-image">
                <h4 class="product-name"><?php echo $product['name']; ?></h4>
                <h4 class="product-description"><?php echo $product['description']; ?></h4>
                <h4 class="product-price">₱<?php echo number_format($product['price'], 2); ?></h4>
                <button class="buy-btn" onclick="showLoginPrompt()">Check</button>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>


<script>
        const companyBtn = document.getElementById("companyBtn");
        const adminBtn = document.getElementById("adminBtn");
        const companyModal = document.getElementById("companyModal");
        const adminModal = document.getElementById("adminModal");
        const modalBackground = document.getElementById("modalBackground");

        companyBtn.addEventListener("click", function(e) {
            e.preventDefault();
            companyModal.classList.add("active");
            modalBackground.classList.add("active");
        });

        adminBtn.addEventListener("click", function(e) {
            e.preventDefault();
            adminModal.classList.add("active");
            modalBackground.classList.add("active");
        });

        function closeModal() {
            companyModal.classList.remove("active");
            modalBackground.classList.remove("active");
        }

        function closeAdminModal() {
            adminModal.classList.remove("active");
            modalBackground.classList.remove("active");
        }

        modalBackground.addEventListener("click", closeModal);
        modalBackground.addEventListener("click", closeAdminModal);
    </script>
<?php
$stmt = $pdo->prepare("SELECT * FROM products WHERE company_name = 'Adidas' LIMIT 3");
$stmt->execute();
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<div class="companiename">Adidas</div>
<div class="product-container">
    <div class="row">
        <?php foreach($products as $product): ?>
        <div class="col">
            <div class="product-card">
                <img src="<?php echo $product['image']; ?>" alt="<?php echo $product['name']; ?>" class="product-image">
                <h4 class="product-name"><?php echo $product['name']; ?></h4>
                <p class="product-description"><?php echo $product['description']; ?></p>
                <p class="product-price">₱<?php echo number_format($product['price'], 2); ?></p>
                <button class="buy-btn" onclick="showLoginPrompt()">Check</button>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>
<style>
.companiename {
    font-size: 64px;
    font-weight: bold;
    color: #00ff7f; 
    margin: 20px 0;
    text-align: center;
    text-transform: uppercase;
}

.product-container {
    display: flex;
    flex-direction: column;
    align-items: center;
    margin-bottom: 40px;
}

.row {
    display: flex;
    justify-content: center;
    gap: 20px;
    flex-wrap: wrap;
}

/* Product Card */
.product-card {
    background-color: #1a1a1a;
    border: 2px solid #00ff7f;
    border-radius: 8px;
    padding: 15px;
    width: 250px;
    text-align: center;
    box-shadow: 0 4px 8px rgba(0, 255, 127, 0.3);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.product-card:hover {
    transform: translateY(-10px);
    box-shadow: 0 8px 16px rgba(0, 255, 127, 0.5);
}

.product-image {
    width: 200px;
    height: 200px;
    object-fit: cover;
    border-radius: 4px;
    margin-bottom: 10px;
}

.product-name {
    font-size: 18px;
    color: #00ff7f;
    margin: 10px 0;
}

.product-description {
    font-size: 14px;
    color: #00ff7f;
    margin-bottom: 10px;
}

.product-price {
    font-size: 16px;
    font-weight: bold;
    color: #00ff7f;
    margin-bottom: 15px;
}

.buy-btn {
    background-color: #00ff7f;
    color: #000000;
    border: none;
    padding: 10px 15px;
    border-radius: 4px;
    font-size: 14px;
    font-weight: bold;
    cursor: pointer;
    transition: background-color 0.3s ease, color 0.3s ease;
}

.buy-btn:hover {
    background-color: #ffffff;
    color: #00ff7f;
}

/* Responsive Design */
@media (max-width: 768px) {
    .row {
        flex-direction: column;
        gap: 15px;
    }

    .product-card {
        width: 100%;
        max-width: 300px;
    }
}
</style>
<div id="login-modal" class="modal-container">
    <div class="modal-content">
        <h3>"Please log in to continue and access the product."</h3>
        <div class="modal-buttons">
            <button onclick="redirectToLogin()">Login</button>
            <button onclick="redirectToRegister()">Register</button>
        </div>
        <button class="cancel-btn" onclick="redirectToIndex()">Cancel</button>
    </div>
</div>

<style>
.modal-container {
    display: none; 
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.8);
    justify-content: center;
    align-items: center;
    z-index: 1000;
}

.modal-content {
    background-color: #000;
    padding: 30px;
    border-radius: 12px;
    text-align: center;
    box-shadow: 0 4px 15px rgba(0, 255, 0, 0.5);
    width: 320px;
    animation: slideDown 0.3s ease-in-out;
}

.modal-content h3 {
    color: #00ff7f;
    font-family: 'Arial', sans-serif;
    margin-bottom: 20px;
 
}

.modal-buttons button {
    margin: 10px;
    padding: 12px 24px;
    border: none;
    background-color: #00ff7f;
    color: #000;
    border-radius: 8px;
    font-size: 16px;
    font-weight: bold;
    cursor: pointer;
   
    transition: all 0.3s ease-in-out;
}

.modal-buttons button:hover {
    background-color: #1aff1a; 
    box-shadow: 0 6px 12px rgba(0, 255, 0, 0.9);
    transform: scale(1.05);
}

.cancel-btn {
    margin-top: 20px;
    padding: 10px;
    background-color: #ff0000; 
    color: white;
    border: none;
    border-radius: 8px;
    font-size: 14px;
    cursor: pointer;

    transition: all 0.3s ease-in-out;
}

.cancel-btn:hover {
    background-color: #ff4d4d; 
   
    transform: scale(1.05);
}

@keyframes slideDown {
    from {
        transform: translateY(-50%);
        opacity: 0;
    }
    to {
        transform: translateY(0);
        opacity: 1;
    }
}
</style>

<script>
function showLoginPrompt() {
    const modal = document.getElementById('login-modal');
    if (modal) {
        modal.style.display = 'flex'; 
    } else {
        console.error("Modal container not found");
    }
}

function redirectToLogin() {
    window.location.href = 'login.php'; 
}

function redirectToRegister() {
    window.location.href = 'register.php';
}

function redirectToIndex() {
    window.location.href = 'index.php'; 
}
</script>

</body>
</html>

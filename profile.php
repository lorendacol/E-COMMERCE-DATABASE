<?php
session_start();

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php");
    exit;
}

require_once "config.php"; 

$user_id = $_SESSION["id"]; 
$sql_user = "SELECT id, full_name, email, cellphone_number, address, birthday, gender, country, created_at 
             FROM users WHERE id = :user_id";
$stmt_user = $pdo->prepare($sql_user);
$stmt_user->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$stmt_user->execute();
$user = $stmt_user->fetch();

// Handle feedback submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['feedback'])) {
    $product_id = $_POST['product_id'];
    $feedback = $_POST['feedback'];
    
    $sql_feedback = "INSERT INTO product_feedback (user_id, product_id, feedback) VALUES (:user_id, :product_id, :feedback)";
    $stmt_feedback = $pdo->prepare($sql_feedback);
    $stmt_feedback->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt_feedback->bindParam(':product_id', $product_id, PDO::PARAM_INT);
    $stmt_feedback->bindParam(':feedback', $feedback, PDO::PARAM_STR);
    $stmt_feedback->execute();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Profile</title>
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
        
    /* Custom Button Style */
    .feedback-btn {
        background-color: #121212; /* Black */
        color: #39ff14; /* Neon Green */
        border: 2px solid #39ff14;
        padding: 10px 20px;
        font-size: 1rem;
        border-radius: 5px;
        transition: all 0.3s ease;
    }

    .feedback-btn:hover {
        background-color: #39ff14; /* Neon Green on Hover */
        color: #121212; /* Black text on hover */
        cursor: pointer;
        transform: scale(1.1); /* Slightly enlarge the button */
    }

    .feedback-btn:focus {
        outline: none;
        box-shadow: 0 0 5px #39ff14; /* Neon green shadow when clicked */
    }

    /* Table Styling */
    table.table-custom {
        background-color: #121212;
        color: #fff;
        border: 1px solid #333;
    }

    table.table-custom th, table.table-custom td {
        text-align: center;
        padding: 15px;
    }

    table.table-custom th {
        background-color: #1a1a1a;
        color: #39ff14; /* Neon green header */
    }

    table.table-custom tr:nth-child(even) {
        background-color: #1a1a1a;
    }

    table.table-custom tr:nth-child(odd) {
        background-color: #121212;
    }

    table.table-custom td {
        border-top: 1px solid #333;
    }
</style>

    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-light">
    <a class="navbar-brand" href="#">User Profile</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ml-auto">
            <li class="nav-item active">
                <a class="nav-link" href="userdashboard.php">Dashboard</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="logout.php">Logout</a>
            </li>
        </ul>
    </div>
</nav>

<div class="container">
    <h2>User Profile</h2>
    <div class="card">
        <div class="card-body">
            <p><strong>Full Name:</strong> <?= htmlspecialchars($user['full_name']); ?></p>
            <p><strong>Email:</strong> <?= htmlspecialchars($user['email']); ?></p>
            <p><strong>Cellphone Number:</strong> <?= htmlspecialchars($user['cellphone_number']); ?></p>
            <p><strong>Address:</strong> <?= nl2br(htmlspecialchars($user['address'])); ?></p>
            <p><strong>Birthday:</strong> <?= htmlspecialchars($user['birthday']); ?></p>
            <p><strong>Gender:</strong> <?= htmlspecialchars($user['gender']); ?></p>
            <p><strong>Country:</strong> <?= htmlspecialchars($user['country']); ?></p>
            <p><strong>Account Created:</strong> <?= htmlspecialchars($user['created_at']); ?></p>
        </div>
    </div>

    <h3>Transaction History</h3>
    <table class="table table-dark">
        <thead>
            <tr>
                <th>#</th>
                <th>Product Name</th>
                <th>Quantity</th>
                <th>Total Price</th>
                <th>Payment Method</th>
                <th>Purchase Date</th>
                <th>Amount Paid</th>
                <th>Change Due</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $sql_transactions = "SELECT p.id, p.name, pu.quantity, pu.total_price, pu.payment_method, pu.purchase_date, pu.amount_paid, pu.change_due
                                 FROM purchases pu
                                 JOIN products p ON pu.product_id = p.id
                                 WHERE pu.user_id = :user_id";
            $stmt_transactions = $pdo->prepare($sql_transactions);
            $stmt_transactions->bindParam(':user_id', $user_id, PDO::PARAM_INT);
            $stmt_transactions->execute();
            $transactions = $stmt_transactions->fetchAll();

            $total_spent = 0;
            foreach ($transactions as $index => $transaction) {
                $total_spent += $transaction['total_price'];
                ?>
                <tr>
                    <td><?= $index + 1; ?></td>
                    <td><?= htmlspecialchars($transaction['name']); ?></td>
                    <td><?= $transaction['quantity']; ?></td>
                    <td>₱<?= number_format($transaction['total_price'], 2); ?></td>
                    <td><?= htmlspecialchars($transaction['payment_method']); ?></td>
                    <td><?= htmlspecialchars($transaction['purchase_date']); ?></td>
                    <td>₱<?= number_format($transaction['amount_paid'], 2); ?></td>
                    <td>₱<?= number_format($transaction['change_due'], 2); ?></td>
                    <td>
                        <button class="btn btn-info" data-toggle="modal" data-target="#feedbackModal" data-product-id="<?= $transaction['id']; ?>">Leave Feedback</button>
                    </td>
                </tr>
                <?php
            }
            ?>
            <tr>
                <th colspan="6">Total Spent</th>
                <th colspan="2">₱<?= number_format($total_spent, 2); ?></th>
            </tr>
        </tbody>
    </table>
</div>
<style>
    .modal-content {
        background-color: #121212; 
        border: 2px solid #00ff00; 
        border-radius: 10px;
        color: #fff;
    }

    .modal-header {
        background-color: #1a1a1a; 
        border-bottom: 1px solid #333;
    }

    .modal-title {
        color: #00ff00;
    }

    .close {
        color: #00ff00;
        font-size: 1.5rem;
    }

    .close:hover {
        color: #ff1a1a; 
    }

    .form-control {
        background-color: #333; 
        color: #fff; 
        border: 1px solid #00ff00; 
        border-radius: 5px;
    }

    .form-control:focus {
        border-color: #00ff00; 
        box-shadow: 0 0 5px #00ff00;
    }

    .btn-secondary {
        background-color: #121212; 
        color: #00ff00; 
        border: 2px solid #00ff00;
        transition: all 0.3s ease;
    }

    .btn-secondary:hover {
        background-color: #00ff00; 
        cursor: pointer;
    }

    .btn-primary {
        background-color: #00ff00; 
        color: #121212; 
        border: 2px solid #00ff00;
        transition: all 0.3s ease;
    }

    .btn-primary:hover {
        background-color: #121212; 
        color: #00ff00; 
        cursor: pointer;
    }
</style>

<div class="modal fade" id="feedbackModal" tabindex="-1" aria-labelledby="feedbackModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="feedbackModalLabel">Leave Your Feedback</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST">
                <div class="modal-body">
                    <input type="hidden" name="product_id" id="product_id">
                    <div class="form-group">
                        <label for="feedback">Your Feedback:</label>
                        <textarea class="form-control" name="feedback" id="feedback" rows="4" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Submit Feedback</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.4.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<script>
    $('#feedbackModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var product_id = button.data('product-id'); 
        var modal = $(this);
        modal.find('#product_id').val(product_id);
    });
</script>

</body>
</html>

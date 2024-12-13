<?php

session_start();

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php");
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Purchase Successful</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <div class="alert alert-success">
            <h2>Your purchase was successful!</h2>
            <p>Your items will be shipped shortly.</p>
            <a href="userdashboard.php" class="btn btn-primary">Return to Dashboard</a>
        </div>
    </div>
</body>
</html>

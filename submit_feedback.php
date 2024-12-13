<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    require_once "config.php";

    $user_id = $_POST["user_id"];
    $product_id = $_POST["product_id"];
    $feedback = $_POST["feedback"];

    $sql = "INSERT INTO product_feedback (user_id, product_id, feedback) VALUES (:user_id, :product_id, :feedback)";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->bindParam(':product_id', $product_id, PDO::PARAM_INT);
    $stmt->bindParam(':feedback', $feedback, PDO::PARAM_STR);

    if ($stmt->execute()) {
        $_SESSION["feedback_success"] = "Feedback submitted successfully.";
    } else {
        $_SESSION["feedback_error"] = "Failed to submit feedback.";
    }

    header("Location: user_profile.php");
    exit;
}
?>

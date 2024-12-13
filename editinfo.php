<?php

session_start();

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php");
    exit;
}

require_once "config.php";

$field = isset($_GET['field']) ? $_GET['field'] : '';
$allowed_fields = ['full_name', 'cellphone_number', 'birthday', 'address', 'gender', 'country'];

if (!in_array($field, $allowed_fields)) {
    echo "Invalid field.";
    exit;
}

$new_value = "";
$update_err = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty(trim($_POST["new_value"]))) {
        $update_err = "Please enter a value.";
    } else {
        $new_value = trim($_POST["new_value"]);

        $sql = "UPDATE users SET $field = :new_value WHERE id = :id";
        if ($stmt = $pdo->prepare($sql)) {
            $stmt->bindParam(":new_value", $new_value, PDO::PARAM_STR);
            $stmt->bindParam(":id", $_SESSION["id"], PDO::PARAM_INT);

            if ($stmt->execute()) {
                header("location: profile.php");
                exit;
            } else {
                $update_err = "Something went wrong. Please try again.";
            }

            unset($stmt);
        }
    }
    unset($pdo);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit <?php echo htmlspecialchars($field); ?></title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2>Edit <?php echo htmlspecialchars($field); ?></h2>
        <?php if (!empty($update_err)): ?>
            <div class="alert alert-danger"><?php echo $update_err; ?></div>
        <?php endif; ?>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . "?field=" . urlencode($field); ?>" method="post">
            <div class="form-group">
                <label>New Value</label>
                <input type="text" name="new_value" class="form-control" value="<?php echo htmlspecialchars($new_value); ?>">
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Update">
                <a href="profile.php" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</body>
</html>

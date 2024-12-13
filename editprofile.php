<?php

session_start();

require_once "config.php";

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: index.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
   
    $full_name = trim($_POST["full_name"]);
    $phone = trim($_POST["phone"]);
    $address = trim($_POST["address"]);
    $birthday = trim($_POST["birthday"]);
    $gender = trim($_POST["gender"]);
    $country = trim($_POST["country"]);

    $sql = "UPDATE users SET 
                full_name = :full_name, 
                cellphone_number = :phone, 
                address = :address, 
                birthday = :birthday, 
                gender = :gender, 
                country = :country 
            WHERE id = :id";

    if ($stmt = $pdo->prepare($sql)) {
      
        $stmt->bindParam(":full_name", $full_name, PDO::PARAM_STR);
        $stmt->bindParam(":phone", $phone, PDO::PARAM_STR);
        $stmt->bindParam(":address", $address, PDO::PARAM_STR);
        $stmt->bindParam(":birthday", $birthday, PDO::PARAM_STR);
        $stmt->bindParam(":gender", $gender, PDO::PARAM_STR);
        $stmt->bindParam(":country", $country, PDO::PARAM_STR);
        $stmt->bindParam(":id", $_SESSION["id"], PDO::PARAM_INT);

        if ($stmt->execute()) {
          
            header("location: userdashboard.php");
            exit;
        } else {
            echo "Something went wrong. Please try again later.";
        }
        unset($stmt);
    }
    unset($pdo);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Profile</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="./css/editprofile.css">
</head>
<body>
    <div class="wrapper">
        <h2>Edit Profile</h2>
        <p>Please complete your profile details.</p>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group">
                <label>Full Name</label>
                <input type="text" name="full_name" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Phone Number</label>
                <input type="text" name="phone" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Address</label>
                <input type="text" name="address" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Birthday</label>
                <input type="date" name="birthday" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Gender</label>
                <select name="gender" class="form-control" required>
                    <option value="Male">Male</option>
                    <option value="Female">Female</option>
                    <option value="Other">Other</option>
                </select>
            </div>
            <div class="form-group">
                <label>Country</label>
                <select name="country" class="form-control" required>
                    <option value="Philippines">Philippines</option>
                    <option value="United States">United States</option>
                    <option value="China">China</option>
                    <option value="Brazil">Brazil</option>
                    <option value="Thailand">Thailand</option>
                    <option value="Japan">Japan</option>
                    <option value="India">India</option>
                    <option value="Germany">Germany</option>
                    <option value="Australia">Australia</option>
                    <option value="Canada">Canada</option>
                </select>
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-primary">Save</button>
            </div>
        </form>
    </div>
</body>
</html>

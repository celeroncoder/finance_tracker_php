<?php
session_start();

// Handle customer information form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["customer_info"])) {
    $_SESSION["customer_name"] = $_POST["name"];
    $_SESSION["customer_address"] = $_POST["address"];
    $_SESSION["customer_phone"] = $_POST["phone"];
    header("Location: product_info.php");
    exit;
}

// Handle product information form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["product_info"])) {
    $_SESSION["product_name"] = $_POST["product_name"];
    $_SESSION["product_quantity"] = $_POST["quantity"];
    $_SESSION["product_rate"] = $_POST["rate"];
    header("Location: bill.php");
    exit;
}
?>

<!-- Customer Information Form -->
<!DOCTYPE html>
<html>
<head>
    <title>Customer Information</title>
</head>
<body>
    <h1>Customer Information</h1>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
        Name: <input type="text" name="name" required><br>
        Address: <textarea name="address" required></textarea><br>
        Phone: <input type="tel" name="phone" required><br>
        <input type="submit" name="customer_info" value="Next">
    </form>
</body>
</html>

<!-- Product Information Form -->
<!DOCTYPE html>
<html>
<head>
    <title>Product Information</title>
</head>
<body>
    <h1>Product Information</h1>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
        Product Name: <input type="text" name="product_name" required><br>
        Quantity: <input type="number" name="quantity" required><br>
        Rate: <input type="number" step="0.01" name="rate" required><br>
        <input type="submit" name="product_info" value="Generate Bill">
    </form>
</body>
</html>

<!-- Bill -->
<!DOCTYPE html>
<html>
<head>
    <title>Bill</title>
</head>
<body>
    <h1>Bill</h1>
    <h2>Customer Information</h2>
    <p>Name: <?php echo $_SESSION["customer_name"]; ?></p>
    <p>Address: <?php echo $_SESSION["customer_address"]; ?></p>
    <p>Phone: <?php echo $_SESSION["customer_phone"]; ?></p>

    <h2>Product Information</h2>
    <p>Product Name: <?php echo $_SESSION["product_name"]; ?></p>
    <p>Quantity: <?php echo $_SESSION["product_quantity"]; ?></p>
    <p>Rate: <?php echo $_SESSION["product_rate"]; ?></p>

    <?php
    $total = $_SESSION["product_quantity"] * $_SESSION["product_rate"];
    echo "<h2>Total: $" . number_format($total, 2) . "</h2>";
    ?>
</body>
</html>
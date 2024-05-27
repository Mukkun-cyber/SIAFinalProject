<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

include 'db.php';
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manufacturing System</title>
</head>
<body>
    <h1>Manufacture Product</h1>
    <form action="manufacture.php" method="post">
        <label for="product_id">Select Product:</label>
        <select name="product_id" id="product_id">
            <?php
            $stmt = $pdo->query('SELECT id, product_name FROM products');
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                echo "<option value='{$row['id']}'>{$row['product_name']}</option>";
            }
            ?>
        </select>
        <button type="submit">Manufacture</button>
    </form>

    <h2>Inventory</h2>
    <ul>
        <?php
        $stmt = $pdo->query('SELECT part_name, quantity FROM inventory');
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo "<li>{$row['part_name']}: {$row['quantity']}</li>";
        }
        ?>
    </ul>

    <h2>Orders</h2>
    <ul>
        <?php
        $stmt = $pdo->query('SELECT orders.id, products.product_name, orders.status, orders.created_at 
                             FROM orders 
                             JOIN products ON orders.product_id = products.id');
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo "<li>Order #{$row['id']} - {$row['product_name']} - {$row['status']} - {$row['created_at']}</li>";
        }
        ?>
    </ul>
</body>
</html>

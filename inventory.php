<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $part_name = $_POST['part_name'];
    $quantity = $_POST['quantity'];

    $stmt = $pdo->prepare('INSERT INTO inventory (part_name, quantity) VALUES (?, ?)');
    if ($stmt->execute([$part_name, $quantity])) {
        echo "Part added successfully!";
    } else {
        echo "Error adding part.";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Inventory Management</title>
</head>
<body>
    <h1>Inventory Management</h1>
    <form action="inventory.php" method="post">
        <input type="text" name="part_name" placeholder="Part Name" required>
        <input type="number" name="quantity" placeholder="Quantity" required>
        <button type="submit">Add Part</button>
    </form>
</body>
</html>

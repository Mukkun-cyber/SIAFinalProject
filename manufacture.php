<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $product_id = $_POST['product_id'];
    $user_id = $_SESSION['user_id'];

    // Get the required parts for the product
    $stmt = $pdo->prepare('SELECT part_name, quantity_required FROM product_parts WHERE product_id = ?');
    $stmt->execute([$product_id]);
    $parts = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Check inventory
    $canManufacture = true;
    foreach ($parts as $part) {
        $stmt = $pdo->prepare('SELECT quantity FROM inventory WHERE part_name = ?');
        $stmt->execute([$part['part_name']]);
        $inventory = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$inventory || $inventory['quantity'] < $part['quantity_required']) {
            $canManufacture = false;
            break;
        }
    }

    if ($canManufacture) {
        // Deduct parts from inventory
        foreach ($parts as $part) {
            $stmt = $pdo->prepare('UPDATE inventory SET quantity = quantity - ? WHERE part_name = ?');
            $stmt->execute([$part['quantity_required'], $part['part_name']]);
        }

        // Create a new order
        $stmt = $pdo->prepare('INSERT INTO orders (product_id, user_id, status) VALUES (?, ?, ?)');
        $stmt->execute([$product_id, $user_id, 'completed']);
        echo "Product manufactured successfully!";
    } else {
        echo "Insufficient parts in inventory.";
    }
}
?>

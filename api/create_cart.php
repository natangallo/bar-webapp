<?php
require '../db.php';

$data = json_decode(file_get_contents('php://input'), true);
$items = $data['items'];

$stmt = $pdo->prepare('INSERT INTO carts (total_price) VALUES (0)');
$stmt->execute();
$cartId = $pdo->lastInsertId();

$totalPrice = 0;
foreach ($items as $item) {
    $productStmt = $pdo->prepare('SELECT price FROM products WHERE id = ?');
    $productStmt->execute([$item['product_id']]);
    $product = $productStmt->fetch(PDO::FETCH_ASSOC);

    $itemPrice = $product['price'] * $item['quantity'];
    $totalPrice += $itemPrice;

    $stmt = $pdo->prepare('INSERT INTO cart_items (cart_id, product_id, quantity) VALUES (?, ?, ?)');
    $stmt->execute([$cartId, $item['product_id'], $item['quantity']]);
}

$stmt = $pdo->prepare('UPDATE carts SET total_price = ? WHERE id = ?');
$stmt->execute([$totalPrice, $cartId]);

header('Content-Type: application/json');
echo json_encode(['cartId' => $cartId]);
?>

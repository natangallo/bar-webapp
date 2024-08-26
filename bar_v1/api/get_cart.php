<?php
require '../db.php';

$cartId = $_GET['id'];

$stmt = $pdo->prepare('SELECT * FROM carts WHERE id = ?');
$stmt->execute([$cartId]);
$cart = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$cart) {
    http_response_code(404);
    echo json_encode(['message' => 'Cart not found']);
    exit;
}

$stmt = $pdo->prepare('
    SELECT ci.quantity, p.name, p.price
    FROM cart_items ci
    JOIN products p ON ci.product_id = p.id
    WHERE ci.cart_id = ?
');
$stmt->execute([$cartId]);
$items = $stmt->fetchAll(PDO::FETCH_ASSOC);

header('Content-Type: application/json');
echo json_encode(['cart' => $cart, 'items' => $items]);
?>

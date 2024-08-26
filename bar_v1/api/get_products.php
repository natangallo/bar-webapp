<?php
require '../db.php';

$stmt = $pdo->query('SELECT id, name, price, stock_quantity FROM products');
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

header('Content-Type: application/json');
echo json_encode($products);
?>

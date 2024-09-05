<?php
header('Content-Type: application/json');

// Includi il file di accesso al DataBase
include '../include/db.php';

$data = json_decode(file_get_contents("php://input"));

$id = $data->id;
$nome = $data->nome;

$query = "UPDATE categorie SET nome = ? WHERE id = ?";
$stmt = $pdo->prepare($query);

if ($stmt->execute([$nome, $id])) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false]);
}
?>



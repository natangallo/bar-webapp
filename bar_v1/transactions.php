<?php
session_start();
require 'db.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

// Fetch all transactions with product names
$query = '
    SELECT t.id, t.quantity, t.transaction_time, t.customer_name, p.name as product_name 
    FROM transactions t 
    JOIN products p ON t.product_id = p.id 
    ORDER BY t.transaction_time DESC';
$transactions = $pdo->query($query)->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transazioni</title>
    <link rel="stylesheet" href="css/admin-styles.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: white;
            color: black;
        }
        .container {
            width: 100%;
            padding: 20px;
        }
        .table-container {
            overflow-x: auto;
            overflow-y: auto;
            max-width: 100%;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: center;
            background-color: rgba(255, 255, 255, 0.8);
        }
        th {
            background-color: #f2f2f2;
            color: black;
        }
        td {
            color: black;
        }
        .logout-btn {
            position: fixed;
            bottom: 20px;
            right: 20px;
            padding: 10px 20px;
            background-color: #d9534f;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .logout-btn:hover {
            background-color: #c9302c;
        }
        .back-btn {
            padding: 10px 20px;
            background-color: #007BFF;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-bottom: 20px;
        }
        .back-btn:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <button class="back-btn" onclick="window.location.href='admin.php'">Torna alla Dashboard</button>
        <h1>Transazioni</h1>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>ID Transazione</th>
                        <th>Nome Prodotto</th>
                        <th>Quantità</th>
                        <th>Data e Ora</th>
                        <th>Nome Cliente</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($transactions as $transaction): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($transaction['id']); ?></td>
                            <td><?php echo htmlspecialchars($transaction['product_name']); ?></td>
                            <td><?php echo htmlspecialchars($transaction['quantity']); ?></td>
                            <td><?php echo htmlspecialchars($transaction['transaction_time']); ?></td>
                            <td><?php echo htmlspecialchars($transaction['customer_name']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <button class="logout-btn" onclick="window.location.href='logout.php'">Logout</button>
</body>
</html>

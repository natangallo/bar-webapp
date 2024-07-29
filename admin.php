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

// Handle add product
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_product'])) {
    $name = $_POST['name'];
    $price = $_POST['price'];
    $stock_quantity = $_POST['stock_quantity'];
    $type = $_POST['type'];

    if (!empty($name) && !empty($price) && !empty($stock_quantity) && !empty($type)) {
        $stmt = $pdo->prepare('INSERT INTO products (name, price, stock_quantity, type) VALUES (?, ?, ?, ?)');
        $stmt->execute([$name, $price, $stock_quantity, $type]);
    }
}

// Handle update product
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_product'])) {
    $id = $_POST['id'];
    $new_price = $_POST['price'];
    $new_stock_quantity = $_POST['stock_quantity'];
    $customer_name = $_POST['customer_name'];

    if (!empty($id) && !empty($new_price) && !empty($new_stock_quantity) && !empty($customer_name)) {
        $stmt = $pdo->prepare('SELECT price, stock_quantity FROM products WHERE id = ?');
        $stmt->execute([$id]);
        $product = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($product) {
            $price_difference = $new_price - $product['price'];
            $stock_difference = $new_stock_quantity - $product['stock_quantity'];

            $stmt = $pdo->prepare('UPDATE products SET price = ?, stock_quantity = ? WHERE id = ?');
            $stmt->execute([$new_price, $new_stock_quantity, $id]);

            // Log the transaction
            $stmt = $pdo->prepare('INSERT INTO transactions (product_id, quantity, transaction_time, customer_name) VALUES (?, ?, NOW(), ?)');
            $stmt->execute([$id, $stock_difference, $customer_name]);
        }
    }
}

// Fetch all products
$products = $pdo->query('SELECT * FROM products ORDER BY name ASC')->fetchAll(PDO::FETCH_ASSOC);

// Fetch all transactions
$transactions = $pdo->query('SELECT * FROM transactions ORDER BY transaction_time DESC')->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin</title>
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
        .buttons {
            display: flex;
            justify-content: space-between;
        }
        button {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        button:hover {
            background-color: #45a049;
        }
        .accordion {
            cursor: pointer;
            padding: 10px;
            width: 100%;
            border: none;
            text-align: left;
            outline: none;
            font-size: 15px;
            background-color: #f2f2f2;
            color: black;
            margin-bottom: 5px;
        }
        .panel {
            padding: 0 18px;
            display: none;
            background-color: white;
            overflow: hidden;
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
        .transaction-details {
            display: none;
            width: 100%;
        }
        .filter-input {
            margin-bottom: 10px;
            padding: 5px;
            width: 100%;
            max-width: 300px;
        }
        .header-buttons {
            display: flex;
            justify-content: flex-end;
            margin-bottom: 10px;
        }
        .transaction-btn {
            padding: 10px 20px;
            background-color: #007BFF;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .transaction-btn:hover {
            background-color: #0056b3;
        }
        .edit-fields {
            display: none;
        }
        .edit-mode .edit-fields {
            display: inline;
        }
        .edit-mode .static-fields {
            display: none;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Admin Panel</h1>

        <div class="header-buttons">
            <button class="transaction-btn" onclick="window.location.href='transactions.php'">Visualizza Transazioni</button>
        </div>

        <input type="text" id="nameFilter" class="filter-input" onkeyup="filterByName()" placeholder="Filtra per nome">

        <h2>Prodotti</h2>
        <div class="table-container">
            <table id="productsTable">
                <thead>
                    <tr>
                        <th>Nome</th>
                        <th>Prezzo</th>
                        <th>Quantità a magazzino</th>
                        <th>Tipo</th>
                        <th>Azioni</th>
                        <th>Dettagli</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($products as $product): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($product['name']); ?></td>
                            <td class="static-fields"><?php echo htmlspecialchars($product['price']); ?></td>
                            <td class="static-fields"><?php echo htmlspecialchars($product['stock_quantity']); ?></td>
                            <td><?php echo htmlspecialchars($product['type']); ?></td>
                            <td>
                                <form method="POST" action="admin.php" class="edit-form">
                                    <input type="hidden" name="id" value="<?php echo $product['id']; ?>">
                                    <span class="edit-fields">
                                        <input type="number" name="price" value="<?php echo $product['price']; ?>" required>
                                        <input type="number" name="stock_quantity" value="<?php echo $product['stock_quantity']; ?>" required>
                                        <input type="text" name="customer_name" placeholder="Nome e Cognome" required>
                                    </span>
                                    <button type="button" class="edit-btn">Modifica</button>
                                    <button type="submit" name="update_product" class="edit-fields">Salva</button>
                                </form>
                            </td>
                            <td>
                                <button class="details-btn" onclick="toggleDetails(<?php echo $product['id']; ?>)">Dettagli</button>
                            </td>
                        </tr>
                        <tr id="details-<?php echo $product['id']; ?>" class="transaction-details">
                            <td colspan="6">
                                <div class="table-container">
                                    <table>
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Quantità</th>
                                                <th>Data e Ora</th>
                                                <th>Nome Cliente</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($transactions as $transaction): ?>
                                                <?php if ($transaction['product_id'] == $product['id']): ?>
                                                    <tr>
                                                        <td><?php echo htmlspecialchars($transaction['id']); ?></td>
                                                        <td><?php echo htmlspecialchars($transaction['quantity'] >= 0 ? '+'.$transaction['quantity'] : $transaction['quantity']); ?></td>
                                                        <td><?php echo htmlspecialchars($transaction['transaction_time']); ?></td>
                                                        <td><?php echo htmlspecialchars($transaction['customer_name']); ?></td>
                                                    </tr>
                                                <?php endif; ?>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <h2>Aggiungi Prodotto</h2>
        <form method="POST" action="admin.php">
            <div>
                <label for="name">Nome:</label>
                <input type="text" id="name" name="name" required>
            </div>
            <div>
                <label for="price">Prezzo:</label>
                <input type="number" step="0.01" id="price" name="price" required>
            </div>
            <div>
                <label for="stock_quantity">Quantità a magazzino:</label>
                <input type="number" id="stock_quantity" name="stock_quantity" required>
            </div>
            <div>
                <label for="type">Tipo:</label>
                <select id="type" name="type" required>
                    <option value="Gelati">Gelati</option>
                    <option value="Bibite">Bibite</option>
                </select>
            </div>
            <button type="submit" name="add_product">Aggiungi Prodotto</button>
        </form>
    </div>

    <button class="logout-btn" onclick="window.location.href='logout.php'">Logout</button>

    <script>
        function toggleDetails(productId) {
            var detailsRow = document.getElementById('details-' + productId);
            if (detailsRow.style.display === 'none' || detailsRow.style.display === '') {
                detailsRow.style.display = 'table-row';
            } else {
                detailsRow.style.display = 'none';
            }
        }

        function filterByName() {
            var input, filter, table, tr, td, i, txtValue;
            input = document.getElementById("nameFilter");
            filter = input.value.toUpperCase();
            table = document.getElementById("productsTable");
            tr = table.getElementsByTagName("tr");

            for (i = 1; i < tr.length; i++) { // Start at 1 to skip table header
                td = tr[i].getElementsByTagName("td")[0]; // The first column
                if (td) {
                    txtValue = td.textContent || td.innerText;
                    if (txtValue.toUpperCase().indexOf(filter) > -1) {
                        tr[i].style.display = "";
                    } else {
                        tr[i].style.display = "none";
                    }
                }       
            }
        }

        document.querySelectorAll('.edit-btn').forEach(button => {
            button.addEventListener('click', () => {
                const form = button.closest('.edit-form');
                form.classList.toggle('edit-mode');
            });
        });
    </script>
</body>
</html>

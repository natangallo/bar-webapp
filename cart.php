<?php
session_start();
require 'db.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['remove'])) {
        $remove_id = $_POST['remove'];
        if (($key = array_search($remove_id, $_SESSION['cart'])) !== false) {
            unset($_SESSION['cart'][$key]);
        }
    } elseif (isset($_POST['products']) && is_array($_POST['products'])) {
        $_SESSION['cart'] = array_fill_keys(array_keys($_POST['products']), 1);
    } elseif (isset($_POST['checkout'])) {
        $firstName = $_POST['first_name'] ?? '';
        $lastName = $_POST['last_name'] ?? '';

        if (empty($firstName) || empty($lastName)) {
            $message = 'Nome e Cognome sono obbligatori.';
        } else {
            $pdo->beginTransaction();
            
            try {
                foreach ($_SESSION['cart'] as $product_id => $quantity) {
                    $quantity = 1;
                    
                    $stmt = $pdo->prepare('SELECT * FROM products WHERE id = ?');
                    $stmt->execute([$product_id]);
                    $product = $stmt->fetch(PDO::FETCH_ASSOC);
                    
                    if ($product['stock_quantity'] >= $quantity) {
                        $stmt = $pdo->prepare('UPDATE products SET stock_quantity = stock_quantity - ? WHERE id = ?');
                        $stmt->execute([$quantity, $product_id]);
                        
                        $stmt = $pdo->prepare('INSERT INTO transactions (product_id, quantity, customer_name) VALUES (?, ?, ?)');
                        $stmt->execute([$product_id, $quantity, $firstName . ' ' . $lastName]);
                    } else {
                        throw new Exception('Prodotto esaurito: ' . $product['name']);
                    }
                }
                
                $pdo->commit();
                
                $_SESSION['cart'] = [];
                
                $message = 'Transazione completata con successo.';
            } catch (Exception $e) {
                $pdo->rollBack();
                $message = 'Transazione fallita: ' . $e->getMessage();
            }
        }
    }
}

$cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];

$product_ids = implode(',', array_keys($cart));
if (!empty($product_ids)) {
    $stmt = $pdo->query("SELECT * FROM products WHERE id IN ($product_ids)");
    $products_in_cart = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    $products_in_cart = [];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carrello</title>
    <link rel="stylesheet" href="css/styles.css">
    <style>
        body {
            background: url('https://images.pexels.com/photos/18720093/pexels-photo-18720093/free-photo-of-sand-on-hawaiian-beach.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=2') no-repeat center center fixed;
            background-size: cover;
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 100%;
            padding: 20px;
            background-color: rgba(255, 255, 255, 0.8);
            color: black;
            display: flex;
            flex-direction: column;
            align-items: center;
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
        }
        th {
            background-color: #f2f2f2;
        }
        td.name {
            background-color: #ffffff;
            color: #000000;
        }
        .buttons {
            display: flex;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 10px;
            width: 100%;
            margin-top: 20px;
        }
        button, a {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            text-decoration: none;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        button:hover, a:hover {
            background-color: #45a049;
        }
        .message {
            color: green;
            margin-bottom: 20px;
        }
        .error {
            color: red;
            margin-bottom: 20px;
        }
        .remove-btn {
            background-color: #f44336;
        }
        .remove-btn:hover {
            background-color: #e53935;
        }
        .quantity-input {
            width: 60px;
        }
        .checkout-form {
            display: flex;
            flex-direction: column;
            align-items: center;
            width: 400px;
            max-width: 100%;
        }
        .checkout-form div {
            margin-bottom: 10px;
            width: 100%;
        }
        .checkout-form label {
            display: block;
            margin-bottom: 5px;
        }
        .checkout-form input {
            width: calc(100% - 20px);
            padding: 5px;
        }
    </style>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('.remove-btn').forEach(function(button) {
                button.addEventListener('click', function(event) {
                    event.preventDefault();
                    const row = this.closest('tr');
                    const productId = this.value;

                    fetch('cart.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                        },
                        body: new URLSearchParams({ remove: productId })
                    })
                    .then(response => response.text())
                    .then(data => {
                        row.remove();
                        const emptyMessage = document.querySelector('.empty-cart-message');
                        const cartTable = document.querySelector('table');
                        if (!cartTable.querySelector('tbody').children.length) {
                            cartTable.remove();
                            emptyMessage.style.display = 'block';
                        }
                    });
                });
            });
        });
    </script>
</head>
<body>
    <div class="container">
        <h1>Carrello</h1>
        <?php if (!empty($message)): ?>
            <p class="<?php echo strpos($message, 'successo') !== false ? 'message' : 'error'; ?>"><?php echo $message; ?></p>
        <?php endif; ?>
        <?php if (count($products_in_cart) > 0): ?>
            <form method="POST" action="cart.php" id="remove-form">
                <table>
                    <thead>
                        <tr>
                            <th>Nome</th>
                            <th>Prezzo</th>
                            <th>Quantità Disponibile</th>
                            <th>Quantità</th>
                            <th>Rimuovi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($products_in_cart as $product): ?>
                            <tr>
                                <td class="name"><?php echo htmlspecialchars($product['name']); ?></td>
                                <td><?php echo htmlspecialchars($product['price']); ?></td>
                                <td><?php echo htmlspecialchars($product['stock_quantity']); ?></td>
                                <td>
                                    <input type="number" value="1" min="1" max="<?php echo $product['stock_quantity']; ?>" class="quantity-input" readonly>
                                </td>
                                <td>
                                    <button type="button" name="remove" value="<?php echo $product['id']; ?>" class="remove-btn">🗑️</button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <p class="empty-cart-message" style="display:none;">Il carrello è vuoto.</p>
            </form>
            <form method="POST" action="cart.php" id="checkout-form" class="checkout-form">
                <div>
                    <label for="first_name">Nome:</label>
                    <input type="text" id="first_name" name="first_name" required>
                </div>
                <div>
                    <label for="last_name">Cognome:</label>
                    <input type="text" id="last_name" name="last_name" required>
                </div>
                <input type="hidden" name="checkout" value="1">
                <div class="buttons">
                    <button type="submit">Checkout</button>
                    <a href="index.php">Torna alla pagina principale</a>
                </div>
            </form>
        <?php else: ?>
            <p>Il carrello è vuoto.</p>
            <a href="index.php">Torna alla pagina principale</a>
        <?php endif; ?>
    </div>
</body>
</html>

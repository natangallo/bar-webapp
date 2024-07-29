<?php
require 'db.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Fetch all products, ordered by name
$stmt = $pdo->query('SELECT * FROM products ORDER BY name ASC');
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Separate products by type
$gelati = array_filter($products, function($product) {
    return $product['type'] == 'Gelato';
});
$bibite = array_filter($products, function($product) {
    return $product['type'] == 'Bibita';
});
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bar WebApp</title>
    <link rel="stylesheet" href="css/styles.css">
    <style>
        body {
            background: url('https://images.pexels.com/photos/18720093/pexels-photo-18720093/free-photo-of-sand-on-hawaiian-beach.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=2') no-repeat center center fixed;
            background-size: cover;
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            color: black;
        }
        .container {
            width: 80%;
            margin: 0 auto;
            padding: 20px;
            background-color: rgba(255, 255, 255, 0.8);
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            max-height: 90vh;
            overflow-y: auto;
            position: relative;
        }
        h1, h2 {
            text-align: center;
        }
        h1 {
            position: sticky;
            top: 0;
            background-color: rgba(255, 255, 255, 0.8);
            padding: 10px;
            margin: 0;
            border-bottom: 1px solid #ddd;
        }
        form {
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .products-container {
            display: flex;
            justify-content: space-around;
            flex-wrap: wrap;
        }
        .product-type {
            flex: 1;
            min-width: 300px;
            margin: 10px;
            position: relative;
        }
        .product-type h2 {
            position: sticky;
            top: 0;
            background-color: rgba(255, 255, 255, 0.8);
            padding: 10px;
            margin: 0;
            border-bottom: 1px solid #ddd;
            z-index: 1;
        }
        .product {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
            padding: 10px;
            background-color: #f9f9f9;
            border-radius: 5px;
        }
        .product label {
            flex: 1;
            margin-right: 10px;
        }
        .product span {
            min-width: 40px;
            text-align: center;
        }
        button, a {
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            text-align: center;
        }
        button:hover, a:hover {
            background-color: #45a049;
        }
        .filter-input {
            margin-bottom: 10px;
            padding: 5px;
            width: 100%;
            box-sizing: border-box;
        }
        .admin-link {
            position: absolute;
            top: 20px;
            right: 20px;
            background-color: #f44336;
        }
        .admin-link:hover {
            background-color: #e53935;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Bar WebApp</h1>
        <a href="admin.php" class="admin-link">Admin Area</a>
        <form method="POST" action="cart.php">
            <div class="products-container">
                <div class="product-type">
                    <h2>Gelati</h2>
                    <input type="text" id="gelati-filter" class="filter-input" placeholder="Filtra gelati...">
                    <div id="gelati-list">
                        <?php foreach ($gelati as $product): ?>
                            <div class="product" data-name="<?php echo htmlspecialchars($product['name']); ?>">
                                <label>
                                    <input type="checkbox" name="products[<?php echo $product['id']; ?>]" value="1">
                                    <?php echo htmlspecialchars($product['name']); ?>
                                </label>
                                <span>
                                    <?php echo $product['stock_quantity'] > 0 ? $product['stock_quantity'] : 'N/D'; ?>
                                </span>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <div class="product-type">
                    <h2>Bibite</h2>
                    <input type="text" id="bibite-filter" class="filter-input" placeholder="Filtra bibite...">
                    <div id="bibite-list">
                        <?php foreach ($bibite as $product): ?>
                            <div class="product" data-name="<?php echo htmlspecialchars($product['name']); ?>">
                                <label>
                                    <input type="checkbox" name="products[<?php echo $product['id']; ?>]" value="1">
                                    <?php echo htmlspecialchars($product['name']); ?>
                                </label>
                                <span>
                                    <?php echo $product['stock_quantity'] > 0 ? $product['stock_quantity'] : 'N/D'; ?>
                                </span>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
            <button type="submit">Checkout</button>
        </form>
    </div>

    <script>
        function filterProducts(type) {
            var filterInput = document.getElementById(type + '-filter').value.toLowerCase();
            var productList = document.getElementById(type + '-list');
            var products = productList.getElementsByClassName('product');

            for (var i = 0; i < products.length; i++) {
                var productName = products[i].getAttribute('data-name').toLowerCase();
                products[i].style.display = productName.includes(filterInput) ? '' : 'none';
            }
        }

        document.getElementById('gelati-filter').addEventListener('input', function() {
            filterProducts('gelati');
        });

        document.getElementById('bibite-filter').addEventListener('input', function() {
            filterProducts('bibite');
        });
    </script>
</body>
</html>

<?php
// checkout.php

// Includi il file per la gestione della sessione
include '../accesso/session.php';

?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
    <link rel="stylesheet" href="../styles.css">
</head>
<body>
    <div class="container">
        <header>
            <h1>Checkout</h1>
            <nav>
                <?php include '../include/menu.php'; ?>
            </nav>

        </header>
        <section id="checkout-section">
            <h2>Riepilogo Acquisti</h2>
            <div id="carrello-riepilogo"></div>
            <h2>Informazioni Cliente</h2>
            <input type="text" id="cerca-cliente" placeholder="Cerca cliente...">
            <div id="clienti-lista"></div>

        </section>
    </div>

    <script src="checkout.js"></script>
    <?php include '../include/footer.php'; ?>
</body>
</html>

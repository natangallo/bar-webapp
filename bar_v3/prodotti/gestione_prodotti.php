<?php
// Includi il file per la gestione della sessione
include '../accesso/session.php';

?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestione Prodotti</title>
    <link rel="stylesheet" href="../styles.css">
</head>
<body>
    <div class="container">
        <header>
            <h1>Gestione Prodotti</h1>
            <nav>
                <?php include '../include/menu.php'; ?>
            </nav>
        </header>
        <section id="gestione-prodotti">
            <h2>Prodotti Esistenti</h2>
            <div id="prodotti-lista"></div>
            <div id="modifica-form" style="display: none;">
    		<h2>Modifica Prodotto</h2>
		    <input type="hidden" id="modifica-id">
		    <label for="modifica-nome">Nome:</label>
		    <input type="text" id="modifica-nome">
		    <br>
		    <label for="modifica-prezzo">Prezzo:</label>
		    <input type="text" id="modifica-prezzo">
		    <br>
		    <label for="modifica-categoria">Categoria:</label>
		    <input type="text" id="modifica-categoria">
		    <br>
		    <button id="salva-modifica">Salva Modifica</button>
			</div>
            <h2>Aggiungi Nuovo Prodotto</h2>
            <form id="aggiungi-prodotto">
                <input type="text" id="nome-prodotto" placeholder="Nome prodotto" required>
                <input type="number" id="prezzo-prodotto" step="0.01" placeholder="Prezzo prodotto" required>
                <input type="text" id="categoria-prodotto" placeholder="Categoria prodotto" required>
                <button type="submit">Aggiungi Prodotto</button>
            </form>

            <h2>Aggiungi Prodotti da CSV</h2>
            <textarea id="csv-input" placeholder="Incolla il CSV qui... 'nome,prezzo,categoria'"></textarea>
            <button id="aggiungi-csv">Aggiungi Prodotti da CSV</button>
        </section>
        <button id="torna-index">Torna alla Home</button>
    </div>

    <script src="prodotti.js"></script>
    <?php include '../include/footer.php'; ?>
</body>
</html>

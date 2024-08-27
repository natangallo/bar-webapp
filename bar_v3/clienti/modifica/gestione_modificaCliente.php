<?php
// Includi il file per la gestione della sessione
include '../../accesso/session.php';

?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifica Cliente</title>
    <link rel="stylesheet" href="../../styles.css">
</head>
<body>
    <div class="container">
        <header>
            <h1>Modifica Cliente</h1>
            <nav>
                <button id="menu-toggle" aria-label="Apri menu">☰</button>
                <ul id="menu" class="hidden">
                <?php include '../../include/menu.php'; ?>
                </ul>
            </nav>
        </header>
        
        <aside class="sidebar">
            <nav class="menu">
            	<ul >
               		<?php include '../../include/menu.php'; ?>
               	</ul>
            </nav>
        </aside>
        
        <section id="modifica-cliente">
            <h2>Dettagli Cliente</h2>
            <form id="modifica-form">
                <input type="hidden" id="cliente-id" name="id">

                <label for="nome">Nome:</label>
                <input type="text" id="nome" name="nome" required>

                <label for="numero-stanza">Numero di Stanza:</label>
                <input type="text" id="numero-stanza" name="numero_stanza" required>

                <label for="categoria">Categoria:</label>
	            <select id="categoria" name="categorie[]" multiple>
                    <!-- Le categorie verranno caricate tramite JS -->
                </select>
		        <label for="saldo-corrente">Saldo Corrente (€):</label>
		        <input type="text" id="saldo-corrente" name="saldo_corrente" disabled>

		        <label for="saldo-incremento">Aggiungi Importo al Saldo (€):</label>
		        <input type="number" step="0.01" id="saldo-incremento" name="saldo_incremento">


                <button type="submit">Salva Modifiche</button>
            </form>
        </section>

        <section id="transazioni">
            <h2>Transazioni</h2>
            <table id="transazioni-table">
    <thead>
        <tr>
            <th>Data</th>
            <th>Descrizione</th>
            <th>Prodotto</th>
            <th>Quantità</th>
            <th>Prezzo</th>
            <th>Totale Transazione</th>
        </tr>
    </thead>
    <tbody>
        <!-- Le righe delle transazioni verranno inserite dinamicamente -->
    </tbody>
</table>
        </section>
    </div>

    <script src="modificaCliente.js"></script>
    <?php include '../../include/footer.php'; ?>
</body>
</html>
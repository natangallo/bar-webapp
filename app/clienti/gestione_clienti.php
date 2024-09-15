<?php
// Includi il file per la gestione della sessione
include '../accesso/session.php';

?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clienti</title>
    <link rel="stylesheet" href="../styles.css">
</head>
<body>
    <div class="container">
        <header>
            <h1>Gestione Clienti</h1>
            <nav>
                <button id="menu-toggle" aria-label="Apri menu">☰</button>
                <ul id="menu" class="hidden">
                <?php include '../include/menu.php'; ?>
                </ul>
            </nav>
        </header>
        
        <aside class="sidebar">
            <nav class="menu">
            	<ul >
               		<?php include '../include/menu.php'; ?>
               	</ul>
            </nav>
        </aside>
        
        <section id="clienti-filtro">
            <select id="categoria-filter">
                <option value="">Tutte le categorie</option>
                <!-- Le categorie verranno caricate tramite JS -->
            </select>
        </section>

        <section id="clienti-lista">
                <input type="text" id="search-clienti" placeholder="Cerca per nome...">        
            <table id="clienti-table">
                <thead>
                    <tr>
                        <th>Nome</th>
                        <th>Numero di Stanza</th>
                        <th>Categoria</th>
                        <th>Saldo</th>
                        <th>Azione</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- I dati verranno inseriti dinamicamente tramite JS -->
                </tbody>
            </table>
        </section>
        <section>
            <button id="aggiungi-cliente-btn">Aggiungi Cliente</button>
            <button id="aggiungi-categoria-btn">Aggiungi Categoria</button>
			<button id="crea-accessi-btn" type="button">Crea Accessi Cliente</button>
<div id="output"></div>
		</section>
        <section id="form-sezione" style="display:none;">
            <h2 id="form-titolo">Aggiungi Singolo Cliente</h2>
            <form id="cliente-form">
                <label for="nome">Nome:</label>
                <input type="text" id="nome" name="nome" required>

                <label for="numero-stanza">Numero di Stanza:</label>
                <input type="text" id="numero-stanza" name="numero-stanza" required>

                <label for="categoria">Categoria:</label>
                <select id="categoria" name="categoria" multiple>
                    <!-- Le categorie verranno caricate tramite JS -->
                </select>

                <button type="submit">Salva</button>
                <button type="button" id="annulla-form">Annulla</button>
            </form>
        </section>

        <section id="form-categoria-sezione" style="display:none;">
            <h2 id="form-categoria-titolo">Aggiungi Nuova Categoria</h2>
            <form id="categoria-form">
                <label for="categoria-nome">Nome Categoria:</label>
                <input type="text" id="categoria-nome" name="categoria-nome" required>

                <button type="submit">Salva</button>
                <button type="button" id="annulla-categoria-form">Annulla</button>
            </form>
        </section>

        <section id="form-csv-sezione">
            <h2>Importa o Aggiorna Clienti da CSV</h2>
            <textarea id="csv-input" placeholder="Incolla il contenuto del CSV qui...'Nome,Numero di Stanza,Categoria' "></textarea>
            <p>Non Usare spazi prima o dopo la virgola. <br> Per un utente che esiste già:<br>- Se il numero di stanza cambia, viene creato un nuovo utente.<br>- Se la categoria cambia, viene aggiunta. </p>
            <button id="importa-csv-btn">Importa CSV</button>
        </section>
    </div>

    <script src="clienti.js"></script>
    <?php include '../include/footer.php'; ?>
</body>
</html>
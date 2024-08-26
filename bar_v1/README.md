### README

# Admin Panel per la Gestione dei Prodotti

Benvenuti nel pannello di amministrazione per la gestione dei prodotti. Questo strumento vi permette di aggiungere, modificare e visualizzare i prodotti e le loro transazioni in modo semplice e intuitivo. Di seguito troverete una descrizione delle funzionalità, le istruzioni per l'installazione e i dettagli tecnici importanti.

## Funzionalità

### Gestione Prodotti

- **Aggiungi Prodotto**: È possibile aggiungere nuovi prodotti inserendo nome, prezzo, quantità a magazzino e tipo (Gelati o Bibite).
- **Modifica Prodotto**: I prodotti possono essere modificati per aggiornare il prezzo e la quantità a magazzino. Le modifiche vengono registrate con il nome del cliente che ha effettuato la transazione.
- **Visualizza Prodotti**: Tutti i prodotti sono elencati in una tabella con la possibilità di filtrare per nome.

### Gestione Transazioni

- **Visualizza Transazioni**: È possibile visualizzare tutte le transazioni effettuate, ordinate per data.
- **Dettagli Prodotto**: Ogni prodotto ha un pulsante "Dettagli" che, se cliccato, mostra le transazioni relative a quel prodotto.

## Installazione

1. **Clona il repository**:
   ```bash
   git clone https://github.com/tuo-repo/admin-panel.git
   ```

2. **Configura il database**:
   - Importa il file `db.sql` nel tuo server MySQL per creare le tabelle necessarie.

3. **Configura il file `db.php`**:
   - Aggiorna le credenziali del database nel file `db.php`:
     ```php
     <?php
     $host = 'localhost';
     $dbname = 'nome_del_database';
     $username = 'nome_utente';
     $password = 'password';
     try {
         $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
         $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
     } catch (PDOException $e) {
         echo 'Connection failed: ' . $e->getMessage();
     }
     ?>
     ```

4. **Avvia il server**:
   - Usa un server web locale come XAMPP, WAMP o MAMP per servire i file PHP. Assicurati che il server sia configurato per supportare PHP e MySQL.

## Dettagli Tecnici

### Login Amministratore

- **URL di Login**: `http://localhost/admin-panel/login.php`
- **Credenziali di Default**:
  - **Username**: admin
  - **Password**: admin_password

### Struttura dei File

- **`index.php`**: Pagina di login iniziale.
- **`admin.php`**: Pannello di amministrazione per la gestione dei prodotti.
- **`transactions.php`**: Pagina per visualizzare tutte le transazioni.
- **`db.php`**: File di configurazione del database.
- **`css/admin-styles.css`**: File di stili CSS per il pannello di amministrazione.
- **`css/styles.css`**: File di stili CSS per la pagina principale.
- **`logout.php`**: Script per gestire il logout dell'amministratore.

### Funzionamento del Codice

- **Form di Aggiunta Prodotto**: Consente l'inserimento di nuovi prodotti nel database.
- **Form di Modifica Prodotto**: I campi di modifica sono disabilitati di default e diventano abilitati quando si clicca su "Modifica". Dopo aver salvato le modifiche, i campi tornano ad essere disabilitati.
- **Filtraggio per Nome**: Un campo di input permette di filtrare i prodotti per nome in tempo reale.
- **Visualizzazione Dettagli**: Ogni prodotto ha un pulsante per mostrare/nascondere i dettagli delle transazioni.

## Note Finali

Questo pannello di amministrazione è stato creato per semplificare la gestione dei prodotti e delle relative transazioni. Se hai domande o bisogno di assistenza, non esitare a contattarci. Buon lavoro con la gestione dei tuoi prodotti!

---

Buona amministrazione!

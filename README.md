Descrizione v3
# Documentazione di funzionamento

La pagina principale fornisce la possibilità di accedere come utente amministratore, oppure come utente standard
l'utente admministratore avrà accesso ad una serie di funzionalità di seguito descritte più avanti, come anche all'interfaccia di cassa. Su tutte le pagine sarà presente un menù in rosso, che permette di accedere velocemente a tutte le sezioni di gestione dell'app: Cassa, Gestione prodotti, Storico Transazioni, Gestione Clienti.
L'utente standard, avrà accesso ad una sola pagina, che permette di visonare il saldo disponibile, le statistiche sui prodotti acquistati, e l'elenco delle transazioni effettutate.

## Pagina Cassa
Dopo avere effettauto l'accesso come amministratore, viene aperta la pagina principale, che riferisce alla gestione di cassa.
Per praticità è possibile cercare i prodotti per nome, per poi selezionarli dal tasto aggiungi. In questo modo verranno aggiunti al carrello.
I prodotti vengono divisi automaticamente in colori, in base alle due categorie, "gelati" e "bibite".
La selezione dei prodotti, genera:
- somma del saldo del carrello visualizzato in alto
- quantitativo dei prodotti e somma del costo del prodotto in base alla quantità
- somma dei prodotti automatica in base alla quantità esistente o dal tasto "+" o selezionando il relativo prodotto dall'elenco prodotti
Dopo avere aggiunto i prodotti al carrello, è possibile effettuare il checkout.

  ### Pagina Checkout
  La pagina di checkout permette di verificare il riepilogo degli acquisti e confermare il totale, con l'obiettivo principale di associare la spesa ad un cliente.
  Vi è quindi la possibilitù di ricercare i clienti per nome o per numero di stanza.
  Selezionando il cliente, viene scaricato il saldo in base al totale carrello.
  La selezione del cliente genera un messaggio di conferma e notifica se il saldo è negativo (il saldo esistente è visionabile già nei dettagli delle informazioni cliente, prima di selezionarlo).

## Pagina Prodotti
### Modifica Prodotto
Vengono elencati tutti i prodotti (come per la cassa), ma con la possibilità di modificare Nome, Prezzo e categoria.
La categoria prevista per il prodotto, è sempre o "bibite" o "gelati", in quanto in base a queste viene definita la diversificazione dei colori nella Cassa.
### Aggiungi Nuovo Prodotto
Permette di aggiungere un singolo prodotto all'elenco. Nel disclaimer dei proddotti, viene specificata la stessa logia indicata in merito alle categorie.
### Aggiungi Prodotti da CSV
Tramite questo box è possibile aggiungere in bulk (massivamente) più prodotti. Per fare ciò è sufficiente copiare una tabella di excel che contenga tre colonne, in riferimento a nome, prezzo e categoria del prodotto. Dopo avere incollato il testo nella casella, è sufficiente cliccare sul relativo pulsante di conferma per concludere l'operazione, che genererà quindi l'aggiunta dei prodotti. 

Note importanti per l'inserimento dei dati da CSV:
  Non Usare spazi prima o dopo la virgola.

## Pagina Transazioni
Viene fornito un elenco completo di tutte le transazioni eseguite sulla piattaforma, con riferimento al saldo scaricato, la data di esecuzione ed il nome del cliente.
La pagine fornisce:
- Un campo di ricerca dove è possibile filtrare le transazioni per nome cliente.
- Ordinare la tabella per nome, prezzo e data.
- Aprire il dettaglio del cliente in riferimento alla transazione. Si verrà reindirizzati alla pagina di modifica del cliente.

## Pagina Clienti
Viene fornita la possibilità di 
- visualizzare i clienti in base alla Categoria. La categoria è di fatto il nome del gruppo o conferenza. La cateogira filtrata, rimane selezionata e memorizzata nel browser, fino a quando non viene effettuata una scelta diversa, pulita la cache o cambiato browser.
- Ricercare i clienti per nome o numero di stanza.
- Accedere per ogni cliente, attraverso un pulsante dedicato, alla sezione di modifica, aggiornamento e dettagli dei dati (Nome, Stanza, Categoria, Saldo, transazioni).
- Aggiungere nuovo cliente, nuova categoria e creare gli accessi privati per il cliente.

### Aggiungere Cliente
Permette di aggiungere un singolo cliente, inserendo nome, stanza e categoria, scegliendo da quelle esistenti.
### Aggiugnere Categoria
Permette di creare nuove categorie e di inserirle nell'elenco di categorie esistenti. 
### Crea Accessi Cliente
Tramite il pulsante dedicato viene fatto un controllo dei clienti e generato un accesso dedicato per cliente alla dashboard personale. Viene creata una password di default che sarà composta da nome utente in base al valore esistente (p.e. "Marco Rossi Junior", diventerà "marcorossijuonir") ed una passoword che sarà composta dal nome utente + 123 (p.e. "marcorossijunior123").
Al primo accesso, l'utente sarà indirizzato alla procedura di cambio password.
### Importa o aggiorna Clienti da CSV
Tramite questo box è possibile aggiungere in bulk (massivamente) più clienti. Per fare ciò è sufficiente copiare una tabella di excel che contenga tre colonne, in riferimento a nome, stanza e categoria del cliente. Dopo avere incollato il testo nella casella, è sufficiente cliccare sul relativo pulsante di conferma per concludere l'operazione, che genererà quindi l'aggiunta dei clienti. 

Note importanti per l'inserimento dei dati da CSV:
Non Usare spazi prima o dopo la virgola. Diversamente potranno generarsi duplicati.
Viene fatta la verifica di utenti esistenti in base al connubio di nome e stanza:
    - Se il numero di stanza cambia, viene creato un nuovo cliente.
    - Se la categoria cambia, il cliente rimane lo stesso, e viene aggiunta la categoria a quella precedente. Il cliente avrà quindi due o più categorie assegnate.

### Gestione e modifica Cliente
La pagina di modifica del cliente permette di: aggiornare nome, stanza, categorie assegnate ed aggiungere un importo al saldo esistente.
L'elenco delle transazioni, eseguite per prodotto, ed associate a questo cliente, verrano visualizzate in ordine cronologico.
Ogni aggiunta di importo al saldo del cliente, verrà tracciato nell'elenco delle transazioni, disponibile sia per l'amministratore, che nella dashboard del cliente.
Al salvataggio delle modifiche, si verrà reindirizzati alla pagina dell'elenco dei clienti.

## Note:
"Funzionalità Self-Service non fornite in questa versione (solo tramite mofifica diretta al database):"
1. Non è possibile eliminare un prodotto qualora non sia utile o ridondante. Nel caso il prodotto non sia utile, può essere rinominato, o modificare la categoria con una nomenclatura non compresa (bibite, gelati), ottenendo così il colore Grigio, nella visualizzazione di Cassa.
2. Non è possibile modificare o eliminare cateogorie dei clienti.

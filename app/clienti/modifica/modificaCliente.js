document.addEventListener('DOMContentLoaded', function () {
    const urlParams = new URLSearchParams(window.location.search);
    const clienteId = urlParams.get('id');
    const modificaForm = document.getElementById('modifica-form');
    const categoriaSelect = document.getElementById('categoria');
    const transazioniTable = document.getElementById('transazioni-table').getElementsByTagName('tbody')[0];
    const menuToggle = document.getElementById('menu-toggle');
    const menu = document.getElementById('menu');

    // Gestione del menu burger
    menuToggle.addEventListener('click', function () {
        menu.classList.toggle('show');
    });

    // Imposta l'ID del cliente nel campo nascosto del modulo
    document.getElementById('cliente-id').value = clienteId;

    // Carica i dettagli del cliente e le transazioni
    fetch(`getTransazioniCliente.php?id=${clienteId}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const cliente = data.cliente;
                document.getElementById('cliente-id').value = cliente.id;
                document.getElementById('nome').value = cliente.nome;
                document.getElementById('numero-stanza').value = cliente.numero_stanza;

                // Imposta il saldo corrente nel campo non modificabile
                const saldoCorrente = parseFloat(cliente.saldo) || 0.00;
                document.getElementById('saldo-corrente').value = saldoCorrente.toFixed(2);

                // Carica le categorie
                fetchCategorie(cliente.categorie.split(','));

                // Carica le transazioni
                loadTransazioni(data.transazioni);
            } else {
                alert(data.message);
            }
        });

    // Gestisci l'invio del modulo di modifica cliente
    modificaForm.addEventListener('submit', function (e) {
        e.preventDefault();
        const formData = new FormData(modificaForm);

        // Aggiungi il saldo incrementale al formData
        const saldoIncremento = parseFloat(document.getElementById('saldo-incremento').value) || 0.00;
        formData.append('saldo_incremento', saldoIncremento);

        fetch('modificaCliente.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Cliente aggiornato con successo!');
                window.location.href = '../gestione_clienti.php';  // Reindirizza alla pagina clienti
            } else {
                alert('Errore durante l\'aggiornamento del cliente: ' + data.message);
            }
        });
    });

    // Carica le categorie
    function fetchCategorie(selectedCategories) {
        fetch('../getCategorie.php')
            .then(response => response.json())
            .then(data => {
                categoriaSelect.innerHTML = '';
                data.categorie.forEach(categoria => {
                    const option = document.createElement('option');
                    option.value = categoria.id;
                    option.textContent = categoria.nome;
                    if (selectedCategories.includes(categoria.id.toString())) {
                        option.selected = true;
                    }
                    categoriaSelect.appendChild(option);
                });
            });
    }

function loadTransazioni(transazioni) {
    const transazioniTable = document.getElementById('transazioni-table').getElementsByTagName('tbody')[0];
    transazioniTable.innerHTML = '';  // Pulisce la tabella esistente

    transazioni.forEach(transazione => {
        const row = transazioniTable.insertRow();
        const cellData = row.insertCell(0);
        const cellDescrizione = row.insertCell(1); // Nuova cella per la descrizione
        const cellProdotto = row.insertCell(2);
        const cellQuantità = row.insertCell(3);
        const cellPrezzo = row.insertCell(4);
        const cellTotale = row.insertCell(5);

        // Assicurati che i campi esistano e abbiano valore
        cellData.innerText = transazione.data_ora || 'N/A';
        cellDescrizione.innerText = transazione.descrizione || 'N/A'; // Inserisci la descrizione
        cellProdotto.innerText = transazione.prodotto_nome || 'N/A';
        cellQuantità.innerText = transazione.quantita || 'N/A';

        // Verifica che 'prezzo' sia un numero
        const prezzo = parseFloat(transazione.prezzo);
        cellPrezzo.innerText = !isNaN(prezzo) ? `€ ${prezzo.toFixed(2)}` : 'N/A';

        // Verifica che 'totale' sia un numero
        const totale = parseFloat(transazione.totale);
        cellTotale.innerText = !isNaN(totale) ? `€ ${totale.toFixed(2)}` : 'N/A';
    });
}

});

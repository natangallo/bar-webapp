document.addEventListener('DOMContentLoaded', function () {
    const prodottiLista = document.getElementById('prodotti-lista');
    const aggiungiProdottoForm = document.getElementById('aggiungi-prodotto');
    const csvInput = document.getElementById('csv-input');
    const aggiungiCsvButton = document.getElementById('aggiungi-csv');
    const tornaIndexButton = document.getElementById('torna-index');
    const menuToggle = document.getElementById('menu-toggle');
    const menu = document.getElementById('menu');

    // Gestione del menu burger
    menuToggle.addEventListener('click', function () {
        menu.classList.toggle('show');
    });

    // Carica i prodotti esistenti
    function caricaProdotti() {
        fetch('prodotti.php')
            .then(response => response.json())
            .then(prodotti => {
                prodottiLista.innerHTML = '';
                prodotti.forEach(prodotto => {
                    const div = document.createElement('div');
                    div.className = 'product-item';
                    div.innerHTML = `
                        <span>${prodotto.nome} - €${prodotto.prezzo}</span>
                        <button data-id="${prodotto.id}" class="modifica">Modifica</button>
                    `;
                    prodottiLista.appendChild(div);
                });
                aggiPulsanteModifica();
            });
    }


// Funzione per aggiungere pulsanti di modifica
function aggiPulsanteModifica() {
    const bottoniModifica = document.querySelectorAll('.modifica');
    bottoniModifica.forEach(button => {
        button.addEventListener('click', function () {
            const prodottoId = button.getAttribute('data-id');
            mostraFormModifica(prodottoId);
        });
    });
}

// Mostra il form di modifica
function mostraFormModifica(id) {
	    console.log(`Fetching details for product ID: ${id}`); // Aggiungi un log per verificare l'ID
    // Effettua la richiesta al server per ottenere i dettagli del prodotto
    fetch(`modifica_prodotto_elenca.php?id=${id}`)
        .then(response => response.json())
        .then(data => {
            // Assicurati che i dati siano stati ricevuti
            if (data.success) {
                const prodotto = data.product;

                // Popola il form con i dettagli del prodotto
                document.getElementById('modifica-nome').value = prodotto.nome;
                document.getElementById('modifica-prezzo').value = prodotto.prezzo;
                document.getElementById('modifica-categoria').value = prodotto.categoria;
                document.getElementById('modifica-id').value = prodotto.id;

                // Mostra il form di modifica
                document.getElementById('modifica-form').style.display = 'block';
            } else {
                alert('Impossibile ottenere i dettagli del prodotto.');
            }
        })
        .catch(error => {
            console.error('Errore durante il recupero dei dettagli del prodotto:', error);
            alert('Errore durante il recupero dei dettagli del prodotto.');
        });
}

// Aggiungi l'event listener al bottone di salvataggio del form di modifica
document.getElementById('salva-modifica').addEventListener('click', function() {
    const id = document.getElementById('modifica-id').value;
    const nome = document.getElementById('modifica-nome').value;
    const prezzo = document.getElementById('modifica-prezzo').value;
    const categoria = document.getElementById('modifica-categoria').value;

    // Esegui una richiesta POST per aggiornare il prodotto
    fetch('modifica_prodotto_aggiorna.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ id, nome, prezzo, categoria })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Prodotto aggiornato con successo!');
            // Aggiorna la pagina o il prodotto specifico nella lista
            location.reload(); // Ricarica la pagina per aggiornare i dati
        } else {
            alert('Errore durante l\'aggiornamento del prodotto.');
        }
    })
    .catch(error => {
        console.error('Errore durante l\'aggiornamento del prodotto:', error);
        alert('Errore HAHAHA durante l\'aggiornamento del prodotto.');
    });
});



// Aggiungi prodotto tramite form
aggiungiProdottoForm.addEventListener('submit', function (e) {
    e.preventDefault();
    const nome = document.getElementById('nome-prodotto').value;
    const prezzo = document.getElementById('prezzo-prodotto').value;
    const categoria = document.getElementById('categoria-prodotto').value;

    fetch('aggiungi_prodotti.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ nome, prezzo, categoria })
    })
    .then(response => response.json())
    .then(result => {
        if (result.success) {
            alert('Prodotto aggiunto!');
            caricaProdotti();
        } else {
            alert('Errore durante l\'aggiunta del prodotto.');
        }
    });
});

// Gestione dell'aggiunta massiva tramite CSV
aggiungiCsvButton.addEventListener('click', function () {
    const csvData = csvInput.value.trim();
    if (csvData) {
        fetch('aggiungi_prodotti.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ csv: csvData })
        })
        .then(response => response.json())
        .then(result => {
            if (result.success) {
                alert('Prodotti aggiunti da CSV!');
                caricaProdotti();
            } else {
                alert('Errore durante l\'aggiunta dei prodotti: ' + result.message);
            }
        })
        .catch(error => {
            console.error('Errore durante l\'aggiunta dei prodotti:', error);
            alert('Errore durante l\'aggiunta dei prodotti.');
        });
    } else {
        alert('Nessun dato CSV fornito.');
    }
});



    // Torna alla pagina index
    tornaIndexButton.addEventListener('click', function () {
        window.location.href = '../index.php';
    });

caricaProdotti();
});

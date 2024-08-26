// transazioni.js

document.addEventListener('DOMContentLoaded', function () {
    const searchInput = document.getElementById('search-transazioni');
    const table = document.getElementById('transazioni-table').getElementsByTagName('tbody')[0];
    const menuToggle = document.getElementById('menu-toggle');
    const menu = document.getElementById('menu');

    // Gestione del menu burger
    menuToggle.addEventListener('click', function () {
        menu.classList.toggle('show');
    });


    // Carica le transazioni al caricamento della pagina
    fetchTransazioni();

    // Filtra le transazioni in tempo reale
    searchInput.addEventListener('keyup', function () {
        const filter = searchInput.value.toLowerCase();
        const rows = table.getElementsByTagName('tr');
        
        for (let i = 0; i < rows.length; i++) {
            const cells = rows[i].getElementsByTagName('td');
            const nome = cells[0].innerText.toLowerCase();
            
            if (nome.includes(filter)) {
                rows[i].style.display = "";
            } else {
                rows[i].style.display = "none";
            }
        }
    });

    // Funzione per ordinare la tabella
function sortTable(column, order) {
    const rowsArray = Array.from(table.getElementsByTagName('tr'));
    const compare = (a, b) => {
        const cellA = a.querySelector(`td[data-column="${column}"]`);
        const cellB = b.querySelector(`td[data-column="${column}"]`);

        if (!cellA || !cellB) {
            return 0; // Evita il confronto se la colonna non esiste (es. Modifica Cliente)
        }

        const valueA = cellA.innerText;
        const valueB = cellB.innerText;

        if (order === 'asc') {
            return valueA.localeCompare(valueB);
        } else {
            return valueB.localeCompare(valueA);
        }
    };

    rowsArray.sort(compare).forEach(row => table.appendChild(row));
}

// Aggiungi l'evento di ordinamento solo per le colonne "nome", "totale", e "data"
document.querySelectorAll('#transazioni-table th[data-column]').forEach(th => {
    th.addEventListener('click', function () {
        const column = th.getAttribute('data-column');
        const order = th.getAttribute('data-order');
        const newOrder = order === 'desc' ? 'asc' : 'desc';
        th.setAttribute('data-order', newOrder);

        sortTable(column, newOrder);
    });
});


    // Funzione per caricare le transazioni dal server
    function fetchTransazioni() {
        fetch('transazioni.php')
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    populateTable(data.transazioni);
                } else {
                    alert('Errore durante il caricamento delle transazioni.');
                }
            });
    }


    // Funzione per popolare la tabella con le transazioni
    function populateTable(transazioni) {
    transazioni.forEach(transazione => {
        const row = table.insertRow();
        row.insertCell().innerText = transazione.nome;
        row.insertCell().innerText = `€${transazione.totale}`;
        row.insertCell().innerText = transazione.data;

        // Aggiungi pulsante "Modifica Cliente"
        const modificaCell = row.insertCell();
        const modificaButton = document.createElement('button');
        modificaButton.innerText = "Apri";
        modificaButton.addEventListener('click', function() {
            window.location.href = `../clienti/modifica/gestione_modificaCliente.php?id=${transazione.cliente_id}`;
        });
        modificaCell.appendChild(modificaButton);

        // Aggiungi attributi per l'ordinamento solo per nome, totale e data
        row.querySelectorAll('td').forEach((td, index) => {
            if (index < 3) { // Solo le prime tre colonne sono ordinabili
                td.setAttribute('data-column', index === 0 ? 'nome' : index === 1 ? 'totale' : 'data');
            }
        });
    });
}
});


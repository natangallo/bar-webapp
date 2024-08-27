document.addEventListener('DOMContentLoaded', function () {
    const searchInput = document.getElementById('search-clienti');
    const categoriaFilter = document.getElementById('categoria-filter');
    const clientiTable = document.getElementById('clienti-table').getElementsByTagName('tbody')[0];
    const clienteForm = document.getElementById('cliente-form');
    const categoriaForm = document.getElementById('categoria-form');
    const csvInput = document.getElementById('csv-input');
    const menuToggle = document.getElementById('menu-toggle');
    const menu = document.getElementById('menu');

    // Gestione del menu burger
    menuToggle.addEventListener('click', function () {
        menu.classList.toggle('show');
    });
    
    // Carica l'ultima categoria selezionata dal localStorage
    const savedCategoriaId = localStorage.getItem('selectedCategoria');
    if (savedCategoriaId) {
        categoriaFilter.value = savedCategoriaId;
    }

    // Carica clienti e categorie
    fetchClienti(categoriaFilter.value);
    fetchCategorie();

    // Filtra i clienti in base alla categoria
    categoriaFilter.addEventListener('change', function () {
        const selectedCategoria = this.value;
        localStorage.setItem('selectedCategoria', selectedCategoria);  // Salva la categoria selezionata
        fetchClienti(selectedCategoria);
    });

    // Cerca clienti in tempo reale
    searchInput.addEventListener('keyup', function () {
        const filter = searchInput.value.toLowerCase();
        const rows = clientiTable.getElementsByTagName('tr');

        for (let i = 0; i < rows.length; i++) {
            const cells = rows[i].getElementsByTagName('td');
            const nome = cells[0].innerText.toLowerCase();
            const numeroStanza = cells[1].innerText.toLowerCase();
            const categoria = cells[2].innerText.toLowerCase();

            if (nome.includes(filter) || numeroStanza.includes(filter) || categoria.includes(filter)) {
                rows[i].style.display = "";
            } else {
                rows[i].style.display = "none";
            }
        }
    });

    // Mostra il modulo per aggiungere un cliente
    document.getElementById('aggiungi-cliente-btn').addEventListener('click', function () {
        document.getElementById('form-sezione').style.display = 'block';
        document.getElementById('form-titolo').innerText = 'Aggiungi Singolo Cliente';
        clienteForm.reset();
    });

    // Mostra il modulo per aggiungere una categoria
    document.getElementById('aggiungi-categoria-btn').addEventListener('click', function () {
        document.getElementById('form-categoria-sezione').style.display = 'block';
        document.getElementById('form-categoria-titolo').innerText = 'Aggiungi Nuova Categoria';
        categoriaForm.reset();
    });

    // Gestisci l'invio del modulo cliente
    clienteForm.addEventListener('submit', function (e) {
        e.preventDefault();
        const formData = new FormData(clienteForm);

        // Ottieni le categorie selezionate e convertili in array di nomi
        const categoriaSelect = document.getElementById('categoria');
        const selectedCategories = Array.from(categoriaSelect.selectedOptions).map(option => option.textContent);

        formData.append('categoria', selectedCategories);  // Aggiungi le categorie come array di nomi

        fetch('updateClienti.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                fetchClienti();
                document.getElementById('form-sezione').style.display = 'none';
            } else {
                alert('Errore durante l\'aggiunta del cliente.');
            }
        });
    });

    // Gestisci l'invio del modulo categoria
    categoriaForm.addEventListener('submit', function (e) {
        e.preventDefault();
        const formData = new FormData(categoriaForm);

        fetch('aggiungiCategoria.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                fetchCategorie();
                document.getElementById('form-categoria-sezione').style.display = 'none';
            } else {
                alert('Errore durante l\'aggiunta della categoria.');
            }
        });
    });

    // Gestisci l'importazione CSV
    document.getElementById('importa-csv-btn').addEventListener('click', function () {
        const csvData = csvInput.value.trim();

        fetch('updateClienti.php', {
            method: 'POST',
            body: JSON.stringify({ csv: csvData }),
            headers: {
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                fetchClienti();
                csvInput.value = '';
            } else {
                alert('Errore durante l\'importazione del CSV.');
            }
        });
    });

    // Funzione per caricare clienti
    function fetchClienti(categoriaId = '') {
        fetch(`getClienti.php?categoria_id=${categoriaId}`)
            .then(response => response.json())
            .then(data => {
                clientiTable.innerHTML = '';
                data.clienti.forEach(cliente => {
                    const saldo = parseFloat(cliente.saldo) || 0.00; // Assicura che saldo sia un numero
                    const row = clientiTable.insertRow();
                    row.insertCell().innerText = cliente.nome;
                    row.insertCell().innerText = cliente.numero_stanza;
                    row.insertCell().innerText = cliente.categoria_nome;
                    row.insertCell().innerText = `€${saldo.toFixed(2)}`; // Usa il saldo come numero
                    row.insertCell().innerHTML = `<button onclick="modificaCliente(${cliente.id})">Modifica</button>`;
                });
            });
    }

    // Funzione per caricare categorie
    function fetchCategorie() {
        fetch('getCategorie.php')
            .then(response => response.json())
            .then(data => {
                const categoriaSelect = document.getElementById('categoria');
                const categoriaFilter = document.getElementById('categoria-filter');

                categoriaSelect.innerHTML = '';
                categoriaFilter.innerHTML = '<option value="">Tutte le categorie</option>';

                data.categorie.forEach(categoria => {
                    const option = document.createElement('option');
                    option.value = categoria.id;
                    option.textContent = categoria.nome;
                    categoriaSelect.appendChild(option);

                    const filterOption = document.createElement('option');
                    filterOption.value = categoria.id;
                    filterOption.textContent = categoria.nome;
                    categoriaFilter.appendChild(filterOption);
                });

                // Se c'è un ID di categoria salvato, selezioniamo quella categoria nel filtro
                if (savedCategoriaId) {
                    categoriaFilter.value = savedCategoriaId;
                    fetchClienti(savedCategoriaId);
                }
            });
    }

    // Funzione per modificare cliente (apri una nuova pagina associata al profilo del cliente)
    window.modificaCliente = function(id) {
        // Reindirizza alla pagina di modifica con l'ID del cliente come parametro
        window.location.href = `modifica/gestione_modificaCliente.php?id=${id}`;
    };
});

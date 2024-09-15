document.addEventListener('DOMContentLoaded', function () {
    const carrelloRiepilogo = document.getElementById('carrello-riepilogo');
    const clientiLista = document.getElementById('clienti-lista');
    const cercaCliente = document.getElementById('cerca-cliente');
    const confermaButton = document.getElementById('conferma');
    const menuToggle = document.getElementById('menu-toggle');
    const menu = document.getElementById('menu');

    // Gestione del menu burger
    menuToggle.addEventListener('click', function () {
        menu.classList.toggle('show');
    });

    // Carica i dati del carrello da sessionStorage
    const carrello = JSON.parse(sessionStorage.getItem('carrello')) || [];
    const totale = sessionStorage.getItem('totale') || '€0.00';

    // Funzione per creare l'elemento del riepilogo
    function creaElementoRiepilogo(item) {
        const div = document.createElement('div');
        div.className = 'product-item';
        div.innerHTML = `
            <span>${item.nome} x${item.quantita} - €${(item.prezzo * item.quantita).toFixed(2)}</span>
        `;
        return div;
    }

    // Popola il riepilogo del carrello
    carrello.forEach(item => {
        const elemento = creaElementoRiepilogo(item);
        carrelloRiepilogo.appendChild(elemento);
    });

    // Mostra il totale
    carrelloRiepilogo.innerHTML += `<h1>Totale: ${totale}</h3>`;

    // Carica e visualizza la lista dei clienti
    function caricaClienti() {
        fetch('clienti.php')
            .then(response => response.json())
            .then(clienti => {
                clienti.forEach(cliente => {
                    const item = creaElementoCliente(cliente);
                    document.getElementById('clienti-lista').appendChild(item);
                });
            });
    }

    // Funzione per creare l'elemento del cliente con il saldo
    function creaElementoCliente(cliente) {
    const div = document.createElement('div');
    const saldo = parseFloat(cliente.saldo); // Conversione a numero
    div.className = 'cliente-item';
    div.innerHTML = `
        <span>${cliente.nome} (Stanza: ${cliente.numero_stanza}) - Saldo: €${saldo.toFixed(2)}</span>
        <button data-id="${cliente.id}">Seleziona</button>
    `;
    div.querySelector('button').addEventListener('click', function () {
        selezionaCliente(cliente.id);
    });
    return div;
}


    // Funzione per selezionare il cliente e confermare la transazione
    function selezionaCliente(clienteId) {
        fetch('conferma_transazione.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                clienteId: clienteId,
                carrello: carrello,
                totale: totale
            })
        })
        .then(response => response.json())
        .then(result => {
            if (result.success) {
                alert(result.message || 'Transazione completata!');
                window.location.href = '../index.php';
            } else {
                alert('Errore durante la transazione: ' + result.message);
            }
        });
    }

    // Ricerca cliente
    cercaCliente.addEventListener('input', function () {
        const query = cercaCliente.value.toLowerCase();
        const items = clientiLista.querySelectorAll('.cliente-item');
        items.forEach(item => {
            const nome = item.textContent.toLowerCase();
            if (nome.includes(query)) {
                item.style.display = '';
            } else {
                item.style.display = 'none';
            }
        });
    });

    caricaClienti();
});

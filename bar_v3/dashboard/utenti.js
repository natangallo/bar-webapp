// utenti.js
document.addEventListener('DOMContentLoaded', function () {
    const saldoElement = document.getElementById('saldo-valore');
    const ctx = document.getElementById('pie-chart').getContext('2d');
    const tbody = document.querySelector('#transazioni-table tbody');

    function fetchDatiDashboard() {
        fetch('get_dati_dashboard.php?id=<?php echo $clienteId; ?>')
            .then(response => response.json())
            .then(data => {
            	// Inserisci il nome dell'utente nella pagina
            	if (data.nomeUtente) {
                document.querySelector('header h1').textContent = "Benvenuto, " + data.nomeUtente + "!";
            } else {
                document.querySelector('header h1').textContent = 'Utente';
            }

                // Imposta il saldo
                saldoElement.textContent = `€${parseFloat(data.saldo).toFixed(2)}`;

                // Popola il grafico a torta
                const chartData = {
                    labels: [],
                    datasets: [{
                        data: [],
                        backgroundColor: []
                    }]
                };

                data.prodotti.forEach(prodotto => {
                    chartData.labels.push(prodotto.prodotto_nome);
                    chartData.datasets[0].data.push(prodotto.quantita_totale);
                    chartData.datasets[0].backgroundColor.push(getRandomColor());
                });

                new Chart(ctx, {
                    type: 'pie',
                    data: chartData,
                    options: {
                        responsive: true
                    }
                });

                // Popola la tabella delle transazioni
                data.transazioni.forEach(transazione => {
                    const row = document.createElement('tr');
                    row.dataset.id = transazione.transazione_id;
                    row.innerHTML = `
                        <td>${transazione.data}</td>
                        <td>€${parseFloat(transazione.totale).toFixed(2)}</td>
                        <td>${transazione.descrizione}</td>
                        <td><button class="expand-button" data-id="${transazione.transazione_id}">Mostra Dettagli</button></td>
                    `;

                    const detailsRow = document.createElement('tr');
                    detailsRow.classList.add('details-row');
                    detailsRow.id = `details-${transazione.transazione_id}`;
                    detailsRow.style.display = 'none';
                    detailsRow.innerHTML = '<td colspan="4"><div class="details-content"></div></td>';

                    tbody.appendChild(row);
                    tbody.appendChild(detailsRow);
                });

                // Gestione del pulsante di espansione
                document.querySelectorAll('.expand-button').forEach(button => {
                    button.addEventListener('click', function () {
                        const transazioneId = this.getAttribute('data-id');
                        const detailsRow = document.getElementById(`details-${transazioneId}`);
                        const detailsContent = detailsRow.querySelector('.details-content');

                        if (detailsRow.style.display === 'none') {
                            fetch(`get_dettagli_transazione.php?id=${transazioneId}`)
							    .then(response => response.json())
							    .then(data => {
							        if (data.success) {
							            console.log('Dati dettagli:', data.dettagli); // Aggiungi questa riga per il debug
							            detailsContent.innerHTML = '<ul>' +
							                data.dettagli.map(dettaglio => {
							                    console.log('Prezzo:', dettaglio.prezzo); // Aggiungi questa riga per il debug
							                    return `<li>${dettaglio.prodotto_nome} - Quantità: ${dettaglio.quantita} - Prezzo: €${Number(dettaglio.prezzo).toFixed(2)}</li>`;
							                }).join('') +
							                '</ul>';
							            detailsRow.style.display = 'table-row';
							        } else {
							            detailsContent.innerHTML = 'Errore nel recupero dei dettagli.';
							        }
							    });

                        } else {
                            detailsRow.style.display = 'none';
                        }
                    });
                });
            });
    }

    // Funzione per ottenere un colore casuale
    function getRandomColor() {
        return 'rgba(' + Math.floor(Math.random() * 255) + ',' +
                        Math.floor(Math.random() * 255) + ',' +
                        Math.floor(Math.random() * 255) + ',0.6)';
    }

    // Carica i dati della dashboard
    fetchDatiDashboard();
});

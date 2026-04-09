// --- Récupération des stats depuis l'API ---
function loadSystemStats() {
    fetch('api/stats.php')
        .then(response => response.json())
        .then(data => {
            updateChart(data.cpu, data.ram, data.disk);
        })
        .catch(error => {
            console.error("Erreur lors du chargement des statistiques :", error);
        });
}

// --- Fonction pour mettre à jour le graphique Chart.js ---
let systemChart;

function updateChart(cpu, ram, disk) {
    const ctx = document.getElementById("systemChart").getContext("2d");

    if (systemChart) {
        // Met à jour les données existantes
        systemChart.data.datasets[0].data = [cpu, ram, disk];
        systemChart.update();
    } else {
        // Crée un nouveau graphique
        systemChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['CPU (%)', 'RAM (%)', 'Disque (%)'],
                datasets: [{
                    label: 'Utilisation système',
                    data: [cpu, ram, disk],
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.7)',
                        'rgba(54, 162, 235, 0.7)',
                        'rgba(75, 192, 192, 0.7)'
                    ]
                }]
            },
            options: {
                scales: {
                    y: { beginAtZero: true, max: 100 }
                }
            }
        });
    }
}

// --- Lancement du chargement des stats ---
document.addEventListener("DOMContentLoaded", () => {
    loadSystemStats();

    // Met à jour toutes les 10 secondes
    setInterval(loadSystemStats, 10000);
});

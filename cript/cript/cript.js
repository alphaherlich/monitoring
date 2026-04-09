// script/script.js

document.addEventListener("DOMContentLoaded", () => {
    // Exemple : affichage d'un message de bienvenue
    console.log("Monitoring chargé.");

    // Ajoute ici tes filtres de date, interactions chart.js, etc.

    // Exemple futur : filtrage dynamique par date
    const filterBtn = document.getElementById("filterBtn");
    if (filterBtn) {
        filterBtn.addEventListener("click", () => {
            alert("Filtrage des logs à venir...");
        });
    }
});

// Ziele.js

// Gesamtzielbetrag
const totalGoal = 10000;

// Aktuell eingenommener Betrag (diesen Wert kannst du anpassen)
let currentAmount = 1450;

// Funktion zum Aktualisieren des Fortschrittsbalkens
function updateProgress() {
    const progressBar = document.getElementById('progress-bar');
    const progressText = document.getElementById('progress-text');
    const currentAmountElement = document.getElementById('current-amount');
    const remainingAmountElement = document.getElementById('remaining-amount');
    
    // Fortschrittsprozentsatz berechnen
    const progressPercent = Math.min((currentAmount / totalGoal) * 100, 100);
    
    // Fortschrittsbalken aktualisieren
    progressBar.style.width = progressPercent + '%';
    progressText.textContent = Math.floor(progressPercent) + '%';
    
    // Beträge aktualisieren
    currentAmountElement.textContent = 'CHF ' + currentAmount.toLocaleString('de-CH');
    remainingAmountElement.textContent = 'CHF ' + (totalGoal - currentAmount).toLocaleString('de-CH');
}

// Funktion beim Laden der Seite ausführen
window.onload = updateProgress;

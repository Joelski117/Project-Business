<?php
// fetch_reviews.php

// Datenbankverbindung konfigurieren
$servername = "reviews"; // Ändere dies, falls dein Server anders ist
$username = "root"; // Ersetze durch deinen DB-Benutzernamen
$password = "Maddox2007"; // Ersetze durch dein DB-Passwort
$dbname = "haus_und_gartenservice"; // Dein Datenbankname

// Verbindung herstellen
$conn = new mysqli($servername, $username, $password, $dbname);

// Verbindung prüfen
if ($conn->connect_error) {
    die("Verbindung fehlgeschlagen: " . $conn->connect_error);
}

// SQL-Befehl zum Abrufen der Bewertungen
$sql = "SELECT name, rating, comment, submitted_at FROM reviews ORDER BY submitted_at DESC";
$result = $conn->query($sql);

// Bewertungen anzeigen
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        echo "<div class='review'>";
        echo "<h4>" . htmlspecialchars($row['name']) . "</h4>";
        echo "<div class='rating'>";
        for ($i = 0; $i < $row['rating']; $i++) {
            echo "&#9733;"; // Sternsymbol
        }
        for ($i = $row['rating']; $i < 5; $i++) {
            echo "&#9734;"; // Leeres Sternsymbol
        }
        echo "</div>";
        echo "<p>" . nl2br(htmlspecialchars($row['comment'])) . "</p>";
        echo "<span class='date'>" . date("d.m.Y H:i", strtotime($row['submitted_at'])) . "</span>";
        echo "</div>";
    }
} else {
    echo "<p>Es gibt noch keine Bewertungen.</p>";
}

$conn->close();
?>

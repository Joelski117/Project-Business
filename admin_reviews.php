<?php
// admin_reviews.php

// Einfacher Passwortschutz (für Demonstrationszwecke)
$admin_password = "Ialrg2049_Webseite"; // Ersetze dies durch ein starkes Passwort

session_start();

// Passwortüberprüfung
if (!isset($_SESSION['logged_in'])) {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        if ($_POST['password'] === $admin_password) {
            $_SESSION['logged_in'] = true;
            header("Location: admin_reviews.php");
            exit;
        } else {
            $error = "Falsches Passwort.";
        }
    }

    // Login-Formular anzeigen
    echo '<!DOCTYPE html>
    <html lang="de">
    <head>
        <meta charset="UTF-8">
        <title>Admin Login - Haus- und Gartenservice St. Gallen</title>
        <link rel="stylesheet" href="style.css">
    </head>
    <body>
        <main>
            <h2>Admin Login</h2>';

    if (isset($error)) {
        echo "<p style='color:red;'>$error</p>";
    }

    echo '<form action="admin_reviews.php" method="post">
                <label for="password">Passwort:</label>
                <input type="password" id="password" name="password" required>
                <button type="submit">Einloggen</button>
            </form>
        </main>
    </body>
    </html>';
    exit;
}

// Nach erfolgreichem Login: Bewertungen anzeigen
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    // Datenbankverbindung konfigurieren
    $servername = "localhost";
    $username = "dein_datenbank_benutzername"; // Ersetze durch deinen DB-Benutzernamen
    $password = "dein_datenbank_passwort"; // Ersetze durch dein DB-Passwort
    $dbname = "haus_und_gartenservice"; // Dein Datenbankname

    // Verbindung herstellen
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Verbindung prüfen
    if ($conn->connect_error) {
        die("Verbindung fehlgeschlagen: " . $conn->connect_error);
    }

    // Bewertung löschen
    $stmt = $conn->prepare("DELETE FROM reviews WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();

    $conn->close();

    header("Location: admin_reviews.php");
    exit;
}

// Bewertungen anzeigen
// Datenbankverbindung konfigurieren
$servername = "localhost";
$username = "dein_datenbank_benutzername"; // Ersetze durch deinen DB-Benutzernamen
$password = "dein_datenbank_passwort"; // Ersetze durch dein DB-Passwort
$dbname = "haus_und_gartenservice"; // Dein Datenbankname

// Verbindung herstellen
$conn = new mysqli($servername, $username, $password, $dbname);

// Verbindung prüfen
if ($conn->connect_error) {
    die("Verbindung fehlgeschlagen: " . $conn->connect_error);
}

// SQL-Befehl zum Abrufen der Bewertungen
$sql = "SELECT id, name, email, rating, comment, submitted_at FROM reviews ORDER BY submitted_at DESC";
$result = $conn->query($sql);

// Admin-Interface anzeigen
echo '<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Admin - Kundenbewertungen</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <div class="logo">
            <img src="images/logo.png" alt="Logo">
            <h1>Haus- und Gartenservice St. Gallen - Admin</h1>
        </div>
    </header>
    <main>
        <h2>Kundenbewertungen verwalten</h2>';

if ($result->num_rows > 0) {
    echo "<table>";
    echo "<tr><th>ID</th><th>Name</th><th>E-Mail</th><th>Bewertung</th><th>Kommentar</th><th>Datum</th><th>Aktionen</th></tr>";
    while($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($row['id']) . "</td>";
        echo "<td>" . htmlspecialchars($row['name']) . "</td>";
        echo "<td>" . htmlspecialchars($row['email']) . "</td>";
        echo "<td>";
        for ($i = 0; $i < $row['rating']; $i++) {
            echo "&#9733;"; // Sternsymbol
        }
        for ($i = $row['rating']; $i < 5; $i++) {
            echo "&#9734;"; // Leeres Sternsymbol
        }
        echo "</td>";
        echo "<td>" . nl2br(htmlspecialchars($row['comment'])) . "</td>";
        echo "<td>" . date("d.m.Y H:i", strtotime($row['submitted_at'])) . "</td>";
        echo "<td><a href='admin_reviews.php?delete=" . htmlspecialchars($row['id']) . "' onclick=\"return confirm('Sind Sie sicher, dass Sie diese Bewertung löschen möchten?');\">Löschen</a></td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "<p>Es gibt noch keine Bewertungen.</p>";
}

echo '<p><a href="admin_logout.php">Logout</a></p>
    </main>
</body>
</html>';

$conn->close();
?>

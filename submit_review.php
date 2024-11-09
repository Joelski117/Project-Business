<?php
// Enable error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Restlicher Code...


// Restlicher Code...

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

// Daten aus dem Formular holen und validieren
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Honeypot-Feld überprüfen (falls verwendet)
    if (!empty($_POST['website'])) {
        // Verdächtige Bewertung (Bot)
        echo "Spam erkannt.";
        exit;
    }

    // reCAPTCHA verifizieren
if (!isset($_POST['g-recaptcha-response'])) {
    echo "Bitte bestätigen Sie das reCAPTCHA.";
    exit;
}

$recaptcha_response = $_POST['g-recaptcha-response'];
$recaptcha_secret = "6LcGyk0qAAAAAI-jRLqsnAtly1q5z-YnKaphmZTU"; // Ersetze mit deinem Secret Key

// Anfrage an Google reCAPTCHA API senden
$verify_url = "https://www.google.com/recaptcha/api/siteverify";
$response = file_get_contents($verify_url . "?secret=" . $recaptcha_secret . "&response=" . $recaptcha_response);
$response_keys = json_decode($response, true);

if (!$response_keys["success"]) {
    echo "reCAPTCHA-Verifizierung fehlgeschlagen. Bitte versuchen Sie es erneut.";
    exit;
}


    // reCAPTCHA verifizieren (optional, wenn integriert)
    // Hier kannst du den reCAPTCHA-Code einfügen, wenn du ihn implementiert hast

    // Formularfelder sichern
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $rating = intval($_POST['rating']);
    $comment = trim($_POST['comment']);

    // Einfache Validierung
    if (empty($name) || empty($email) || empty($rating) || empty($comment)) {
        echo "Bitte füllen Sie alle Felder aus.";
        exit;
    }

    if ($rating < 1 || $rating > 5) {
        echo "Bewertung muss zwischen 1 und 5 liegen.";
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Bitte geben Sie eine gültige E-Mail-Adresse ein.";
        exit;
    }

    // Prepared Statement verwenden, um SQL-Injektionen zu verhindern
    $stmt = $conn->prepare("INSERT INTO reviews (name, email, rating, comment) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssis", $name, $email, $rating, $comment);

    if ($stmt->execute()) {
        echo "Vielen Dank für Ihre Bewertung!";
    } else {
        echo "Fehler: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>

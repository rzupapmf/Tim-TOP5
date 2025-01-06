<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Poveznica sa bazom, provjeriti svatko za sebe jesu li ispravni podaci pošto lokalno radimo hosting
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'Projekt';

// Upit za povezivanje na bazu
$conn = new mysqli($host, $username, $password, $database);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Registracija korisnika
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Napravljena zaštita nad lozinkom

    // Provjera je li username postoji
    $sql_check = "SELECT * FROM Korisnici WHERE username = ?";
    $stmt_check = $conn->prepare($sql_check);
    $stmt_check->bind_param('s', $username);
    $stmt_check->execute();
    $result = $stmt_check->get_result();

    if ($result->num_rows > 0) {
        echo "Username already taken.";
    } else {
        // Unos korisnika
        $sql = "INSERT INTO Korisnici (username, password) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('ss', $username, $password);

        if ($stmt->execute()) {
            echo "Registration successful!";
        } else {
            echo "Error: " . $stmt->error;
        }
    }
}
$conn->close();
?>

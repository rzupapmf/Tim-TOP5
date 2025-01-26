<?php
session_start(); // Pokretanje sesije

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Poveznica sa bazom
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'projekt';

// Povezivanje na bazu
$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Upit za log in
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Provjera podataka
    $sql = "SELECT * FROM korisnici WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['password'])) {
            // Login uspješan
            $_SESSION['user_id'] = $row['id']; // korisnički ID

            // Redirekcija na naslovnicu
            header("Location: Naslovnica.html");
            exit();
        } else {
            echo "Invalid password.";
        }
    } else {
        echo "No user found with this username.";
    }
}

$conn->close();
?>

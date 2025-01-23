<?php
// Povezivanje na bazu
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'Projekt';

$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die("Povezivanje nije uspjelo: " . $conn->connect_error);
}

// Dohvat korisnika (hardkodirani za sada)
$loggedInUser = "Ime Prezime";

// Dohvat kategorija (generičke i korisničke)
$sql = "SELECT * FROM Kategorije ORDER BY genericka DESC, id ASC LIMIT 6";
$result = $conn->query($sql);

$categories = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $categories[] = $row;
    }
}

$conn->close();
?>


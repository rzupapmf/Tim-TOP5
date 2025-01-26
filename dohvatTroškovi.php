<?php
session_start();

// Provjera da li je korisnik prijavljen
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(["error" => "Unauthorized"]);
    exit();
}

$categoryId = isset($_GET['categoryId']) ? $_GET['categoryId'] : null;

if ($categoryId === null) {
    echo json_encode(["error" => "Category ID is required"]);
    exit();
}

// Povezivanje na bazu podataka
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'projekt';

$conn = new mysqli($host, $username, $password, $database);
if ($conn->connect_error) {
    die(json_encode(["error" => "Database connection failed: " . $conn->connect_error]));
}

// Dohvaćanje troškova za određenu kategoriju
$sql = "SELECT kolicina, opis, date FROM troškovi WHERE kategorije_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $categoryId);
$stmt->execute();
$result = $stmt->get_result();

$expenses = [];
while ($row = $result->fetch_assoc()) {
    $expenses[] = $row;
}

echo json_encode($expenses);

$stmt->close();
$conn->close();
?>

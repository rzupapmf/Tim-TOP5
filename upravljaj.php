<?php
session_start();

// Provjera da li je korisnički ID postavljen u sesiji
if (!isset($_SESSION['user_id'])) {
    http_response_code(401); // HTTP status kod za neautorizirane korisnike
    echo json_encode([
        "error" => "Unauthorized - No session ID found", // Povratna poruka u slučaju da sesija nije postavljena
        "debug" => [
            "session" => $_SESSION, // Ispis trenutne sesije za debuggiranje
            "headers" => getallheaders() // Ispis HTTP zaglavlja za debuggiranje
        ]
    ]);
    exit;
}

// Dohvaćanje korisničkog ID-a iz sesije
$user_id = $_SESSION['user_id'];

// Povezivanje na bazu podataka
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'projekt';

$conn = new mysqli($host, $username, $password, $database);
if ($conn->connect_error) {
    die(json_encode(["error" => "Database connection failed: " . $conn->connect_error])); // Provjera veze s bazom
}

// SQL upit za dohvaćanje kategorija korisnika
$sql = "SELECT id, ime, budget FROM kategorije WHERE user_id = ? LIMIT 6";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

// Provjera jesu li dohvaćene kategorije
$categories = [];
if ($result->num_rows > 0) {
    // Ako ima rezultata, dodajemo kategorije u array
    while ($row = $result->fetch_assoc()) {
        $categories[] = [
            'id' => $row['id'],
            'ime' => $row['ime'],
            'budget' => (float)$row['budget'] // Pretvaramo budget u float
        ];
    }
} else {
    // Ako nema kategorija, vraćamo praznu listu
    $categories = [];
}

// Slanje rezultata u JSON formatu
header('Content-Type: application/json');
echo json_encode($categories);

// Zatvaranje prepared statementa i konekcije na bazu
$stmt->close();
$conn->close();
?>

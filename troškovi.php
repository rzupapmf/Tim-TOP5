<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(["error" => "Unauthorized"]);
    exit();
}

$data = json_decode(file_get_contents("php://input"));

$categoryId = $data->kategorije_id;  // Promijenjeno
$spending = $data->kolicina;         // Promijenjeno
$description = isset($data->opis) ? trim($data->opis) : null;  // Promijenjeno

if (empty($categoryId) || !is_numeric($spending) || $spending <= 0 || empty($description)) {
    http_response_code(400);
    echo json_encode(["error" => "Invalid input data"]);
    exit();
}

$host = 'localhost';
$username = 'root';
$password = '';
$database = 'projekt';

$conn = new mysqli($host, $username, $password, $database);
if ($conn->connect_error) {
    die(json_encode(["error" => "Database connection failed: " . $conn->connect_error]));
}

$sql = "INSERT INTO troškovi (kategorije_id, kolicina, opis, date) VALUES (?, ?, ?, CURDATE())";
$stmt = $conn->prepare($sql);
$stmt->bind_param('ids', $categoryId, $spending, $description);

if ($stmt->execute()) {
    echo json_encode(["success" => true]);
} else {
    echo json_encode(["error" => "Failed to save spending"]);
}

$stmt->close();
$conn->close();
?>

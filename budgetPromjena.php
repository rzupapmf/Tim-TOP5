<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(["error" => "Unauthorized"]);
    exit();
}

$data = json_decode(file_get_contents("php://input"));

$categoryId = $data->id; // 'id' umjesto 'categoryId'
$budget = $data->budget; // 'budget' umjesto 'newBudget'

$host = 'localhost';
$username = 'root';
$password = '';
$database = 'Projekt';

$conn = new mysqli($host, $username, $password, $database);
if ($conn->connect_error) {
    die(json_encode(["error" => "Database connection failed: " . $conn->connect_error]));
}

$sql = "UPDATE Kategorije SET budget = ? WHERE id = ?";
$stmt = $conn->prepare($sql);

if ($stmt === false) {
    echo json_encode(["error" => "Prepare statement failed: " . $conn->error]);
    exit();
}

$stmt->bind_param('di', $budget, $categoryId);

if ($stmt->execute()) {
    echo json_encode(["success" => true]);
} else {
    echo json_encode(["error" => "Failed to update budget: " . $stmt->error]);
}

$stmt->close();
$conn->close();
?>


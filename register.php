<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Poveznica sa 
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'Projekt';

// Povezivanje na bazu
$conn = new mysqli($host, $username, $password, $database);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Registracija korisnika
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Prikupljanje podataka s forme
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Zaštita lozinke

    // Provjera postoji li korisnik s tim username-om
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

            // Unos generičkih kategorija za novog korisnika
            $user_id = $conn->insert_id;  // ID novog korisnika koji je upravo registriran

            // Definicija generičkih kategorija
            $categories = [
                ['name' => 'Hrana', 'budget' => 00.00],
                ['name' => 'Stanarina', 'budget' => 00.00],
                ['name' => 'Režije', 'budget' => 00.00],
            ];

            // SQL upit za unos kategorija u tablicu kategorija
            $category_sql = "INSERT INTO Kategorije (user_id, name, budget, is_generic) VALUES (?, ?, ?, ?)";
            $stmt_category = $conn->prepare($category_sql);

            // Provjera za greške u pripremi upita
            if ($stmt_category === false) {
                die('Error preparing statement: ' . $conn->error);
            }

            $is_generic = 1; // Ovdje postavljamo generičke kategorije na 1

            // Unos svake kategorije
            foreach ($categories as $category) {
                // Bind param za svaku kategoriju
                $stmt_category->bind_param('ssdi', $user_id, $category['name'], $category['budget'], $is_generic);
                $stmt_category->execute(); // Izvrši unos kategorije u bazu
            }

            echo " and generic categories have been added!";
        } else {
            // Ako dođe do greške u unosu korisnika
            echo "Error: " . $stmt->error;
        }
    }
}
$conn->close();
?>


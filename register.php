<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Pokreni sesiju
session_start();

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

// Registracija korisnika
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Prikupljanje podataka s forme
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Zaštita lozinke

    // Provjera postoji li korisnik s tim username-om
    $sql_check = "SELECT * FROM korisnici WHERE username = ?";
    $stmt_check = $conn->prepare($sql_check);
    $stmt_check->bind_param('s', $username);
    $stmt_check->execute();
    $result = $stmt_check->get_result();

    if ($result->num_rows > 0) {
        echo "Username already taken.";
    } else {
        // Unos korisnika
        $sql = "INSERT INTO korisnici (username, password) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('ss', $username, $password);

        if ($stmt->execute()) {
            echo "Registration successful!";

            // Unos generičkih kategorija za novog korisnika
            $user_id = $conn->insert_id;  // ID novog korisnika koji je upravo registriran
            // Provjera korisničkog ID-a
            echo "User ID: " . $user_id;

            // Provjera postoji li korisnik
            $check_user = "SELECT id FROM korisnici WHERE id = ?";
            $stmt_check_user = $conn->prepare($check_user);
            $stmt_check_user->bind_param('i', $user_id);
            $stmt_check_user->execute();
            $user_check_result = $stmt_check_user->get_result();

            if ($user_check_result->num_rows > 0) {
                // Definicija generičkih kategorija
                $categories = [
                    ['ime' => 'Hrana', 'budget' => 00.00],
                    ['ime' => 'Stanarina', 'budget' => 00.00],
                    ['ime' => 'Režije', 'budget' => 00.00],
                    ['ime' => 'Štednja', 'budget' => 00.00],
                    ['ime' => 'Zabava', 'budget' => 00.00],
                    ['ime' => 'Ostalo', 'budget' => 00.00],
                ];

                // SQL upit za unos kategorija u tablicu kategorije
                $category_sql = "INSERT INTO kategorije (user_id, ime, budget, is_generic) VALUES (?, ?, ?, ?)";
                $stmt_category = $conn->prepare($category_sql);

                // Provjera za greške u pripremi upita
                if ($stmt_category === false) {
                    die('Error preparing statement: ' . $conn->error);
                }
                $is_generic = 1; // Ovdje postavljamo generičke kategorije na 1

                // Unos svake kategorije
                foreach ($categories as $category) {
                    // Bind param za svaku kategoriju
                    $stmt_category->bind_param('ssdi', $user_id, $category['ime'], $category['budget'], $is_generic);
                    $stmt_category->execute(); // Izvrši unos kategorije u bazu
                }

                echo " and generic categories have been added!";

                // POSTAVLJANJE KORISNIČKE SESIJE
                $_SESSION['user_id'] = $user_id; // Pohranjivanje ID-a korisnika u sesiju
                $_SESSION['username'] = $username; // Pohranjivanje username-a u sesiju

                // Preusmjeravanje na test.html nakon uspješne registracije
                header('Location: Naslovnica.html');
                exit; // Prekid izvršavanja kako ne bi bilo dodatnog ispisa
            } else {
                echo "Error: User does not exist.";
            }
        } else {
            echo "Error: " . $stmt->error;
        }
    }
}
$conn->close();
?>

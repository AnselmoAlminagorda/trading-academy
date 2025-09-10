<?php
$host = 'localhost';
$db   = 'trading-academy'; // Nombre de la BD en XAMPP
$user = 'root';
$pass = '';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,          
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
    // echo "Conexión exitosa"; // (opcional para pruebas)
} catch (PDOException $e) {
    echo "Error de conexión con PDO: " . $e->getMessage();
}
?>

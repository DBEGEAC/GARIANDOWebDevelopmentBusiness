<?php
$host = 'localhost';
$db = 'web_development_business';
$user = 'root'; // Default username for XAMPP
$pass = ''; // Default password for XAMPP is empty

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>
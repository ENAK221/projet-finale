<?php
try {
    $conn = new PDO('mysql:host=localhost;dbname=mon_site;charset=utf8mb4', 'root', '');
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $conn->exec("set names utf8mb4"); // Assurer l'utilisation de UTF-8
} catch (PDOException $e) {
    echo 'Erreur de connexion : ' . $e->getMessage();
}
?>


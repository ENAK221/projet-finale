<?php
include 'connect.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT * FROM reservations WHERE user_id = ?");
$stmt->execute([$user_id]);
$reservations = $stmt->fetchAll();

foreach ($reservations as $reservation) {
    echo "Date : " . $reservation['date'] . " | Heure : " . $reservation['time'] . " | Statut : " . $reservation['status'] . "<br>";
}
?>

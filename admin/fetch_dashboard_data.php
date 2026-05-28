<?php
include '../components/connect.php';
session_start();

if (!isset($_SESSION['admin_id'])) {
    echo json_encode(['error' => 'User not authenticated']);
    exit;
}

$response = [];

// Profil de l'administrateur
$admin_id = $_SESSION['admin_id'];
$select_profile = $conn->prepare("SELECT name FROM admins WHERE id = ?");
$select_profile->execute([$admin_id]);
$fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);
$response['admin_name'] = $fetch_profile['name'];

// Commandes en attente
$total_pendings = 0;
$select_pendings = $conn->prepare("SELECT total_price FROM `orders` WHERE payment_status = ?");
$select_pendings->execute(['En Attente']);
while ($fetch_pending = $select_pendings->fetch(PDO::FETCH_ASSOC)) {
    $total_pendings += $fetch_pending['total_price'];
}
$response['total_pendings'] = $total_pendings;

// Commandes livrées
$total_completes = 0;
$select_completes = $conn->prepare("SELECT total_price FROM `orders` WHERE payment_status = ?");
$select_completes->execute(['Livré']);
while ($fetch_completes = $select_completes->fetch(PDO::FETCH_ASSOC)) {
    $total_completes += $fetch_completes['total_price'];
}
$response['total_completes'] = $total_completes;

// Nombre total de commandes
$select_orders = $conn->prepare("SELECT COUNT(*) FROM `orders`");
$select_orders->execute();
$response['number_of_orders'] = $select_orders->fetchColumn();

// Nombre total de produits
$select_products = $conn->prepare("SELECT COUNT(*) FROM `products`");
$select_products->execute();
$response['number_of_products'] = $select_products->fetchColumn();

// Nombre total de comptes utilisateurs
$select_users = $conn->prepare("SELECT COUNT(*) FROM `users`");
$select_users->execute();
$response['number_of_users'] = $select_users->fetchColumn();

// Nombre total d'administrateurs
$select_admins = $conn->prepare("SELECT COUNT(*) FROM `admins`");
$select_admins->execute();
$response['number_of_admins'] = $select_admins->fetchColumn();

// Nombre total de messages
$select_messages = $conn->prepare("SELECT COUNT(*) FROM `messages`");
$select_messages->execute();
$response['number_of_messages'] = $select_messages->fetchColumn();

echo json_encode($response);

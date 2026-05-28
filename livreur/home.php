<?php
include '../components/connect.php';

ini_set('session.gc_maxlifetime', 1440);
session_start();

// Empêche les warnings
$user_id = $_SESSION['livreur_id'] ?? '';

$select_orders = $conn->prepare("SELECT * FROM `orders` WHERE payment_status = 'En cours'");
$select_orders->execute();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Commandes en cours</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css">
    <link rel="stylesheet" href="../css/style.css">
</head>

<body>

<header>
    <img src="../images/logo1.png" alt="Logo">
    <a href="#" id="logout-btn" class="logout-btn">Deconnexion</a>
</header>

<section class="orders">
    <h1>Commandes en cours</h1>

    <div class="box-container">
        <?php
        if ($select_orders->rowCount() > 0) {
            while ($order = $select_orders->fetch(PDO::FETCH_ASSOC)) {
        ?>
        <div class="box">
            <p>Numéro Commande : 
                <span><?= htmlspecialchars($order['order_id'] ?? '') ?></span>
            </p>

            <p>Client : 
                <span><?= htmlspecialchars(($order['name'] ?? '') . ' ' . ($order['surname'] ?? '')) ?></span>
            </p>

            <p>Total : 
                <span><?= htmlspecialchars($order['total_price'] ?? '') ?> Fcfa</span>
            </p>

            <p>Numero Telephone : 
                <span><?= htmlspecialchars($order['phone'] ?? '') ?></span>
            </p>

            <p>Date de livraison : 
                <span><?= htmlspecialchars($order['delivery_date'] ?? '') ?></span>
            </p>

            <a href="order_details.php?order_id=<?= htmlspecialchars($order['order_id'] ?? '') ?>" class="btn">
                Voir Détails
            </a>
        </div>
        <?php
            }
        } else {
            echo '<p class="empty">Aucune commande en cours</p>';
        }
        ?>
    </div>
</section>

<footer class="footer">
    <div class="footer-content">
        <div class="footer-logo">
            <img src="../images/logo1.png" alt="ToolBiTrading_SARL Logo">
        </div>

        <div class="footer-contact">
            <h3>Contactez-nous</h3>
            <p>ToolBiTrading_SARL</p>
            <p>Adresse: Dieupeul-Derklé</p>
            <p>Téléphone: +221 76 740 92 95 / 76 991 41 81 / 77 110 60 76</p>
            <p>Email: toolbitradingsarl@outlook.com</p>
        </div>

        <div class="footer-social">
            <h3>Suivez-nous</h3>
            <a href="https://facebook.com" target="_blank"><i class="fab fa-facebook-f"></i> Facebook</a>
            <a href="https://twitter.com" target="_blank"><i class="fab fa-twitter"></i> Twitter</a>
            <a href="https://instagram.com" target="_blank"><i class="fab fa-instagram"></i> Instagram</a>
        </div>
    </div>

    <div class="footer-bottom">
        <p>&copy; 2024 ToolBiTrading_SARL. Tous droits réservés.</p>
    </div>
</footer>

<script>
document.getElementById('logout-btn').addEventListener('click', function(event) {
    event.preventDefault();
    if (confirm('Voulez-vous vraiment vous déconnecter ?')) {
        window.location.href = '../components/admin_logout.php';
    }
});
</script>

</body>
</html>

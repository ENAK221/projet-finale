<?php
include '../components/connect.php';
session_start();

if (!isset($_SESSION['admin_id'])) {
    header('location:admin_login.php');
    exit;
}

$admin_id = $_SESSION['admin_id'];
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Détails de la Commande</title>
    <!-- lien CDN Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css">
    <!-- lien fichier CSS personnalisé -->
    <link rel="stylesheet" href="../css/admin_style.css">
    <style>
        .container { max-width: 800px; margin: 2rem auto; padding: 0 1rem; }
        .order-details {
            background: #f9f9f9;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
            border: 1px solid #ddd;
        }
        .order-details h3 { margin-bottom: 15px; color: #333; }
        .order-details table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .order-details th, .order-details td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: left;
        }
        .order-details th { background-color: #f2f2f2; font-weight: bold; }
        .back-btn {
            display: inline-block;
            margin-bottom: 1rem;
            padding: 8px 16px;
            background: #6c757d;
            color: #fff;
            border-radius: 5px;
            text-decoration: none;
        }
        .back-btn:hover { background: #5a6268; }
    </style>
</head>
<body>
    <?php include '../components/admin_header.php'; ?>

    <div class="container">
        <a href="livreur.php" class="back-btn"><i class="fas fa-arrow-left"></i> Retour</a>
        <h2>Détails de la Commande</h2><br>

        <?php
        if (isset($_GET['order_id']) && !empty($_GET['order_id'])) {
            $order_id = $_GET['order_id'];

            $stmt = $conn->prepare("SELECT * FROM `orders` WHERE order_id = ?");
            $stmt->execute([$order_id]);
            $order = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($order) {
        ?>
        <div class="order-details">
            <h3>Informations sur la Commande</h3>
            <table>
                <tr><th>Numéro de Commande</th><td>#<?= htmlspecialchars($order['order_id']); ?></td></tr>
                <tr><th>Prénom</th><td><?= htmlspecialchars($order['name']); ?></td></tr>
                <tr><th>Nom</th><td><?= htmlspecialchars($order['surname']); ?></td></tr>
                <tr><th>Téléphone</th><td><?= htmlspecialchars($order['phone']); ?></td></tr>
                <tr><th>Adresse</th><td><?= htmlspecialchars($order['address']); ?></td></tr>
                <tr><th>Produits commandés</th><td><?= htmlspecialchars($order['total_products']); ?></td></tr>
                <tr><th>Total TTC</th><td><?= htmlspecialchars($order['total_price']); ?> Fcfa</td></tr>
                <tr><th>Date de livraison</th><td><?= htmlspecialchars($order['delivery_date']); ?></td></tr>
                <tr>
                    <th>Statut</th>
                    <td><strong><?= htmlspecialchars($order['payment_status']); ?></strong></td>
                </tr>
            </table>
            <a href="../livreur/generate_pdf.php?order_id=<?= htmlspecialchars($order['order_id']); ?>" class="back-btn" style="background:#007bff">
                <i class="fas fa-file-pdf"></i> Télécharger la facture
            </a>
        </div>
        <?php
            } else {
                echo "<p>Aucune commande trouvée avec l'ID spécifié.</p>";
            }
        } else {
            echo "<p>Paramètre d'ID de commande manquant.</p>";
        }
        ?>
    </div>

    <!-- lien fichier JS personnalisé -->
    <script src="../js/admin_script.js"></script>
</body>
</html>

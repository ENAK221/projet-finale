<?php
include '../components/connect.php';
session_start();

$admin_id = $_SESSION['admin_id'] ?? null;
if (!$admin_id) {
    header('location:admin_login.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Commandes Livreurs</title>
    <!-- lien CDN Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css">
    <!-- lien fichier CSS personnalisé -->
    <link rel="stylesheet" href="../css/admin_style.css">
    <style>
        .container {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            padding: 2rem;
        }
        .order-item {
            background: #f9f9f9;
            padding: 15px;
            border-radius: 8px;
            width: calc(33.33% - 20px);
            box-sizing: border-box;
            border: 1px solid #ddd;
        }
        .order-item p { margin: 6px 0; font-size: .9rem; }
        .order-item p span { font-weight: bold; color: #333; }
        .order-item a {
            display: inline-block;
            margin-top: 10px;
            margin-right: 6px;
            text-decoration: none;
            background-color: #007bff;
            color: white;
            padding: 6px 12px;
            border-radius: 4px;
            font-size: .85rem;
        }
        .order-item a:hover { background-color: #0056b3; }
        .status-badge {
            display: inline-block;
            padding: 3px 10px;
            border-radius: 12px;
            font-size: .78rem;
            font-weight: 700;
        }
        .badge-encours  { background: #fff3cd; color: #856404; }
        .badge-livre    { background: #d1e7dd; color: #0f5132; }
        .badge-attente  { background: #f8d7da; color: #842029; }
    </style>
</head>
<body>
<?php include '../components/admin_header.php'; ?>

<section>
    <div class="container">
        <h2 style="width:100%">Commandes assignées aux livreurs</h2>

        <?php
        $query = $conn->prepare("SELECT * FROM `orders` WHERE payment_status IN ('En cours', 'Livré') ORDER BY delivery_date ASC");
        $query->execute();

        if ($query->rowCount() > 0) {
            while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                $badge = 'badge-attente';
                if ($row['payment_status'] === 'En cours') $badge = 'badge-encours';
                if ($row['payment_status'] === 'Livré')    $badge = 'badge-livre';
        ?>
        <div class="order-item">
            <p>Commande : <span>#<?= htmlspecialchars($row['order_id']); ?></span></p>
            <p>Client : <span><?= htmlspecialchars($row['name'] . ' ' . $row['surname']); ?></span></p>
            <p>Téléphone : <span><?= htmlspecialchars($row['phone']); ?></span></p>
            <p>Total : <span><?= htmlspecialchars($row['total_price']); ?> Fcfa</span></p>
            <p>Livraison : <span><?= htmlspecialchars($row['delivery_date']); ?></span></p>
            <p>Statut : <span class="status-badge <?= $badge ?>"><?= htmlspecialchars($row['payment_status']); ?></span></p>
            <a href="details_commande.php?order_id=<?= htmlspecialchars($row['order_id']); ?>">Voir détails</a>
            <a href="../livreur/generate_pdf.php?order_id=<?= htmlspecialchars($row['order_id']); ?>">Voir facture</a>
        </div>
        <?php
            }
        } else {
            echo '<p style="padding:2rem">Aucune commande assignée à un livreur.</p>';
        }
        ?>
    </div>
</section>

<!-- lien fichier JS personnalisé -->
<script src="../js/admin_script.js"></script>
</body>
</html>

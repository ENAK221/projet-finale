<?php
    include '../components/connect.php';
    session_start();

    $admin_id = $_SESSION['admin_id'];

    if(!isset($admin_id))
    {
        header('location:admin_login.php');
    }

    // Fonction pour formater la date en français
    function formatDate($date) {
        setlocale(LC_TIME, 'fr_FR.UTF-8'); // Assurez-vous que la locale française est activée sur votre serveur
        $dateTime = new DateTime($date);
        return $dateTime->format('d F Y'); // Format français : jour mois année
    }

    if(isset($_POST['update_payment']))
    {
        $order_id = $_POST['order_id'];
        $order_id = filter_var($order_id, FILTER_SANITIZE_SPECIAL_CHARS);

        $payment_status = $_POST['payment_status'];
        $payment_status = filter_var($payment_status, FILTER_SANITIZE_SPECIAL_CHARS);

        $update_status = $conn->prepare("UPDATE `orders` SET payment_status=? WHERE order_id=?");
        $update_status->execute([$payment_status, $order_id]);
        $message[] = 'Statut de la commande mis à jour avec succès !';
    }

    if(isset($_GET['delete']))
    {
        $delete_id = $_GET['delete'];
        $delete_order = $conn->prepare("DELETE FROM `orders` WHERE order_id=?");
        $delete_order->execute([$delete_id]);

        header('location:placed_orders.php');

        $message[] = 'Commande supprimée avec succès !';
    }

    // Sélection des commandes triées par date de livraison décroissante
    $select_orders = $conn->prepare("SELECT * FROM `orders` ORDER BY delivery_date ASC");
    $select_orders->execute();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Commandes passées</title>

    <!-- Font Awesome CDN link -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css">

    <!-- Custom CSS file link -->
    <link rel="stylesheet" href="../css/admin_style.css">
    <style>
        /* Style pour les listes de commandes */
        .order-list {
            list-style-type: none;
            padding: 0;
        }

        .order-list-item {
            background-color: #f0f0f0; /* Fond gris */
            margin-bottom: 10px;
            padding: 15px;
            border-radius: 5px;
        }

        .order-list-item p {
            margin: 15px 0;
        }
        .order-list-item p span{
            margin: 10px 0;
            color: red;
            font-weight: bold;
        }
    </style>
</head>
<body>

<?php
    include '../components/admin_header.php'
?>

<!-- Section des commandes passées -->
<section class="placed-orders">
    <h1 class="heading">Commandes passées</h1>

    <ul class="order-list">
        <?php
            if($select_orders->rowCount() > 0)
            {
                while($fetch_orders = $select_orders->fetch(PDO::FETCH_ASSOC))
                {
        ?>

        <li class="order-list-item">
            <p> Numéro Commande : <span><?= $fetch_orders['order_id']; ?></span> </p>
            <p> Date de Livraison : <span><?= formatDate($fetch_orders['delivery_date']); ?></span> </p>
            <!-- <p> ID Utilisateur : <span><?= $fetch_orders['user_id']; ?></span> </p> -->
            <p> Date Commande : <span><?= formatDate($fetch_orders['order_date']); ?></span> </p>
            <p> Prénom : <span><?= $fetch_orders['name']; ?></span> </p>
            <p> Nom : <span><?= $fetch_orders['surname']; ?></span> </p>
            <!-- <p> Email : <span><?= $fetch_orders['email']; ?></span> </p> -->
            <p> Numéro : <span><?= $fetch_orders['phone']; ?></span> </p>
            <p> Adresse : <span><?= $fetch_orders['address']; ?></span> </p>
            <p> Total Produits : <span><?= $fetch_orders['total_products']; ?></span> </p>
            <p> Total TTC : <span><?= $fetch_orders['total_price']; ?> Fcfa</span> </p>

           <!-- Code existant -->
<form action="" method="post">
    <input type="hidden" name="order_id" value="<?= $fetch_orders['order_id']; ?>">
    <select name="payment_status" class="drop-down">
        <option value="<?= $fetch_orders['payment_status']; ?>" selected><?= $fetch_orders['payment_status']; ?></option>
        <option value="En Attente">En Attente</option>
        <option value="En cours">En cours</option>
    </select>
    <div class="flex-btn">
        <input type="submit" value="Modifier" class="btn" name="update_payment">
        
        <!-- Condition pour désactiver le bouton supprimer si le statut est "Livré" -->
        <?php if ($fetch_orders['payment_status'] === 'Livré'): ?>
            <button class="delete-btn" disabled style="opacity: 0.5; cursor: not-allowed;">Supprimer</button>
        <?php else: ?>
            <a href="placed_orders.php?delete=<?= $fetch_orders['order_id']; ?>" class="delete-btn" onclick="return confirm('Supprimer cette commande ?');">Supprimer</a>
        <?php endif; ?>
        
        <a href="./generate_pdf.php?order_id=<?= $fetch_orders['order_id']; ?>" class="btn">Voir Facture</a>
    </div>
</form>

        </li>

        <?php
                }
            }
            else
            {
                echo '<li class="empty">Aucune commande passée pour le moment !</li>';
            }
        ?>
    </ul>
</section>

<!-- Fichier JavaScript personnalisé -->
<script src="../js/admin_script.js"></script>
    
</body>
</html>

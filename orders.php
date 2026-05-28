<?php
    include 'components/connect.php';

    session_start();

    if(isset($_SESSION['user_id'])) {
        $user_id = $_SESSION['user_id'];
    } else {
        $user_id = '';
    }

    function formatDate($dateStr) {
        $date = new DateTime($dateStr);
        $formattedDate = $date->format('d F Y');
        $months = [
            'January' => 'Janvier', 'February' => 'Février', 'March' => 'Mars', 'April' => 'Avril', 'May' => 'Mai', 'June' => 'Juin',
            'July' => 'Juillet', 'August' => 'Août', 'September' => 'Septembre', 'October' => 'Octobre', 'November' => 'Novembre', 'December' => 'Décembre'
        ];
        $formattedDate = str_replace(array_keys($months), array_values($months), $formattedDate);
        return "Le " . $formattedDate;
    }
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Commandes</title>

    <!-- Font Awesome CDN link -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css">

    <!-- Custom CSS file link -->
    <link rel="stylesheet" href="css/style.css">

    <style>
        .order-list {
            list-style-type: none;
            padding: 0;
        }

        .order-item {
            background-color: #f0f0f0;
            margin-bottom: 10px;
            padding: 15px;
            border-radius: 5px;
        }

        .order-item p {
            margin: 5px 0;
        }

        .btn {
            padding: 10px 15px;
            border-radius: 5px;
            text-decoration: none;
            color: #fff;
        }

        .btn.status-pending {
            background-color: red;
        }

        .btn.status-inprogress {
            background-color: orange;
        }

        .btn.status-delivered {
            background-color: green;
        }

        .btn.blue-btn {
            background-color: blue;
        }
    </style>
</head>
<body>

    <?php
        include 'components/user_header.php';
    ?>

    <!-- Orders section starts -->
    <section class="show-orders">
        <h1 class="heading">Vos Commandes</h1>

        <ul class="order-list">
            <?php
                $show_orders = $conn->prepare("SELECT * FROM `orders` WHERE user_id=? ORDER BY `order_date` DESC");
                $show_orders->execute([$user_id]);
                if($show_orders->rowCount()>0) {
                    while($fetch_orders = $show_orders->fetch(PDO::FETCH_ASSOC)) {
            ?>
            <li class="order-item">
                <p>Nom: <span><?= $fetch_orders['name']; ?></span> Prénom: <span><?= $fetch_orders['surname']; ?></span></p>
                <p>Adresse: <span><?= $fetch_orders['address']; ?></span></p>
                <p>Date de livraison: <span><?= formatDate($fetch_orders['delivery_date']); ?></span></p>
                <p>Status: 
                    <span>
                        <?php if($fetch_orders['payment_status'] == 'En Attente') { ?>
                            <a href="#" class="btn status-pending"><?= $fetch_orders['payment_status']; ?></a>
                        <?php } elseif($fetch_orders['payment_status'] == 'En cours') { ?>
                            <a href="#" class="btn status-inprogress"><?= $fetch_orders['payment_status']; ?></a>
                        <?php } else { ?>
                            <a href="#" class="btn status-delivered"><?= $fetch_orders['payment_status']; ?></a>
                        <?php } ?>
                    </span>
                </p>
                <a href="livreur/generate_pdf.php?user_id=<?= $user_id ?>&order_id=<?= $fetch_orders['order_id']; ?>" class="btn blue-btn">Voir Facture</a>
            </li>
            <?php
                    }
                } else {
                    echo '<p class="empty">Actuellement, aucune commande n\'a été passée.</p>';
                }
            ?>
        </ul>
    </section>

    <?php
        include 'components/footer.php';
    ?>

    <!-- Custom JavaScript file link -->
    <script src="js/script.js"></script>
</body>
</html>

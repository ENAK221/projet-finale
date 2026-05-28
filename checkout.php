<?php
include 'components/connect.php';

session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: home.php');
    exit();
}

$user_id = $_SESSION['user_id'];
$total_products = '';

if (isset($_POST['order'])) {
    $address = $_POST['address'];
    $delivery_date = $_POST['delivery_date'];
    $total_products = $_POST['total_products'];
    $total_price = $_POST['total_price'];
    $payment_status = "En Attente";

    // Récupération des informations de l'utilisateur
    $select_user = $conn->prepare("SELECT name, surname, phone FROM `users` WHERE id = ?");
    $select_user->execute([$user_id]);
    $fetch_user = $select_user->fetch(PDO::FETCH_ASSOC);

    if ($fetch_user) {
        // Pas de htmlspecialchars ici pour les données à stocker
        $name = $fetch_user['name'];
        $surname = $fetch_user['surname'];
        $phone = $fetch_user['phone'];

        $check_cart = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
        $check_cart->execute([$user_id]);

        if ($check_cart->rowCount() > 0) {
            // Insertion de la commande dans la base de données
            $insert_order = $conn->prepare("INSERT INTO `orders` (user_id, name, surname, phone, address, total_products, total_price, delivery_date, payment_status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $insert_order->execute([$user_id, $name, $surname, $phone, $address, $total_products, $total_price, $delivery_date, $payment_status]);

            $_SESSION['address'] = $address;

            $order_id = $conn->lastInsertId(); // Récupérer l'order_id de la commande insérée
            $message = 'Commande passée avec succès! Votre numéro de commande est: ' . $order_id;

            // Suppression des articles du panier
            $delete_cart = $conn->prepare("DELETE FROM `cart` WHERE user_id = ?");
            $delete_cart->execute([$user_id]);
        } else {
            $message = 'Votre panier est vide!';
        }
    } else {
        $message = 'Utilisateur non trouvé.';
    }
}

$address = isset($_SESSION['address']) ? $_SESSION['address'] : '';
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Finaliser l'achat</title>

    <!-- Font Awesome CDN link -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css">

    <!-- Custom CSS file link -->
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

    <?php include 'components/user_header.php'; ?>

    <!-- Checkout section starts -->
    <section class="checkout">
        <h1 class="heading">Votre commande</h1>
 
        <div class="display-orders">
            <?php
                $grand_total = 0;
                $cart_items = [];
                $select_cart = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
                $select_cart->execute([$user_id]);
                if ($select_cart->rowCount() > 0) {
                    while ($fetch_cart = $select_cart->fetch(PDO::FETCH_ASSOC)) {
                        $grand_total += ($fetch_cart['price'] * $fetch_cart['quantity']);
                        $cart_items[] = htmlspecialchars($fetch_cart['name'], ENT_QUOTES, 'UTF-8') . ' (' . htmlspecialchars($fetch_cart['quantity'], ENT_QUOTES, 'UTF-8') . ')';
                    }
                    $total_products = implode(', ', $cart_items);
                } else {
                    echo '<p class="empty">Votre Panier est vide!</p>';
                }
            ?>
        </div>
        
        <p class="grand-total">Total TTC: <span><?= number_format($grand_total, 2, ',', ' ') ?> Fcfa</span></p>

        <form action="" method="post" accept-charset="UTF-8">
            <h1 class="heading">Coordonnées</h1>
            <input type="hidden" name="total_products" value="<?= htmlspecialchars($total_products, ENT_QUOTES, 'UTF-8'); ?>">
            <input type="hidden" name="total_price" value="<?= htmlspecialchars($grand_total, ENT_QUOTES, 'UTF-8'); ?>">

            

                <div class="inputBox">
                    <span>Date de livraison: </span>
                    <input type="date" placeholder="Sélectionnez la date de livraison" required class="box" name="delivery_date" id="delivery_date">
                </div>
            </div>

            <input type="submit" value="Commander" class="btn <?= ($grand_total > 0) ? '' : 'disabled'; ?>" name="order">
        </form>

        <!-- Affichage des messages -->
        <?php if (isset($message)) : ?>
            <p class="message"><?= htmlspecialchars($message, ENT_QUOTES, 'UTF-8'); ?></p>
        <?php endif; ?>
    </section>

    <?php include 'components/footer.php'; ?>

    <!-- Custom JavaScript file link -->
    <script src="js/script.js"></script>

    <script>
        // Script pour définir la date minimale
        document.addEventListener('DOMContentLoaded', (event) => {
            const dateInput = document.getElementById('delivery_date');
            const today = new Date();
            const day = String(today.getDate()).padStart(2, '0');
            const month = String(today.getMonth() + 1).padStart(2, '0');
            const year = today.getFullYear();
            const minDate = `${year}-${month}-${day}`;
            dateInput.setAttribute('min', minDate);
        });
    </script>
    
</body>
</html>

<?php
    include 'components/connect.php';

    session_start();

    if (isset($_SESSION['user_id'])) {
        $user_id = $_SESSION['user_id'];
    } else {
        header('location:home.php');
        exit();
    }

    if (isset($_POST['delete'])) {
        $cart_id = $_POST['cart_id'];
        $delete_cart = $conn->prepare("DELETE FROM `cart` WHERE id=?");
        $delete_cart->execute([$cart_id]);
        $message[] = 'Produit supprimé avec succès';
    }

    if (isset($_GET['delete_all'])) {
        $delete_all_cart = $conn->prepare("DELETE FROM `cart` WHERE user_id=?");
        $delete_all_cart->execute([$user_id]);
        header('location:cart.php');
        exit();
    }

    if (isset($_POST['update_qty'])) {
        $cart_id = $_POST['cart_id'];
        $qty = $_POST['qty'];
        $update_qty = $conn->prepare("UPDATE `cart` SET quantity=? WHERE id=?");
        $update_qty->execute([$qty, $cart_id]);
        $message[] = 'Quantité mise à jour avec succès!';
    }
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panier</title>

    <!-- lien CDN de Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css">

    <!-- lien vers votre fichier CSS personnalisé -->
    <link rel="stylesheet" href="css/style.css">

</head>
<body>

    <?php include 'components/user_header.php'; ?>

    <!-- section du panier commence ici -->
    <section class="products">

        <h1 class="heading">Votre Panier</h1>

        <div class="box-container">
            <?php
                $grand_total = 0;
                $select_cart = $conn->prepare("SELECT * FROM `cart` WHERE user_id=?"); 
                $select_cart->execute([$user_id]);
                if ($select_cart->rowCount() > 0) {
                    while ($fetch_cart = $select_cart->fetch(PDO::FETCH_ASSOC)) {
                        // Calcul du sous-total pour chaque produit
                        $sub_total = $fetch_cart['price'] * $fetch_cart['quantity'];
                        // Ajout du sous-total au grand_total
                        $grand_total += $sub_total;
            ?>

            <form action="" method="post" class="box" accept-charset="UTF-8">
                <input type="hidden" name="cart_id" value="<?= htmlspecialchars($fetch_cart['id'], ENT_QUOTES, 'UTF-8'); ?>">

                <a href="quick_view.php?pid=<?= htmlspecialchars($fetch_cart['pid'], ENT_QUOTES, 'UTF-8'); ?>" class="fas fa-eye"></a>
                <img src="uploaded_img/<?= htmlspecialchars($fetch_cart['image'], ENT_QUOTES, 'UTF-8'); ?>" alt="" class="image">
                <div class="name"><?= htmlspecialchars($fetch_cart['name'], ENT_QUOTES, 'UTF-8'); ?></div>
                <div class="flex">
                    <div class="price"><span><?= htmlspecialchars($fetch_cart['price'], ENT_QUOTES, 'UTF-8'); ?></span> Fcfa</div>
                    <input type="number" name="qty" class="qty" min="1" max="99" value="<?= htmlspecialchars($fetch_cart['quantity'], ENT_QUOTES, 'UTF-8'); ?>" onkeypress="if(this.value.length==2) return false;">
                    <button type="submit" class="fas fa-edit" name="update_qty"></button>
                </div>
                <div class="sub-total">Prix Unitaire: <span><?= htmlspecialchars($fetch_cart['price'], ENT_QUOTES, 'UTF-8'); ?> Fcfa</span></div>
                <input type="submit" value="Supprimer Produit" onclick="return confirm('Voulez-vous vraiment supprimer ce produit du panier?');" name="delete" class="delete-btn">
            </form>

            <?php
                    }
                } else {
                    echo '<p class="empty">Votre Panier est vide!</p>';
                }
            ?>
        </div>

        <div class="grand-total">
            <p>Total TTC: <span><?= htmlspecialchars($grand_total, ENT_QUOTES, 'UTF-8'); ?> Fcfa</span></p>
            <a href="shop.php" class="option-btn">Continuer l'achat</a>
            <a href="cart.php?delete_all" class="delete-btn <?= ($grand_total > 0) ? '' : 'disabled'; ?>" onclick="return confirm('Voulez-vous supprimer tout le panier?');">Supprimer tout</a>
            <a href="checkout.php" class="btn <?= ($grand_total > 0) ? '' : 'disabled'; ?>">Valider commande</a>
        </div>

    </section>

    <?php include 'components/footer.php'; ?>

    
    <script src="js/script.js"></script>
    
</body>
</html>

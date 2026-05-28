<?php
    include 'components/connect.php';

    session_start();

    if(isset($_SESSION['user_id']))
    {
        $user_id = $_SESSION['user_id'];
    }
    else
    {
        $user_id = '';
    }

    include 'components/wishlist_cart.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>categories</title>

    <!-- font awesome cdn link  -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css">

    <!-- custom css file link  -->
    <link rel="stylesheet" href="css/style.css">

</head>
<body>

    <?php
        include 'components/user_header.php'
    ?>

    <!-- category hero -->
    <?php $category = isset($_GET['category']) ? $_GET['category'] : 'Tous'; ?>
    <section class="category-hero">
        <div class="category-hero-content">
            <div>
                <p class="eyebrow">Catégorie</p>
                <h1>Produits de <?= htmlspecialchars(ucfirst($category)); ?></h1>
                <p class="subtext">Découvrez nos produits sélectionnés pour la catégorie « <?= htmlspecialchars($category); ?> ». Qualité garantie, stock actualisé et livraison rapide.</p>
            </div>
            <div class="hero-badges">
                <span>💎 Qualité Premium</span>
                <span>🏷️ Meilleurs prix</span>
                <span>🚚 Livraison rapide</span>
            </div>
        </div>
    </section>

    <section class="products">
        <div class="section-head">
            <h2 class="heading">Nos produits <?= htmlspecialchars(ucfirst($category)); ?></h2>
            <a href="shop.php" class="link-btn">Voir toutes les categories</a>
        </div>

        <div class="box-container category-grid">
            <?php
                $category = isset($_GET['category']) ? $_GET['category'] : '';
                $select_products = $conn->prepare("SELECT * FROM `products` WHERE category = ?");
                $select_products->execute([$category]);

                if($select_products->rowCount()>0){ 
                    while($fetch_products = $select_products->fetch(PDO::FETCH_ASSOC)){
            ?>

            <form action="" method="post" class="box modern-box">
                <input type="hidden" name="pid" value="<?= htmlspecialchars($fetch_products['id'], ENT_QUOTES, 'UTF-8'); ?>">
                <input type="hidden" name="name" value="<?= htmlspecialchars($fetch_products['name'], ENT_QUOTES, 'UTF-8'); ?>">
                <input type="hidden" name="price" value="<?= htmlspecialchars($fetch_products['price'], ENT_QUOTES, 'UTF-8'); ?>">
                <input type="hidden" name="image" value="<?= htmlspecialchars($fetch_products['image_01'], ENT_QUOTES, 'UTF-8'); ?>">

                <div class="card-top">
                    <button type="submit" name="add_to_wishlist" class="icon-btn fas fa-heart"></button>
                    <a href="quick_view.php?pid=<?= htmlspecialchars($fetch_products['id'], ENT_QUOTES, 'UTF-8'); ?>" class="icon-btn fas fa-eye"></a>
                </div>
                <img src="uploaded_img/<?= htmlspecialchars($fetch_products['image_01'], ENT_QUOTES, 'UTF-8'); ?>" class="image" alt="<?= htmlspecialchars($fetch_products['name'], ENT_QUOTES, 'UTF-8'); ?>">
                <div class="name"><?= htmlspecialchars($fetch_products['name'], ENT_QUOTES, 'UTF-8'); ?></div>
                <div class="flex">
                    <div class="price"> <span><?= htmlspecialchars($fetch_products['price'], ENT_QUOTES, 'UTF-8'); ?></span> Fcfa</div>
                    <input type="number" name="qty" class="qty" min="1" max="99" value="1" onkeypress="if(this.value.length==2) return false;">
                </div>
                <input type="submit" value="Ajouter au panier" name="add_to_cart" class="btn add-to-cart-btn">
            </form>

            <?php
                    }
                } else {
                    echo '<p class="empty">Aucun produit trouvé dans cette catégorie.</p>';
                }
            ?>
        </div>
    </section>














    <?php
        include 'components/footer.php';
    ?>

    <!-- custom js file link  -->
    <script src="js/script.js"></script>
    
</body>
</html>
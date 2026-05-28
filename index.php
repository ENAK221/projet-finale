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

    include 'components/wishlist_cart.php'
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accueil - ToolBiTrading_Sarl</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@8/swiper-bundle.min.css">

    <!-- lien CDN Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css">

    <!-- lien fichier CSS personnalisé -->
    <link rel="stylesheet" href="css/style.css">
    <style>
    .hero {
        display: grid;
        grid-template-columns: 1.1fr 0.9fr;
        gap: 1.6rem;
        background: linear-gradient(125deg, #eef3ff 0%, #f9fbff 100%);
        border: 1px solid #e5e9ff;
        border-radius: 18px;
        box-shadow: 0 14px 35px rgba(27, 49, 104, 0.08);
        padding: 2rem;
        margin: 1rem 0;
        align-items: center;
    }
    .hero-content h1 { font-size: 3rem; margin-bottom: .8rem; color: #22315f; line-height: 1.2; }
    .hero-content p { font-size: 1.5rem; margin-bottom: 1.5rem; color: #4f5777; }
    .hero-content .hero-cta { display:inline-flex; align-items:center; gap:.6rem; background:#2f80ed; color:#fff; border-radius:10px; padding:.9rem 1.4rem; font-size:1.45rem; font-weight:600; margin-right:.7rem; }
    .hero-content .hero-cta:hover{background:#1f6bd8;}
    .hero-image{width:100%; max-height:330px; object-fit:cover; border-radius:14px; border:1px solid #e5e9ff;}
    .hero-badges{display:flex; gap:.6rem; margin-top:1rem; flex-wrap:wrap;}
    .hero-badges .badge{font-size:1.3rem; background:#eff3ff; color:#2f4f94; border-radius:8px; padding:.45rem .8rem; border:1px solid #dbe4ff;}
    .success-message{color: #1f7a1f; border:2px solid #a8d5a8; background:#f2fff2; padding:1rem; border-radius:8px; margin:1rem 0; font-size:1.5rem; }
    @media (max-width: 900px){ .hero{grid-template-columns:1fr;} .hero-content h1{font-size:2.2rem;} .hero-content p{font-size:1.2rem;} }
    </style>

</head>
<body>

    <?php include 'components/user_header.php'; ?>
    <?php if (isset($_SESSION['success_message'])): ?>
        <p class="success-message"><?= htmlspecialchars($_SESSION['success_message'], ENT_QUOTES, 'UTF-8'); ?></p>
    <?php unset($_SESSION['success_message']); endif; ?>

    <section class="hero">
        <div class="hero-content">
            <span class="badge" style="background:#ffd8ad;color:#7d4d07;padding:.45rem .8rem;border-radius:8px;font-size:1.1rem;">PROMO DU JOUR</span>
            <h1>Faites vos courses vite et sans effort avec ToolBiTrading</h1>
            <p>Produits frais, sanitaires et boissons — sélection premium, livraison rapide dans votre ville, et paiement sécurisé.</p>
            <div style="display:flex; gap:.6rem; flex-wrap:wrap; margin-top:1rem;">
                <a href="shop.php" class="hero-cta">Acheter maintenant</a>
                <a href="category.php?category=eau" class="hero-cta" style="background:#6f44df;">Voir nos offres</a>
            </div>
            <div class="hero-badges">
                <span class="badge">🛒 +500 produits</span>
                <span class="badge">🚚 Livraison 24-48h</span>
                <span class="badge">🧾 Facturation en ligne</span>
            </div>
        </div>
        <div>
            <img src="images/bgg.jpg" alt="Boutique en ligne" class="hero-image">
        </div>
    </section>

    <!-- carrousel produits en vedette -->
    <section class="section-block">
        <div class="section-head">
            <div>
                <p class="eyebrow">En vedette</p>
                <h2>Produits populaires</h2>
            </div>
            <a href="shop.php" class="link-btn">Voir tout</a>
        </div>

        <div class="swiper featured-slider">
            <div class="swiper-wrapper">
                <?php
                    $select_featured = $conn->prepare("SELECT * FROM `products` ORDER BY id DESC LIMIT 10");
                    $select_featured->execute();
                    while($featured = $select_featured->fetch(PDO::FETCH_ASSOC)):
                ?>
                <form action="" method="post" class="swiper-slide featured-card">
                    <input type="hidden" name="pid" value="<?= htmlspecialchars($featured['id'], ENT_QUOTES, 'UTF-8'); ?>">
                    <input type="hidden" name="name" value="<?= htmlspecialchars($featured['name'], ENT_QUOTES, 'UTF-8'); ?>">
                    <input type="hidden" name="price" value="<?= htmlspecialchars($featured['price'], ENT_QUOTES, 'UTF-8'); ?>">
                    <input type="hidden" name="image" value="<?= htmlspecialchars($featured['image_01'], ENT_QUOTES, 'UTF-8'); ?>">
                    <div class="card-top">
                        <button type="submit" name="add_to_wishlist" class="icon-btn fas fa-heart"></button>
                        <a href="quick_view.php?pid=<?= htmlspecialchars($featured['id'], ENT_QUOTES, 'UTF-8'); ?>" class="icon-btn fas fa-eye"></a>
                    </div>
                    <img src="uploaded_img/<?= htmlspecialchars($featured['image_01'], ENT_QUOTES, 'UTF-8'); ?>" alt="<?= htmlspecialchars($featured['name'], ENT_QUOTES, 'UTF-8'); ?>" class="card-image">
                    <div class="card-body">
                        <h3><?= htmlspecialchars($featured['name'], ENT_QUOTES, 'UTF-8'); ?></h3>
                        <p class="price"><?= htmlspecialchars($featured['price'], ENT_QUOTES, 'UTF-8'); ?> Fcfa</p>
                                <div class="card-actions">
                            <input type="number" name="qty" class="qty" min="1" max="99" value="1" style="width:4.5rem;">
                            <button type="submit" name="add_to_cart" class="btn add-to-cart-btn">Ajouter au panier</button>
                        </div>
                    </div>
                </form>
                <?php endwhile; ?>
            </div>
            <div class="swiper-pagination"></div>
        </div>
    </section>

    <!-- cartes catégories -->
    <section class="section-block">
        <div class="section-head">
            <div>
                <p class="eyebrow">Catégories</p>
                <h2>Trouvez votre produit</h2>
            </div>
            <a href="category.php" class="link-btn">Voir toutes les catégories</a>
        </div>

        <div class="category-layout">
            <a href="category.php?category=eau" class="category-tile tile-blue">
                <div class="tile-top"><img src="images/eau.png" alt="Eau"></div>
                <div class="tile-body">
                    <h3>Eaux</h3>
                    <p>Produits d'hydratation premium pour votre famille.</p>
                </div>
            </a>
            <a href="category.php?category=oeuf" class="category-tile tile-green">
                <div class="tile-top"><img src="images/oeuf.png" alt="Oeuf"></div>
                <div class="tile-body">
                    <h3>Oeufs</h3>
                    <p>Oeufs frais, qualité supérieure, disponibles en stock.</p>
                </div>
            </a>
            <a href="category.php?category=savon" class="category-tile tile-purple">
                <div class="tile-top"><img src="images/Savon.png" alt="Savon"></div>
                <div class="tile-body">
                    <h3>Savons</h3>
                    <p>Soins corporels et hygiène au quotidien.</p>
                </div>
            </a>
            <a href="category.php?category=boisson" class="category-tile tile-orange">
                <div class="tile-top"><img src="images/fanta.png" alt="Boisson"></div>
                <div class="tile-body">
                    <h3>Boissons</h3>
                    <p>Boissons rafraîchissantes pour toute la famille.</p>
                </div>
            </a>
        </div>
    </section>

    <!-- début section produits accueil -->

    <section class="home-products">
    <h1 class="heading">ELECTRONIQUE</h1>

    <div class="products-grid">

        <?php
            $select_products = $conn->prepare("SELECT * FROM `products` LIMIT 8");
            $select_products->execute();
            if($select_products->rowCount() > 0) {
                while($fetch_products = $select_products->fetch(PDO::FETCH_ASSOC)) {
        ?>

        <form action="" method="post" class="product-card">
            <input type="hidden" name="pid" value="<?= htmlspecialchars($fetch_products['id'], ENT_QUOTES, 'UTF-8'); ?>">
            <input type="hidden" name="name" value="<?= htmlspecialchars($fetch_products['name'], ENT_QUOTES, 'UTF-8'); ?>">
            <input type="hidden" name="price" value="<?= htmlspecialchars($fetch_products['price'], ENT_QUOTES, 'UTF-8'); ?>">
            <input type="hidden" name="image" value="<?= htmlspecialchars($fetch_products['image_01'], ENT_QUOTES, 'UTF-8'); ?>">

            <button type="submit" name="add_to_wishlist" class="fas fa-heart wishlist-btn"></button>
            <a href="quick_view.php?pid=<?= htmlspecialchars($fetch_products['id'], ENT_QUOTES, 'UTF-8'); ?>" class="fas fa-eye quick-view-btn"></a>
            <img src="uploaded_img/<?= htmlspecialchars($fetch_products['image_01'], ENT_QUOTES, 'UTF-8'); ?>" class="product-image" alt="">
            <div class="name"><?= htmlspecialchars($fetch_products['name'], ENT_QUOTES, 'UTF-8'); ?></div>
            <div class="flex">
                <div class="price"><span><?= htmlspecialchars($fetch_products['price'], ENT_QUOTES, 'UTF-8'); ?></span> Fcfa</div>
                <input type="number" name="qty" class="qty" min="1" max="99" value="1" onkeypress="if(this.value.length==2) return false;">
            </div>
            <input type="submit" value="Ajouter au panier" name="add_to_cart" class="btn add-to-cart-btn">
        </form>

        <?php
                }
            } else {
                echo '<p class="empty">Aucun produit ajouté pour le moment !</p>';
            }
        ?>

    </div>
</section>
<section class="home-sanitaire">
    <h1 class="heading">SANITAIRE</h1>

    <div class="products-grid">

        <?php
            $select_sanitaire = $conn->prepare("SELECT * FROM `products` WHERE category = 'sanitaire' LIMIT 8");
            $select_sanitaire->execute();
            if($select_sanitaire->rowCount() > 0) {
                while($fetch_products = $select_sanitaire->fetch(PDO::FETCH_ASSOC)) {
        ?>

        <form action="" method="post" class="product-card">
            <input type="hidden" name="pid" value="<?= htmlspecialchars($fetch_products['id'], ENT_QUOTES, 'UTF-8'); ?>">
            <input type="hidden" name="name" value="<?= htmlspecialchars($fetch_products['name'], ENT_QUOTES, 'UTF-8'); ?>">
            <input type="hidden" name="price" value="<?= htmlspecialchars($fetch_products['price'], ENT_QUOTES, 'UTF-8'); ?>">
            <input type="hidden" name="image" value="<?= htmlspecialchars($fetch_products['image_01'], ENT_QUOTES, 'UTF-8'); ?>">

            <button type="submit" name="add_to_wishlist" class="fas fa-heart wishlist-btn"></button>
            <a href="quick_view.php?pid=<?= htmlspecialchars($fetch_products['id'], ENT_QUOTES, 'UTF-8'); ?>" class="fas fa-eye quick-view-btn"></a>
            <img src="uploaded_img/<?= htmlspecialchars($fetch_products['image_01'], ENT_QUOTES, 'UTF-8'); ?>" class="product-image" alt="">
            <div class="name"><?= htmlspecialchars($fetch_products['name'], ENT_QUOTES, 'UTF-8'); ?></div>
            <div class="flex">
                <div class="price"><span><?= htmlspecialchars($fetch_products['price'], ENT_QUOTES, 'UTF-8'); ?></span> Fcfa</div>
                <input type="number" name="qty" class="qty" min="1" max="99" value="1" onkeypress="if(this.value.length==2) return false;">
            </div>
            <input type="submit" value="Ajouter au panier" name="add_to_cart" class="btn add-to-cart-btn">
        </form>

        <?php
                }
            } else {
                echo '<p class="empty">Aucun produit sanitaire pour le moment !</p>';
            }
        ?>

    </div>
</section>



    <?php
        include 'components/footer.php';
    ?>

    <script src="https://cdn.jsdelivr.net/npm/swiper@8/swiper-bundle.min.js"></script>

    <!-- lien fichier JS personnalisé -->
    <script src="js/script.js"></script>

    <script>
        var swiper = new Swiper(".category-slider", {
        loop: true,
        grabCursor: true,
        spaceBetween: 20,
        pagination: {
            el: ".swiper-pagination",
            },
        breakpoints: {
            0: {
                slidesPerView: 2,
            },
            640: {
                slidesPerView: 3,
            },
            768: {
                slidesPerView: 4,
            },
            1024: {
                slidesPerView: 5,
            },
        },
        });

        var swiper = new Swiper(".home-slider", {
        loop: true,
        grabCursor: true,
        pagination: {
            el: ".swiper-pagination",
            },
        });

        var featuredSwiper = new Swiper(".featured-slider", {
            loop: false,
            grabCursor: true,
            spaceBetween: 16,
            pagination: {
                el: ".swiper-pagination",
                clickable: true,
            },
            breakpoints: {
                0: { slidesPerView: 1.1 },
                640: { slidesPerView: 2.1 },
                900: { slidesPerView: 3.1 },
                1200: { slidesPerView: 4 },
            },
        });

        var swiper = new Swiper(".product-slider", {
        loop: true,
        grabCursor: true,
        spaceBetween: 20,
        pagination: {
            el: ".swiper-pagination",
            },
        breakpoints: {
            0: {
                slidesPerView: 1,
            },
            640: {
                slidesPerView: 2,
            },
            768: {
                slidesPerView: 3,
            },
            1024: {
                slidesPerView: 4,
            },
        },
        });
    </script>

</body>
</html>

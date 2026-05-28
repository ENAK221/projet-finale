<?php
    include 'components/connect.php';
    session_start();

    if(isset($_SESSION['user_id'])) {
        $user_id = $_SESSION['user_id'];
    } else {
        $user_id = '';
    }

    include 'components/wishlist_cart.php';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accueil — ToolBiTrading_Sarl</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@8/swiper-bundle.min.css">
    <!-- lien CDN Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css">
    <!-- lien fichier CSS personnalisé -->
    <link rel="stylesheet" href="css/style.css">

    <style>
    /* ── VARIABLES ── */
    :root {
        --blue:    #2563eb;
        --blue-d:  #1d4ed8;
        --indigo:  #4f46e5;
        --orange:  #f59e0b;
        --dark:    #0f172a;
        --gray:    #64748b;
        --light:   #f8fafc;
        --white:   #ffffff;
        --radius:  14px;
        --shadow:  0 4px 24px rgba(15,23,42,.08);
        --shadow-h:0 12px 36px rgba(37,99,235,.18);
    }

    * { margin:0; padding:0; box-sizing:border-box; }
    body { font-family:'Segoe UI',system-ui,-apple-system,sans-serif; background:var(--light); color:var(--dark); }

    /* ── MESSAGE SUCCESS ── */
    .toast-success {
        position:fixed; top:80px; right:1.5rem; z-index:999;
        background:#ecfdf5; color:#065f46; border:1px solid #6ee7b7;
        border-radius:10px; padding:.85rem 1.4rem; font-size:.92rem;
        font-weight:600; box-shadow:var(--shadow);
        display:flex; align-items:center; gap:.6rem;
        animation: slideIn .35s ease;
    }
    @keyframes slideIn { from{opacity:0;transform:translateX(60px)} to{opacity:1;transform:translateX(0)} }

    /* ── HERO ── */
    .hero {
        background: linear-gradient(135deg, #0f172a 0%, #1e3a8a 50%, #1d4ed8 100%);
        padding: 5rem 4rem 4rem;
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 3rem;
        align-items: center;
        position: relative;
        overflow: hidden;
    }
    .hero::before {
        content:'';
        position:absolute; inset:0;
        background: radial-gradient(ellipse at 70% 50%, rgba(99,102,241,.25) 0%, transparent 60%);
        pointer-events:none;
    }
    .hero-promo {
        display:inline-flex; align-items:center; gap:.5rem;
        background:rgba(245,158,11,.15); color:var(--orange);
        border:1px solid rgba(245,158,11,.4);
        border-radius:20px; padding:.3rem .9rem;
        font-size:.8rem; font-weight:700; letter-spacing:.8px;
        text-transform:uppercase; margin-bottom:1.2rem;
    }
    .hero-promo i { font-size:.75rem; }
    .hero h1 {
        font-size:3rem; font-weight:800; color:#fff;
        line-height:1.15; margin-bottom:1.2rem;
    }
    .hero h1 span { color:var(--orange); }
    .hero-sub { font-size:1.05rem; color:#94a3b8; line-height:1.7; margin-bottom:2rem; }
    .hero-btns { display:flex; gap:.8rem; flex-wrap:wrap; margin-bottom:2rem; }
    .btn-hero-primary {
        background:var(--blue); color:#fff;
        padding:.85rem 1.8rem; border-radius:10px;
        font-weight:700; font-size:.95rem;
        text-decoration:none; display:inline-flex; align-items:center; gap:.5rem;
        transition:background .2s, transform .15s;
        box-shadow:0 4px 15px rgba(37,99,235,.4);
    }
    .btn-hero-primary:hover { background:var(--blue-d); transform:translateY(-2px); }
    .btn-hero-ghost {
        background:rgba(255,255,255,.1); color:#fff;
        border:1px solid rgba(255,255,255,.25);
        padding:.85rem 1.8rem; border-radius:10px;
        font-weight:600; font-size:.95rem;
        text-decoration:none; display:inline-flex; align-items:center; gap:.5rem;
        transition:background .2s, transform .15s;
    }
    .btn-hero-ghost:hover { background:rgba(255,255,255,.2); transform:translateY(-2px); }
    .hero-stats { display:flex; gap:2rem; }
    .hero-stat { text-align:center; }
    .hero-stat strong { display:block; font-size:1.6rem; font-weight:800; color:#fff; }
    .hero-stat span { font-size:.78rem; color:#94a3b8; }
    .hero-img-wrap { position:relative; }
    .hero-img-wrap img {
        width:100%; max-height:400px; object-fit:cover;
        border-radius:20px;
        box-shadow:0 24px 60px rgba(0,0,0,.4);
        border:3px solid rgba(255,255,255,.08);
    }
    .hero-badge-float {
        position:absolute; bottom:-16px; left:-16px;
        background:#fff; border-radius:14px;
        padding:.8rem 1.2rem; box-shadow:var(--shadow-h);
        display:flex; align-items:center; gap:.7rem;
    }
    .hero-badge-float .badge-icon {
        width:40px; height:40px; background:#ecfdf5;
        border-radius:10px; display:flex; align-items:center; justify-content:center;
        color:#059669; font-size:1.1rem;
    }
    .hero-badge-float strong { display:block; font-size:.88rem; font-weight:700; color:var(--dark); }
    .hero-badge-float span { font-size:.75rem; color:var(--gray); }

    /* ── FEATURES BAR ── */
    .features-bar {
        background:#fff; padding:2rem 4rem;
        display:grid; grid-template-columns:repeat(4,1fr);
        gap:1.5rem; border-bottom:1px solid #e2e8f0;
    }
    .feature-item {
        display:flex; align-items:center; gap:1rem;
        padding:1rem; border-radius:12px;
        transition:background .2s;
    }
    .feature-item:hover { background:var(--light); }
    .feature-icon {
        width:46px; height:46px; border-radius:12px;
        display:flex; align-items:center; justify-content:center;
        font-size:1.15rem; flex-shrink:0;
    }
    .fi-blue  { background:#eff6ff; color:var(--blue); }
    .fi-green { background:#ecfdf5; color:#059669; }
    .fi-orange{ background:#fffbeb; color:var(--orange); }
    .fi-purple{ background:#f5f3ff; color:var(--indigo); }
    .feature-item h4 { font-size:.88rem; font-weight:700; color:var(--dark); margin-bottom:.15rem; }
    .feature-item p  { font-size:.78rem; color:var(--gray); }

    /* ── SECTION HEADER ── */
    .sec-wrap { max-width:1300px; margin:0 auto; padding:3.5rem 2rem; }
    .sec-head {
        display:flex; align-items:flex-end; justify-content:space-between;
        margin-bottom:2rem;
    }
    .sec-eyebrow {
        font-size:.75rem; font-weight:700; color:var(--blue);
        text-transform:uppercase; letter-spacing:1.5px; margin-bottom:.3rem;
    }
    .sec-head h2 { font-size:1.8rem; font-weight:800; color:var(--dark); }
    .sec-link {
        color:var(--blue); font-size:.88rem; font-weight:600;
        text-decoration:none; display:flex; align-items:center; gap:.35rem;
        white-space:nowrap;
    }
    .sec-link:hover { color:var(--blue-d); }

    /* ── PRODUCT CARDS ── */
    .prod-card {
        background:#fff; border-radius:var(--radius);
        overflow:hidden; border:1px solid #e8edf5;
        box-shadow:var(--shadow);
        transition:transform .2s, box-shadow .2s;
        display:flex; flex-direction:column;
        position:relative;
    }
    .prod-card:hover { transform:translateY(-5px); box-shadow:var(--shadow-h); border-color:#c7d9ff; }
    .prod-card .card-media { position:relative; overflow:hidden; }
    .prod-card .card-media img {
        width:100%; height:200px; object-fit:cover;
        transition:transform .35s;
    }
    .prod-card:hover .card-media img { transform:scale(1.06); }
    .prod-card .card-actions-overlay {
        position:absolute; top:.7rem; right:.7rem;
        display:flex; flex-direction:column; gap:.4rem;
    }
    .icon-action {
        width:34px; height:34px; background:#fff;
        border:none; border-radius:8px; cursor:pointer;
        display:flex; align-items:center; justify-content:center;
        color:var(--gray); font-size:.88rem;
        box-shadow:0 2px 8px rgba(0,0,0,.12);
        text-decoration:none;
        transition:background .2s, color .2s;
    }
    .icon-action:hover { background:var(--blue); color:#fff; }
    .prod-card .card-body { padding:1rem 1.1rem 1.3rem; flex:1; display:flex; flex-direction:column; }
    .prod-card .prod-name { font-size:.92rem; font-weight:700; color:var(--dark); margin-bottom:.4rem; }
    .prod-card .prod-price { font-size:1.15rem; font-weight:800; color:var(--blue); margin-bottom:.8rem; }
    .prod-card .prod-price span { font-size:.75rem; font-weight:500; color:var(--gray); }
    .prod-card .card-buy {
        display:flex; align-items:center; gap:.5rem; margin-top:auto;
    }
    .qty-input {
        width:52px; padding:.4rem .5rem; border:1px solid #e2e8f0;
        border-radius:8px; font-size:.85rem; text-align:center; color:var(--dark);
    }
    .btn-cart {
        flex:1; background:var(--blue); color:#fff;
        border:none; border-radius:8px; padding:.5rem .8rem;
        font-size:.82rem; font-weight:700; cursor:pointer;
        transition:background .2s; text-align:center;
    }
    .btn-cart:hover { background:var(--blue-d); }

    /* ── FEATURED SWIPER ── */
    .featured-wrap { position:relative; }
    .featured-wrap .swiper-slide { width:240px; }
    .featured-wrap .prod-card .card-media img { height:180px; }

    /* ── GRILLE PRODUITS ── */
    .prods-grid {
        display:grid;
        grid-template-columns:repeat(auto-fill, minmax(210px, 1fr));
        gap:1.3rem;
    }

    /* ── CATÉGORIES ── */
    .cats-grid {
        display:grid;
        grid-template-columns:repeat(4,1fr);
        gap:1.2rem;
    }
    .cat-card {
        background:#fff; border-radius:var(--radius);
        overflow:hidden; text-decoration:none;
        border:1px solid #e8edf5; box-shadow:var(--shadow);
        transition:transform .2s, box-shadow .2s;
        display:flex; flex-direction:column;
    }
    .cat-card:hover { transform:translateY(-4px); box-shadow:var(--shadow-h); }
    .cat-img {
        height:130px; display:flex; align-items:center; justify-content:center;
        padding:1.2rem;
    }
    .cat-img img { max-height:100px; max-width:100%; object-fit:contain; }
    .cat-blue   { background:linear-gradient(135deg,#eff6ff,#dbeafe); }
    .cat-green  { background:linear-gradient(135deg,#ecfdf5,#d1fae5); }
    .cat-purple { background:linear-gradient(135deg,#f5f3ff,#ede9fe); }
    .cat-orange { background:linear-gradient(135deg,#fffbeb,#fef3c7); }
    .cat-body { padding:1rem 1.1rem 1.2rem; }
    .cat-body h3 { font-size:.95rem; font-weight:700; color:var(--dark); margin-bottom:.25rem; }
    .cat-body p  { font-size:.78rem; color:var(--gray); line-height:1.5; }

    /* ── SECTION BG ALT ── */
    .sec-alt { background:#fff; }

    /* ── VIDE ── */
    .empty-msg { text-align:center; color:var(--gray); padding:3rem 1rem; font-size:.95rem; }

    /* ── RESPONSIVE ── */
    @media(max-width:1024px){
        .hero { padding:3rem 2rem; }
        .features-bar { grid-template-columns:repeat(2,1fr); padding:1.5rem 2rem; }
        .cats-grid { grid-template-columns:repeat(2,1fr); }
    }
    @media(max-width:768px){
        .hero { grid-template-columns:1fr; padding:2.5rem 1.5rem; }
        .hero-img-wrap { display:none; }
        .hero h1 { font-size:2rem; }
        .hero-stats { gap:1.2rem; }
        .features-bar { grid-template-columns:1fr 1fr; padding:1.2rem; }
        .sec-wrap { padding:2.5rem 1rem; }
        .prods-grid { grid-template-columns:repeat(2,1fr); }
    }
    @media(max-width:480px){
        .features-bar { grid-template-columns:1fr; }
        .cats-grid { grid-template-columns:1fr 1fr; }
        .hero-btns { flex-direction:column; }
    }
    </style>
</head>
<body>

<?php include 'components/user_header.php'; ?>

<?php if (isset($_SESSION['success_message'])): ?>
<div class="toast-success">
    <i class="fas fa-check-circle"></i>
    <?= htmlspecialchars($_SESSION['success_message'], ENT_QUOTES, 'UTF-8'); ?>
</div>
<?php unset($_SESSION['success_message']); endif; ?>

<!-- ── HERO ── -->
<section class="hero">
    <div class="hero-content">
        <div class="hero-promo"><i class="fas fa-bolt"></i> Promo du jour</div>
        <h1>Faites vos courses<br>avec <span>ToolBiTrading</span></h1>
        <p class="hero-sub">Produits frais, sanitaires et boissons — sélection premium, livraison rapide à Dakar, paiement sécurisé.</p>
        <div class="hero-btns">
            <a href="shop.php" class="btn-hero-primary"><i class="fas fa-shopping-bag"></i> Acheter maintenant</a>
            <a href="category.php" class="btn-hero-ghost"><i class="fas fa-th-large"></i> Nos catégories</a>
        </div>
        <div class="hero-stats">
            <div class="hero-stat"><strong>+500</strong><span>Produits</span></div>
            <div class="hero-stat"><strong>24-48h</strong><span>Livraison</span></div>
            <div class="hero-stat"><strong>100%</strong><span>Sécurisé</span></div>
        </div>
    </div>
    <div class="hero-img-wrap">
        <img src="images/bgg.jpg" alt="ToolBiTrading boutique">
        <div class="hero-badge-float">
            <div class="badge-icon"><i class="fas fa-truck"></i></div>
            <div><strong>Livraison rapide</strong><span>Dakar & environs</span></div>
        </div>
    </div>
</section>

<!-- ── FEATURES BAR ── -->
<div class="features-bar">
    <div class="feature-item">
        <div class="feature-icon fi-blue"><i class="fas fa-shipping-fast"></i></div>
        <div><h4>Livraison rapide</h4><p>24 à 48h dans Dakar</p></div>
    </div>
    <div class="feature-item">
        <div class="feature-icon fi-green"><i class="fas fa-shield-alt"></i></div>
        <div><h4>Paiement sécurisé</h4><p>Vos données protégées</p></div>
    </div>
    <div class="feature-item">
        <div class="feature-icon fi-orange"><i class="fas fa-star"></i></div>
        <div><h4>Qualité premium</h4><p>Produits sélectionnés</p></div>
    </div>
    <div class="feature-item">
        <div class="feature-icon fi-purple"><i class="fas fa-headset"></i></div>
        <div><h4>Support 7j/7</h4><p>+221 76 740 92 95</p></div>
    </div>
</div>

<!-- ── PRODUITS EN VEDETTE ── -->
<div class="sec-wrap">
    <div class="sec-head">
        <div>
            <p class="sec-eyebrow">En vedette</p>
            <h2>Produits populaires</h2>
        </div>
        <a href="shop.php" class="sec-link">Voir tout <i class="fas fa-arrow-right"></i></a>
    </div>

    <div class="featured-wrap">
        <div class="swiper featured-slider">
            <div class="swiper-wrapper">
                <?php
                $select_featured = $conn->prepare("SELECT * FROM `products` ORDER BY id DESC LIMIT 10");
                $select_featured->execute();
                while($featured = $select_featured->fetch(PDO::FETCH_ASSOC)):
                ?>
                <form action="" method="post" class="swiper-slide">
                    <div class="prod-card">
                        <input type="hidden" name="pid"   value="<?= htmlspecialchars($featured['id'],       ENT_QUOTES,'UTF-8'); ?>">
                        <input type="hidden" name="name"  value="<?= htmlspecialchars($featured['name'],     ENT_QUOTES,'UTF-8'); ?>">
                        <input type="hidden" name="price" value="<?= htmlspecialchars($featured['price'],    ENT_QUOTES,'UTF-8'); ?>">
                        <input type="hidden" name="image" value="<?= htmlspecialchars($featured['image_01'],ENT_QUOTES,'UTF-8'); ?>">
                        <div class="card-media">
                            <img src="uploaded_img/<?= htmlspecialchars($featured['image_01'],ENT_QUOTES,'UTF-8'); ?>" alt="<?= htmlspecialchars($featured['name'],ENT_QUOTES,'UTF-8'); ?>">
                            <div class="card-actions-overlay">
                                <button type="submit" name="add_to_wishlist" class="icon-action"><i class="fas fa-heart"></i></button>
                                <a href="quick_view.php?pid=<?= htmlspecialchars($featured['id'],ENT_QUOTES,'UTF-8'); ?>" class="icon-action"><i class="fas fa-eye"></i></a>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="prod-name"><?= htmlspecialchars($featured['name'],ENT_QUOTES,'UTF-8'); ?></div>
                            <div class="prod-price"><?= htmlspecialchars($featured['price'],ENT_QUOTES,'UTF-8'); ?> <span>Fcfa</span></div>
                            <div class="card-buy">
                                <input type="number" name="qty" class="qty-input" min="1" max="99" value="1">
                                <button type="submit" name="add_to_cart" class="btn-cart">Ajouter</button>
                            </div>
                        </div>
                    </div>
                </form>
                <?php endwhile; ?>
            </div>
            <div class="swiper-pagination" style="margin-top:.8rem;position:static;"></div>
        </div>
    </div>
</div>

<!-- ── CATÉGORIES ── -->
<div class="sec-alt">
    <div class="sec-wrap">
        <div class="sec-head">
            <div>
                <p class="sec-eyebrow">Catégories</p>
                <h2>Trouvez votre produit</h2>
            </div>
            <a href="category.php" class="sec-link">Toutes les catégories <i class="fas fa-arrow-right"></i></a>
        </div>
        <div class="cats-grid">
            <a href="category.php?category=eau" class="cat-card">
                <div class="cat-img cat-blue"><img src="images/eau.png" alt="Eau"></div>
                <div class="cat-body"><h3>Eaux</h3><p>Hydratation premium pour toute la famille.</p></div>
            </a>
            <a href="category.php?category=oeuf" class="cat-card">
                <div class="cat-img cat-green"><img src="images/oeuf.png" alt="Oeufs"></div>
                <div class="cat-body"><h3>Oeufs</h3><p>Frais, qualité supérieure, en stock.</p></div>
            </a>
            <a href="category.php?category=savon" class="cat-card">
                <div class="cat-img cat-purple"><img src="images/savon.png" alt="Savon"></div>
                <div class="cat-body"><h3>Savons</h3><p>Hygiène et soins corporels au quotidien.</p></div>
            </a>
            <a href="category.php?category=boisson" class="cat-card">
                <div class="cat-img cat-orange"><img src="images/fanta.png" alt="Boissons"></div>
                <div class="cat-body"><h3>Boissons</h3><p>Rafraîchissantes pour toute la famille.</p></div>
            </a>
        </div>
    </div>
</div>

<!-- ── TOUS LES PRODUITS ── -->
<div class="sec-wrap">
    <div class="sec-head">
        <div>
            <p class="sec-eyebrow">Catalogue</p>
            <h2>Tous nos produits</h2>
        </div>
        <a href="shop.php" class="sec-link">Voir tout <i class="fas fa-arrow-right"></i></a>
    </div>
    <div class="prods-grid">
        <?php
        $select_products = $conn->prepare("SELECT * FROM `products` LIMIT 8");
        $select_products->execute();
        if($select_products->rowCount() > 0) {
            while($p = $select_products->fetch(PDO::FETCH_ASSOC)):
        ?>
        <form action="" method="post" class="prod-card">
            <input type="hidden" name="pid"   value="<?= htmlspecialchars($p['id'],       ENT_QUOTES,'UTF-8'); ?>">
            <input type="hidden" name="name"  value="<?= htmlspecialchars($p['name'],     ENT_QUOTES,'UTF-8'); ?>">
            <input type="hidden" name="price" value="<?= htmlspecialchars($p['price'],    ENT_QUOTES,'UTF-8'); ?>">
            <input type="hidden" name="image" value="<?= htmlspecialchars($p['image_01'],ENT_QUOTES,'UTF-8'); ?>">
            <div class="card-media">
                <img src="uploaded_img/<?= htmlspecialchars($p['image_01'],ENT_QUOTES,'UTF-8'); ?>" alt="">
                <div class="card-actions-overlay">
                    <button type="submit" name="add_to_wishlist" class="icon-action"><i class="fas fa-heart"></i></button>
                    <a href="quick_view.php?pid=<?= htmlspecialchars($p['id'],ENT_QUOTES,'UTF-8'); ?>" class="icon-action"><i class="fas fa-eye"></i></a>
                </div>
            </div>
            <div class="card-body">
                <div class="prod-name"><?= htmlspecialchars($p['name'],ENT_QUOTES,'UTF-8'); ?></div>
                <div class="prod-price"><?= htmlspecialchars($p['price'],ENT_QUOTES,'UTF-8'); ?> <span>Fcfa</span></div>
                <div class="card-buy">
                    <input type="number" name="qty" class="qty-input" min="1" max="99" value="1" onkeypress="if(this.value.length==2) return false;">
                    <button type="submit" name="add_to_cart" class="btn-cart">Ajouter</button>
                </div>
            </div>
        </form>
        <?php endwhile; } else { echo '<p class="empty-msg">Aucun produit pour le moment.</p>'; } ?>
    </div>
</div>

<!-- ── SANITAIRE ── -->
<div class="sec-alt">
    <div class="sec-wrap">
        <div class="sec-head">
            <div>
                <p class="sec-eyebrow">Hygiène</p>
                <h2>Produits sanitaires</h2>
            </div>
            <a href="category.php?category=sanitaire" class="sec-link">Voir tout <i class="fas fa-arrow-right"></i></a>
        </div>
        <div class="prods-grid">
            <?php
            $select_sanitaire = $conn->prepare("SELECT * FROM `products` WHERE category = 'sanitaire' LIMIT 8");
            $select_sanitaire->execute();
            if($select_sanitaire->rowCount() > 0) {
                while($p = $select_sanitaire->fetch(PDO::FETCH_ASSOC)):
            ?>
            <form action="" method="post" class="prod-card">
                <input type="hidden" name="pid"   value="<?= htmlspecialchars($p['id'],       ENT_QUOTES,'UTF-8'); ?>">
                <input type="hidden" name="name"  value="<?= htmlspecialchars($p['name'],     ENT_QUOTES,'UTF-8'); ?>">
                <input type="hidden" name="price" value="<?= htmlspecialchars($p['price'],    ENT_QUOTES,'UTF-8'); ?>">
                <input type="hidden" name="image" value="<?= htmlspecialchars($p['image_01'],ENT_QUOTES,'UTF-8'); ?>">
                <div class="card-media">
                    <img src="uploaded_img/<?= htmlspecialchars($p['image_01'],ENT_QUOTES,'UTF-8'); ?>" alt="">
                    <div class="card-actions-overlay">
                        <button type="submit" name="add_to_wishlist" class="icon-action"><i class="fas fa-heart"></i></button>
                        <a href="quick_view.php?pid=<?= htmlspecialchars($p['id'],ENT_QUOTES,'UTF-8'); ?>" class="icon-action"><i class="fas fa-eye"></i></a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="prod-name"><?= htmlspecialchars($p['name'],ENT_QUOTES,'UTF-8'); ?></div>
                    <div class="prod-price"><?= htmlspecialchars($p['price'],ENT_QUOTES,'UTF-8'); ?> <span>Fcfa</span></div>
                    <div class="card-buy">
                        <input type="number" name="qty" class="qty-input" min="1" max="99" value="1" onkeypress="if(this.value.length==2) return false;">
                        <button type="submit" name="add_to_cart" class="btn-cart">Ajouter</button>
                    </div>
                </div>
            </form>
            <?php endwhile; } else { echo '<p class="empty-msg">Aucun produit sanitaire pour le moment.</p>'; } ?>
        </div>
    </div>
</div>

<?php include 'components/footer.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/swiper@8/swiper-bundle.min.js"></script>
<!-- lien fichier JS personnalisé -->
<script src="js/script.js"></script>

<script>
    new Swiper(".featured-slider", {
        loop: false,
        grabCursor: true,
        spaceBetween: 16,
        pagination: { el: ".swiper-pagination", clickable: true },
        breakpoints: {
            0:    { slidesPerView: 1.2 },
            480:  { slidesPerView: 2.1 },
            768:  { slidesPerView: 3.1 },
            1200: { slidesPerView: 4   },
        },
    });

    /* auto-hide toast */
    const toast = document.querySelector('.toast-success');
    if(toast) setTimeout(() => { toast.style.opacity='0'; toast.style.transition='opacity .5s'; setTimeout(()=>toast.remove(),500); }, 4000);
</script>
</body>
</html>

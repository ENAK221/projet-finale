<?php
include '../components/connect.php';

session_start();

$livreur_id = $_SESSION['livreur_id'] ?? '';

if (empty($livreur_id)) {
    header('Location: ../admin/admin_login.php');
    exit;
}

$select_orders = $conn->prepare("SELECT * FROM `orders` WHERE payment_status = 'En cours'");
$select_orders->execute();
$total_orders = $select_orders->rowCount();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Espace Livreur — ToolBiTrading</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
            background: #f0f4ff;
            color: #1e293b;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* ── NAVBAR ── */
        .navbar {
            background: linear-gradient(135deg, #1a56db 0%, #0e3fa3 100%);
            padding: 0 2rem;
            height: 68px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0 4px 20px rgba(26,86,219,.35);
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .navbar-brand {
            display: flex;
            align-items: center;
            gap: .75rem;
        }

        .navbar-brand img {
            height: 40px;
            border-radius: 8px;
            background: #fff;
            padding: 3px;
        }

        .navbar-brand span {
            color: #fff;
            font-size: 1.1rem;
            font-weight: 700;
            letter-spacing: .3px;
        }

        .navbar-right {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .badge-orders {
            background: rgba(255,255,255,.18);
            color: #fff;
            border: 1px solid rgba(255,255,255,.3);
            border-radius: 20px;
            padding: .3rem .9rem;
            font-size: .82rem;
            font-weight: 600;
        }

        .badge-orders i { margin-right: .4rem; color: #fbbf24; }

        .logout-btn {
            background: rgba(255,255,255,.15);
            color: #fff;
            border: 1px solid rgba(255,255,255,.4);
            border-radius: 8px;
            padding: .45rem 1.1rem;
            font-size: .88rem;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: .4rem;
            transition: background .2s, transform .15s;
        }

        .logout-btn:hover {
            background: rgba(255,255,255,.28);
            transform: translateY(-1px);
        }

        /* ── HERO BANNER ── */
        .hero-banner {
            background: linear-gradient(120deg, #1a56db 0%, #1e40af 60%, #0e3fa3 100%);
            color: #fff;
            padding: 2.2rem 2.5rem;
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .hero-banner .hero-icon {
            width: 58px;
            height: 58px;
            background: rgba(255,255,255,.15);
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.7rem;
            flex-shrink: 0;
        }

        .hero-banner h1 {
            font-size: 1.6rem;
            font-weight: 800;
            margin-bottom: .2rem;
        }

        .hero-banner p {
            font-size: .92rem;
            opacity: .8;
        }

        /* ── MAIN ── */
        main {
            flex: 1;
            padding: 2rem 2.5rem;
            max-width: 1300px;
            width: 100%;
            margin: 0 auto;
        }

        .section-title {
            font-size: 1rem;
            font-weight: 700;
            color: #475569;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 1.4rem;
            display: flex;
            align-items: center;
            gap: .5rem;
        }

        .section-title::after {
            content: '';
            flex: 1;
            height: 1px;
            background: #e2e8f0;
        }

        /* ── CARDS GRID ── */
        .cards-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 1.4rem;
        }

        .card {
            background: #fff;
            border-radius: 16px;
            padding: 1.5rem;
            box-shadow: 0 2px 12px rgba(0,0,0,.07);
            border: 1px solid #e8edf5;
            transition: transform .2s, box-shadow .2s;
            display: flex;
            flex-direction: column;
            gap: .85rem;
        }

        .card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 28px rgba(26,86,219,.14);
            border-color: #c7d9ff;
        }

        .card-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .order-number {
            font-size: .78rem;
            font-weight: 700;
            color: #6366f1;
            background: #eef2ff;
            border-radius: 8px;
            padding: .25rem .7rem;
            letter-spacing: .5px;
        }

        .status-badge {
            font-size: .75rem;
            font-weight: 700;
            color: #d97706;
            background: #fffbeb;
            border: 1px solid #fcd34d;
            border-radius: 20px;
            padding: .2rem .75rem;
            display: flex;
            align-items: center;
            gap: .3rem;
        }

        .status-badge::before {
            content: '';
            width: 7px;
            height: 7px;
            background: #f59e0b;
            border-radius: 50%;
            animation: pulse 1.5s infinite;
        }

        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: .35; }
        }

        .client-name {
            font-size: 1.15rem;
            font-weight: 700;
            color: #1e293b;
            display: flex;
            align-items: center;
            gap: .5rem;
        }

        .client-name i {
            width: 32px;
            height: 32px;
            background: #e0e7ff;
            color: #4f46e5;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: .9rem;
        }

        .card-info {
            display: flex;
            flex-direction: column;
            gap: .5rem;
        }

        .info-row {
            display: flex;
            align-items: center;
            gap: .6rem;
            font-size: .88rem;
            color: #475569;
        }

        .info-row i {
            width: 26px;
            text-align: center;
            color: #94a3b8;
            font-size: .85rem;
        }

        .info-row strong {
            color: #1e293b;
            font-weight: 600;
        }

        .divider {
            height: 1px;
            background: #f1f5f9;
        }

        .card-footer {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding-top: .3rem;
        }

        .price-tag {
            font-size: 1.2rem;
            font-weight: 800;
            color: #1a56db;
        }

        .price-tag span {
            font-size: .75rem;
            font-weight: 500;
            color: #94a3b8;
            margin-left: .2rem;
        }

        .btn-details {
            background: linear-gradient(135deg, #1a56db, #4f46e5);
            color: #fff;
            border-radius: 10px;
            padding: .55rem 1.2rem;
            font-size: .85rem;
            font-weight: 700;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: .4rem;
            transition: opacity .2s, transform .15s;
            box-shadow: 0 3px 10px rgba(26,86,219,.3);
        }

        .btn-details:hover {
            opacity: .9;
            transform: translateY(-1px);
        }

        /* ── EMPTY STATE ── */
        .empty-state {
            grid-column: 1 / -1;
            text-align: center;
            padding: 4rem 2rem;
            color: #94a3b8;
        }

        .empty-state .empty-icon {
            font-size: 4rem;
            margin-bottom: 1rem;
            opacity: .4;
        }

        .empty-state h2 {
            font-size: 1.2rem;
            font-weight: 700;
            color: #64748b;
            margin-bottom: .4rem;
        }

        .empty-state p {
            font-size: .9rem;
        }

        /* ── FOOTER ── */
        footer {
            background: #0f172a;
            color: #94a3b8;
            margin-top: auto;
        }

        .footer-inner {
            max-width: 1300px;
            margin: 0 auto;
            padding: 2.5rem 2.5rem 1.5rem;
            display: grid;
            grid-template-columns: auto 1fr auto;
            gap: 2rem;
            align-items: start;
        }

        .footer-logo img {
            height: 44px;
            border-radius: 8px;
            background: #fff;
            padding: 4px;
        }

        .footer-contact h3,
        .footer-social h3 {
            color: #e2e8f0;
            font-size: .9rem;
            font-weight: 700;
            margin-bottom: .7rem;
            text-transform: uppercase;
            letter-spacing: .8px;
        }

        .footer-contact p {
            font-size: .83rem;
            line-height: 1.7;
        }

        .footer-social {
            display: flex;
            flex-direction: column;
            gap: .5rem;
        }

        .footer-social a {
            color: #94a3b8;
            text-decoration: none;
            font-size: .85rem;
            display: flex;
            align-items: center;
            gap: .5rem;
            transition: color .2s;
        }

        .footer-social a:hover { color: #60a5fa; }

        .footer-social a i {
            width: 28px;
            height: 28px;
            background: #1e293b;
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: .8rem;
        }

        .footer-bottom {
            border-top: 1px solid #1e293b;
            padding: 1rem 2.5rem;
            text-align: center;
            font-size: .8rem;
            max-width: 1300px;
            margin: 0 auto;
        }

        /* ── RESPONSIVE ── */
        @media (max-width: 640px) {
            .navbar { padding: 0 1rem; }
            .navbar-brand span { display: none; }
            main { padding: 1.2rem 1rem; }
            .hero-banner { padding: 1.5rem 1rem; }
            .footer-inner { grid-template-columns: 1fr; }
        }
    </style>
</head>

<body>

<!-- NAVBAR -->
<nav class="navbar">
    <div class="navbar-brand">
        <img src="../images/logo1.png" alt="Logo">
        <span>Espace Livreur</span>
    </div>
    <div class="navbar-right">
        <div class="badge-orders">
            <i class="fas fa-box"></i><?= $total_orders ?> commande<?= $total_orders > 1 ? 's' : '' ?>
        </div>
        <a href="#" id="logout-btn" class="logout-btn">
            <i class="fas fa-sign-out-alt"></i> Déconnexion
        </a>
    </div>
</nav>

<!-- HERO -->
<div class="hero-banner">
    <div class="hero-icon"><i class="fas fa-motorcycle"></i></div>
    <div>
        <h1>Tableau de bord livraison</h1>
        <p>Gérez vos commandes en cours et suivez vos livraisons</p>
    </div>
</div>

<!-- MAIN -->
<main>
    <div class="section-title"><i class="fas fa-clock"></i> Commandes en cours</div>

    <div class="cards-grid">
        <?php if ($total_orders > 0):
            while ($order = $select_orders->fetch(PDO::FETCH_ASSOC)): ?>

        <div class="card">
            <div class="card-header">
                <span class="order-number"># <?= htmlspecialchars($order['order_id'] ?? '') ?></span>
                <span class="status-badge">En cours</span>
            </div>

            <div class="client-name">
                <i class="fas fa-user"></i>
                <?= htmlspecialchars(($order['name'] ?? '') . ' ' . ($order['surname'] ?? '')) ?>
            </div>

            <div class="card-info">
                <div class="info-row">
                    <i class="fas fa-phone"></i>
                    <span><?= htmlspecialchars($order['phone'] ?? '') ?></span>
                </div>
                <div class="info-row">
                    <i class="fas fa-calendar-alt"></i>
                    <span>Livraison le <strong><?= htmlspecialchars($order['delivery_date'] ?? '') ?></strong></span>
                </div>
            </div>

            <div class="divider"></div>

            <div class="card-footer">
                <div class="price-tag">
                    <?= htmlspecialchars($order['total_price'] ?? '') ?><span>Fcfa</span>
                </div>
                <a href="order_details.php?order_id=<?= htmlspecialchars($order['order_id'] ?? '') ?>" class="btn-details">
                    <i class="fas fa-map-marked-alt"></i> Voir détails
                </a>
            </div>
        </div>

        <?php endwhile;
        else: ?>
        <div class="empty-state">
            <div class="empty-icon"><i class="fas fa-box-open"></i></div>
            <h2>Aucune commande en cours</h2>
            <p>Toutes les livraisons sont à jour. Revenez plus tard.</p>
        </div>
        <?php endif; ?>
    </div>
</main>

<!-- FOOTER -->
<footer>
    <div class="footer-inner">
        <div class="footer-logo">
            <img src="../images/logo1.png" alt="ToolBiTrading_SARL">
        </div>
        <div class="footer-contact">
            <h3>Contact</h3>
            <p>ToolBiTrading_SARL</p>
            <p>Dieupeul-Derklé, Dakar</p>
            <p>+221 76 740 92 95 / 76 991 41 81 / 77 110 60 76</p>
            <p>toolbitradingsarl@outlook.com</p>
        </div>
        <div class="footer-social">
            <h3>Réseaux</h3>
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
document.getElementById('logout-btn').addEventListener('click', function(e) {
    e.preventDefault();
    if (confirm('Voulez-vous vraiment vous déconnecter ?')) {
        window.location.href = '../components/livreur_logout.php';
    }
});
</script>

</body>
</html>

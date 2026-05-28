<?php
include '../components/connect.php';
session_start();

if (!isset($_SESSION['admin_id'])) {
    header('location:admin_login.php');
    exit;
}

$admin_id = $_SESSION['admin_id'];
$select_profile = $conn->prepare("SELECT name FROM admins WHERE id = ?");
$select_profile->execute([$admin_id]);
$fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page Administrateur</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css">
    <link rel="stylesheet" href="../css/admin_style.css">
</head>
<body>

<?php include '../components/admin_header.php'; ?>

<section class="dashboard">
    <h1 class="heading">Administrateur</h1>
    <div class="box-container">
        <div class="box">
            <h3>Bienvenue !</h3>
            <p id="admin_name"><?= htmlspecialchars($fetch_profile['name']); ?></p>
            <a href="update_profile.php" class="btn">Mise à jour Profile</a>
        </div>

        <div class="box">
            <h3><span></span><span id="total_pendings"></span><span> Fcfa</span></h3>
            <p>Commande En Attente</p>
            <a href="placed_orders.php" class="btn">Voir Commandes</a>
        </div>

        <div class="box">
            <h3><span></span><span id="total_completes"></span><span> Fcfa</span></h3>
            <p>Commandes Livrées</p>
            <a href="placed_orders.php" class="btn">Voir Commandes</a>
        </div>

        <div class="box">
            <h3 id="number_of_orders"></h3>
            <p>Total Commandes</p>
            <a href="placed_orders.php" class="btn">Voir Commandes</a>
        </div>

        <div class="box">
            <h3 id="number_of_products"></h3>
            <p>Produits Ajoutés</p>
            <a href="products.php" class="btn">Voir Produits</a>
        </div>

        <div class="box">
            <h3 id="number_of_users"></h3>
            <p>Comptes utilisateurs</p>
            <a href="user_accounts.php" class="btn">Voir utilisateurs</a>
        </div>

        <div class="box">
            <h3 id="number_of_admins"></h3>
            <p>Total Administrateurs</p>
            <a href="admin_accounts.php" class="btn">Voir Administrateur</a>
        </div>

        <div class="box">
            <h3 id="number_of_messages"></h3>
            <p>Nouveaux Messages</p>
            <a href="messages.php" class="btn">Voir messages</a>
        </div>
    </div>
</section>

<script>
    function fetchDashboardData() {
        fetch('fetch_dashboard_data.php')
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    alert(data.error);
                    return;
                }

                document.getElementById("admin_name").innerText = data.admin_name;
                document.getElementById("total_pendings").innerText = data.total_pendings;
                document.getElementById("total_completes").innerText = data.total_completes;
                document.getElementById("number_of_orders").innerText = data.number_of_orders;
                document.getElementById("number_of_products").innerText = data.number_of_products;
                document.getElementById("number_of_users").innerText = data.number_of_users;
                document.getElementById("number_of_admins").innerText = data.number_of_admins;
                document.getElementById("number_of_messages").innerText = data.number_of_messages;
            })
            .catch(error => console.error('Erreur lors de la récupération des données:', error));
    }

    // Actualisation automatique toutes les 5 secondes
    setInterval(fetchDashboardData, 5000);
    window.onload = fetchDashboardData;
</script>

<script src="../js/admin_script.js"></script>

</body>
</html>

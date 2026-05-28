<?php 
    if(isset($message))
    {
        foreach($message as $message)
        {
            echo '
                <div class="message">
                    <span>'.$message.'</span>
                    <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
                </div>
            ';
        }
    }
?>

<header class="header">
    <section class="flex">
        <a href="dashboard.php" class="logo">Admin<span>Panel</span></a>

        <nav class="navbar">
            <a href="dashboard.php">Accueil</a>
            <a href="products.php">Produits</a>
            <a href="placed_orders.php">Commandes</a>
            <a href="livreur.php">Livreurs</a>
            <a href="admin_accounts.php">Admins</a>
            <a href="user_accounts.php">Utilisateurs</a>
            <a href="messages.php">Messages</a>
        </nav>

        <div class="icons">
            <div id="menu-btn" class="fas fa-bars"></div>
            <div id="user-btn" class="fas fa-user"></div>

        </div>

        <div class="profile">
            <?php
                $select_profile = $conn->prepare("SELECT *FROM `admins` WHERE id = ?");
                $select_profile->execute([$admin_id]);
                $fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);
            ?>

            <p><?= $fetch_profile['name']; ?></p>

            <a href="update_profile.php" class="btn">Mise à jour Profil</a>

            <a href="../components/admin_logout.php" class="delete-btn" onclick="return confirm('Voulez-vous vraiment vous déconnecter ?');">Déconnexion</a>

        </div>

    </section>
</header>
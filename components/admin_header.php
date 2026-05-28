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
        <a href="/projet-finale/admin/dashboard.php" class="logo">Admin<span>Panel</span></a>

        <nav class="navbar">
            <a href="/projet-finale/admin/dashboard.php">Accueil</a>
            <a href="/projet-finale/admin/products.php">Produits</a>
            <a href="/projet-finale/admin/placed_orders.php">Commandes</a>
            <a href="/projet-finale/admin/livreur.php">Livreurs</a>
            <a href="/projet-finale/admin/admin_accounts.php">Admins</a>
            <a href="/projet-finale/admin/user_accounts.php">Utilisateurs</a>
            <a href="/projet-finale/admin/messages.php">Messages</a>
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

            <a href="/projet-finale/admin/update_profile.php" class="btn">Mise a Jour Profile</a>

            <a href="/projet-finale/components/admin_logout.php" class="delete-btn" onclick="return confirm('Voulez vous vraiment se deconnecter');">Deconnexion</a>

        </div>

    </section>
</header>
<header class="header">
    <section class="flex">
        <a href="index.php" class="logo">ToolBi<span>Trading</span></a>

        <nav class="navbar">
            <a href="index.php">Accueil</a>
            <a href="about.php">À propos</a>
            <a href="orders.php">Commandes</a>
            <a href="shop.php">Boutique</a>
            <a href="contact.php">Contact</a>
        </nav>

        <div class="icons">
            <a href="search_page.php" class="search-icon"><i class="fas fa-search"></i></a>
            <a href="wishlist.php" class="icon-link"><i class="fas fa-heart"></i><span><?= $total_wishlist_items ?></span></a>
            <a href="cart.php" class="icon-link"><i class="fas fa-shopping-cart"></i><span><?= $total_cart_items ?></span></a>
            <div id="user-btn" class="icon-link fas fa-user"></div>
        </div>

        <div class="profile">
            <?php
                $select_profile = $conn->prepare("SELECT * FROM `users` WHERE id = ?");
                $select_profile->execute([$user_id]);

                if($select_profile->rowCount()>0){
                    $fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);
            ?>

            <p><?= ucwords(strtolower($fetch_profile['name'] . ' ' . $fetch_profile['surname'])); ?></p>

            <a href="update_user.php" class="btn">Mise a jour profile</a>
            <a href="components/user_logout.php" class="delete-btn" onclick="return confirm('Voulez vous vraiment se deconnecter');">Deconnexion</a>

            <?php } else { ?>

            <div class="flex-btn">
                <a href="user_login.php" class="option-btn">Connexion</a>
                <a href="user_register.php" class="option-btn">Inscription</a>
            </div>

            <?php } ?>
        </div>

    </section>
</header>

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
    <title>Boutique-ToolBiTrading</title>

    <!-- lien CDN Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css">

    <!-- lien fichier CSS personnalisé -->
    <link rel="stylesheet" href="css/style.css">
    <style>
    /* Ajouter ce style dans votre section <style> ou dans votre fichier CSS */
.products .box-container {
    display: grid;
    grid-template-columns: repeat(4, 1fr); /* 4 colonnes pour les grands écrans */
    gap: 20px;
    margin-top: 20px;
}

.products .box {
    background-color: #f9f9f9;
    border: 1px solid #ddd;
    border-radius: 10px;
    padding: 15px;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    transition: transform 0.3s ease;
    text-align: center;
}

.products .box img {
    width: 100%;
    border-radius: 10px;
    height: 200px;
    object-fit: cover;
}

.products .box .name {
    font-size: 18px;
    font-weight: 600;
    margin: 10px 0;
    color: #333;
}

.products .box .price {
    font-size: 16px;
    color: #555;
    margin-bottom: 10px;
}

.products .box .btn {
    background-color: #007bff;
    color: #fff;
    border: none;
    padding: 10px;
    border-radius: 5px;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

.products .box .btn:hover {
    background-color: #0056b3;
}

.products .box .fas.fa-heart,
.products .box .fas.fa-eye {
    font-size: 20px;
    margin: 5px;
    color: #555;
}

.products .box .fas.fa-heart:hover,
.products .box .fas.fa-eye:hover {
    color: #007bff;
}

.products .box:hover {
    transform: scale(1.05);
}

/* Styles pour les petits écrans */
@media (max-width: 768px) {
    .products .box-container {
        grid-template-columns: repeat(2, 1fr); /* 2 colonnes pour les petits écrans */
    }
}

/* Styles pour les très petits écrans */
@media (max-width: 480px) {
    .products .box-container {
        grid-template-columns: 1fr; /* 1 colonne pour les très petits écrans */
    }
}


    </style>

</head>
<body>

    <?php
        include 'components/user_header.php'
    ?>


    <!-- début section boutique -->

    <section class="products">
        <h1 class="heading">Votre Boutique</h1>

        <div class="box-container">
            <?php
                $select_products = $conn->prepare("SELECT * FROM `products`");
                $select_products->execute();
                if($select_products->rowCount()>0)
                {
                    while($fetch_products = $select_products->fetch(PDO::FETCH_ASSOC))
                    {
            ?>

            <form action="" method="post" class="box">
                <input type="hidden" name="pid" value="<?= $fetch_products['id']; ?>">
                <input type="hidden" name="name" value="<?= $fetch_products['name']; ?>">
                <input type="hidden" name="price" value="<?= $fetch_products['price']; ?>">
                <input type="hidden" name="image" value="<?= $fetch_products['image_01']; ?>">

                <button type="submit" name="add_to_wishlist" class="fas fa-heart"></button>
                <a href="quick_view.php?pid=<?= $fetch_products['id']; ?>" class="fas fa-eye"></a>
                <img src="uploaded_img/<?= $fetch_products['image_01']; ?>" class="image" alt="">
                <div class="name"><?= $fetch_products['name']; ?></div>
                <div class="flex">
                    <div class="price"> <span><?= $fetch_products['price']; ?></span> Fcfa</div>
                    <input type="number" name="qty" class="qty" min="1" max="99" value="1" onkeypress="if(this.value.length==2) return false;">
                </div>
                <input type="submit" value="Ajouter au panier" name="add_to_cart" class="btn">
            </form>

            <?php
                    }
                }
                else
                {
                    echo '<p class="empty">Aucun produit ajouté pour le moment !</p>';
                }
            ?>
        </div>

    </section>








    <?php
        include 'components/footer.php';
    ?>

    <!-- lien fichier JS personnalisé -->
    <script src="js/script.js"></script>
    
</body>
</html>
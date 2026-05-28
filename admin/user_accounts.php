<?php
    include '../components/connect.php';
    session_start();

    $admin_id = $_SESSION['admin_id'];

    if(!isset($admin_id))
    {
        header('location:admin_login.php');
        exit;
    }

    if(isset($_GET['delete']))
    {
        $delete_id = $_GET['delete'];
        $delete_user = $conn->prepare("DELETE FROM `users` WHERE id=?");
        $delete_user->execute([$delete_id]);

        $delete_order = $conn->prepare("DELETE FROM `orders` WHERE user_id=?");
        $delete_order->execute([$delete_id]);

        $delete_cart = $conn->prepare("DELETE FROM `cart` WHERE id=?");
        $delete_cart->execute([$delete_id]);

        $delete_wishlist = $conn->prepare("DELETE FROM `wishlist` WHERE id=?");
        $delete_wishlist->execute([$delete_id]);

        $delete_messages = $conn->prepare("DELETE FROM `messages` WHERE id=?");
        $delete_messages->execute([$delete_id]);

        header('location:user_accounts.php');

        $message[] = 'Compte utilisateur supprimé avec succés!';
    }
?> 

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Comptes utilisateurs</title>

    <!-- lien CDN Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css">

    <!-- lien fichier CSS personnalisé -->
    <link rel="stylesheet" href="../css/admin_style.css">
</head>
<body>

<?php
    include '../components/admin_header.php'
?>

<!-- début section comptes utilisateurs -->

<section class="accounts">

    <h1 class="heading">Comptes Utilisateurs</h1>

    <div class="box-container">
      
        <?php
            $select_account = $conn->prepare("SELECT * FROM `users`");
            $select_account->execute();

            if($select_account->rowCount()>0)
            {
                while($fetch_accounts = $select_account->fetch(PDO::FETCH_ASSOC))
                {
        ?>

        <div class="box">
            <br><br>
            <p> Identifiant utilisateur: <span><?= $fetch_accounts['id']; ?></span> </p>
            <p>Nom et Prénom utilisateur: <span><?= $fetch_accounts['name'] . ' ' . $fetch_accounts['surname']; ?></span></p>
            <a href="user_accounts.php?delete=<?= $fetch_accounts['id']; ?>" class="delete-btn" onclick="return confirm('Voulez vous supprimer ce compte?');">supprimer</a>
        </div>

        <?php
                }
            }
            else
            {
                echo '<p class="empty">Aucun compte utilisateurs!</p>';
            }
        ?>
    </div>

</section>


<!-- lien fichier JS personnalisé -->
<script src="../js/admin_script.js"></script>
    
</body>
</html>
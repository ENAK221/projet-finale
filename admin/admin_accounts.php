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
        $delete_admin = $conn->prepare("DELETE FROM `admins` WHERE id=?");
        $delete_admin->execute([$delete_id]);

        header('location:admin_accounts.php');

        $message[] = 'Compte administrateur supprimé avec succès!';
    }
?> 

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Comptes Admins</title>

    <!-- lien CDN Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css">

    <!-- lien fichier CSS personnalisé -->
    <link rel="stylesheet" href="../css/admin_style.css">
</head>
<body>

<?php
    include '../components/admin_header.php'
?>

<!-- début section comptes administrateurs -->

<section class="accounts">

    <h1 class="heading">Comptes des Administrateurs</h1>

    <div class="box-container">
        <div class="box">
            <p>Inscrire un nouveau Admin</p>
            <a href="register_admin.php" class="option-btn">Inscription</a>
        </div>

        <?php
            $select_account = $conn->prepare("SELECT * FROM `admins`");
            $select_account->execute();

            if($select_account->rowCount()>0)
            {
                while($fetch_accounts = $select_account->fetch(PDO::FETCH_ASSOC))
                {
        ?>

        <div class="box">
            <br><br>
            <p> Identifiant Admin : <span><?= $fetch_accounts['id']; ?></span> </p>
            <p> Prenom Et Nom Admin : <span><?= $fetch_accounts['name']; ?></span> </p>
            <p> Email : <span><?= $fetch_accounts['email']; ?></span> </p>
            <p> Role : <span><?= $fetch_accounts['role']; ?></span> </p>

            <div class="flex-btn">
                <a href="admin_accounts.php?delete=<?= $fetch_accounts['id']; ?>" class="delete-btn" onclick="return confirm('Voulez vous supprimer ce compte?');">supprimer</a>
                <?php
                   if($fetch_accounts['id']==$admin_id) 
                   {
                        echo '<a href="update_profile.php" class="option-btn">Modifier</a>'; 
                   }
                ?>
            </div>
        </div>

        <?php
                }
            }
            else
            {
                echo '<p class="empty">Aucun compte disponible!</p>';
            }
        ?>
    </div>

</section>



<!-- lien fichier JS personnalisé -->
<script src="../js/admin_script.js"></script>
    
</body>
</html>
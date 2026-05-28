<?php
    include 'components/connect.php';

    session_start(); // Placez ceci au tout début de votre fichier

    if(isset($_SESSION['user_id'])) {
        $user_id = $_SESSION['user_id'];
        
        // Récupérer les informations de l'utilisateur
        $select_user = $conn->prepare("SELECT * FROM `users` WHERE id = ?"); // Assurez-vous que 'id' est le nom correct de la colonne
        $select_user->execute([$user_id]);
        $user_info = $select_user->fetch(PDO::FETCH_ASSOC);
        
        // Nom et prénom de l'utilisateur
        $user_name = $user_info['name'];  // Nom
        $user_surname = $user_info['surname'];  // Prénom
    } else {
        $user_id = '';
        $user_name = '';
        $user_surname = '';
    }

    if(isset($_POST['send'])) {
        $number = $_POST['number'];
        $number = filter_var($number, FILTER_SANITIZE_FULL_SPECIAL_CHARS);

        $msg = $_POST['msg'];
        $msg = filter_var($msg, FILTER_SANITIZE_FULL_SPECIAL_CHARS);

        $select_message = $conn->prepare("SELECT * FROM `messages` WHERE number=? AND message=?");
        $select_message->execute([$number, $msg]);

        if($select_message->rowCount() > 0) {
            $message[] = 'Ce message a déjà été envoyé !';
        } else {
            $send_message = $conn->prepare("INSERT INTO `messages` (user_id, name, surname, number, message) VALUES (?, ?, ?, ?, ?)");
            $send_message->execute([$user_id, $user_name, $user_surname, $number, $msg]);
            $message[] = 'Message envoyé avec succès !';
        }
    }
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact - ToolBiTrading</title>

    <!-- Font Awesome CDN link -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css">

    <!-- Custom CSS file link -->
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

    <?php
        include 'components/user_header.php';
    ?>

    <!-- Contact section starts -->
    <section class="form-container">
        <form action="" method="post" class="box">
            <h3>Envoyez-nous un message !</h3>
            <input type="number" required placeholder="Entrer votre numéro" max="9999999999" min="0" name="number" class="box" onkeypress="if(this.value.length==10) return false;">
            <textarea name="msg" placeholder="Entrer votre message" required class="box" cols="30" rows="10"></textarea>
            <input type="submit" value="Envoyer un message" class="btn" name="send">
        </form>
    </section>

    <?php
        include 'components/footer.php';
    ?>

    <!-- Custom JS file link -->
    <script src="js/script.js"></script>
</body>
</html>

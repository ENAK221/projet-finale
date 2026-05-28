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
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>À propos</title>

    <!-- Lien CDN pour Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css">

    <!-- Fichier CSS personnalisé -->
    <link rel="stylesheet" href="css/style.css">

    <style>
        .about-description {
            background-color: #ffffff;
            border: 3px solid #000000;
            padding: 20px;
            margin-top: 30px;
        }
        .about-description img {
            width: 120px;
            height: 120px;
            margin-top: 20px;
        }
        .about-description h3 {
            font-size: 24px;
        }
        .about-description p {
            font-size: 16px;
            line-height: 1.6;
        }
    </style>
</head>
<body>

    <?php
        include 'components/user_header.php'
    ?>

    <!-- Section À propos commence ici -->

    <section class="about">

        <div class="row">
            <div class="image">
                <img src="images/propos.png" alt="">
            </div>

            <div class="content">
                <h3>Pourquoi nous choisir ?</h3>
                <p>
                    Large choix de produits | Prix compétitifs | Service client exceptionnel | Achats sûrs et sécurisés
                </p>
                <a href="contact.php" class="btn">Nous contacter</a>
            </div>
        </div>

        <div class="about-description">
            <h3>À propos de ToolBiTrading SARL</h3> <br><br>
            <p>
                Bienvenue chez ToolBiTrading SARL, votre partenaire de confiance dans la commercialisation et la distribution de produits alimentaires de qualité au Sénégal. Notre mission est d'apporter innovation et efficacité à l'approvisionnement des boutiquiers à travers notre plateforme numérique avancée.

                Chez ToolBiTrading, nous facilitons la gestion des stocks et des commandes pour les boutiquiers grâce à une application intuitive. Notre équipe dédiée travaille activement pour promouvoir nos services et notre application, assurant ainsi un approvisionnement optimal pour nos partenaires commerciaux.

                Nous croyons fermement au pouvoir des partenariats pour stimuler la croissance des PME. En collaborant avec nous, vous bénéficiez non seulement d'une gamme variée de produits et de prix compétitifs, mais aussi d'un support dédié pour atteindre vos objectifs commerciaux.

                Joignez-vous à ToolBiTrading pour une expérience d'approvisionnement fluide et efficace. Ensemble, nous construisons l'avenir de la distribution alimentaire au Sénégal.
            </p>
            <img src="images/logo1.png" alt="Logo ToolBiTrading SARL">
        </div>

    </section>

    <?php
        include 'components/footer.php';
    ?>

    <!-- Lien vers le fichier JavaScript personnalisé -->
    <script src="js/script.js"></script>

</body>
</html>

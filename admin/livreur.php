<?php
    include '../components/connect.php';
    session_start();

    $admin_id = $_SESSION['admin_id'];

    if(!isset($admin_id)) {
        header('location:admin_login.php');
    }
?> 

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des Commandes</title>
    <!-- font awesome cdn link -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css">

    <!-- custom css file link -->
    <link rel="stylesheet" href="../css/admin_style.css">
    <style>
        /* body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            margin: 20px;
        } */
        .container {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
        }
        .order-item {
            background: #f9f9f9;
            padding: 10px;
            border-radius: 5px;
            width: calc(33.33% - 20px); /* Pour 3 colonnes par ligne avec espace */
            box-sizing: border-box;
        }
        .order-item a {
            display: block;
            margin-top: 10px;
            text-decoration: none;
            background-color: #007bff;
            color: white;
            padding: 5px 10px;
            border-radius: 3px;
            text-align: center;
        }
        .order-item a:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
<?php include '../components/admin_header.php'; ?>
<section>
    <div class="container">
        <h2>Liste des Commandes</h2>

        <?php
        // Requête SQL pour récupérer les commandes avec les détails du client et du livreur
        $query = "SELECT * from livrer ";
        $result = $conn->query($query);

        if ($result->rowCount() > 0) {
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                ?>
                <div class="order-item">
                    <p><strong>Commande ID:</strong> <?= htmlspecialchars($row['order_id']); ?></p>
                    <p><strong>Client:</strong> <?= htmlspecialchars($row['client_name']); ?></p>
                    <p><strong>Numéro de téléphone:</strong> <?= htmlspecialchars($row['client_number']); ?></p>
                    <!-- <p><strong>Email:</strong> <?= htmlspecialchars($row['client_email']); ?></p> -->
                    <p><strong>Livreur:</strong> <?= htmlspecialchars($row['livreur_name']); ?></p>
                    <!-- <p><strong>Email du livreur:</strong> <?= htmlspecialchars($row['livreur_email']); ?></p> -->
                    <a href="details_commande.php?order_id=<?= htmlspecialchars($row['order_id']); ?>">Voir détails</a>
                    <a href="../livreur/generate_pdf.php?order_id=<?= htmlspecialchars($row['order_id']); ?>" class="delete-btn">Voir facture</a>
                </div>
                <?php
            }
        } else {
            echo "<p>Aucune commande trouvée.</p>";
        }
        ?>

    </div>
</section>

</body>
</html>

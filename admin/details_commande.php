<?php
    include '../components/connect.php';
    session_start();

    if(!isset($_SESSION['admin_id'])) {
        header('location:admin_login.php');
        exit;
    }

    $admin_id = $_SESSION['admin_id'];
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Détails de la Commande</title>
    <!-- Font Awesome CDN link -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css">


    <link rel="stylesheet" href="../css/admin_style.css">
    <style>
        /* body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            margin: 20px;
        } */
        .container {
            width: 800px;
            margin: 20px;
        }
        .order-details {
            background: #f9f9f9;
            padding: 20px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .order-details h2 {
            margin-bottom: 10px;
        }
        .order-details table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            border: 1px solid #ddd;
        }
        .order-details th, .order-details td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: left;
        }
        .order-details th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
        .order-details .total {
            font-weight: bold;
            font-size: 1.2em;
        }
    </style>
</head>
<body>
    <?php include '../components/admin_header.php'; ?>

    <div class="container"> <br> <br>
        <h2>Détails de la Commande</h2> <br><br>

        <?php
        // Vérifier si l'ID de la commande est passé en paramètre
        if (isset($_GET['order_id']) && !empty($_GET['order_id'])) {
            $order_id = $_GET['order_id'];

            // Requête SQL pour récupérer les détails de la commande depuis la table livrer
            $query = "SELECT * FROM livrer WHERE order_id = ?";
            $stmt = $conn->prepare($query);
            $stmt->execute([$order_id]);
            $order_details = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($order_details) {
                ?>
                <div class="order-details">
                    <h3>Informations sur la Commande</h3>
                    <table>
                        <tr>
                            <th>Commande ID</th>
                            <td><?= htmlspecialchars($order_details['order_id']); ?></td>
                        </tr>
                        <tr>
                            <th>Nom du Client</th>
                            <td><?= htmlspecialchars($order_details['client_name']); ?></td>
                        </tr>
                        <tr>
                            <th>Email du Client</th>
                            <td><?= htmlspecialchars($order_details['client_email']); ?></td>
                        </tr>
                        <tr>
                            <th>Numéro de Téléphone du Client</th>
                            <td><?= htmlspecialchars($order_details['client_number']); ?></td>
                        </tr>
                        <tr>
                            <th>Adresse du Client</th>
                            <td><?= htmlspecialchars($order_details['client_address']); ?></td>
                        </tr>
                        <tr>
                            <th>Total Produits</th>
                            <td><?= htmlspecialchars($order_details['total_products']); ?></td>
                        </tr>
                        <tr>
                            <th>Total Prix</th>
                            <td><?= htmlspecialchars($order_details['total_price']); ?></td>
                        </tr>
                        <tr>
                            <th>Nom du Livreur</th>
                            <td><?= htmlspecialchars($order_details['livreur_name']); ?></td>
                        </tr>
                        <tr>
                            <th>Email du Livreur</th>
                            <td><?= htmlspecialchars($order_details['livreur_email']); ?></td>
                        </tr>
                        <tr>
                            <th>Date de Livraison</th>
                            <td><?= htmlspecialchars($order_details['delivered_at']); ?></td>
                        </tr>
                    </table>
                </div>
                <?php
            } else {
                echo "<p>Aucune commande trouvée avec l'ID spécifié.</p>";
            }
        } else {
            echo "<p>Paramètre d'ID de commande manquant ou invalide.</p>";
        }
        ?>

    </div>
</body>
</html>

<?php
include '../components/connect.php';

if (isset($_GET['order_id'])) {
    $order_id = $_GET['order_id'];

    // Récupérer les détails de la commande
    $select_order = $conn->prepare("SELECT * FROM `orders` WHERE order_id = ?");
    $select_order->execute([$order_id]);

    if ($select_order->rowCount() > 0) {
        $order = $select_order->fetch(PDO::FETCH_ASSOC);
        
        // Récupérer la position du client
        $select_position = $conn->prepare("SELECT * FROM `positions` WHERE user_id = ?");
        $select_position->execute([$order['user_id']]);
        $position = $select_position->fetch(PDO::FETCH_ASSOC);
    } else {
        echo "Commande non trouvée";
        exit;
    }
} else {
    echo "ID de commande manquant";
    exit;
}

// Marquer la commande comme livré
if (isset($_POST['mark_as_delivered'])) {
    $update_order = $conn->prepare("UPDATE `orders` SET payment_status = 'Livré' WHERE order_id = ?");
    $update_order->execute([$order_id]);

    header('Location: Home.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Détails de la commande</title>
    <link rel="stylesheet" href="../css/style.css">
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAyLp2OnxoZ1MLmuUu7tFV7KRjoSaXxg2I"></script>
    <style>
        /* Vos styles personnalisés ici */
        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 20px;
            background-color: #007BFF;
        }
        header img {
            height: 50px;
        }
        header .logout-btn {
            background-color: red;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
        }
        header .logout-btn:hover {
            background-color: #0056b3;
        }
        .footer-content {
            display: flex;
            justify-content: space-around;
            padding: 20px; 
            background-color: #007BFF; 
        }
        .footer-bottom {
            text-align: center;
            padding: 10px;
            background-color: #f1f1f1;
            color: white;
        }
        .order-details {
            padding: 20px;
        }
        .order-details h1 {
            margin-bottom: 20px;
        }
        .order-details p {
            margin-bottom: 10px;
        }
        .btn {
            background-color: #28a745;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .btn:hover {
            background-color: #218838;
        }
    </style>
</head>
<body>

<header>
    <img src="../images/logo1.png" alt="Logo"> <!-- Assurez-vous que le chemin est correct -->
    <a href="home.php" id="logout-btn" class="logout-btn">Retour</a>
</header>

<section class="order-details">
    <h1>Détails de la commande #<?= htmlspecialchars($order['order_id']); ?></h1>
    <p>Client : <span><?= htmlspecialchars($order['name'] . ' ' . $order['surname']); ?></span></p>
    <p>Total : <span><?= htmlspecialchars($order['total_price']); ?> Fcfa</span></p>
    <p>Numero Telephone : <span><?= htmlspecialchars($order['phone']); ?></span></p>
    <p>Produits Commandés : <span><?= htmlspecialchars($order['total_products']); ?></span></p>
    <p>Date de livraison : <span><?= htmlspecialchars($order['delivery_date']); ?></span></p>
    
    <div id="map" style="height: 400px; width: 100%;"></div>

    <form method="post" onsubmit="return confirmDelivery();">
        <button type="submit" name="mark_as_delivered" class="btn">Marquer comme livré</button>
    </form>
</section>

<footer class="footer">
    <div class="footer-content">
        <div class="footer-logo">
            <img src="../images/logo1.png" alt="ToolBiTrading_SARL Logo">
        </div>
        <div class="footer-contact">
            <h3>Contactez-nous</h3>
            <p>ToolBiTrading_SARL</p>
            <p>Adresse: Dieupeul-Derklé</p>
            <p>Téléphone: +33 6 45 90 36 56</p>
            <p>Email: toolbitradingsarl@outlook.com</p>
        </div>
        <div class="footer-social">
            <h3>Suivez-nous</h3>
            <a href="https://facebook.com" target="_blank"><i class="fab fa-facebook-f"></i> Facebook</a>
            <a href="https://twitter.com" target="_blank"><i class="fab fa-twitter"></i> Twitter</a>
            <a href="https://instagram.com" target="_blank"><i class="fab fa-instagram"></i> Instagram</a>
        </div>
    </div>
    <div class="footer-bottom">
        <p>&copy; 2024 ToolBiTrading_SARL. Tous droits réservés.</p>
    </div>
</footer>

<script>
    function initMap() {
    var clientLocation = {lat: <?= $position['latitude']; ?>, lng: <?= $position['longitude']; ?>};

    var map = new google.maps.Map(document.getElementById('map'), {
        zoom: 15,
        center: clientLocation
    });

    var clientMarker = new google.maps.Marker({
        position: clientLocation,
        map: map,
        icon: 'http://maps.google.com/mapfiles/ms/icons/red-dot.png',
        title: 'Client'
    });

    // Obtenir la position du livreur en temps réel
    if (navigator.geolocation) {
        navigator.geolocation.watchPosition(function(position) {
            var livreurLocation = {
                lat: position.coords.latitude,
                lng: position.coords.longitude
            };

            var livreurMarker = new google.maps.Marker({
                position: livreurLocation,
                map: map,
                icon: 'http://maps.google.com/mapfiles/ms/icons/blue-dot.png',
                title: 'Livreur'
            });

            // Utiliser l'API Directions pour calculer l'itinéraire
            var directionsService = new google.maps.DirectionsService();
            var directionsRenderer = new google.maps.DirectionsRenderer({
                map: map,
                polylineOptions: {
                    strokeColor: '#FF0000',
                    strokeOpacity: 1.0,
                    strokeWeight: 4
                }
            });

            directionsService.route({
                origin: livreurLocation,
                destination: clientLocation,
                travelMode: google.maps.TravelMode.DRIVING
            }, function(response, status) {
                if (status === google.maps.DirectionsStatus.OK) {
                    directionsRenderer.setDirections(response);
                } else {
                    alert('Impossible de calculer l\'itinéraire : ' + status);
                }
            });

        });
    } else {
        alert("La géolocalisation n'est pas supportée par votre navigateur.");
    }
}


    function confirmDelivery() {
        return confirm("Êtes-vous sûr de vouloir marquer cette commande comme livrée ?");
    }

    window.onload = initMap;
</script>

</body>
</html>

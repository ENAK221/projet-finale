<?php
include 'components/connect.php';

session_start();

if(isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
} else {
    $user_id = '';
}

if(isset($_POST['submit'])) {
    // Utilisation de htmlspecialchars pour assainir les entrées utilisateur
    $name = $_POST['name'];
    $name = htmlspecialchars($name, ENT_QUOTES, 'UTF-8');

    $surname = $_POST['surname'];
    $surname = htmlspecialchars($surname, ENT_QUOTES, 'UTF-8');

    $phone = $_POST['phone'];
    $phone = htmlspecialchars($phone, ENT_QUOTES, 'UTF-8');

    $password = $_POST['password'];
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $latitude = $_POST['latitude'];
    $longitude = $_POST['longitude'];

    $select_user = $conn->prepare("SELECT * FROM `users` WHERE phone=?");
    $select_user->execute([$phone]);

    if($select_user->rowCount() > 0) {
        $message[] = 'Utilisateur déjà existant !';
    } else {
        $cpassword = $_POST['cpassword'];

        if($password != $cpassword) {
            $message[] = 'Le mot de passe de confirmation ne correspond pas !';
        } else {
            $insert_user = $conn->prepare("INSERT INTO `users` (name, surname, phone, password) VALUES (?, ?, ?, ?)");
            $insert_user->execute([$name, $surname, $phone, $hashed_password]);

            $user_id = $conn->lastInsertId();

            $insert_position = $conn->prepare("INSERT INTO `positions` (user_id, latitude, longitude) VALUES (?, ?, ?)");
            $insert_position->execute([$user_id, $latitude, $longitude]);

            $_SESSION['success_message'] = 'L\'utilisateur s\'est inscrit avec succès, veuillez vous connecter maintenant !';
            header('Location: user_login.php');
            exit();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription</title>

    <!-- Font Awesome CDN Link -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css">

    <!-- Custom CSS File Link -->
    <link rel="stylesheet" href="css/style.css">

    <style>
        /* Styles pour la fenêtre modale */
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.4);
        }

        .modal-content {
            background-color: #fff;
            margin: 15% auto;
            padding: 20px;
            border: 3px solid #000;
            width: 30%;
            text-align: center;
        }

        .modal-content button {
            background-color: blue;
            color: white;
            padding: 10px 20px;
            margin: 10px;
            border: none;
            cursor: pointer;
        }

        .password-container {
            position: relative;
            width: 100%;
        }

        .password-container input[type="password"],
        .password-container input[type="text"] {
            width: 100%;
            padding-right: 40px; /* espace pour l'icône */
        }

        .password-container .toggle-password {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const modal = document.getElementById('geoModal');
            const allowButton = document.getElementById('allowButton');
            const cancelButton = document.getElementById('cancelButton');

            if ('geolocation' in navigator) {
                modal.style.display = 'block';

                allowButton.onclick = function() {
                    navigator.geolocation.getCurrentPosition(function(position) {
                        document.getElementById('latitude').value = position.coords.latitude;
                        document.getElementById('longitude').value = position.coords.longitude;
                        modal.style.display = 'none';
                    }, function(error) {
                        console.error('Erreur de géolocalisation:', error.message);
                        alert('Veuillez activer la géolocalisation pour l\'inscription.');
                    });
                };

                cancelButton.onclick = function() {
                    alert('Veuillez activer la géolocalisation pour continuer.');
                };
            } else {
                alert('La géolocalisation n\'est pas prise en charge par votre navigateur.');
            }
        });

        function togglePasswordVisibility(passwordFieldId, toggleIconId) {
            const passwordField = document.getElementById(passwordFieldId);
            const toggleIcon = document.getElementById(toggleIconId);
            
            if (passwordField.type === "password") {
                passwordField.type = "text";
                toggleIcon.classList.remove("fa-eye");
                toggleIcon.classList.add("fa-eye-slash");
            } else {
                passwordField.type = "password";
                toggleIcon.classList.remove("fa-eye-slash");
                toggleIcon.classList.add("fa-eye");
            }
        }

        function validateForm() {
            const password = document.getElementById('password').value;
            if (password !== '1234Passer') {
                alert('Veuillez vous rapprocher d\'un agent de ToolBiTrading pour l\'ouverture de votre compte.');
                return false; // Empêcher la soumission du formulaire
            }
            return true; // Autoriser la soumission du formulaire
        }
    </script>
</head>
<body>

<?php
include 'components/user_header.php';
?>

<!-- user register section starts -->
<section class="form-container">
    <form action="" method="post" onsubmit="return validateForm();">
        <h3>Inscription</h3>
        <input type="hidden" id="latitude" name="latitude">
        <input type="hidden" id="longitude" name="longitude">
        <input type="text" required maxlength="20" name="name" class="box" placeholder="Entrer votre Nom complet">
        <input type="text" required maxlength="20" name="surname" class="box" placeholder="Entrer votre Prénom">
        <input type="text" required maxlength="15" name="phone" class="box" placeholder="Entrer votre Téléphone">
        <div class="password-container">
            <input type="password" required maxlength="20" name="password" id="password" class="box" placeholder="Entrer votre mot de passe">
            <i class="fa fa-eye toggle-password" id="togglePassword" onclick="togglePasswordVisibility('password', 'togglePassword')"></i>
        </div>
        <div class="password-container">
            <input type="password" required maxlength="20" name="cpassword" id="cpassword" class="box" placeholder="Confirmer votre mot de passe">
            <i class="fa fa-eye toggle-password" id="toggleCPassword" onclick="togglePasswordVisibility('cpassword', 'toggleCPassword')"></i>
        </div>
        <input type="submit" value="Inscription" class="btn" name="submit">
        <p>Déjà un compte?</p>
        <a href="user_login.php" class="option-btn">Connexion</a>
    </form>
</section>

<div id="geoModal" class="modal">
    <div class="modal-content">
        <p>Veuillez activer la géolocalisation pour continuer.</p>
        <button id="allowButton">Autoriser</button>
        <button id="cancelButton">Annuler</button>
    </div>
</div>

<?php
include 'components/footer.php';
?>

<!-- custom js file link  -->
<script src="js/script.js"></script>
</body>
</html>

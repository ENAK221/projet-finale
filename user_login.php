<?php
include 'components/connect.php';
session_start();

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
} else {
    $user_id = '';
}

if (isset($_POST['submit'])) {
    $phone = $_POST['phone'];
    $phone = filter_var($phone, FILTER_SANITIZE_SPECIAL_CHARS);

    $password = $_POST['password'];
    $password = filter_var($password, FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    // Récupérer l'utilisateur depuis la base de données
    $select_user = $conn->prepare("SELECT * FROM `users` WHERE phone=?");
    $select_user->execute([$phone]);
    $user = $select_user->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        // Vérifier le mot de passe
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['success_message'] = 'Connexion réussie ! Bienvenue sur votre tableau de bord.';
            header('Location: index.php');
            exit();
        } else {
            $message[] = 'Numéro de téléphone ou Mot de Passe incorrect';
        }
    } else {
        $message[] = 'Numéro de téléphone ou Mot de Passe incorrect';
    }
}

// Afficher le message de succès après l'inscription
$success_message = '';
if (isset($_SESSION['success_message'])) {
    $success_message = $_SESSION['success_message'];
    unset($_SESSION['success_message']);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>

    <!-- font awesome cdn link  -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css">

    <!-- custom css file link  -->
    <link rel="stylesheet" href="css/style.css">

    <style>
        .error-message {
            color: red;
            border: 2px solid red;
            padding: 10px;
            margin: 10px 0;
            text-align: center;
        }

        .success-message {
            color: green;
            border: 2px solid green;
            padding: 10px;
            margin: 10px 0;
            text-align: center;
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
    </script>
</head>
<body>

<?php include 'components/user_header.php'; ?>

<!-- user login section starts -->
<section class="form-container">
    <form action="" method="post">
        <h3>Connexion</h3>
        <?php
        if (isset($message) && is_array($message)) {
            foreach ($message as $msg) {
                echo '<p class="error-message">' . $msg . '</p>';
            }
        }
        if ($success_message) {
            echo '<p class="success-message">' . $success_message . '</p>';
        }
        ?>
        <input type="text" required maxlength="15" name="phone" class="box" placeholder="Entrer votre numéro de téléphone" oninput="this.value.replace(/\s/g, '')">
        <div class="password-container">
            <input type="password" required maxlength="20" name="password" id="password" class="box" placeholder="Entrer votre mot de passe" oninput="this.value.replace(/\s/g, '')">
            <i class="fa fa-eye toggle-password" id="togglePassword" onclick="togglePasswordVisibility('password', 'togglePassword')"></i>
        </div>
        <input type="submit" value="Connexion" class="btn" name="submit">
        <p>Vous n'avez pas de compte ?</p>
        <a href="user_register.php" class="option-btn">Inscrivez-vous</a>
    </form>
</section>

<?php include 'components/footer.php'; ?>

<!-- custom js file link  -->
<script src="js/script.js"></script>
</body>
</html>

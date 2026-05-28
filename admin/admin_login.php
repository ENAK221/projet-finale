<?php
include '../components/connect.php';
session_start();

if (isset($_POST['submit'])) {
    $name = $_POST['name'];
    $pass = $_POST['pass'];

    // Préparation de la requête pour récupérer l'utilisateur
    $select_admin = $conn->prepare("SELECT * FROM `admins` WHERE name = ?");
    $select_admin->execute([$name]);
    $admin = $select_admin->fetch(PDO::FETCH_ASSOC);

    if ($admin) {
        $hashed_password = $admin['password'];

        // Vérification du mot de passe
        if (password_verify($pass, $hashed_password)) {
            $_SESSION['admin_id'] = $admin['id']; // Stocke l'ID de l'administrateur dans la session
            header('Location: dashboard.php'); // Redirige vers le tableau de bord
            exit();
        } else {
            $message = 'Nom d\'utilisateur ou mot de passe incorrect.';
        }
    } else {
        $message = 'Administrateur non existant';
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion Admin</title>
    <link rel="stylesheet" href="../css/admin_style.css">
    <style>
        .password-container {
            position: relative;
            width: 100%;
        }
        .password-container input[type="password"],
        .password-container input[type="text"] {
            width: 100%;
            padding-right: 40px; /* Espace pour l'icône */
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
        function togglePasswordVisibility() {
            const passwordField = document.getElementById('pass');
            const toggleIcon = document.getElementById('togglePass');

            if (passwordField.type === 'password') {
                passwordField.type = 'text';
                toggleIcon.classList.remove('fa-eye');
                toggleIcon.classList.add('fa-eye-slash');
            } else {
                passwordField.type = 'password';
                toggleIcon.classList.remove('fa-eye-slash');
                toggleIcon.classList.add('fa-eye');
            }
        }
    </script>
</head>
<body>

<?php
if (isset($message)) {
    echo '
    <div class="message">
        <span>' . $message . '</span>
        <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
    </div>
    ';
}
?>

<section class="form-container">
    <form action="" method="post">
        <h3>Connexion</h3>
        <input type="text" name="name" required placeholder="Entrer votre nom utilisateur" maxlength="20" class="box" oninput="this.value = this.value.replace(/\s/g, '')">
        <div class="password-container">
            <input type="password" name="pass" id="pass" required placeholder="Entrer votre mot de passe" maxlength="20" class="box" oninput="this.value = this.value.replace(/\s/g, '')">
            <i class="fas fa-eye toggle-password" id="togglePass" onclick="togglePasswordVisibility()"></i>
        </div>
        <input type="submit" value="Connexion" class="btn" name="submit">
    </form>
</section>

</body>
</html>

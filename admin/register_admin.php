<?php
include '../components/connect.php';
session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)) {
    header('location:admin_login.php');
    exit;
}

if(isset($_POST['submit'])) {
    $name = $_POST['name'];
    $name = filter_var($name, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $pass = $_POST['pass'];
    $pass = filter_var($pass, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $cpass = $_POST['cpass'];
    $cpass = filter_var($cpass, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $email = $_POST['email']; // Nouveau champ email
    $email = filter_var($email, FILTER_SANITIZE_EMAIL); // Filtrage de l'email
    $role = $_POST['role']; // Nouveau champ rôle

    $select_admin =  $conn->prepare("SELECT * FROM `admins` WHERE name=? OR email=?");
    $select_admin->execute([$name, $email]);
    $fetch_admin = $select_admin->fetch(PDO::FETCH_ASSOC);

    if($select_admin->rowCount() > 0) {
        $message[] = 'Nom d\'utilisateur ou email déjà utilisé';
    } else {
        if($pass != $cpass) {
            $message[] = 'Les mots de passe ne correspondent pas!';
        } else {
            // Hacher le mot de passe
            $hashed_password = password_hash($pass, PASSWORD_DEFAULT);

            $insert_admin = $conn->prepare("INSERT INTO `admins`(name, password, email, role) VALUES(?, ?, ?, ?)");
            $insert_admin->execute([$name, $hashed_password, $email, $role]);
            $message[] = 'Nouvel administrateur enregistré avec succès!';
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

    <!-- lien CDN Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css">

    <!-- lien fichier CSS personnalisé -->
    <link rel="stylesheet" href="../css/admin_style.css">

    <style>
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

<?php
include '../components/admin_header.php';
?>

<!-- début section inscription administrateur -->
<section class="form-container">
    <form action="" method="post">
        <h3>Inscription</h3>
        <input type="text" name="name" maxlength="20" required placeholder="Entrer votre nom utilisateur" class="box" oninput="this.value = this.value.replace(/\s/g, '')"></input>
        <input type="email" name="email" maxlength="50" required placeholder="Entrer votre adresse email" class="box"></input> <!-- Champ Email -->
        <div class="password-container">
            <input type="password" name="pass" id="pass" maxlength="20" required placeholder="Entrer votre mot de passe" class="box" oninput="this.value = this.value.replace(/\s/g, '')"></input>
            <i class="fa fa-eye toggle-password" id="togglePass" onclick="togglePasswordVisibility('pass', 'togglePass')"></i>
        </div>
        <div class="password-container">
            <input type="password" name="cpass" id="cpass" maxlength="20" required placeholder="Confirmer votre mot de passe" class="box" oninput="this.value = this.value.replace(/\s/g, '')"></input>
            <i class="fa fa-eye toggle-password" id="toggleCpass" onclick="togglePasswordVisibility('cpass', 'toggleCpass')"></i>
        </div>
        <select name="role" required class="box">
            <option value="">Sélectionner le rôle</option>
            <option value="Administrateur">Administrateur</option>
            <option value="Livreur">Livreur</option>
        </select> <!-- Champ rôle -->
        <input type="submit" value="Inscription" name="submit" class="btn">
    </form>
</section>

<!-- lien fichier JS personnalisé -->
<script src="../js/admin_script.js"></script>
</body>
</html>

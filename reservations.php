<?php
include 'components/connect.php';
session_start();

// Si l'utilisateur est déjà connecté, on récupère son ID
$user_id = $_SESSION['user_id'] ?? '';

// Traitement du formulaire de connexion
if (isset($_POST['submit_login'])) {
    $phone = filter_var($_POST['phone'], FILTER_SANITIZE_SPECIAL_CHARS);
    $password = filter_var($_POST['password'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    // Vérification des identifiants
    $stmt = $conn->prepare("SELECT * FROM `users` WHERE phone = ?");
    $stmt->execute([$phone]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        header('Location: index.php');
        exit();
    } else {
        $message = 'Numéro de téléphone ou mot de passe incorrect';
    }
}

// Traitement du formulaire de réservation
if (isset($_POST['submit_reservation']) && $user_id) {
    $date = $_POST['date'];
    $time = $_POST['time'];
    $service = $_POST['service'];

    // Insertion de la réservation dans la base de données
    $stmt = $conn->prepare("INSERT INTO `reservations` (user_id, date, time, service) VALUES (?, ?, ?, ?)");
    $stmt->execute([$user_id, $date, $time, $service]);

    $message = 'Réservation effectuée avec succès !';
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion et Réservation</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<?php include 'components/user_header.php'; ?>

<!-- Formulaire de connexion -->
<section class="form-container">
    <form action="" method="post">
        <h3>Connexion</h3>
        <?php if (isset($message)) echo "<p class='error-message'>$message</p>"; ?>
        <input type="text" name="phone" placeholder="Numéro de téléphone" required>
        <input type="password" name="password" placeholder="Mot de passe" required>
        <input type="submit" name="submit_login" value="Se connecter">
    </form>
</section>

<?php if ($user_id): ?>
<!-- Formulaire de réservation -->
<section class="form-container">
    <form action="" method="post">
        <h3>Réserver un service</h3>
        <input type="date" name="date" required>
        <input type="time" name="time" required>
        <select name="service" required>
            <option value="service1">Service 1</option>
            <option value="service2">Service 2</option>
            <option value="service3">Service 3</option>
        </select>
        <input type="submit" name="submit_reservation" value="Réserver">
    </form>
</section>
<?php endif; ?>

<?php include 'components/footer.php'; ?>

</body>
</html>


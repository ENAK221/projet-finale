<?php
session_start();
include 'components/connect.php';

if (!isset($_SESSION['admin'])) {
    header('Location: admin/admin_login.php');
    exit;
}

if (isset($_POST['submit'])) {
    $name = $_POST['name'];
    $price = $_POST['price'];
    $category = $_POST['category'];

    $image = $_FILES['image']['name'];
    $image_tmp = $_FILES['image']['tmp_name'];
    $image_folder = 'uploaded_img/' . $image;

    if (!empty($name) && !empty($price) && !empty($category) && !empty($image)) {
        $insert_product = $conn->prepare("INSERT INTO products (name, price, image_01, category) VALUES (?, ?, ?, ?)");
        $insert_product->execute([$name, $price, $image, $category]);

        move_uploaded_file($image_tmp, $image_folder);

        $message = "✅ Produit ajouté avec succès !";
    } else {
        $message = "❌ Veuillez remplir tous les champs.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ajouter Produit (Admin)</title>
    <style>
        form { max-width: 400px; margin: auto; background: #f9f9f9; padding: 20px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        input, select { width: 100%; padding: 10px; margin-bottom: 15px; }
        .message { color: green; text-align: center; }
    </style>
</head>
<body>

    <h2 style="text-align: center;">Ajouter un Produit (Admin)</h2>

    <?php if (isset($message)) echo "<p class='message'>$message</p>"; ?>

    <form action="" method="post" enctype="multipart/form-data">
        <input type="text" name="name" placeholder="Nom du produit" required>
        <input type="number" name="price" placeholder="Prix (Fcfa)" required>

        <select name="category" required>
            <option value="">-- Choisir une catégorie --</option>
            <option value="eau">Eau</option>
            <option value="sanitaire">Sanitaire</option>
            <option value="electronique">Électronique</option>
        </select>

        <input type="file" name="image" accept="image/*" required>
        <input type="submit" name="submit" value="Ajouter le produit">
    </form>

</body>
</html>

<?php
    include '../components/connect.php';
    session_start();

    $admin_id = $_SESSION['admin_id'] ?? null;
    if (!$admin_id) {
        header('location:admin_login.php');
        exit;
    }

    $message = [];

    if (isset($_POST['add_product'])) {
        // Sécurisation des champs
        $name     = htmlspecialchars($_POST['name'], ENT_QUOTES, 'UTF-8');
        $price    = filter_var($_POST['price'], FILTER_VALIDATE_FLOAT, ['options' => ['decimal' => '.']]);
        $details  = htmlspecialchars($_POST['details'], ENT_QUOTES, 'UTF-8');
        $category = htmlspecialchars($_POST['category'], ENT_QUOTES, 'UTF-8');

        // Gestion des images
        $images    = [];
        $upload_ok = true;

        for ($i = 1; $i <= 3; $i++) {
            $key        = "image_0{$i}";
            $img_name   = $_FILES[$key]['name'] ?? '';
            $img_size   = $_FILES[$key]['size'] ?? 0;
            $img_tmp    = $_FILES[$key]['tmp_name'] ?? '';
            $img_folder = "../uploaded_img/{$img_name}";

            if ($img_name === '') {
                $message[]  = "Image {$i} manquante !";
                $upload_ok  = false;
                break;
            }
            if ($img_size > 2_000_000) {
                $message[]  = "Image {$i} trop volumineuse (max 2 Mo).";
                $upload_ok  = false;
                break;
            }
            move_uploaded_file($img_tmp, $img_folder);
            $images[] = htmlspecialchars($img_name, ENT_QUOTES, 'UTF-8');
        }

        if ($upload_ok) {
            // Vérifier l'existence
            $check = $conn->prepare("SELECT id FROM products WHERE name = ?");
            $check->execute([$name]);
            if ($check->rowCount() > 0) {
                $message[] = 'Produit déjà existant.';
            } else {
                // Insertion
                $ins = $conn->prepare("
                    INSERT INTO products
                    (name, details, price, category, image_01, image_02, image_03)
                    VALUES (?, ?, ?, ?, ?, ?, ?)
                ");
                $ins->execute([
                    $name, $details, $price, $category,
                    $images[0], $images[1], $images[2]
                ]);
                $message[] = 'Nouveau produit ajouté !';
            }
        }
    }

    // Suppression
    if (isset($_GET['delete'])) {
        $delete_id = (int)$_GET['delete'];
        // Récupérer les noms d'images
        $sel = $conn->prepare("SELECT image_01, image_02, image_03 FROM products WHERE id = ?");
        $sel->execute([$delete_id]);
        if ($imgs = $sel->fetch(PDO::FETCH_ASSOC)) {
            foreach ($imgs as $file) {
                $path = "../uploaded_img/{$file}";
                if (file_exists($path)) unlink($path);
            }
        }
        // Supprimer le produit
        $del = $conn->prepare("DELETE FROM products WHERE id = ?");
        $del->execute([$delete_id]);
        // Nettoyer panier et wishlist
        $conn->prepare("DELETE FROM cart WHERE pid = ?")->execute([$delete_id]);
        $conn->prepare("DELETE FROM wishlist WHERE pid = ?")->execute([$delete_id]);

        header('location:products.php');
        exit;
    }
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Produits - Admin</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css">
    <link rel="stylesheet" href="../css/admin_style.css">
</head>
<body>

<?php include '../components/admin_header.php'; ?>

<!-- Affichage des messages -->
<?php if (!empty($message)): ?>
    <?php foreach ($message as $msg): ?>
        <p class="message"><?= $msg; ?></p>
    <?php endforeach; ?>
<?php endif; ?>

<!-- Formulaire d'ajout -->
<section class="add-products">
    <h1 class="heading">Ajouter un Produit</h1>
    <form action="" method="post" enctype="multipart/form-data">
        <div class="flex">

            <div class="inputBox">
                <span>Nom du produit</span>
                <input type="text" name="name" class="box" required maxlength="100">
            </div>

            <div class="inputBox">
                <span>Prix (Fcfa)</span>
                <input type="number" name="price" class="box" min="0" step="0.01" required>
            </div>

            <div class="inputBox">
                <span>Catégorie</span>
                <select name="category" class="box" required>
                    <option value="">Sélectionner</option>
                    <option value="eau">Eau</option>
                    <option value="oeuf">Œuf</option>
                    <option value="savon">Savon</option>
                    <option value="boisson">Boisson</option>
                    <option value="sanitaire">Sanitaire</option>
                    <option value="electronique">Électronique</option>
                </select>
            </div>

            <?php for ($i = 1; $i <= 3; $i++): ?>
            <div class="inputBox">
                <span>Image <?= $i; ?></span>
                <input type="file" name="image_0<?= $i; ?>" class="box" accept="image/*" required>
            </div>
            <?php endfor; ?>

            <div class="inputBox">
                <span>Description</span>
                <textarea name="details" class="box" required maxlength="500" cols="30" rows="10"></textarea>
            </div>

            <input type="submit" name="add_product" value="Ajouter Produit" class="btn">
        </div>
    </form>
</section>

<!-- Liste des produits ajoutés -->
<section class="show-products" style="padding-top:0;">
    <h1 class="heading">Produits Ajoutés</h1>
    <div class="box-container">

    <?php
        $show = $conn->prepare("SELECT * FROM products ORDER BY id DESC");
        $show->execute();
        if ($show->rowCount() > 0):
            while ($prod = $show->fetch(PDO::FETCH_ASSOC)):
    ?>
        <div class="box">
            <img src="../uploaded_img/<?= htmlspecialchars($prod['image_01'], ENT_QUOTES, 'UTF-8'); ?>" alt="">
            <div class="name"><?= htmlspecialchars($prod['name'], ENT_QUOTES, 'UTF-8'); ?></div>
            <div class="price"><?= htmlspecialchars($prod['price'], ENT_QUOTES, 'UTF-8'); ?> Fcfa</div>
            <div class="category">Catégorie : <?= htmlspecialchars($prod['category'], ENT_QUOTES, 'UTF-8'); ?></div>
            <div class="details"><?= htmlspecialchars($prod['details'], ENT_QUOTES, 'UTF-8'); ?></div>
            <div class="flex-btn">
                <a href="update_product.php?update=<?= $prod['id']; ?>" class="option-btn">Modifier</a>
                <a href="products.php?delete=<?= $prod['id']; ?>" class="delete-btn"
                   onclick="return confirm('Supprimer ce produit ?');"
                >Supprimer</a>
            </div>
        </div>
    <?php
            endwhile;
        else:
    ?>
        <p class="empty">Aucun produit ajouté.</p>
    <?php endif; ?>

    </div>
</section>

<script src="../js/admin_script.js"></script>
</body>
</html>

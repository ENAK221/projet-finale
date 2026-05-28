<?php
    if(isset($_POST['add_to_wishlist']))
    {
        if($user_id == '')
        {
            header('location:user_login.php');
        }
        else
        {
            $pid = $_POST['pid'];
            $pid = filter_var($pid, FILTER_SANITIZE_FULL_SPECIAL_CHARS);

            $name = $_POST['name'];
            $name = filter_var($name, FILTER_SANITIZE_FULL_SPECIAL_CHARS);

            $price = $_POST['price'];
            $price = filter_var($price, FILTER_SANITIZE_FULL_SPECIAL_CHARS);

            $image = $_POST['image'];
            $image = filter_var($image, FILTER_SANITIZE_FULL_SPECIAL_CHARS);

            $check_wishlist_number = $conn->prepare("SELECT * FROM `wishlist` WHERE name=? AND user_id=?");
            $check_wishlist_number->execute([$name, $user_id]);

            $check_cart_number = $conn->prepare("SELECT * FROM `cart` WHERE name=? AND user_id=?");
            $check_cart_number->execute([$name, $user_id]);

            if($check_wishlist_number->rowCount()>0)
            {
                $message[] = 'déjà ajouté à la liste de souhaits!';
            }
            elseif($check_cart_number->rowCount()>0)
            {
                $message[] = 'déjà ajouté au panier !';
            }
            else
            {
                $insert_wishlist = $conn->prepare("INSERT INTO `wishlist`(user_id, pid, name, price, image) VALUES(?, ?, ?, ?, ?)");
                $insert_wishlist->execute([$user_id, $pid, $name, $price, $image]);
                $message[] = 'produit ajouté à la liste de souhaits avec succès !';
            }
        }
    }

    if (isset($_POST['add_to_cart'])) {
        if ($user_id == '') {
            header('location:user_login.php');
        } else {
            $pid = $_POST['pid'];
            $name = $_POST['name'];
            $price = $_POST['price'];
            $image = $_POST['image'];
            $qty = $_POST['qty'];
    
            // Assurez-vous que les données sont propres mais ne modifiez pas les caractères spéciaux
            $pid = filter_var($pid, FILTER_SANITIZE_NUMBER_INT);
            $name = htmlspecialchars($name, ENT_QUOTES, 'UTF-8');
            $price = filter_var($price, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
            $image = htmlspecialchars($image, ENT_QUOTES, 'UTF-8');
            $qty = filter_var($qty, FILTER_SANITIZE_NUMBER_INT);
    
            // Vérifiez si le produit est déjà dans le panier
            $check_cart_number = $conn->prepare("SELECT * FROM `cart` WHERE name=? AND user_id=?");
            $check_cart_number->execute([$name, $user_id]);
    
            if ($check_cart_number->rowCount() > 0) {
                $message[] = 'déjà ajouté au panier !';
            } else {
                $check_wishlist_number = $conn->prepare("SELECT * FROM `wishlist` WHERE name=? AND user_id=?");
                $check_wishlist_number->execute([$name, $user_id]);
    
                if ($check_wishlist_number->rowCount() > 0) {
                    $delete_wishlist = $conn->prepare("DELETE FROM `wishlist` WHERE name=? AND user_id=?");
                    $delete_wishlist->execute([$name, $user_id]);
                }
    
                $insert_cart = $conn->prepare("INSERT INTO `cart`(user_id, pid, name, price, quantity, image) VALUES(?, ?, ?, ?, ?, ?)");
                $insert_cart->execute([$user_id, $pid, $name, $price, $qty, $image]);
                $message[] = 'produit ajouté au panier avec succès !';
            }
        }
    }
    
?>
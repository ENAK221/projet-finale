<?php
// Mot de passe à hacher
$mot_de_passe = "admin123";

// Hachage du mot de passe avec l'algorithme bcrypt
$mot_de_passe_hache = password_hash($mot_de_passe, PASSWORD_BCRYPT);

// Affichage du mot de passe haché
echo "Mot de passe haché : " . $mot_de_passe_hache;
?>

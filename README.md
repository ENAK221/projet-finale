# ToolBiTrading_SARL — Plateforme E-commerce

Application web e-commerce complète développée en PHP/MySQL pour **ToolBiTrading_SARL**, une entreprise basée à Dakar, Sénégal. La plateforme couvre l'ensemble du cycle de vente : navigation client, gestion des commandes, tableau de bord administrateur et interface de livraison.

---

## Table des matières

- [Aperçu](#aperçu)
- [Fonctionnalités](#fonctionnalités)
- [Stack technique](#stack-technique)
- [Structure du projet](#structure-du-projet)
- [Installation](#installation)
- [Configuration de la base de données](#configuration-de-la-base-de-données)
- [Rôles utilisateurs](#rôles-utilisateurs)
- [Informations de contact](#informations-de-contact)

---

## Aperçu

ToolBiTrading_SARL est une boutique en ligne avec trois espaces distincts :

| Espace | Accès | Description |
|---|---|---|
| **Client** | `index.php` | Navigation, achats, suivi de commandes |
| **Administrateur** | `admin/admin_login.php` | Gestion complète du site |
| **Livreur** | `livreur/home.php` | Gestion et livraison des commandes |

---

## Fonctionnalités

### Côté Client
- Inscription avec capture de **géolocalisation** (latitude/longitude)
- Connexion sécurisée avec session
- Catalogue produits avec recherche et filtres par catégorie
- Fiche produit avec galerie (3 images)
- Panier d'achat persistant
- Liste de souhaits (wishlist)
- Passage de commande avec adresse et date de livraison souhaitée
- Suivi des commandes en temps réel
- Formulaire de contact
- Page À propos et page Réservations

### Côté Administrateur
- Tableau de bord avec statistiques dynamiques :
  - Montant total des commandes en attente / livrées
  - Nombre total de commandes et de produits
- **Gestion des produits** : ajout, modification, suppression (3 images par produit, max 2 Mo chacune)
- **Gestion des commandes** : visualisation détaillée, mise à jour des statuts
- **Gestion des comptes** : utilisateurs et livreurs
- **Messagerie** : consultation des messages des clients
- Mise à jour du profil administrateur
- Enregistrement de nouveaux admins

### Côté Livreur
- Liste des commandes avec statut **"En cours"**
- Page de détails avec :
  - Informations client (nom, téléphone, adresse)
  - **Carte Google Maps** avec position GPS du client
  - Montant total et date de livraison
- Marquer une commande comme **"Livré"**
- **Génération de bon de livraison PDF** (via FPDF) avec en-tête ToolBiTrading_SARL

---

## Stack technique

| Technologie | Usage |
|---|---|
| **PHP 7+** | Backend, logique métier |
| **MySQL** | Base de données relationnelle |
| **PDO** | Accès sécurisé à la base de données |
| **FPDF** | Génération de PDF (bons de livraison) |
| **Google Maps API** | Localisation client pour les livreurs |
| **Font Awesome 6.3** | Icônes |
| **CSS personnalisé** | Styles (`css/style.css`, `css/admin_style.css`) |
| **JavaScript** | Interactions, dashboard dynamique |
| **XAMPP** | Environnement de développement local (Apache + MySQL) |

---

## Structure du projet

```
projet-finale/
│
├── index.php                  # Page d'accueil
├── shop.php                   # Catalogue produits
├── cart.php                   # Panier
├── checkout.php               # Validation de commande
├── orders.php                 # Suivi des commandes client
├── wishlist.php               # Liste de souhaits
├── user_login.php             # Connexion client
├── user_register.php          # Inscription client (avec géolocalisation)
├── update_user.php            # Mise à jour profil client
├── search_page.php            # Résultats de recherche
├── category.php               # Produits par catégorie
├── quick_view.php             # Aperçu rapide produit
├── about.php                  # Page à propos
├── contact.php                # Formulaire de contact
├── reservations.php           # Page de réservations
├── dashreserv.php             # Dashboard réservations
│
├── admin/
│   ├── admin_login.php        # Connexion admin
│   ├── register_admin.php     # Création compte admin
│   ├── dashboard.php          # Tableau de bord
│   ├── fetch_dashboard_data.php # Données stats (AJAX)
│   ├── products.php           # Gestion des produits
│   ├── update_product.php     # Modification d'un produit
│   ├── placed_orders.php      # Liste des commandes
│   ├── details_commande.php   # Détails d'une commande
│   ├── user_accounts.php      # Comptes clients
│   ├── admin_accounts.php     # Comptes administrateurs
│   ├── livreur.php            # Gestion des livreurs
│   ├── messages.php           # Messages des clients
│   └── update_profile.php     # Profil admin
│
├── livreur/
│   ├── home.php               # Liste commandes "En cours"
│   ├── order_details.php      # Détails + carte Google Maps
│   ├── generate_pdf.php       # Génération bon de livraison PDF
│   └── fpdf/                  # Bibliothèque FPDF
│
├── components/
│   ├── connect.php            # Connexion PDO à la base de données
│   ├── user_header.php        # Header client
│   ├── admin_header.php       # Header admin
│   ├── footer.php             # Footer commun
│   ├── wishlist_cart.php      # Compteurs panier/wishlist
│   ├── user_logout.php        # Déconnexion client
│   └── admin_logout.php       # Déconnexion admin/livreur
│
├── uploaded_img/              # Images uploadées des produits
├── images/                    # Images statiques du site
├── css/
│   ├── style.css              # Styles côté client
│   └── admin_style.css        # Styles côté admin
└── js/
    ├── script.js              # Scripts client
    └── admin_script.js        # Scripts admin
```

---

## Installation

### Prérequis
- [XAMPP](https://www.apachefriends.org/) (Apache + PHP 7+ + MySQL)
- Navigateur web

### Étapes

1. **Cloner ou copier le projet** dans le dossier `htdocs` de XAMPP :
   ```
   C:\xampp\htdocs\projet-finale\
   ```

2. **Démarrer XAMPP** : lancer Apache et MySQL depuis le panneau de contrôle.

3. **Importer la base de données** :
   - Ouvrir [http://localhost/phpmyadmin](http://localhost/phpmyadmin)
   - Créer une base de données nommée `mon_site`
   - Importer le fichier SQL du projet

4. **Créer le dossier d'upload des images** (s'il n'existe pas) :
   ```
   projet-finale/uploaded_img/
   ```

5. **Accéder au site** :
   - Site client : [http://localhost/projet-finale/projet-finale/](http://localhost/projet-finale/projet-finale/)
   - Admin : [http://localhost/projet-finale/projet-finale/admin/admin_login.php](http://localhost/projet-finale/projet-finale/admin/admin_login.php)
   - Livreur : [http://localhost/projet-finale/projet-finale/livreur/home.php](http://localhost/projet-finale/projet-finale/livreur/home.php)

---

## Configuration de la base de données

Le fichier de connexion se trouve dans [components/connect.php](components/connect.php) :

```php
$conn = new PDO('mysql:host=localhost;dbname=mon_site;charset=utf8mb4', 'root', '');
```

| Paramètre | Valeur |
|---|---|
| Hôte | `localhost` |
| Base de données | `mon_site` |
| Utilisateur | `root` |
| Mot de passe | *(vide — config XAMPP par défaut)* |

### Tables principales

| Table | Description |
|---|---|
| `users` | Comptes clients (nom, prénom, téléphone, mot de passe hashé) |
| `admins` | Comptes administrateurs |
| `products` | Catalogue produits (nom, détails, prix, catégorie, 3 images) |
| `cart` | Panier par utilisateur |
| `wishlist` | Liste de souhaits par utilisateur |
| `orders` | Commandes (client, produits, prix, statut, date de livraison) |
| `positions` | Coordonnées GPS des clients (latitude, longitude) |
| `messages` | Messages envoyés via le formulaire de contact |

### Statuts de commande

| Statut | Description |
|---|---|
| `En Attente` | Commande passée, en attente de traitement |
| `En cours` | Commande prise en charge par un livreur |
| `Livré` | Commande livrée au client |

---

## Rôles utilisateurs

### Client
- Inscription via numéro de téléphone (unique) + géolocalisation automatique
- Authentification par session PHP
- Accès au panier, wishlist, commandes et profil

### Administrateur
- Authentification séparée (`$_SESSION['admin_id']`)
- Accès complet à la gestion du site
- Peut créer d'autres comptes admin

### Livreur
- Authentification via `$_SESSION['livreur_id']`
- Visualise uniquement les commandes en cours
- Peut marquer une commande comme livrée
- Génère des bons de livraison PDF

---

## Informations de contact

**ToolBiTrading_SARL**  
Adresse : Dieupeul-Derklé, Dakar, Sénégal  
Téléphone : +221 76 740 92 95 / 76 991 41 81 / 77 110 60 76  
Email : toolbitradingsarl@outlook.com  

---

## Sécurité

- Mots de passe hashés avec `password_hash()` (bcrypt)
- Requêtes SQL préparées avec PDO (protection contre les injections SQL)
- Échappement des sorties HTML avec `htmlspecialchars()`
- Validation des fichiers uploadés (type et taille max 2 Mo)
- Vérification de session sur toutes les pages protégées

---

*© 2024 ToolBiTrading_SARL. Tous droits réservés.*

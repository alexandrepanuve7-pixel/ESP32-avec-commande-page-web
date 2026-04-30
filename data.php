<?php
// ======================
// CONFIGURATION PHP
// ======================

// Active l'affichage des erreurs (utile pour le développement et le debug)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Chemin du fichier où la valeur envoyée par l'ESP32 sera stockée
$file = __DIR__ . "/valeur.txt";

// Valeur par défaut affichée si aucun fichier n'existe encore
$valeur = "Aucune valeur reçue pour l'instant.";

// ======================
// TRAITEMENT DES REQUÊTES POST (ESP32 → SERVEUR)
// ======================

// Vérifie si la requête reçue est de type POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Vérifie si la variable "valeur" existe dans les données envoyées
    if (isset($_POST['valeur'])) {

        // Récupère la valeur envoyée par l'ESP32
        $valeur = $_POST['valeur'];

        // Écrit la valeur dans le fichier texte (stockage)
        $result = file_put_contents($file, $valeur);

        // Vérifie si l'écriture a réussi
        if ($result === false) {
            echo "Erreur : impossible d'écrire dans le fichier";
        } else {
            // Affiche un message de confirmation (sécurisé)
            echo "Valeur enregistrée : " . htmlspecialchars($valeur);
        }

    } else {
        // Cas où la requête POST est envoyée sans donnée "valeur"
        echo "Erreur : aucune valeur reçue";
    }

    // Arrête le script ici (important pour éviter d'afficher le HTML)
    exit;
}

// ======================
// TRAITEMENT DES REQUÊTES GET (NAVIGATEUR → AFFICHAGE)
// ======================

// Si le fichier existe, on lit la dernière valeur enregistrée
if (file_exists($file)) {
    $valeur = file_get_contents($file);
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">

    <!-- Titre de la page -->
    <title>Valeur Arduino / ESP32</title>

    <!-- Rafraîchissement automatique toutes les 5 secondes -->
    <meta http-equiv="refresh" content="5">

    <style>
        /* ======================
           STYLE GLOBAL DE LA PAGE
           ====================== */

        body {
            font-family: Arial, sans-serif; /* police simple */
            text-align: center;             /* centre tout le contenu */
            margin-top: 50px;               /* espace en haut */
        }

        /* Bloc d'affichage de la valeur */
        .valeur {
            font-size: 2em;       /* texte grand */
            color: #007BFF;       /* couleur bleue */
            margin: 20px;         /* espace autour */
            font-weight: bold;    /* texte en gras */
        }

        /* Style des boutons */
        button {
            padding: 10px 20px;    /* taille interne */
            margin: 10px;          /* espace entre boutons */
            font-size: 16px;       /* taille du texte */
            cursor: pointer;       /* curseur main */
        }
    </style>
</head>

<body>

    <!-- Titre principal de la page -->
    <h1>Dernière valeur reçue de l’ESP32</h1>

    <!-- Affichage de la valeur reçue -->
    <div class="valeur">
        <?php echo htmlspecialchars($valeur); ?>
    </div>

    <!-- ======================
         CONTRÔLE DE L’ESP32
         ====================== -->

    <!-- Bouton pour allumer la LED -->
    <button onclick="fetch('http://IP_DE_L_ESP32/led')">
        💡 Allumer LED
    </button>

    <!-- Bouton pour activer le buzzer -->
    <button onclick="fetch('http://IP_DE_L_ESP32/son')">
        🔊 Activer Buzzer
    </button>

</body>
</html>
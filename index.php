<?php
// ======================
// PARTIE PHP (lecture du fichier)
// ======================

// Valeur affichée par défaut si aucun fichier n'existe
$valeur = "Aucune donnée";

// Vérifie si le fichier contenant la dernière valeur existe
if (file_exists("valeur.txt")) {

    // Lit le contenu du fichier et le stocke dans la variable
    $valeur = file_get_contents("valeur.txt");
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">

<!-- ======================
     AUTO-REFRESH
     ======================
     La page se recharge automatiquement toutes les 2 secondes
     pour afficher la dernière valeur reçue
-->
<meta http-equiv="refresh" content="2">

<title>Donnée ESP32</title>

<style>
/* ======================
   STYLE GLOBAL DE LA PAGE
   ====================== */

body {
    font-family: Arial, sans-serif; /* police simple et lisible */
    text-align: center;             /* centre tout le contenu */
    margin-top: 50px;               /* espace en haut */
}

/* ======================
   BOÎTE D'AFFICHAGE
   ======================
   Sert à afficher la valeur reçue de l'ESP32
*/
.box {
    display: inline-block;
    padding: 20px 40px;
    border: 2px solid #333;
    border-radius: 12px;
    font-size: 28px;
    background-color: #f2f2f2;
}

/* ======================
   STYLE DES BOUTONS
   ======================
*/
button {
    padding: 10px 20px;
    margin-top: 20px;
    font-size: 18px;
    cursor: pointer;
}
</style>
</head>

<body>

<!-- ======================
     TITRE PRINCIPAL
     ======================
-->
<h1>Valeur reçue depuis l'ESP32</h1>

<!-- ======================
     AFFICHAGE DE LA VALEUR
     ======================
     htmlspecialchars() sécurise l'affichage
-->
<div class="box">
    <?php echo htmlspecialchars($valeur); ?>
</div>

<!-- ======================
     PARTIE CONTRÔLE ESP32
     ======================
     Ces boutons envoient des requêtes HTTP à l'ESP32
-->

<h1>Commande ESP32</h1>

<!-- Bouton pour activer le buzzer -->
<button onclick="fetch('http://IP_DE_L_ESP32/son')">
    🔊 Activer Buzzer
</button>

<!-- Bouton pour allumer la LED -->
<button onclick="fetch('http://IP_DE_L_ESP32/led')">
    💡 Allumer LED
</button>

</body>
</html>
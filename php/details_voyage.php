<?php

//Permet l'affichage des option détaillées d'un voyage et leurs modification

session_start();
//Gestion de l'utilisateur
require_once "php-include/utilisateur.php";
require_once "php-include/fonctions_voyages.php";

$identifiant_v = recup_id_voyage();

$utilisateur = connexionUtilisateurRequise($_SERVER["PHP_SELF"] . "?id=" . $identifiant_v);
if ($utilisateur != null && !utilisateurValide($utilisateur)) {
    die("Erreur : Utilisateur invalide");
}

//gestion des voyages

//Chargement des options déjà enregistrées du voyage
$voyage = null;
$opt_enr = null;  //options enregistrées 
//Verification : voyage déjà consulté ?
$opt_enr = optionVoyageConsulte($utilisateur, $identifiant_v);

//Verification : voyage déjà acheté ?
$modifiable = "";
$opt_achat = optionVoyageAchete($utilisateur, $identifiant_v);
if ($opt_achat != null) {
    if ($opt_enr != null) {
        die("Le voyage d'id $identifiant_v est déjà présent chez l'utilisateur
        dans catégorie voyages consulté, ne devrait pas être acheté.");
    }
    $modifiable = "disabled";   //le voyage n'est plus modifiable
    $opt_enr = $opt_achat;
}

$voyage = chargerVoyageParId($identifiant_v);
if ($voyage == null) {
    die("Erreur : ID de voyage $identifiant_v  introuvable ou corrompu.");
}
unset_donnees_sensibles_voyage($voyage);

$titre_page = $voyage["titre"];
$places = intval($voyage['nb_places_tot']);
$places_restantes = intval($voyage['nb_places_restantes']);
?><!DOCTYPE html>
<html lang="fr">

<head>
    <meta name="auteur" content="DIOP Bineta, CRISSOT Martin" />
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../style.css">
    <link rel="icon" type="image/x-icon" href="../img/logo.png">
    <meta name="description" content="Page de présentation du site et recherche rapide" />
    <title><?= $titre_page; ?> - PixelTravels</title>
</head>

<body>
    <script type="text/javascript">
        var voyage = <?= json_encode($voyage); ?>;
        var modifiable = <?= empty($modifiable) ? 'true' : 'false' ?>;
        var opt_enr = <?= json_encode($opt_enr); ?>;        
    </script>
    <?php
    require_once "php-include/header.php";
    ?>
    <main>
        <!-- Laisser vide, contenu généré dans generer_options_voyage.js -->
    </main>
    <br><br>
    <script src="../js/generer_options_voyage.js"></script>
    <script src="../js/prix_dyn.js"></script>
    <script src="../js/utiles.js"></script>
    <?php
    require_once "php-include/footer.php";
    ?>
</body>

</html>
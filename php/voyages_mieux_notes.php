<!DOCTYPE html>
<html lang="fr">
<head>
    <head>
    <meta name="auteur" content="DIOP Bineta" />
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../style.css">
    <link rel="icon" type="image/x-icon" href="../img/logo.png">
    <meta name="description" content="Page de présentation du site et recherche rapide" />
    <title>Les mieux notes - PixelTravels</title>
</head>
</head>
<body>

<?php
require_once "php-include/fonctions_voyages.php";
$fichier = "../donnees/voyage/voyages.json";

$voyages = trierVoyage($fichier);

//boucle 5 premiers voyages
$voyages = array_splice($voyages, 0, 5);

// Parcourir les 5 premiers éléments
    echo '<div class="mieux_notes">';
    echo "<center><h1>LES MIEUX NOTES<h1/></center>";

foreach ($voyages as $v) {
	$index=(int)$v['id'];
	$index--;
echo '<div class="info_voyage">
    <h2><a href="details_voyage.php?id='.$index.'">Voyage: ' . $v['titre'] . '</a></h2>
	   <p><strong>Note:</strong> ' . $v['note'] .' / 5</p>
	   <p><strong>Description:</strong> ' . $v['description'] . '</p>
	   	<p><strong>Durée:</strong> ' . $v['dates']['duree'] . ' jours</p></h5>
    <hr>
    </div>';
}
echo "</div>";

?>
	
</body>
</html>

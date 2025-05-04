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
    require_once "php-include/header.php";

    require_once "php-include/fonctions_voyages.php";
    $fichier = "../donnees/voyage/voyages.json";

    $voyages = trierVoyageParNote(5);

    //boucle 5 premiers voyages
    $voyages = array_splice($voyages, 0, 5);

    // Parcourir les 5 premiers éléments
    echo '<br><br><br><div class="mieux_notes">';
    echo '<div class="texte-centre"><h1>LES MIEUX NOTES<h1/></div>';

    foreach ($voyages as $v) {
        $index = (int) $v['id'];
        $index--;
        echo '<div class="info_voyage">
            <h2><a href="details_voyage.php?id=' . $index . '">' . $v["titre"] . '</a></h2>

	   <p><strong>Note:</strong> ' . $v['note'] . ' / 5</p>
	   <p><strong>Description:</strong> ' . $v['description'] . '</p>
	   	<p><strong>Durée:</strong> ' . $v['dates']['duree'] . ' jours</p></h5>
    <hr>
    </div>';
    }
    echo "</div>";

    require_once "php-include/footer.php";
    ?>
	<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>
    <script src="../js/mode.js">
    </script>
</body>

</html>

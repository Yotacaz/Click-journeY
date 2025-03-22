<?php
require_once "php-include/fonctions_voyages.php";
$voyages= decodageDonnees("DataVoyage001.json");
//$identifiant= $GET_["id"];
$identifiant= 0;
if ($identifiant > 14) {
    echo "cet identifiant n'existe pas";
    exit();
}

if(is_array($voyages)){ //decodage reussi on peut trier
$titre_page= $voyages[$identifiant]["titre"];}
    else{     // Si ce n'est pas un tableau, afficher le message d'erreur
        echo $voyages;
        exit();
    }
$v= array_splice($voyages ,$identifiant,1);  //ne garder que le jeu de la page concernee  
$v=$v[0];
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo "$titre_page"; ?></title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>


<?php echo "<h1>".$titre_page." - Détails du voyage</h1>"; ?>

<!-- Affichage des informations générales du voyage -->
<div class="description">
    <h3>Description</h3>
    <p><?php echo $v["description"]; ?></p>
</div>
<hr>
<div class="details_voyage">
    <h2><?php echo $v['titre']; ?> (Note: <?php echo $v['note']; ?>)</h2>
    <p><strong>Dates:</strong> <?php echo $v['dates']['debut']; ?> - <?php echo $v['dates']['fin']; ?></p>
    <p><strong>Durée:</strong> <?php echo $v['dates']['duree']; ?> jours</p>
</div>

<h3>Étapes du voyage</h3>

<!-- le formulaire pour modifier: -->
<form action="modif_voyage.php" method="POST">

<?php
// Parcourir chaque étape et afficher les options modifiables
foreach ($v['etapes'] as $etape_index => $etape) {
    echo '<div class="etape">';
    echo '<h4>' . $etape['nom'] . '</h4>';
    echo '<p><strong>Dates:</strong> ' . $etape['dates']['debut'] . ' - ' . $etape['dates']['fin'] . '</p>';
    echo '<p><strong>Durée:</strong> ' . $etape['dates']['duree'] . ' jours</p>';

    // Options à modifiable
    foreach ($etape['options'] as $option_index => $option) {
        echo '<div class="option">';
        echo '<label for="option_' . $etape_index . '_' . $option_index . '">' . $option['nom'] . ' (Prix par personne: ' . $option['prix_par_personne'] . ')</label>';
        echo '<select name="option_' . $etape_index . '_' . $option_index . '" id="option_' . $etape_index . '_' . $option_index . '">';
        
        // Afficher valeurs  l'option
        foreach ($option['valeurs_possibles'] as $valeur) {
            echo '<option value="' . $valeur . '">' . $valeur . '</option>';
        }

        echo '</select>';
        echo '<label for="nombre_personnes_' . $etape_index . '_' . $option_index . '">Nombre de personnes: </label>';
        echo '<input type="number" name="nombre_personnes_' . $etape_index . '_' . $option_index . '" value="' . $option['nombre_personnes'] . '" min="1" max="10"><br>';
        echo '</div>'; 
    }

    echo '</div><hr>'; 
}
?>


<div class="buttons">
    <input type="submit" value="Reservez Maintenant !">
</div>

</form>

</body>
</html>

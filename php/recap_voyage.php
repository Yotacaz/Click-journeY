<?php
session_start();
require_once "php-include/utilisateur.php";
require_once "php-include/fonctions_voyages.php";

$identifiant_v = recup_id_voyage();
$utilisateur = connexionUtilisateurRequise($_SERVER["PHP_SELF"] . "?id=" . $identifiant_v);
if ($utilisateur != null && !utilisateurValide($utilisateur)) {
    die("Erreur : Utilisateur invalide");
}

//Chargement des données voyages et options:
//Verification : voyage déjà consulté ?
$opt_enr = optionVoyageConsulte($utilisateur, $identifiant_v);

//Verification : voyage déjà acheté ?
$tmp = optionVoyageAchete($utilisateur, $identifiant_v);
if ($tmp != null) {
    if ($opt_enr != null) {
        die("Le voyage d'id $identifiant_v est déjà présent chez l'utilisateur
        dans catégorie voyages consulté, ne devrait pas être acheté.");
    }
    $opt_enr = $tmp;
}

$voyage = chargerVoyageParId($identifiant_v);
if ($voyage == null) {
    die("Erreur : ID de voyage $identifiant_v  introuvable ou corrompu.");
}

$titre_page = $voyage["titre"];
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta name="auteur" content="DIOP Bineta" />
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../style.css">
    <link rel="icon" type="image/x-icon" href="../img/logo.png">
    <meta name="description" content="Page de présentation du site et recherche rapide" />
    <title><?php echo $titre_page; ?> - PixelTravels</title>
</head>

<body>
    <?php
    require_once "php-include/header.php";
    ?>
    <br><br><br>
    <main>

        <h1 class="bandeau">Récapitulatif du voyage</h1>

        <div class="bandeau">

            <div class="voyage-details">
                <div class="flex">
                    <h2><?php echo $voyage['titre']; ?></h2>&nbsp - &nbsp<b> (Note: <?php echo $voyage['note']; ?>/5
                        ⭐)</b>
                </div>
                <ul>
                    <li><b>Description:</b> <?php echo $voyage['description']; ?></li>
                    <li><b>Dates:</b> <?php echo $voyage['dates']['debut']; ?> -
                        <?php echo $voyage['dates']['fin']; ?>
                    </li>
                    <li><b>Durée:</b> <?php echo $voyage['dates']['duree']; ?> jours</li>
                    <li><b>Prix total </b>(options incluses): <b><?php echo $opt_enr["prix"]; ?> €</b></li>
                </ul>
            </div>
        </div>

        <!-- Affichage -->
        <center>
            <br>
            <h2>Étapes du voyage</h2>
        </center>

        <?php
        $i = 0;
        foreach ($voyage['etapes'] as $etape_index => $etape) {
            $i++;
            echo '<div class="contour-bloc">';
            echo '<div class="texte-gauche">';
            echo "<h3>$i - " . $etape['nom'] . '</h3>';
            echo '<p><b>Dates:</b> ' . $etape['dates']['debut'] . ' - ' . $etape['dates']['fin'] . '</p>';
            echo '<p><b>Durée:</b> ' . $etape['dates']['duree'] . ' jours</p>';

            // Affichage des options modifiées
            foreach ($etape['options'] as $option_index => $option) {
                $nom_option_form = "option_$etape_index" . "_$option_index";
                $nom_nb_personne_form = "nombre_personnes_$etape_index" . "_$option_index";
                echo '<p><b>' . $option['nom'] . ':</b> ' . $opt_enr[$nom_nb_personne_form] . ' personnes choisies';
                if (isset($option['valeur_choisie'])) {
                    echo ' (Option choisie: ' . $opt_enr[$nom_option_form] . ')';
                }
                echo '</p>';
            }
            echo '</div>
                </div>';
        }
        if ($i == 0) {
            echo "<em>Aucune étape prévue...</em>";
        }
        ?>

        <div class="flex space-evenly">

            <button class="input-formulaire grand" name="valider-recherche"><a
                    href="details_voyage.php?id=<?php echo $identifiant_v; ?>">Revoir détail du voyage</a></button>
            <button class="input-formulaire grand" name="valider-recherche"><a
                    href="paiement.php?id=<?php echo $identifiant_v; ?>">Payer</a></button>

        </div>
        <p>

        </p>
    </main>
    <?php
    require_once "php-include/footer.php";
    ?>
</body>

</html>
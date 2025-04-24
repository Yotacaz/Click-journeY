<?php
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
    <title><?php echo $titre_page; ?> - PixelTravels</title>
</head>

<body>
    <?php
    require_once "php-include/header.php";
    ?>
    <main>
        <div class="bandeau-image">
            <img src='../img/banieres/<?php echo $voyage["image"][0]; ?>' alt="Logo ">
            <?php echo '<h1 class="centre">' . $titre_page . " - Détails du voyage</h1>"; ?>
        </div>

        <?php
        if (isset($_GET["erreur"])) {
            echo '<div class="erreur">';
            if ($_GET["erreur"] === "places_insuffisantes") {
                echo "<h1><b>Erreur : Nombre de personnes spécifié supérieur au nombre de places restantes.</b></h1>";
            }
            echo '</div>';
        }
        ?>

        <!-- Affichage des informations générales du voyage -->

        <div class="bandeau">
            <div>
                <h3>Description</h3>
                <em><?php echo $voyage["description"]; ?></em>
                <ul>
                    <li><b>Note : <?php echo $voyage['note']; ?>/5⭐</b></li>
                    <li> 🎮 &nbsp
                        <?php echo $voyage['genre'] . ", " . $voyage['theme']; ?>
                    </li>
                    <li> 📅 &nbsp
                        Du <b> <?php echo $voyage['dates']['debut']; ?> </b> au <b>
                            <?php echo $voyage['dates']['fin']; ?> </b>
                        (<?php echo $voyage['dates']['duree']; ?> jours)
                    </li>
                    <li>🌍 &nbsp<?php echo $voyage['localisation']['ville']
                        . " - " . $voyage['localisation']['pays'] ?></li>
                    <li>&nbsp <b>Prix unitaire</b> (sans les options) : <b><?php echo $voyage['prix_total'] ?> €</b>
                    </li>
                    <?php echo isset($opt_enr["prix"]) ? "<li>&nbsp <b>Prix enregistré</b> (avec les options) : <b>" . $opt_enr["prix"] . "€</b></li>" : ""; ?>
                    <li><?php echo "$places_restantes / $places places restantes" ?></li>
                </ul>
            </div>
        </div>

        <div class="bandeau">
            <h2>
                Réservation du voyage &nbsp
            </h2>
            <?php echo empty($modifiable) ? "" : " (modification impossible:&nbsp <b>voyage acheté</b>)" ?>
        </div>


        <br>
        <form action="<?php echo empty($modifiable) ? "modif_voyage.php?id=$identifiant_v" : ""; ?>" method="post">
            <div>
                <?php
                if ($opt_enr != null && isset($opt_enr["nombre_personnes_totales"])) {
                    $nb_personnes_total = intval($opt_enr["nombre_personnes_totales"]);
                } else {
                    $nb_personnes_total = 1;
                }
                echo '
                <div class="contour-bloc">
                <label> Nombre de personnes participant au voyage (option pour les groupes) <br>
                <em> (max : ' . $places_restantes . ' personnes) :</em>
                    <input type="number" name="nombre_personnes_totales" value="' . $nb_personnes_total . '" min="1" max="' . $places_restantes . '" ' . $modifiable . '>
                </label>
                </div>';
                ?>
            </div>


            <div class="texte-centre">
                <h2>Étapes du voyage</h2>
            </div>

            <!-- Formulaire global pour modifier plusieurs étapes -->

            <?php

            $i = 0;
            // Parcourir chaque étape et afficher les options modifiables
            foreach ($voyage['etapes'] as $etape_index => $etape) {
                $i++;
                echo '<div class="contour-bloc">';
                echo '<div class="texte-gauche">';
                echo '<h3>' . $i . " - " . $etape['nom'] . '</h3>';
                echo '
                📅 &nbsp
                        Du <b> ' . $etape['dates']['debut'] . '</b> au <b>
                            ' . $etape['dates']['fin'] . '</b>
                        (' . $etape['dates']['duree'] . ' jours)';

                // Options à modifier pour chaque étape
                echo "<p><br><b>Options :</b></p>
                        <ul>";

                foreach ($etape['options'] as $option_index => $option) {
                    $nom_option_form = "option_$etape_index" . "_$option_index";
                    $nom_nb_personne_form = "nombre_personnes_$etape_index" . "_$option_index";
                    echo '<li>
                            <label for="' . $nom_option_form . '">'
                        . $option['nom'] . ' (Prix par personne: ' . $option['prix_par_personne']
                        . ' €) </label>';

                    // Afficher les valeurs possibles pour l'option
                    if (!empty($option['valeurs_possibles'])) {
                        echo
                            '<select class="input-formulaire" name="' . $nom_option_form . '" 
                        id="' . $nom_option_form . '" ' . $modifiable . '>';
                        foreach ($option['valeurs_possibles'] as $valeur) {
                            if ($opt_enr != null && isset($opt_enr[$nom_option_form]) && $opt_enr["option_" . $etape_index . "_$option_index"] == $valeur) {
                                echo '<option selected value="' . $valeur . '">' . $valeur . '</option>';
                            } else {
                                echo '<option value="' . $valeur . '">' . $valeur . '</option>';
                            }
                        }
                    }
                    if ($opt_enr != null && isset($opt_enr[$nom_nb_personne_form])) {
                        $nb_personnes_option = intval($opt_enr[$nom_nb_personne_form]);
                    } else {
                        $nb_personnes_option = 1;
                    }
                    echo '</select><br>
                            <div title="si vous ne souhaitez pas participer, entrez 0, pour inviter un ami entrez 2 (max : ' . $option['nombre_personnes'] . ' invités)">
                                <label for="' . $nom_nb_personne_form . '">Nombre de personnes participant : </label>';
                    echo
                        '<input type="number" name="' . $nom_nb_personne_form . '" value="' . $nb_personnes_option . '" min="0" max="' . $nb_personnes_total . '" ' . $modifiable . '><br>
                            </div>
                            </li><br>';
                }
                echo "</ul>";
                echo '</div>';
                echo '</div>';
            }
            if ($i == 0) {
                echo '<em>Aucune étape trouvée.</em>';
            }
            ?>

            <!-- Soumettre le formulaire -->
            <?php
            echo empty($modifiable) ? '
            <div class="texte-centre">
                <button type="submit" name="submit_voyage" class="input-formulaire grand" ' . $modifiable . '>
                    Réservez Maintenant !
                </button>
                <br>
                <br>
            </div>' : "";
            ?>

        </form>
        <br><br>
    </main>
    <?php
    require_once "php-include/footer.php";
    ?>
</body>

</html>
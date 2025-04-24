<?php
session_start();
require_once "php-include/utilisateur.php";
$utilisateur = restaurerSessionUtilisateur();
if ($utilisateur != null && !utilisateurValide($utilisateur)) {
    die("Erreur : Utilisateur invalide");
}

require_once "php-include/fonctions_voyages.php";

$voyages = chargerVoyages();
$dossier_resultat = "../img/voyage/";
?><!DOCTYPE html>
<html>

<head>
    <meta name="auteur" content="DIOP Bineta,CRISSOT Martin" />
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../style.css">
    <link rel="icon" type="image/x-icon" href="../img/logo.png">
    <meta name="description" content="Recherche un voyage selon des catégories" />
    <title>Recherche - PixelTravels</title>
</head>


<body>
    <?php
    require_once "php-include/header.php";
    ?>
    <main>
        <?php if (!isset($voyages)) {
            die("Les voyages n'ont pas pu être chargé");
        }
        $nom_validation = "valider-recherche";

        //On initialise les variables de recherche
        $genre = "Tout";
        $theme = "Tout";
        $tri = "defaut"; //defaut, note, prix-croissant, prix-decroissant, date
        $prix_min = 0;
        $prix_max = 10000;
        $date_min = strtotime("2025-01-01");
        $date_max = strtotime("2050-12-31");
        $lieux = ["france", "etats-unis", "japon", "chine", "autre"];

        if (isset($_GET[$nom_validation])) {
            $genre = $_GET["genre"];
            $theme = $_GET["theme"];
            $tri = $_GET["tri"]; //defaut, note, prix-croissant, prix-decroissant, date, aleatoire
            $prix_min = $_GET["prix_min_nb"];
            $prix_max = $_GET["prix_max_nb"];
            $date_min = strtotime($_GET["date_min"]);
            $date_max = strtotime($_GET["date_max"]);
            if (isset($_GET["lieu"])) {
                if (!is_array($_GET["lieu"])) {
                    die("Erreur lors de la récupération des lieux (pas un tableau)");
                }
            } else {
                die("Erreur lors de la récupération des lieux");
            }
            $lieux = array_map('strtolower', $_GET["lieu"]);
        }
        ?>

        <div class="bandeau-image">
            <img src="../img/arcade.png" alt="image arcade">
            <div class="centre">
                <label for="recherche-textuelle">
                    Rechercher un jeu, une activité
                </label>
                <div>
                    <input name="recherche-textuelle" form="form-recherche" id="recherche-textuelle"
                        class="input-formulaire tres-grand bordure-violette" type="text"
                        placeholder="Recherchez un jeu..." maxlength="50">
                    <button name="<?php echo $nom_validation; ?>" form="form-recherche" class="sans-mise-en-forme"
                        type="submit"><img class="img-4em contour-img" src="../img/search.png"
                            alt="Rechercher"></button>
                </div>
            </div>
        </div>

        <div class="texte-centre :">
            <h1>Recherche avancée</h1>
            <form action="#" method="get" id="form-recherche">
                <?php

                echo '
                <div class="flex">
                    <div class="flex marge-droite">
                        <h3><label for="genre">Genre : &nbsp;</label></h3>
                        <select class="filtre_genre input-formulaire" name="genre" id="genre">
                            <option ' . ($genre === "Tout" ? "selected" : "") . ' value="Tout">Tout</option>
                            <option ' . ($genre === "MMORPG" ? "selected" : "") . ' value="MMORPG">MMORPG</option>
                            <option ' . ($genre === "Aventure" ? "selected" : "") . ' value="Aventure">Aventure</option>
                            <option ' . ($genre === "Sport" ? "selected" : "") . ' value="Sport">Sport</option>
                            <option ' . ($genre === "Action" ? "selected" : "") . ' value="Action">Action</option>
                            <option ' . ($genre === "Fiction interactive" ? "selected" : "") . ' value="Fiction interactive">Fiction</option>
                            <option ' . ($genre === "Strategie" ? "selected" : "") . ' value="Strategie">Stratégie</option>
                            <option ' . ($genre === "Bac a sable" ? "selected" : "") . 'value="Bac a sable">Bac a sable</option>
                        </select>
                    </div>

                    <div class="flex marge-droite">
                        <h3><label for="theme">Thème : &nbsp;</label></h3>
                        <select class="filtre_theme input-formulaire" name="theme" id="theme">
                            <option ' . ($theme === "Tout" ? "selected" : "") . ' selected value="Tout">Tout</option>
                            <option ' . ($theme === "Guerre" ? "selected" : "") . ' value="Guerre">Guerre</option>
                            <option ' . ($theme === "Science fiction" ? "selected" : "") . ' value="Science fiction">Science</option>
                            <option ' . ($theme === "Fantasy" ? "selected" : "") . ' value="Fantasy">Fantasy</option>
                            <option ' . ($theme === "Horreur" ? "selected" : "") . ' value="Horreur">Horreur</option>
                            <option ' . ($theme === "Dystopie" ? "selected" : "") . ' value="Dystopie">Dystopie</option>
                            <option ' . ($theme === "Super Hero" ? "selected" : "") . ' value="Super Hero">Super Hero</option>
                            <option ' . ($theme === "Espace" ? "selected" : "") . ' value="Espace">Espace</option>
                            <option ' . ($theme === "Medieval" ? "selected" : "") . ' value="Medieval">Medieval</option>
                            <option ' . ($theme === "City" ? "selected" : "") . '  value="City">City</option>
                            <option ' . ($theme === "Aquatique" ? "selected" : "") . '  value="Aquatique">Aquatique</option>
                            <option ' . ($theme === "Nature" ? "selected" : "") . '  value="Nature">Nature</option>
                        </select>
                    </div>
                </div>
                <div class="flex">
                    <h3><label for="tri">Trier par : &nbsp;</label></h3>
                    <select class="input-formulaire" name="tri" id="tri">
                        <option ' . ($tri === "defaut" ? "selected" : "") . '  value="defaut">Défaut</option>
                        <option ' . ($tri === "note" ? "selected" : "") . ' value="note">Note</option>
                        <option ' . ($tri === "prix-croissant" ? "selected" : "") . ' value="prix-croissant">Prix croissant</option>
                        <option ' . ($tri === "prix-decroissant" ? "selected" : "") . ' value="prix-decroissant">Prix décroissant</option>
                        <option ' . ($tri === "date" ? "selected" : "") . ' value="date">Date</option>
                    </select>
                </div>

                <div class="separateur-section-haut">
                    <h2>Prix</h2>
                    <div class="flex">
                        <label class="flex" for="prix_min_range">
                            Prix minimum :&nbsp;
                            <input type="range" name="prix_min_range" id="prix_min_range" value="' . $prix_min . '" min="0"
                                max="10000" step="100" oninput="this.form.prix_min_nb.value=this.value">
                        </label>
                        <input type="number" name="prix_min_nb" value="' . $prix_min . '" min="0" max="10000" step="100"
                            oninput="this.form.prix_min_range.value=this.value">
                        &nbsp;€
                    </div>
                    <div class="flex">
                        <label class="flex" for="prix_max_range">
                            Prix maximum :
                            <input type="range" name="prix_max_range" id="prix_max_range" value="' . $prix_max . '" min="0"
                                max="10000" step="100" oninput="this.form.prix_max_nb.value=this.value">
                        </label>
                        <input type="number" name="prix_max_nb" value="' . $prix_max . '" min="0" max="10000" step="100"
                            oninput="this.form.prix_max_range.value=this.value">
                        &nbsp;€
                    </div>

                </div>
                <div class="separateur-section-haut">
                    <h2>Date</h2>
                    <label for="date_min">Date minimum :</label>
                    <input class="input-formulaire" type="date" name="date_min" id="date_min" value="' . date("Y-m-d", $date_min) . '">
                    <label for="date_max">Date maximum :</label>
                    <input class="input-formulaire" type="date" name="date_max" id="date_max" value="' . date("Y-m-d", $date_max) . '">
                </div>
                <div class="separateur-section-haut">
                    <h2>Lieux</h2>
                    <input type="checkbox" name="lieu[]" id="lieu-1" value="france" ' . (in_array("france", $lieux) ? "checked" : "") . '>
                    <label for="lieu-1">France</label>
                    <input type="checkbox" name="lieu[]" id="lieu-2" value="etats-unis" ' . (in_array("etats-unis", $lieux) ? "checked" : "") . '>
                    <label for="lieu-2">États-Unis</label>
                    <input type="checkbox" name="lieu[]" id="lieu-3" value="japon" ' . (in_array("japon", $lieux) ? "checked" : "") . '>
                    <label for="lieu-3">Japon</label>
                    <input type="checkbox" name="lieu[]" id="lieu-4" value="chine" ' . (in_array("chine", $lieux) ? "checked" : "") . '>
                    <label for="lieu-4">Chine</label>
                    <input type="checkbox" name="lieu[]" id="lieu-5" value="autre" ' . (in_array("autre", $lieux) ? "checked" : "") . '>
                    <label for="lieu-5">Autre</label>
                </div>';

                ?>
                <div class="separateur-section-haut contenu-centre">
                    <p><br></p>
                    <button class="input-formulaire grand" name="<?php echo $nom_validation; ?>">Rechercher</button>
                </div>
            </form>
            <h1>Résultats</h2>
                <?php
                $voyage_dispo = [];
                foreach ($voyages as $voyage) {
                    $places = intval($voyage['nb_places_tot']);
                    $places_restantes = $places - count($voyage['email_personnes_inscrites']);
                    if ($places_restantes < 2) {
                        //on n'affiche pas les voyages avec seulement une place restante pour avoir une marge
                        continue;
                    }
                    $voyage_dispo[] = $voyage;
                }

                if (isset($_GET[$nom_validation])) {
                    $resultats = [];
                    foreach ($voyage_dispo as $voyage) {
                        if (strtolower($genre) !== "tout" && strtolower($voyage["genre"]) !== strtolower($genre)) {
                            continue;
                        }
                        if (strtolower($theme) !== "tout" && strtolower($voyage["theme"]) !== strtolower($theme)) {
                            continue;
                        }
                        if ($voyage["prix_total"] < $prix_min) {
                            continue;
                        }
                        if ($voyage["prix_total"] > $prix_max) {
                            continue;
                        }
                        $date_debut = strtotime($voyage["dates"]["debut"]);
                        $date_fin = strtotime($voyage["dates"]["fin"]);
                        if ($date_min > $date_debut || $date_max < $date_fin) {
                            continue;
                        }
                        ;
                        if (!(in_array(strtolower($voyage["localisation"]["pays"]), $lieux) || (in_array("autre", $lieux) && !(in_array(strtolower($voyage["localisation"]["pays"]), ["france", "etats-unis", "japon", "chine"]))))) {
                            continue;
                        }

                        $resultats[] = $voyage;
                    }


                    switch ($tri) {
                        case "defaut":
                            // shuffle($resultats);
                            break;
                        case "note":
                            $note = array_column($resultats, 'note');
                            if (!array_multisort($note, SORT_DESC, $resultats)) {
                                die("Erreur lors du tri par note");
                            }
                            break;
                        case "prix-croissant":
                            $prix = array_column($resultats, 'prix_total');
                            if (!array_multisort($prix, SORT_ASC, $resultats)) {
                                die("Erreur lors du tri par prix");
                            }
                            ;
                            break;
                        case "prix-decroissant":
                            $prix = array_column($resultats, 'prix_total');
                            if (!array_multisort($prix, SORT_DESC, $resultats)) {
                                die("Erreur lors du tri par prix");
                            }
                            break;
                        case "date":
                            $date_debut = array_column($resultats, 'dates', 'debut');
                            if (!array_multisort($date_debut, SORT_ASC, $resultats)) {
                                die("Erreur lors du tri par date");
                            }
                            break;
                        default:
                            die("Erreur lors du tri");
                    }
                    $voyages = $resultats;

                }
                ?>

                <?php
                $nb_elem = count($voyages);
                $elem_par_page = 8;
                $form_id = "form-recherche";
                require_once "php-include/compteur_page.php";
                ?>
                <div class="resultat" id="resultat">
                    <?php
                    if ($nb_elem > 0) {
                        $j = ($page_active - 1) * $elem_par_page;
                        for ($i = $j; $i < min($j + $elem_par_page, $nb_elem); $i++) {
                            $voyage = $voyages[$i];
                            afficherResumeVoyage($voyage);
                            // echo "$i ";
                        }
                    }
                    ?>
                </div>
        </div>
    </main>
    <?php
    require_once "php-include/footer.php";
    ?>

</body>

</html>
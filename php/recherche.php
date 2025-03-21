<?php
require_once "php-include/fonctions_voyages.php";

$voyages = chargerVoyages();
$dossier_resultat = "../img/voyage/";

?>

<!DOCTYPE html>
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
        ?>

        <div class="bandeau-image">
            <img src="../img/arcade.png" alt="image arcade">
            <div class="centre">
                <label for="recherche">
                    Rechercher un jeu, une activité
                </label>
                <div>
                    <input form="form-recherche" id="recherche" class="input-formulaire tres-grand bordure-violette"
                        type="text" placeholder="Recherchez un jeu..." maxlength="50">
                    <button form="form-recherche" class="sans-mise-en-forme" type="submit"><img
                            class="img-4em contour-img" src="../img/search.png" alt="Rechercher"></button>
                </div>
            </div>
        </div>


        <form action="#" method="get" id="form-recherche">
        </form>

        <div class="texte-centre :">
            <h1>Recherche avancée</h1>
            <form action="#" method="get">
                <h2>Trier par genre</h2>
                <div>
                    <select class="filtre_genre input-formulaire" name="genre">
                        <option value="Tout">Tout</option>
                        <option value="MMORPG">MMORPG</option>
                        <option value="Aventure">Aventure</option>
                        <option value="Sport">Sport</option>
                        <option value="Action">Action</option>
                        <option value="MMORPG">MMORPG</option>
                        <option value="Aventure">Aventure</option>
                        <option value="Fiction interactive">Fiction</option>
                        <option value="Stratègie">Stratègie</option>
                    </select>
                </div>
                <div class="separateur-section-haut">

                    <h2>Trier par thème</h2>
                    <select class="filtre_theme input-formulaire" name="theme">
                        <option value="Tout">Tout</option>
                        <option value="Guerre">Guerre</option>
                        <option value="Science fiction">Science</option>
                        <option value="Fantasy">Fantasy</option>
                        <option value="Horreur">Horreur</option>
                        <option value="Dystopie">Dystopie</option>
                        <option value="Super Hero">Super Hero</option>
                        <option value="Espace">Espace</option>
                        <option value="Medieval">Medieval</option>
                        <option value="City">City</option>
                        <option value="Aquatique">Aquatique</option>
                    </select>
                    <br>
                </div>

                <div class="separateur-section-haut">
                    <h2>Trier par prix</h2>
                    <input type="radio" name="prix" id="prix-0" checked value="aucun">
                    <label for="prix-0">Aucun filtre</label>
                    <input type="radio" name="prix" id="prix-1" value="croissant">
                    <label for="prix-1">Prix croissant</label>
                    <input type="radio" name="prix" id="prix-2" value="decroissant">
                    <label for="prix-2">Prix décroissant</label>
                    <br>
                    <div class="flex">
                        <label class="flex" for="prix_min_range">
                            Prix minimum :&nbsp;
                            <input type="range" name="prix_min_range" id="prix_min_range" value="100" min="0"
                                max="25000" step="100" oninput="this.form.prix_min_nb.value=this.value">
                        </label>
                        <input type="number" name="prix_min_nb" value="100" min="0" max="25000" step="100"
                            oninput="this.form.prix_min_range.value=this.value">
                        &nbsp;€
                    </div>
                    <div class="flex">
                        <label class="flex" for="prix_max_range">
                            Prix maximum :
                            <input type="range" name="prix_max_range" id="prix_max_range" value="25000" min="0"
                                max="25000" step="100" oninput="this.form.prix_max_nb.value=this.value">
                        </label>
                        <input type="number" name="prix_max_nb" value="25000" min="0" max="25000" step="100"
                            oninput="this.form.prix_max_range.value=this.value">
                        &nbsp;€
                    </div>

                </div>
                <div class="separateur-section-haut">
                    <h2>Trier par date</h2>
                    <label for="date_min">Date minimum :</label>
                    <input class="input-formulaire" type="date" name="date_min" id="date_min" value="2025-01-01">
                    <label for="date_max">Date maximum :</label>
                    <input class="input-formulaire" type="date" name="date_max" id="date_max" value="2050-12-31">
                </div>
                <div class="separateur-section-haut">
                    <h2>Trier par lieux</h2>
                    <input type="checkbox" name="lieu" id="lieu-1" value="france" checked>
                    <label for="lieu-1">France</label>
                    <input type="checkbox" name="lieu" id="lieu-2" value="etats-unis" checked>
                    <label for="lieu-2">États-Unis</label>
                    <input type="checkbox" name="lieu" id="lieu-3" value="japon" checked>
                    <label for="lieu-3">Japon</label>
                    <input type="checkbox" name="lieu" id="lieu-4" value="chine" checked>
                    <label for="lieu-4">Chine</label>
                    <input type="checkbox" name="lieu" id="lieu-5" value="autre" checked>
                    <label for="lieu-5">Autre</label>
                </div>
                <div class="separateur-section-haut contenu-centre">
                    <p><br></p>
                    <button class="input-formulaire grand">Rechercher</button>
                </div>
            </form>
            <h1>Résultats</h2>
                <?php
                $nb_elem = count($voyages);
                $elem_par_page = 8;
                require_once "php-include/compteur_page.php";
                ?>
                <div class="resultat" id="resultat">
                    <?php
                    $j = $_SESSION["page"]["recherche.php"] * $elem_par_page;
                    for ($i = $j; $i < min($j + $elem_par_page, $nb_elem); $i++) {
                        $voyage = $voyages[$i];
                        afficherResumeVoyage($voyage);
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
<?php

//restauration des données utilisateurs
session_start();
require_once "php-include/utilisateur.php";
$utilisateur = restaurerSessionUtilisateur();
if ($utilisateur != null && !utilisateurValide($utilisateur)) {
    die("Erreur : Utilisateur invalide");
}
require_once "php-include/fonctions_voyages.php";

?><!DOCTYPE html>
<html>

<script src="../js/form.js" defer type="module"></script>

<head>
    <meta name="auteur" content="DIOP Bineta" />
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../style.css">
    <link rel="icon" type="image/x-icon" href="../img/logo.png">
    <meta name="description" content="Page de présentation du site et recherche rapide" />
    <title>Accueil - PixelTravels</title>
</head>

<body>
    <?php
    require_once "php-include/header.php";
    ?>
    <main>
        <p></p>
        <div class="presentation">
            <div class="texte_pre">
                <h2>Voyager à travers l'univers des jeux vidéo : une aventure immersive à ne pas manquer</h2>

                Bienvenue sur notre site de voyage dédié aux passionnés de jeux vidéo ! Explorez des destinations où le
                monde virtuel prend vie, et plongez au cœur des paysages, des cultures et des univers qui ont inspiré
                vos jeux préférés.
                <br>
                Que vous soyez un fan de fantasy, un <em>explorateur de mondes futuristes ou un amateur de
                    rétro-gaming</em>, nos voyages vous emmènent dans des lieux iconiques qui ont marqué l'histoire du
                jeu vidéo.
                <br>
                Partez sur les traces des héros légendaires, visitez les studios de création de vos jeux favoris et
                découvrez des événements exclusifs où la réalité et le virtuel se rencontrent. Prêt à embarquer pour une
                aventure qui dépasse les limites du possible ? Le voyage commence ici.

            </div>

        </div>
        <!-- <table class="menu_selecteur">
            <tr>
                <th> Les plus populaires </th>
            </tr>
            <tr>
                <td>Minecraft</td>
            </tr>
            <tr>
                <td><a href="gta5.php">GTA 5</a></td>
            </tr>
            <tr>
                <td>The Last of Us</td>
            </tr>
            <tr>
                <td>God of War</td>
            </tr>
        </table> -->

        <p><br></p>
        <div class="contour-bloc">
            <label class="grand" for="recherche">
                Rechercher un jeu, une activité
            </label>
            <form action="#resultats" id="form-recherche" method="get" class="centre-sans-debordement">
                <div class="enveloppe-input flex">
                    <div class="input-formulaire tres-grand bordure-violette">
                        <input name="recherche-textuelle" form="form-recherche" id="recherche-textuelle"
                            class="tres-grand" type="text" placeholder="Recherchez un jeu..."
                            maxlength="<?= MAX_STRING_LENGTH ?>">
                        <b class="compteur">0/<?= MAX_STRING_LENGTH ?></b>
                    </div>
                    <p class="message-erreur"></p>
                </div>
                <button name="rechercher" form="form-recherche" class="sans-mise-en-forme" type="submit"><img
                        class="img-4em contour-img" src="../img/search.png" alt="Rechercher"></button>
            </form>
        </div>
        <?php
        if (!empty($_GET['recherche-textuelle'])) {
            $voyages = rechercheTextuelle($_GET['recherche-textuelle']);
        } else {
            $voyages = trierVoyageParNote(6);
        }
        ?>
        <div class="texte-centre">
            <h1>Selection de voyages :</h1>
            <div class="resultats" id="resultats">
                <?php
                $nb_elem = count($voyages);
                if ($nb_elem > 0) {

                    for ($i = 0; $i < $nb_elem; $i++) {
                        afficherResumeVoyage($voyages[$i]);
                    }
                } else {
                    echo "<em>Aucun voyage correspondant à votre recherche.</em>";
                }
                ?>
            </div>
        </div>
        <div class="texte-centre"><a class="input-formulaire" href="recherche.php">→ Voir plus</a></div>
        <p><br></p>
    </main>

    <?php
    require_once "php-include/footer.php";
    ?>
</body>

</html>

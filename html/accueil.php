<!DOCTYPE html>
<html>

<head>
    <meta name="auteur" content="DIOP Bineta" />
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../style.css">
    <link rel="icon" type="image/x-icon" href="../img/logo.png">
    <meta name="description" content="Page de présentation du site et recherche rapide" />
    <title>Accueil - PixelTravels</title>
    <?php $titre_page = "accueil"; ?>
</head>

<body>
    <?php
    require_once "php-include/header.php";
    ?>
    <main>
        <p></p>
        <div class="presentation">
            <div class="texte_pre">
                <h2>Voyagez à travers l'univers des jeux vidéo : une aventure immersive à ne pas manquer</h2>

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
        <table class="menu_selecteur">
            <tr>
                <th> Les plus populaires </th>
            </tr>
            <tr>
                <td>Minecraft</td>
            </tr>
            <tr>
                <td><a href="gta5.html">GTA 5</a></td>
            </tr>
            <tr>
                <td>The Last of Us</td>
            </tr>
            <tr>
                <td>God of War</td>
            </tr>
        </table>
        <p><br></p>
        <div class="contour-bloc">
            <label class="grand" for="recherche">
                Rechercher un jeu, une activité
            </label>
            <div>
                <input form="form-recherche" id="recherche" class="input-formulaire tres-grand bordure-violette"
                    type="text" placeholder="Recherchez un jeu..." maxlength="50">
                <button form="form-recherche" class="sans-mise-en-forme" type="submit"><img class="img-4em contour-img"
                        src="../img/search.png" alt="Rechercher"></button>
            </div>
        </div>


    </main>

    <?php
    require_once "php-include/footer.php";
    ?>

</body>

</html>
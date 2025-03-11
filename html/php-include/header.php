<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../style.css">
</head>


<body>
    <?php

    if (!isset($titre_page)) {
        echo "titre de page non définis";
        die("titre de page non définis");
    }
    function classe_actif($page_active, $page): void
    {
        if ($page == $page_active) {
            echo ' class="actif"';
        }
    }

    $currentPage = $_SERVER["PHP_SELF"];
    ;
    echo basename(path: $currentPage);
    ?>

    <div class="header">
        <div class="sectionH">
            <a href="../index.html">
                <img class="icone-site" src="../img/logo.png" alt="Logo.png">
                <h1>PixelTravels</h1>
            </a>
        </div>
        <div class="sectionH">
            <nav class="navbar">

                <a href="accueil.html" <?php
                classe_actif($titre_page, "accueil");
                ?>>
                    Accueil</a>
                <a href="recherche.html" <?php
                classe_actif($titre_page, "recherche");
                ?>>
    <img class="img-1em" src="../img/search.png" alt="🔍">&nbsp;Rechercher
                </a>

            </nav>
        </div>
        <div class="sectionH">
            <nav class="navbar">
                <a href="Connexion.html" <?php
                classe_actif($titre_page, "connexion");
                ?>>Connexion</a>
                <a href="inscription.html" <?php
                classe_actif($titre_page, "inscription");
                ?>>Inscription</a>
                <a href="profil.html" <?php
                classe_actif($titre_page, "profil");
                ?>>Profil</a>
                <!-- <a href="#">Admin</a> -->
                <!-- Si connecté : page profil, si admin : page admin ?? -->
            </nav>
        </div>
    </div>
</body>

</html>
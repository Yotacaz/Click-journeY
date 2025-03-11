<?php

if (!isset($_SERVER["PHP_SELF"])) {
    echo "Des variables headers ne sont pas définies.";
    die("Des variables headers ne sont pas définies.");
}
function classe_actif($page_active, $page): void
{
    if ($page == $page_active) {
        echo ' class="actif"';
    }
}
$page_active = basename(path: $_SERVER["PHP_SELF"]);
?>

<div class="header">
    <div class="sectionH">
        <a href="../index.php">
            <img class="icone-site" src="../img/logo.png" alt="Logo.png">
            <h1>PixelTravels</h1>
        </a>
    </div>
    <div class="sectionH">
        <nav class="navbar">

            <a href="accueil.php" <?php
            classe_actif($page_active, "accueil.php");
            ?>>
                Accueil</a>
            <a href="recherche.php" <?php
            classe_actif($page_active, "recherche.php");
            ?>>
  <img class="img-1em" src="../img/search.png" alt="🔍">&nbsp;Rechercher
            </a>

        </nav>
    </div>
    <div class="sectionH">
        <nav class="navbar">
            <a href="connexion.php" <?php
            classe_actif($titre_page, "connexion.php");
            ?>>Connexion</a>
            <a href="inscription.php" <?php
            classe_actif($titre_page, "inscription.php");
            ?>>Inscription</a>
            <a href="profil.php" <?php
            classe_actif($titre_page, "profil.php");
            ?>>Profil</a>
            <!-- <a href="#">Admin</a> -->
            <!-- Si connecté : page profil, si admin : page admin ?? -->
        </nav>
    </div>
</div>
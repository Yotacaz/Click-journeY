<style>
    <?php include_once "header.css"; ?>
</style>
<?php

if (!isset($_SERVER["PHP_SELF"])) {
    die("Des variables serveurs ne sont pas définies.");
}
if (!isset($utilisateur) || empty($utilisateur)) {
    $utilisateur = null;
}
function classe_actif($page_active, $page): void
{
    if ($page == $page_active) {
        echo ' class="actif"';
    }
}
$page_active = basename(path: htmlspecialchars($_SERVER["PHP_SELF"]));
?>

<div class="header">
    <div class="sectionH">
        <a href="../index.php">
            <img class="icone-site" src="../img/logo.png" alt="Logo.png">
            <h1 class="cacher">PixelTravels</h1>
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
  <img class="img-1em cacher" src="../img/search.png" alt="🔍">&nbsp;Rechercher
            </a>

        </nav>
    </div>
    <div class="sectionH">
        <nav class="navbar">
            <?php
            if ($utilisateur!=null) {
                
                echo '<a href="profil.php" ';
                classe_actif($page_active, "profil.php");
                echo '>Profil</a>';
                if ($utilisateur["role"]==="admin") {
                    echo '<a href="admin.php" ';
                    classe_actif($page_active, "admin.php");
                    echo '>Admin</a>';
                }
                echo '<a href="php-include/deconnexion.php" class="cacher" ';
                echo '>Déconnexion</a>';
            } else {
                echo '<a href="connexion.php" ';
                classe_actif($page_active, "connexion.php");
                echo '>Connexion</a>';
                echo '<a href="inscription.php" ';
                classe_actif($page_active, "inscription.php");
                echo '>Inscription</a>';
            }
            ?>
            <!-- <a href="connexion.php" <?php
            classe_actif($page_active, "connexion.php");
            ?>>Connexion</a>
            <a href="inscription.php" <?php
            classe_actif($page_active, "inscription.php");
            ?>>Inscription</a>
            <a href="profil.php" <?php
            classe_actif($page_active, "profil.php");
            ?>>Profil</a> -->
            <!-- <a href="#">Admin</a> -->
            <!-- Si connecté : page profil, si admin : page admin ?? -->
        </nav>
    </div>
</div>
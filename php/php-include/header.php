<!-- baniere / haut de page -->

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
        <a href="../index.html">
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
            <div class="menu-deroulant">
                <?php
                $contenu_menu_deroulant = ["profil", "panier", "connexion", "inscription", "admin"];
                $dans_menu_deroulant = in_array(basename($page_active, ".php"), $contenu_menu_deroulant);
                ?>
                <a class="contenu-affiche-menu <?= $dans_menu_deroulant ? " actif" : "" ?>" href="#">
                    &#9207; <p class="cacher" href="#">
                        <?= $utilisateur != null ? $utilisateur["info"]["prenom"] : "Compte" ?>
                    </p> &nbsp;
                    <img src="../img/profile-circle-icon-.png" alt="icône compte">
                </a>
                <div class="contenu-menu">
                    <?php
                    if ($utilisateur != null) {
                        echo '<a href="profil.php" ';
                        classe_actif($page_active, "profil.php");
                        echo '>Profil</a>';
                        if ($utilisateur["role"] === "admin") {
                            echo '<a href="admin.php" ';
                            classe_actif($page_active, "admin.php");
                            echo '>Admin</a>';
                        }
                        echo '<a href="panier.php"';
                        classe_actif($page_active, "panier.php");
                        echo '>Panier</a> ';
                        echo '<a href="php-include/deconnexion.php">Déconnexion</a>';
                    } else {
                        echo '<a href="connexion.php" ';
                        classe_actif($page_active, "connexion.php");
                        echo '>Connexion</a>';
                        echo '<a href="inscription.php" ';
                        classe_actif($page_active, "inscription.php");
                        echo '>Inscription</a>';
                    }
                    ?>
                    <span><input type="checkbox" name="theme" id="theme" class="modif-theme" onchange="changerTheme()"><label for="theme"
                            id="mode">&nbsp;Thème inverse</label></span>
                </div>
            </div>
        </nav>
    </div>

</div>

<script src="../js/mode.js"></script>
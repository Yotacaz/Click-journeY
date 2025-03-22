<!DOCTYPE html>
<html lang="fr">

<?php

session_start();
require_once "php-include/utilisateur.php";
require_once "php-include/fonctions_voyages.php";
//TODO A supprimer
$_SESSION["utilisateur"] = chargerUtilisateurParEmail("bob@bob.bob");
if (!isset($_SESSION["utilisateur"]) || !utilisateurValide($_SESSION["utilisateur"])) {
    header("Location: connexion.php");
    exit;
}
$utilisateur = $_SESSION["utilisateur"];


?>

<head>
    <meta name="auteur" content="CRISSOT Martin" />
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../style.css">
    <link rel="icon" type="image/x-icon" href="../img/logo.png">
    <meta name="description" content="modification profil utilisateur" />
    <title>Profil - PixelTravels</title>
</head>


<body>
    <?php
    require_once "php-include/header.php";
    ?>
    <main>
        <div class="bandeau">
            <h1><img src="../img/profile-circle-icon-.png" alt="icône de profil">&nbsp;Bonjour
                <?php echo $utilisateur["info"]["prenom"]; ?> !
            </h1>
        </div>
        <div class="contour-bloc scrollable">
            <h2>Vos voyages prévus : </h2>
            <ul>
                <?php
                if (!isset($voyages)) {
                    die("Les voyages n'ont pas pu être chargé");
                }
                foreach ($utilisateur["voyages"]["achetes"] as $nom_voyage) {
                    $voyage = chargerVoyageParNom($nom_voyage);
                    if ($voyage != null) {
                        echo "<li><b>" . $voyage["nom"] . "</b> - " . $voyage["destination"] . ", " . $voyage["pays"] . ", le " . $voyage["date"] . "</li>";
                    }
                }
                ?>
            </ul>
            <ul>
                <li><b>Animal Crossing</b> - États Unis, Hawaï, le 03/08/2025</li>
                <li><a href="gta5.php">GTA V</a><b></b> - États Unis, Los Angeles, le 11/07/2025 </li>
            </ul>
        </div>
        <div class="bloc">
            <h2>Votre profil :</h2>
            <br>
            <form class="grille3" action="#" method="post" name="profil">

                <label for="nom" class="col1">Nom : </label>
                <input class="col2 " type="text" name="nom" id="nom" disabled placeholder="NOM"
                    value="<?php echo $utilisateur["info"]["nom"]; ?>">

                <button title="modifier" class="col3" id="modifierNom" name="modifierNom"><img src="../img/modifier.png"
                        alt="modifier"></button>

                <label for="prenom" class="col1">Prénom : </label>
                <input class="col2" type="text" name="prenom" id="prenom" disabled contenteditable="false"
                    placeholder="Prénom" value="<?php echo $utilisateur["info"]["prenom"]; ?>">
                <button title="modifier" class="col3" id="modifierPrenom" type="button"><img src="../img/modifier.png"
                        alt="modifier"></button>


                <label for="adresse" class="col1">Adresse :</label>
                <input class="col2" type="email" name="adresse" id="adresse" placeholder="adresse@email.exemple"
                    value="<?php echo $utilisateur["email"]; ?> " disabled>
                <button title="modifier" class="col3" id="modifierEmail" type="button"><img src="../img/modifier.png"
                        alt="modifier"></button>

                <label for="mdp" class="col1">Mot de passe :</label>
                <input class="col2" type="password" name="mot de passe" id="mdp" placeholder="Entrez un mot de passe"
                    value="<?php echo $utilisateur["mdp"]; ?>" disabled>
                <button title="modifier" class="col3" id="modifierMdp" type="button"><img src="../img/modifier.png"
                        alt="modifier"></button>

                <p class="col1">Genre :</p>
                <div class="col2">
                    <label for="genreH"><input type="radio" name="genre" id="genreH" <?php
                    if ($utilisateur["info"]["sexe"] == "homme") {
                        echo " checked ";
                    }
                    ?> disabled>Homme</label>
                    <label for="genreF"><input type="radio" name="genre" id="genreF" <?php
                    if ($utilisateur["info"]["sexe"] == "femme") {
                        echo " checked ";
                    } ?> disabled>Femme</label>
                    <label for="genreA"><input type="radio" name="genre" id="genreA" <?php
                    if ($utilisateur["info"]["sexe"] == "autre") {
                        echo " checked ";
                    }
                    ?>disabled>Autre</label>
                </div>
                <button title="modifier" class="col3" id="modifierGenre" type="button"><img src="../img/modifier.png"
                        alt="modifier"></button>
                <label class="col1" for="date">Date de naissance:</label>
                <input class="col2" type="date" name="date" id="date" min="1900-01-01"
                    value="<?php echo $utilisateur["info"]["date_naissance"]; ?>" disabled>
                <button title="modifier" class="col3" id="modifierDate" type="button"><img src="../img/modifier.png"
                        alt="modifier"></button>

            </form>

        </div>
        <form class="texte-centre" action="accueil.php" method="post">
            <input class="input-formulaire grand" type="submit" value="Déconnexion" name="deconnexion">
        </form>
    </main>
    <?php
    require_once "php-include/footer.php";
    ?>
</body>

</html>
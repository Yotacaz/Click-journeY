<?php
session_start();
require_once "../config.php";
require_once "php-include/utilisateur.php";
require_once "php-include/utiles.php";
$utilisateur = connexionUtilisateurRequise();
$erreur = "";
if (!utilisateurValide($utilisateur)) {
    $erreur = "Utilisateur invalide";
}

require_once "php-include/fonctions_voyages.php";
?><!DOCTYPE html>
<html lang="fr">


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
        <?php
        if ($erreur != "") {
            echo '<div class="erreur">' . $erreur . '</div>';
        }
        ?>
        <div class="bandeau">
            <h1><img src="../img/profile-circle-icon-.png" alt="icône de profil">&nbsp;Bonjour
                <?php echo $utilisateur["info"]["prenom"]; ?> !
            </h1>

        </div>
        <div class="contour-bloc scrollable">
            <h2 class="js-replier">Vos voyages prévus : </h2>
            <ul class="repliable">
                <?php

                if (count($utilisateur["voyages"]["achetes"]) == 0) {
                    echo "<li>Vous n'avez pas de voyage prévu</li>";
                }
                foreach ($utilisateur["voyages"]["achetes"] as $id_v => $opt_achat) {
                    $voyage = chargerVoyageParId($id_v);

                    if ($voyage != null) {
                        echo
                            '<li><b><a href="details_voyage.php?id=' . $id_v . '">' . $voyage["titre"] . '</a></b> - ' . $voyage["localisation"]["ville"]
                            . ', ' . $voyage["localisation"]["pays"] . ', du ' . $voyage["dates"]["debut"] . ' au ' . $voyage["dates"]["fin"] . ' pour
                            ' . $opt_achat["prix"] . '€ (' . $opt_achat["nombre_personnes_totales"] . ' personnes)</li>';
                    }
                }
                ?>
            </ul>
        </div>
        <div class="bloc" id="modifiable">
            <h2 class="js-replier">Votre profil :</h2>
            <form action="php-form/profil_modification.php" class="js-form repliable" method="post" name="form-profil"
                id="form-profil">
                <div class="grille3">
                    <p class="col1">Adresse mail:</p>
                    <p class="col2 enveloppe-input " id="email"><?php echo $utilisateur["email"]; ?> </p>
                    <br>
                    <!-- Pour des raisons de sécurité, l'adresse email ne doit pas pouvoir être modifiée-->

                    <!-- Mot de passe -->
                    <label id="label-mdp" for="mdp" class="col1">Mot de passe :</label>
                    <div class="col2 enveloppe-input">
                        <!-- Noter que l'input suivante n'a pas la classe js-a-verifier car le mot de passe n'est pas changé par défaut -->
                        <div class="fill-col">
                            <input class="desactivable js-mdp" type="password" name="mdp" id="mdp"
                                placeholder="Entrez un mot de passe" value="<?php
                                for ($i = 0; $i < strlen($utilisateur["mdp"]); $i++) {
                                    echo "*";
                                }
                                ?>" <?php echo ' maxlength="' . MAX_MDP_LENGTH . '" ' ?> readonly>
                            <span
                                class="compteur"><?php echo strlen($utilisateur["mdp"]) . "/" . MAX_MDP_LENGTH ?></span>
                            <!-- <button class="btn-img" title="voir" id="voir-mdp" type="button" onclick="voirMDP('mdp')">
                                <img src="../img/oeil.png" alt="voir"></button> -->
                        </div>
                        <p class="message-erreur"></p>
                    </div>

                    <div class="col3">
                        <button class="btn-img btn-annuler" title="annuler" id="annuler-mdp" type="button"
                            onclick="reinitialiserMDP()" hidden>
                            <b>X</b>
                        </button>
                        <button class="btn-img" title="modifier" id="modifier-mdp" type="button" onclick="modifMDP()">
                            <img src="../img/modifier.png" alt="modifier"></button>
                    </div>

                    <!-- confirmation mot de passe -->
                    <label for="mdp2" class="col1" id="label-mdp2" hidden>Confirmer mot de passe :</label>
                    <div class="col2 enveloppe-input" id="div-mdp2" hidden>
                        <!-- Noter que l'input suivante n'a pas la classe  car le mot de passe n'est pas changé par défaut -->
                        <div class="fill-col">
                            <input class="desactivable js-mdp" type="password" name="mdp2" id="mdp2"
                                placeholder="Confirmez le mot de passe" value="" readonly <?php echo ' maxlength="' . MAX_MDP_LENGTH . '" ' ?>>
                            <span class="compteur"><?php echo "0/" . MAX_MDP_LENGTH ?></span>

                            <!-- <button class="btn-img" title="voir" id="voir-mdp" type="button" onclick="la_fonction('id_de_input_mdp')">
                                <img src="../img/oeil.png" alt="voir"></button> -->
                        </div>
                        <p class="message-erreur"></p>
                    </div>
                    <br id="col3-mdp2" hidden>

                    <!-- nom -->
                    <label for="nom" class="col1">Nom : </label>
                    <div class="col2 enveloppe-input">
                        <div class="fill-col">
                            <input class="desactivable  js-nom" type="text" name="nom" id="nom" readonly
                                placeholder="NOM" value="<?php echo $utilisateur["info"]["nom"]; ?>" <?php echo ' maxlength="' . MAX_STRING_LENGTH . '"'; ?>>
                            <span
                                class="compteur"><?php echo strlen($utilisateur["info"]["nom"]) . "/" . MAX_STRING_LENGTH ?></span>

                        </div>
                        <p class="message-erreur"></p>
                    </div>
                    <div class="col3">
                        <button class="btn-img btn-annuler" title="annuler" id="annuler-nom" type="button"
                            onclick="reinitialiserInput('nom')" hidden>
                            <b>X</b>
                        </button>
                        <button class="btn-img" title="modifier" id="modifier-nom" type="button"
                            onclick="inputModifiable('nom')">
                            <img src="../img/modifier.png" alt="modifier"></button>
                    </div>

                    <!-- prénom -->
                    <label for="prenom" class="col1">Prénom : </label>
                    <div class="col2 enveloppe-input">
                        <div class="fill-col">
                            <input class="desactivable  js-prenom" type="text" name="prenom" id="prenom" readonly
                                contenteditable="false" placeholder="Prénom"
                                value="<?php echo $utilisateur["info"]["prenom"]; ?>" <?php echo ' maxlength="' . MAX_STRING_LENGTH . '" ' ?>>
                            <span
                                class="compteur"><?php echo strlen($utilisateur["info"]["prenom"]) . "/" . MAX_STRING_LENGTH ?></span>
                        </div>
                        <p class="message-erreur"></p>
                    </div>
                    <div class="col3">
                        <button class="btn-img btn-annuler" title="annuler" id="annuler-prenom" type="button"
                            onclick="reinitialiserInput('prenom')" hidden>
                            <b>X</b>
                        </button>
                        <button class="btn-img" title="modifier" id="modifier-prenom" type="button"
                            onclick="inputModifiable('prenom')">
                            <img src="../img/modifier.png" alt="modifier"></button>
                    </div>

                    <!-- genre -->
                    <p class="col1">Genre :</p>
                    <div class="col2">
                        <?php
                        switch ($utilisateur["info"]["sexe"]) {
                            case 'femme':
                                echo
                                    '<label class="desactivable" id="label-genreF" for="genreF"><input class="desactivable" type="radio" name="genre" id="genreF" value="femme" checked hidden>Femme</label>
                                    <label class="desactivable" id="label-genreH" for="genreH" hidden><input class="desactivable" type="radio" name="genre" id="genreH value="homme">Homme</label>
                                    <label class="desactivable" id="label-genreA" for="genreA" hidden><input class="desactivable" type="radio" name="genre" id="genreA" value="autre">Autre</label>';
                                break;
                            case 'homme':
                                echo
                                    '<label class="desactivable" id="label-genreF" for="genreF" hidden><input class="desactivable" type="radio" name="genre" id="genreF" value="femme">Femme</label>
                                    <label class="desactivable" id="label-genreH" for="genreH"><input class="desactivable" type="radio" name="genre" id="genreH" value="homme" checked hidden>Homme</label>
                                    <label class="desactivable" id="label-genreA" for="genreA" hidden><input class="desactivable" type="radio" name="genre" id="genreA" value="autre">Autre</label>';
                                break;
                            case 'autre':
                                echo
                                    '<label class="desactivable" id="label-genreF" for="genreF" hidden><input class="desactivable" type="radio" name="genre" id="genreF" value="femme">Femme</label>
                                    <label class="desactivable" id="label-genreH" for="genreH" hidden><input class="desactivable" type="radio" name="genre" id="genreH" value="homme">Homme</label>
                                    <label class="desactivable" id="label-genreA" for="genreA"><input class="desactivable" type="radio" name="genre" id="genreA" value="autre" checked hidden>Autre</label>';
                                break;
                            default:
                                die("Erreur : genre non reconnu");
                        }
                        ?>
                        <!-- pas d'erreur prévue ici -->
                    </div>
                    <div class="col3">
                        <button class="btn-img btn-annuler" title="annuler" id="annuler-genre" type="button"
                            onclick="reinitialiserRadio('annuler-genre', ['genreA','genreH','genreF'])" hidden>
                            <b>X</b>
                        </button>
                        <button class="btn-img" title="modifier" id="modifier-genre" type="button"
                            onclick="afficherBtnRadio('annuler-genre', ['genreA','genreH','genreF'])">
                            <img src="../img/modifier.png" alt="modifier"></button>
                    </div>

                    <!-- date de naissance -->
                    <label class="col1" for="date">Date de naissance:</label>
                    <div class="col2">
                        <div class="fill-col">
                            <input class="desactivable fill-col js-date-passe" type="date" name="date" id="date"
                                min="1900-01-01" placeholder="JJ/MM/AAAA"
                                value="<?php echo $utilisateur["info"]["date_naissance"]; ?>" readonly>
                        </div>
                        <p class="message-erreur"></p>
                    </div>
                    <div class="col3">
                        <button class="btn-img" title="annuler" id="annuler-date" type="button"
                            onclick="reinitialiserInput('date')" hidden>
                            <b>X</b>
                        </button>
                        <button class="btn-img" title="modifier" id="modifier-date" type="button"
                            onclick="inputModifiable('date')">
                            <img src="../img/modifier.png" alt="modifier"></button>
                    </div>
                </div>
                <div class="grille3" id="valider"></div>

                <!-- Popup mot de passe actuel (avant modification finale) -->
                <div class="popup" id="popup" hidden>
                    <div class="bloc" id="popup-elem">
                        <label for="mdp-actuel">Mot de passe actuel : </label>
                        <div class="enveloppe-input">
                            <div>
                                <input class=" js-mdp" name="mdp-actuel" id="mdp-actuel" type="password"
                                    placeholder="Mot de passe actuel" maxlength="<?= MAX_MDP_LENGTH ?>">
                                <span class="compteur"><?= "0/" . MAX_MDP_LENGTH ?></span>

                                <!-- <button class="btn-img" title="voir" id="voir-mdp" type="button" onclick="voirMDP('mdp')">
                                <img src="../img/oeil.png" alt="voir"></button> -->
                            </div>
                            <p class="message-erreur"></p>
                        </div>
                        <input type="hidden" name="valider-modif">
                        <div>
                            <br>
                            <input class="input-formulaire-2" type="button" value="valider"
                                onclick="envoyerFormulaire()">
                            &nbsp;
                            <input class="input-formulaire-2" type="button" value="annuler" onclick="fermerPopup()">
                        </div>
                    </div>
                </div>
            </form>
            <?php
            if (isset($_GET["erreur"]) && $_SERVER["REQUEST_METHOD"] == "GET") {
                echo '<div class="erreur"> ⚠️ Erreur : ';
                switch ($_GET["erreur"]) {
                    case "date_invalide":
                        echo "Date invalide";
                        break;
                    case "email_invalide":
                        echo "Email invalide";
                        break;
                    case "mdp_invalide":
                        echo "Mot de passe doit avoir entre 6 et 16 caractères, au moins un chiffre et un caractère spécial parmi !@#$%^&*";
                        break;
                    case "mdp_different":
                        echo "Les mots de passe ne correspondent pas";
                        break;
                    case "utilisateur_invalide":
                        echo "Utilisateur invalide";
                        break;
                    case "nom_invalide":
                        echo "Nom invalide";
                        break;
                    case "prenom_invalide":
                        echo "Prénom invalide";
                        break;
                    case "genre_invalide":
                        echo "Genre invalide";
                        break;
                    case "mdp_actuel_vide":
                        echo "Mot de passe actuel vide";
                        break;
                    case "mdp_actuel_invalide":
                        echo "Mot de passe actuel invalide";
                        break;
                    case "mdp_actuel_incorrect":
                        echo "Mot de passe actuel incorrect";
                        break;
                    case "action_invalide":
                        echo "Action invalide";
                        break;
                    default:
                        echo "Erreur inconnue : " . $_GET["erreur"];
                        break;
                }
                echo '</div>';
            }
            ?>
        </div>
        <div class="texte-centre"><a href="panier.php" class="input-formulaire"> → Acceder à votre panier</a></div>

        <form class="texte-centre" action="php-include/deconnexion.php" method="post">
            <input class="input-formulaire grand" type="submit" value="Déconnexion" name="deconnexion">
        </form>
    </main>
    <?php
    require_once "php-include/footer.php";
    ?>
    <script src="../js/utiles.js"></script>
    <script src="../js/profil.js" type="module">
    </script>

</body>

</html>
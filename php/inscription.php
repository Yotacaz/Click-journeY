<?php
session_start();
require_once "php-include/utilisateur.php";
require_once "php-include/utiles.php";
if (utilisateurEstConnecte()) {
    header("Location: profil.php");
    exit;
}
$msg_err = "";

$mail = $mdp = $mdp2 = $nom = $prenom = $genre = $date_naissance = "";

$date = date("Y-m-d");
if (isset($_POST["boutton"]) && $_SERVER["REQUEST_METHOD"] == "POST") {

    $nom = isset($_POST["nom"]) ? test_input($_POST["nom"]) : "";
    $prenom = isset($_POST["prenom"]) ? test_input($_POST["prenom"]) : "";
    $genre = isset($_POST["genre"]) ? test_input($_POST["genre"]) : "";
    $date_naissance = isset($_POST["date_naissance"]) ? test_input($_POST["date_naissance"]) : "";
    $date_naissance = date("Y-m-d", strtotime($date_naissance));

    $mail = isset($_POST["email"]) ? test_input($_POST["email"]) : "";
    $mdp = isset($_POST["mdp"]) ? test_input($_POST['mdp']) : "";
    $mdp2 = isset($_POST["mdp2"]) ? test_input($_POST['mdp2']) : "";

    if (empty($nom) || empty($prenom) || empty($genre) || empty($date_naissance) || empty($mail) || empty($mdp) || empty($mdp2)) {
        $msg_err = "Merci de remplir tous les champs.";

    } elseif (est_nom($nom) == false) {
        $msg_err = "Merci de rentrer un nom valide (" . MIN_STRING_LENGTH . " à " . MAX_STRING_LENGTH . " caractères).";

    } elseif (est_prenom($prenom) == false) {
        $msg_err = "Merci de rentrer un prénom valide (" . MIN_STRING_LENGTH . " à " . MAX_STRING_LENGTH . " caractères).";

    } else if (strlen($prenom) < MIN_STRING_LENGTH || strlen($prenom) > MAX_STRING_LENGTH) {
        $msg_err = "Merci de rentrer un prénom valide (2 à 50 caractères).";

    } else if ($genre != "homme" && $genre != "femme" && $genre != "autre") {
        $msg_err = "Merci de choisir un genre.";

    } else if (!est_date($date_naissance) || $date_naissance > $date) {
        $msg_err = "Merci de rentrer une date de naissance valide.";

    } else if (!est_mdp($mdp)) {
        $msg_err = "Merci de rentrer un mot de passe valide (6 à 16 caractères, au moins un chiffre et un caractère spécial).";

    } else if (!est_email($mail)) {
        $msg_err = "Merci de rentrer une adresse mail valide (max 50 caractères).";

    } else if ($mdp !== $mdp2) {
        $msg_err = "Merci de rentrer le même mot de passe.";

    } else if (chargerUtilisateurParEmail($mail) != null) {
        $msg_err = "Adresse déjà utilisé";

    } else {
        $chemin = genererCheminFichierUtilisateur($mail);
        $id = genererIdUtilisateur();
        $info = array(
            'nom' => $nom,
            'prenom' => $prenom,
            'sexe' => $genre,
            'date_naissance' => $date_naissance
        );
        $voyages = array('consultes' => [], 'achetes' => []);
        $autres = array('date_inscription' => $date, 'date_derniere_connexion' => '');
        $finale = array("email" => $mail, "mdp" => $mdp, "id" => $id, "role" => "normal", "info" => $info, "voyages" => $voyages, "autres" => $autres);
        $open = fopen($chemin, 'w');
        fwrite($open, json_encode($finale, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
        fclose($open);
        header("Location: connexion.php");
        exit;
    }
}
require_once "php-include/utiles.php";
?><!DOCTYPE html>
<html lang="fr">

<head>
    <title>Inscription - PixelTravels</title>
    <meta name=”auteur” content=”Augustin Aveline” />
    <meta name=”description” content=”page d inscription au site” />
    <link rel="stylesheet" type="text/css" href="../style.css" />
    <link rel="icon" type="image/x-icon" href="../img/logo.png">
    <meta charset="UTF-8">
</head>

<body>
    <?php
    require_once "php-include/header.php";
    ?>
    <main>
        <div class="bandeau">
            <h1>
                Bonjour et bienvenue à vous
            </h1>
        </div>
        <div class="conteneur-image-texte">
            <div class="bloc" class="conteneur-texte">
                <h2>Créé votre compte </h2><br>
                <form class="grille3 js-form" action="#" method="post" name="inscription" id="inscription">

                    <label for="nom" class="col1">Nom : </label>
                    <div class="enveloppe-input">
                        <div class="fill-col">
                            <input class="col2 js-a-verifier js-nom" type="text" name="nom" id="nom"
                                contenteditable="false" placeholder="Nom" value="<?php echo $nom ?>"
                                maxlength="<?= MAX_STRING_LENGTH ?>">
                            <span class="compteur"></span>
                        </div>
                        <p class="message-erreur"></p>
                    </div>
                    <br />
                    <label for="prenom" class="col1">Prénom : </label>
                    <div class="enveloppe-input">
                        <div class="fill-col">
                            <input class="col2 js-a-verifier js-prenom" type="text" name="prenom" id="prenom"
                                contenteditable="false" placeholder="Prénom" value="<?php echo $prenom ?>"
                                maxlength="<?= MAX_STRING_LENGTH ?>">
                            <span class="compteur"></span>
                        </div>
                        <p class="message-erreur"></p>
                    </div>
                    </br>

                    <div class="col1">Genre :</div>
                    <div>
                        <label for="genreH"><input type="radio" value="homme" name="genre" id="genreH" <?php echo $genre == "homme" ? "checked" : "" ?>>Homme</label>
                        <label for="genreF"><input type="radio" value="femme" name="genre" id="genreF" <?php echo $genre == "femme" ? "checked" : "" ?>>Femme</label>
                        <label for="genreA"><input type="radio" value="autre" name="genre" id="genreA" <?php echo $genre == "autre" ? "checked" : "" ?>>Autre</label>
                    </div><br />

                    <label class="col1" for="date_naissance">Date de naissance:</label>
                    <div class="enveloppe-input">
                        <div class="fill-col">
                            <input class="col2 js-a-verifier js-date-passe" type="date" name="date_naissance"
                                id="date_naissance" min="1900-01-01" value="<?php echo $date_naissance ?>"
                                max="<?php echo $date; ?>">
                        </div>
                        <p class="message-erreur"></p>
                    </div>
                    <br />

                    <label for="adresse" class="col1">Adresse mail :</label>
                    <div class="enveloppe-input">
                        <div class="fill-col">
                            <input class="col2 js-a-verifier js-email" type="email" name="email" id="adresse"
                                placeholder="adresse@email.exemple" value="<?php echo $mail ?>"
                                maxlength="<?= MAX_STRING_LENGTH ?>">
                            <span class="compteur"></span>
                        </div>
                        <p class="message-erreur"></p>
                    </div>
                    </br>


                    <label for="mdp" class="col1">Mot de passe :</label>
                    <div class="enveloppe-input">
                        <div class="fill-col">
                            <input class="col2 js-a-verifier js-mdp" type="password" name="mdp" id="mdp"
                                placeholder="Entrez un mot de passe" <?php echo $mdp ?>
                                maxlength="<?= MAX_MDP_LENGTH ?>">
                            <span class="compteur"></span>
                        </div>
                        <p class="message-erreur"></p>
                    </div>
                    <br>
                    <label for="mdp2" class="col1">Confirmation Mot de passe :</label>
                    <div class="enveloppe-input">
                        <div class="fill-col">
                            <input class="col2 js-a-verifier js-mdp" type="password" name="mdp2" id="mdp2"
                                placeholder="Entrez un mot de passe" <?php echo $mdp2 ?>
                                maxlength="<?= MAX_MDP_LENGTH ?>">
                            <span class="compteur"></span>
                        </div>
                        <p class="message-erreur"></p>
                    </div>
                    <br>
                    <p class="col1"> </p>
                    <label class="col2">
                        <button type="submit" name="boutton" class="submit">Envoyer</button>
                    </label>
                </form>

                <?php
                echo $msg_err != "" ? '<div class="erreur"> ⚠️ ' . $msg_err . '</div> ' : "";
                ?>

                <p>
                    Vous avez déjà un compte ?
                    <a href="connexion.php" class="lien">Connectez-vous</a>
                </p>
            </div>
            <div">
                <img class="conteneur-image" src="../img/Exemple de voyage.png" alt="exemple de voyage">
        </div>
        </div>
    </main>
    <?php
    require_once "php-include/footer.php";
    ?>
    <script src="../js/form.js" type="module">
    </script>

    <script type="text/javascript">

        document.forms["inscription"].addEventListener("submit", function (event) {
            let nb_erreurs = verifiersInputs();
            if (nb_erreurs != 0) {
                event.preventDefault();
            }
        });
    </script>
</body>

</html>
<?php
session_start();
require_once "php-include/utilisateur.php";
if (utilisateurEstConnecte()) {
    header("Location: profil.php");
    exit;
}
$msg_err = "";
$date = date("Y-m-d");
if (isset($_POST["boutton"])) {
    $mail = $_POST["email"];
    $mdp = $_POST['mdp'];
    $mdp2 = $_POST['mdp2'];
    if ($mdp !== $mdp2) {
        $msg_err = "Merci de rentrer le même mot de passe.";
    } else if (chargerUtilisateurParEmail($mail) != null) {
        $msg_err = "Adresse déjà utilisé";
    } else {
        $chemin = genererCheminFichierUtilisateur($mail);
        $id = genererIdUtilisateur();
        $info = array('nom' => $_POST["nom"], 'prenom' => $_POST["prenom"], 'sexe' => $_POST['genre'], 'date_naissance' => $_POST["date_naissance"]);
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
?>

<!DOCTYPE html>
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
                <form class="grille3" action="#" method="post" name="inscription">

                    <label for="nom" class="col1">Nom : </label>
                    <input class="col2" type="text" name="nom" id="nom" contenteditable="false" placeholder="Nom"><br />

                    <label for="prenom" class="col1">Prénom : </label>
                    <input class="col2" type="text" name="prenom" id="prenom" contenteditable="false"
                        placeholder="Prénom"></br>

                    <div class="col1">Genre :</div>
                    <div>
                        <label for="genreH"><input type="radio" value="homme" name="genre" id="genreH">Homme</label>
                        <label for="genreF"><input type="radio" value="femme" name="genre" id="genreF">Femme</label>
                        <label for="genreA"><input type="radio" value="autre" name="genre" id="genreA">Autre</label>
                    </div><br />

                    <label class="col1" for="date_naissance">Date de naissance:</label>
                    <input class="col2" type="date" name="date_naissance" id="date_naissance" min="1900-01-01"
                        max="<?php echo "$date"; ?>"><br />

                    <label for="adresse" class="col1">Adresse mail :</label>
                    <input class="col2" type="email" name="email" id="adresse"
                        placeholder="adresse@email.exemple"><br />

                    <label for="mdp" class="col1">Mot de passe :</label>
                    <input class="col2" type="password" name="mdp" id="mdp" placeholder="Entrez un mot de passe"><br />

                    <label for="mdp2" class="col1">Confirmation Mot de passe :</label>
                    <input class="col2" type="password" name="mdp2" id="mdp2"
                        placeholder="Entrez un mot de passe"><br />

                    <p class="col1"> </p>
                    <label class="col2">
                        <button type="submit" name="boutton" class="submit">Envoyer</button>
                    </label>
                </form>

                <?php
                echo $msg_err;
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
</body>

</html>
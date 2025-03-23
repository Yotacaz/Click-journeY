<?php
session_start();
require_once "php-include/utilisateur.php";
if (utilisateurEstConnecte()) {
    header("Location: profil.php");
}
$chemin_utilisateurs = nomDossierUtilisateur();
$message = "test";

if (isset($_POST["boutton"])) {
    $mail = $_POST['email'];
    $mdp = $_POST['mdp'];
    $utilisateur = chargerUtilisateurParEmail($mail);
    if ($utilisateur != null) {
        //TODO : verification de mot de passe avec password_verify($mdp, $utilisateur["mdp"])
        if ($utilisateur["email"] === $mail && $utilisateur["mdp"] === $mdp) {
            $maxAttempts = 16;
            $attempts = 0;

            $id_session = genererSessionIdUnique();

            if (!setcookie('id_session', $id_session)) {
                die("Erreur lors de la génération du cookie.");
            }
            session_write_close();
            if (!session_id($id_session)) {
                die("Erreur lors de la mise en place de l'id de session.");
            }
            $utilisateur["autres"]["date_derniere_connexion"] = date("Y-m-d");
            
            if (!ecrireFichierUtilisateur($utilisateur)) {
                die("Erreur lors de l'écriture du fichier utilisateur (changement de date de connexion).");
            }
            session_start();
            $_SESSION[$id_session] = $utilisateur;
            if (isset($_GET["redirection"])) {
                header("Location: " . $_GET["redirection"]);
            }
            else{
                header("Location: profil.php");
            }
            exit;
        } else {
            $message = "mot de passe erroné.";
        }
    } else {
        $message = "Pas de compte existant ou adresse mail erronée!";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <title>Connexion - PixelTravels</title>
    <meta name="auteur" content="Augustin Aveline" />
    <meta name="description" content="page de connexion au site" />
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
                Bonjour, c'est bon de vous revoir
            </h1>
        </div>
        <div class="conteneur-image-texte">
            <!-- class="conteneur-texte" -->
            <div class="bloc1 conteneur-texte">
                <h2>Connexion</h2><br />
                <form class="grille3" action="#" method="post" name="connexion">
                    <label for="adresse" class="col1">Adresse mail :</label>
                    <input class="col2" type="email" name="email" id="adresse" placeholder="adresse@email.exemple"
                        value="<?php echo isset($_POST["boutton"]) ? $_POST["email"] : "" ?>"><br />

                    <label for="mdp" class="col1">Mot de passe :</label>
                    <input class="col2" type="password" name="mdp" id="mdp" placeholder="Entrez un mot de passe"><br />

                    <p class="col1"></p>
                    <label class="col2">
                        <button type="submit" name="boutton" class="submit">Connexion</button>
                    </label>
                </form>
                <?php
                if (isset($_POST["boutton"])) {
                    echo "$message<br>";
                }
                ?>

                <p>
                    <a class="lien" href="#"> Mots de passe oubliée ?</a><br />
                    Vous n'avez pas de compte ?
                    <a href="inscription.php" class="lien">Créez le rapidement</a>
                </p>
            </div>
            <div class="conteneur-image">
                <img class="dessert conteneur-image" src="../img/Comparaison Desert.png"
                    alt="comparaison desert minecraft réalité">
            </div>
        </div>
    </main>
    <?php
    require_once "php-include/footer.php";
    ?>
</body>

</html>
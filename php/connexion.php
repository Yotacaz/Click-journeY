<!DOCTYPE html>
<html lang="fr">

<head>
    <title>Connexion - PixelTravels</title>
    <meta name=”auteur” content=”Augustin Aveline” />
    <meta name=”description” content=”page de connexion au site” />
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
            <div class="bloc1" class="conteneur-texte">
                <h2>Connexion</h2><br />
                <form class="grille3" action="#" method="post" name="connexion">
                    <label for="adresse" class="col1">Adresse mail :</label>
                    <input class="col2" type="email" name="email" id="adresse"
                        placeholder="adresse@email.exemple"><br />

                    <label for="mdp" class="col1">Mot de passe :</label>
                    <input class="col2" type="password" name="mdp" id="mdp"
                        placeholder="Entrez un mot de passe"><br />

                    <p class="col1"></p>
                    <label class="col2">
                        <button type="submit" name="boutton" class="submit">Connexion</button>
                    </label>
                </form>

                <?php 
                    require_once "php-include/utilisateur.php";
                    session_start();
                    if(isset($_POST["boutton"])){
                        $mail=$_POST['email'];
                        $mdp=$_POST['mdp'];
                        $utilisateur = chargerUtilisateurParEmail("$mail");
                        if($utilisateur != null){
                            if($utilisateur["email"] === $mail && $utilisateur["mdp"] === $mdp){
                                echo "bienvenue";
                                $_SESSION["utilisateur"] = $utilisateur;
                                header("Location: profil.php");
                            }
                            else{
                                echo "mots de passe erroné.";
                            }
                        }
                        else{
                            echo "Pas de compte ou adresse mail est erroné!";
                        }
                    }
                ?>

                <p>
                    <a class="lien" href="#"> Mots de passe oubliée ?</a><br />
                    Vous n'avez pas de compte ?
                    <a href="inscription.php" class="lien">Crée le rapidement</a>
                </p>
            </div>
            <img class="dessert" src="../img/Comparaison Desert.png" alt="comparaison desert minecraft réalité">
        </div>
    </main>
    <?php
    require_once "php-include/footer.php";
    ?>
</body>

</html>

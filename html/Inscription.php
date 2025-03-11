<!DOCTYPE html>
<html lang="fr">

<head>
    <title>Inscription - PixelTravels</title>
    <meta name=”auteur” content=”Augustin Aveline” />
    <meta name=”description” content=”page d inscription au site” />
    <link rel="stylesheet" type="text/css" href="../style.css" />
    <link rel="icon" type="image/x-icon" href="../img/logo.png">
    <meta charset="UTF-8">
    <?php $titre_page = "inscription"; ?>
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
                        placeholder="Prénom">

                    <div>Genre :</div>
                    <div>
                        <label for="genreH"><input type="radio" name="genre" id="genreH">Homme</label>
                        <label for="genreF"><input type="radio" name="genre" id="genreF">Femme</label>
                        <label for="genreA"><input type="radio" name="genre" id="genreA">Autre</label>
                    </div><br />

                    <label class="col1" for="date">Date de naissance:</label>
                    <input class="col2" type="date" name="date" id="date" min="1900-01-01"><br />

                    <label for="addresse" class="col1">Adresse :</label>
                    <input class="col2" type="email" name="addresse" id="addresse"
                        placeholder="addresse@email.exemple"><br />

                    <label for="mdp" class="col1">Mot de passe :</label>
                    <input class="col2" type="password" name="mot de passe" id="mdp"
                        placeholder="Entrez un mot de passe"><br />

                    <label for="mdp2" class="col1">Confirmation Mot de passe :</label>
                    <input class="col2" type="password" name="mot de passe2" id="mdp2"
                        placeholder="Entrez un mot de passe"><br />

                    <p class="col1"> </p>
                    <label class="col2">
                        <button type="submit" class="submit">Envoyée</button>
                    </label>
                </form>
                <p>
                    Vous avez déja un compte ?
                    <a href="Connexion.html" class="lien">Connectez-vous</a>
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
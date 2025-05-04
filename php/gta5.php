<!DOCTYPE html>
<html lang="fr">

<head>
    <meta name="auteur" content="CRISSOT Martin" />
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../style.css">
    <link rel="icon" type="image/x-icon" href="../img/logo.png">
    <meta name="description" content="modification profil utilisateur" />
    <title>GTA-V - PixelTravels</title>
</head>

<body>
    <?php
    require_once "php-include/header.php";
    ?>
    <main>
        <div class="bandeau-image">
            <img src="../img/GTA-V/gtaV.jpg" alt="Logo GTA-V">
            <h1 class="centre">Visite de l'environnement GTA-V</h1>
        </div>
        <p><br></p>
        <div class="separateur-section">
            <div class="conteneur-image-texte">

                <div class="conteneur-image">
                    <img src="../img/GTA-V/Los-Angeles-GTA-V.jpg" alt="comparaison Los Angeles GTA-V">
                </div>

                <div class="conteneur-texte">
                    <h2>Description</h2>
                    <em>Découvrez l'environnement dans lequel s'ancre le jeu vidéo GTA-V</em>. Vous pourrez visiter les
                    lieux emblématiques du jeu, et découvrir les secrets de sa création. Vous pourrez également
                    rencontrer
                    les développeurs du jeu, et participer à des ateliers de création de jeu vidéo.
                </div>

            </div>
            <div class="conteneur-image-texte">
                <div class="conteneur-texte">
                    <h2>Activités</h2>
                    <p><em>Participez à des activités inédites</em>. Vous pourrez participer à des simulations
                        courses de voitures,
                        des combats de rue, et des missions de police. Vous pourrez également visiter les studios de
                        Rockstar
                        Games, et découvrir les coulisses de la création du jeu.
                    </p>
                </div>
                <img class="conteneur-image" src="../img/GTA-V/GTA-V-Police.jpg" alt="activité courses poursuite GTA-V">
            </div>
        </div>
        <div class="contour-bloc">
            <h2>Informations techniques :
            </h2>
            <ul>
                <li><b>Destination : </b> États-Unis - Los Angeles</li>
                <li><b>Date : </b> 12/12/2025</li>
                <li><b>Durée : </b> 1 semaine</li>
                <li><b>Nombre de places : </b> 20</li>
                <li><b>Prix : </b> 2000€</li>
            </ul>
            À prévoir : boissons, équipements personnel, assurances optionnelles.
        </div>

        <div class="contour-bloc">
            <h2>Informations pratiques :
            </h2>
            <ul>
                <li><b>Transport : </b> avion <em>(modifiable)</em></li>
                <li><b>Hébergement : </b> hôtel 4 étoiles <em>(modifiable)</em></li>
                <li><b>Repas : </b> inclus <em>(modifiable)</em></li>
                <li><b>Activités : </b> visites guidées, casinos, simulation de courses poursuite, coulisses de GTA-V...
                </li>
            </ul>
        </div>
        <div class="texte-centre"><button class="input-formulaire tres-grand">Réservez Maintenant !</button></div>
    </main>
    <?php
    require_once "php-include/footer.php";
    ?>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>
    <script src="../js/mode.js">
    </script>
</body>

</html>

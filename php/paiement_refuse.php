<?php

//page de retour après une transaction si le paiement est refusé

require_once "php-include/utiles.php";
$raison = isset($_GET["raison"]) ? test_input($_GET["raison"]) : "";
$id_transaction = isset($_GET["id_transaction"]) ? test_input($_GET["id_transaction"]) : "";
$achat = test_input($_GET["achat"]);    //Soit "panier" soit un id de voyage
if ($achat != "panier") {
    $achat = intval($achat);
}
require_once "php-include/utilisateur.php";
$utilisateur = connexionUtilisateurRequise($_SERVER["PHP_SELF"] . "?raison=$raison&id_transaction=$id_transaction&achat=$achat");
if ($utilisateur != null && !utilisateurValide($utilisateur)) {
    die("Erreur : Utilisateur invalide");
}
if (!isset($_GET["achat"])) {
    die("Erreur : Paramètres incorrects.");
}
?><!DOCTYPE html>
<html lang="fr">

<head>
    <title>Paiement refusé - PixelTravels</title>
    <meta name=”auteur” content=”Augustin Aveline” />
    <meta name=”description” content=” paiement refusé” />
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
                Paiement refusé
            </h1>
        </div>
        <div class="conteneur-image-texte">
            <div class="bloc" class="conteneur-texte">
                <?php
                switch ($raison) {
                    case "paiement_refuse":
                        echo "<h2>Le paiement a été refusé par votre carte bancaire, potentiellement par manque d'argent sur le compte.</h2>";
                        break;
                    case "voyage_deja_achete":
                        echo "<h2>Vous avez déjà réservé ce voyage.</h2>";
                        break;
                    case "places_restantes_insuffisantes":
                        echo "<h2>Le nombre de places restantes pour un des voyages réservé est inférieur au nombre total de places.</h2>";
                        break;
                    case "montant_incoherent":
                        echo "<h2>Le montant du paiement est incohérent avec le tarif d'un des voyages.</h2>";
                        break;
                    default:
                        echo "<h2>Erreur : Raison inconnue.</h2>";
                        break;
                }
                if ($id_transaction != "") {
                    echo "<div> Pour demander un <a href=\"#\">remboursement</a> pour cette transaction, merci de fournir les informations suivantes :
                        <ul>
                        <li>ID de transaction : $id_transaction</li>
                        <li>Nom du client : " . $utilisateur["nom"] . "</li>
                        <li>Adresse mail : " . $utilisateur["email"] . "</li>
                        </ul>
                        </div>";
                }
                ?>
                <br>
                <div>
                    Si vous voulez tentez à nouveau de payer :
                </div></br>
                <a href="paiement.php?<?= $achat == "panier" ? "achat_panier=oui" : "id=$achat"; ?>"
                    class="lien">ICI</a></br></br>
                <div>
                    Si vous voulez changer des options pour réduire le prix :
                </div></br>
                <a href="<?= $achat == "panier" ? "panier.php" : "details_voyage.php?id=$achat"; ?>"
                    class="lien">ICI</a>
            </div>
        </div>
    </main>
    <?php
    require_once "php-include/footer.php";
    ?>
</body>

</html>
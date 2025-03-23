<?php
session_start();
require_once "php-include/utilisateur.php";
$utilisateur = connexionUtilisateurRequise();
if ($utilisateur != null && !utilisateurValide($utilisateur)) {
    die("Erreur : Utilisateur invalide");
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <title>Paiement refusé - PixelTravels</title>
    <meta name=”auteur” content=”Augustin Aveline” />
    <meta name=”description” content=” paiement refusé” />
    <link rel="stylesheet" type="text/css" href="../style.css" />
    <link rel="icon" type="image/x-icon" href="../img/logo.png">
    <meta charset="UTF-8">
</head>

<?php
    $info = json_decode(file_get_contents("../donnees/paiement/transaction_en_cours.json"), true);
    $date=date("j_F_Y");
    $status=$_GET["status"];
    if($status == "accepted"){
        $transaction=$_GET["transaction"];
        $vendeur=$_GET["vendeur"];
        $montant=$_GET["montant"];
        $info_transaction=array("date_transaction" => "$date" , "ID_transaction" => "$transaction" , "vendeur" => "$vendeur" , "montant" => "$montant");
        $tracabilite=array("compte utilisateur" => "$info[utilisateur]" , "Info_transaction" => $info_transaction , "Voyage" => $info["voyage"] , "Coordonnee_Bancaire" => $info["cobancaire"]);
        $open = fopen("../donnees/paiement/transaction_finis.json",'a+');
        fwrite( $open , json_encode($tracabilite));
        fclose($open);
        header("Location: accueil.php");
    }
?>

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
                <h2>paiment refusé par manque d'argent sur le compte</h2><br>
                    <div>
                        Si vous voulez tentez à nouveaux de payez :
                    </div></br>
                    <a href="paiement.php" class="lien">ICI</a></br></br>
                    <div>
                        Si vous voulez changer des options pour réduire le prix :
                    </div></br>
                    <a href="connexion.php" class="lien">ICI</a>
            </div>
        </div>
    </main>
    <?php
    require_once "php-include/footer.php";
    ?>
</body>

</html>
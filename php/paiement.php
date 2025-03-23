<?php
session_start();
require_once "php-include/utilisateur.php";
$utilisateur = connexionUtilisateurRequise();
if ($utilisateur != null && !utilisateurValide($utilisateur)) {
    die("Erreur : Utilisateur invalide");
}

$donnees_voyage = $utilisateur['voyages']['consultes'];
//TODO: Récupérer les données du voyage

// print_r($utilisateur);
// if (empty($donnees_voyage)) {
//     die("Erreur : Aucun voyage spécifié");
// }
// $donnees_voyage = $donnees_voyage[count($donnees_voyage) - 1];
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <title>Paiement voyage - PixelTravels</title>
    <meta name=”auteur” content=”Augustin Aveline” />
    <meta name=”description” content=”page de paiement au site” />
    <link rel="stylesheet" type="text/css" href="../style.css" />
    <link rel="icon" type="image/x-icon" href="../img/logo.png">
    <meta charset="UTF-8">
</head>

<body>
    <?php
    require_once "php-include/header.php";
    require_once "php-include/getapikey.php";
    $date = date("Y-m-d");
    $info = json_decode(file_get_contents("../donnees/paiement/transaction_en_cours.json"), true);
    $passage= array("a","z","e","r","t","y","u","i","o","p","q","s","d","f","g","h","j","k","l","m","w","x","c","v","b","n","0","1","2","3","4","5","6","7","8","9","A","Z","E","R","T","Y","U","I","O","P","Q","S","D","F","G","H","J","K","L","M","W","X","C","V","B","N");
    $nb1 = rand(10,24);
    $vendeur="MI-3_A";
    $base_url = "http://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']);
    $retour = $base_url . "/retour_transaction";
    $transaction=$passage[rand(0,61)];
    for($i=0;$i<$nb1 -1;$i++){
        $transaction=$passage[rand(0,61)].$transaction;
    }
    $api_key = getAPIKey("$vendeur");
    $control=md5($api_key."#".$transaction."#".$info["montant"]."#".$vendeur."#".$retour."#");
    ?>
    
    <main>
        <div class="bandeau">
            <h1>
                Page de paiement
            </h1>
        </div>
        <div class="conteneur-image-texte">
            <div class="bloc" class="conteneur-texte">
                <h2>Coordonnée bancaire</h2><br>
                <form class="grille3" action='https://www.plateforme-smc.fr/cybank/index.php' method="post" name="paiement">

                    <label for="num_carte" class="col1">Num Carte : </label>
                    <input class="col2" type="text" name="num_carte" id="num_carte" contenteditable="false" placeholder="Num carte bancaire"><br /> 
                    
                    <label for="proprio" class="col1">Nom-Prénom propriétaire carte :</label>
                    <input class="col2" type="text" name="proprio_carte" id="proprio" contenteditable="false" placeholder="nom-prénom propriétaire carte"></br>

                    <label class="col1" for="date">Date d'expiration:</label>
                    <input class="col2" type="date" name="date_expiration" id="date" min="<?php echo "$date"; ?>" ><br />

                    <label for="valcontrole" class="col1">Valeur de contrôle :</label>
                    <input class="col2" type="text" name="valeur_controle" id="valcontrol" contenteditable="false" placeholder="valeur de contrôle"><br />

                    <input type='hidden' name='transaction' value="<?php echo "$transaction" ?>">
                    <input type='hidden' name='montant' value="<?php echo "$info[montant]" ?>">
                    <input type='hidden' name='vendeur' value="<?php echo "$vendeur" ?>">
                    <input type='hidden' name='retour' value="<?php echo "$retour" ?>">
                    <input type='hidden' name='control' value="<?php echo "$control" ?>">


                    <p class="col1"> </p>
                    <label class="col2">
                        <button type="submit" name="boutton" class="submit">validé et payé</button>
                    </label>
                </form>

                <?php
                    require_once "php-include/utilisateur.php";
                    if(isset($_POST["boutton"])){
                        $num_carte=$_POST['num_carte'];
                        $valeur=$_POST['valeur_controle'];
                        if(strlen($num_carte) != 16){
                            echo "Merci de rentré un numéros de carte valide.";
                        }
                        else if(strlen($valeur) != 3){
                            echo "Merci de rentré une valeur de contrôle valide.";
                        }
                        else{
                            
                                
                        }
                        /*$temp= array('nom_voyage' => "GTA V",'nb_personne' => 6,'montant' => 1800.00 ,'dates_debut' => "2028-12-20" ,'date_fin' => "2029-01-05" , "utilisateur" => "bob@bob.bob");
                        $open = fopen("../donnees/paiement/transaction_en_cours.json",'a+');
                        fwrite( $open , json_encode($temp));
                        fclose($open);*/
                    }
                ?>

            </div>
            <div>
                <div class="conteneur-texte">
                    <h2>Recapitulatif</h2>
                    Vous participé au voyage <em><?php echo "$info[nom_voyage]" ?></em> à <em><?php echo "$info[nb_personne]" ?></em> personnes.</br>
                    Le voyage débuteras le <em><?php echo "$info[dates_debut]" ?></em> et finiras le <em><?php echo "$info[date_fin]" ?></em>.</br>
                    Pour un montant totale de <em><?php echo "$info[montant] €" ?></em>.
                </div>
            </div>
        </div>
    </main>
    <?php
    require_once "php-include/footer.php";
    ?>
</body>

</html>

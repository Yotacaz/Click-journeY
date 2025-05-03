<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
//Gestion de l'utilisateur
require_once "php-include/utilisateur.php";
require_once "php-include/fonctions_voyages.php";

$identifiant_v = recup_id_voyage();

$utilisateur = connexionUtilisateurRequise($_SERVER["PHP_SELF"] . "?id=" . $identifiant_v);
if ($utilisateur != null && !utilisateurValide($utilisateur)) {
    die("Erreur : Utilisateur invalide");
}

//gestion des voyages

//Chargement des options déjà enregistrées du voyage
$voyage = null;
$opt_enr = null;  //options enregistrées 
//Verification : voyage déjà consulté ?
$opt_enr = optionVoyageConsulte($utilisateur, $identifiant_v);

//Verification : voyage déjà acheté ?
$opt_achat = optionVoyageAchete($utilisateur, $identifiant_v);
if ($opt_achat != null) {
    if ($opt_enr != null) {
        die("Le voyage d'id $identifiant_v est déjà présent chez l'utilisateur
        dans catégorie voyages consulté, ne devrait pas être acheté.");
    }
    header("Location: modif_voyage.php?id=$identifiant_v");
    exit; //Voyage déjà acheté, ne devrait pas pouvoir être racheter
}
if ($opt_enr == null) {
    header("Location: modif_voyage.php?id=$identifiant_v");
    exit; //Erreur : Voyage introuvable
}

$voyage = chargerVoyageParId($identifiant_v);
if ($voyage == null) {
    die("Erreur : ID de voyage $identifiant_v  introuvable ou corrompu.");
}
?><!DOCTYPE html>
<html lang="fr">

<head>
    <title>Paiement voyage - PixelTravels</title>
    <meta name="auteur" content="Augustin Aveline" />
    <meta name="description" content="page de paiement au site" />
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
    $passage = array("a", "z", "e", "r", "t", "y", "u", "i", "o", "p", "q", "s", "d", "f", "g", "h", "j", "k", "l", "m", "w", "x", "c", "v", "b", "n", "0", "1", "2", "3", "4", "5", "6", "7", "8", "9", "A", "Z", "E", "R", "T", "Y", "U", "I", "O", "P", "Q", "S", "D", "F", "G", "H", "J", "K", "L", "M", "W", "X", "C", "V", "B", "N");
    $nb1 = rand(10, 24);
    $vendeur = "MI-3_A";
    $base_url = "http://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']);
    $retour = $base_url . "/retour_transaction";
    $transaction = $passage[rand(0, 61)];
    for ($i = 0; $i < $nb1 - 1; $i++) {
        $transaction = $passage[rand(0, 61)] . $transaction;
    }
    $api_key = getAPIKey("$vendeur");
    $control = md5($api_key . "#" . $transaction . "#" . $opt_enr["prix"] . "#" . $vendeur . "#" . $retour . "#");

    $cobanc = array("proprietaire" => "bob bob", "dates_expirations" => "2029-01-01", "valeur_controle" => "555", "num_carte" => "5555 1234 5678 9000");    //valeur de controle devrait pas être stocké ici
    $temp = array('id_voyage' => $identifiant_v, 'options_enregistres' => $opt_enr, "utilisateur" => $utilisateur["email"], "cobancaire" => $cobanc, "date_transaction" => $date);

    if (!empty($info[$transaction])) {
        //cas pas pris en charge, on écrase le précédent
    }
    $info[$transaction] = $temp;
    file_put_contents("../donnees/paiement/transaction_en_cours.json", json_encode($info, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    $utilisateur["voyages"]["en_cours_d_achat"][$identifiant_v] = $opt_enr;
    $utilisateur["voyages"]["en_cours_d_achat"][$identifiant_v]["id_transaction"] = $transaction;
    sauvegarderSessionUtilisateur($utilisateur);
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
                <form class="grille3" action='https://www.plateforme-smc.fr/cybank/index.php' method="post"
                    name="paiement">

                    <label for="num_carte" class="col1">Num Carte : </label>
                    <input class="col2" type="text" name="num_carte" id="num_carte" contenteditable="false"
                        placeholder="Num carte bancaire"><br />

                    <label for="proprio" class="col1">Nom-Prénom propriétaire carte :</label>
                    <input class="col2" type="text" name="proprio_carte" id="proprio" contenteditable="false"
                        placeholder="nom-prénom propriétaire carte"></br>

                    <label class="col1" for="date">Date d'expiration:</label>
                    <input class="col2" type="date" name="date_expiration" id="date" min="<?php echo "$date"; ?>"><br />

                    <label for="valcontrole" class="col1">Valeur de contrôle :</label>
                    <input class="col2" type="text" name="valeur_controle" id="valcontrol" contenteditable="false"
                        placeholder="valeur de contrôle"><br />

                    <input type='hidden' name='transaction' value="<?php echo "$transaction" ?>">
                    <input type='hidden' name='montant' value="<?php echo $opt_enr["prix"] ?>">
                    <input type='hidden' name='vendeur' value="<?php echo "$vendeur" ?>">
                    <input type='hidden' name='retour' value="<?php echo "$retour" ?>">
                    <input type='hidden' name='control' value="<?php echo "$control" ?>">


                    <p class="col1"> </p>
                    <label class="col2">
                        <button type="submit" name="boutton" class="submit input-formulaire-2">valider et payer</button>
                    </label>
                </form>

                <?php
                require_once "php-include/utilisateur.php";
                if (isset($_POST["boutton"]) && $_SERVER["REQUEST_METHOD"] != "POST") {
                    $num_carte = $_POST['num_carte'];
                    $valeur = $_POST['valeur_controle'];
                    if (strlen($num_carte) != 16) {
                        echo "Merci de rentré un numéros de carte valide.";
                    } else if (strlen($valeur) != 3) {
                        echo "Merci de rentré une valeur de contrôle valide.";
                    } else {


                    }
                }
                ?>

            </div>
            <div>
                <div class="conteneur-texte">
                    <h2>Récapitulatif</h2>
                    Vous participé à <em><?= $opt_enr["nombre_personnes_totales"] ?></em> personne(s) au voyage
                    <em><?php echo "$voyage[titre]" ?></em></br>
                    Le voyage débuteras le <em><?php echo $voyage["dates"]["debut"] ?></em> et finiras le
                    <em><?php echo $voyage["dates"]["fin"] ?></em>.</br>
                    Pour un montant total de <em><?= $opt_enr["prix"] ?> €</em>.
                </div>
            </div>
        </div>
    </main>
    <?php
    require_once "php-include/footer.php";
    ?>
</body>

</html>
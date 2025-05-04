<?php
session_start();
require_once "php-include/utilisateur.php";
$utilisateur = connexionUtilisateurRequise();
if ($utilisateur != null && !utilisateurValide($utilisateur)) {
    die("Erreur : Utilisateur invalide");
}

require_once "php-include/fonctions_voyages.php";
if (!isset($_GET["status"], $_GET["transaction"], $_GET["montant"], $_GET["vendeur"], $_GET["control"])) {
    die("Erreur : Paramètres incorrects.");
}
$id_transaction = $_GET["transaction"];
$montant = floatval($_GET["montant"]);

$en_cours_d_achats = $utilisateur["voyages"]["en_cours_d_achat"];
$identifiant_v = -1;
foreach ($en_cours_d_achats as $id_voyage => $details) {
    if ($details["id_transaction"] == $id_transaction) {
        if (abs(floatval($details["prix"]) - $montant) > 0.005) {
            die("Erreur : Montant de la transaction incohérent.");
        }
        $identifiant_v = $id_voyage;
        break;
    }
}
if ($identifiant_v == -1) {
    $achetes = $utilisateur["voyages"]["achetes"];
    foreach ($achetes as $id_voyage => $details) {
        if ($details["id_transaction"] == $id_transaction) {
            if (abs(floatval($details["prix"]) - $montant) > 0.005) {
                die("Erreur : Voyage $id_voyage déjà acheté et montant de la transaction incohérent avec voyage.");
            }
            $identifiant_v = $id_voyage;
            break;
        }
    }
    if ($identifiant_v == -1) {
        die("Erreur : Transaction introuvable.");
    }
    die("Erreur : Voyage déjà acheter.");
}


$info = json_decode(file_get_contents("../donnees/paiement/transaction_en_cours.json"), true);
$tout_tracabilite = json_decode(file_get_contents("../donnees/paiement/transaction_finis.json"), true);

$voyage = chargerVoyageParId($identifiant_v);
if ($voyage == null || $voyage['id'] != $identifiant_v) {
    die("Erreur : ID de voyage $identifiant_v  introuvable ou corrompu.");
}
if (empty($utilisateur["voyages"]["consultes"][$identifiant_v]) || empty($utilisateur["voyages"]["en_cours_d_achat"][$identifiant_v])) {
    die("Erreur : Voyage $identifiant_v  introuvable dans la liste des voyages consultés ou en cours d'achats.");
}

$places_achetes = intval($utilisateur["voyages"]["consultes"][$identifiant_v]["nombre_personnes_totales"]);
$places_restantes = intval($voyage['nb_places_restantes']);
if ($places_achetes > $places_restantes) {
    die("Erreur : Vous avez dépassé le nombre de places restantes.");
}
if ($places_achetes < 0) {
    die("Erreur : Vous ne pouvez pas acheter un nombre négatif de places.");
}


$date = date("j_F_Y");
$status = $_GET["status"];
if ($status == "accepted") {

    $vendeur = $_GET["vendeur"];

    $info_transaction = array("date_transaction" => "$date", "id_transaction" => "$id_transaction", "vendeur" => "$vendeur", "montant" => "$montant");
    $new_tracabilite = array("compte utilisateur" => $utilisateur["email"], "option_enregistrees" => $utilisateur["voyages"]["en_cours_d_achat"][$identifiant_v], "Info_transaction" => $info_transaction, "id_voyage" => $info[$id_transaction][$identifiant_v], "Coordonnee_Bancaire" => $info[$id_transaction]["cobancaire"]);
    unset($new_tracabilite['option_enregistrees']['id_transaction']);
    $tout_tracabilite[$id_transaction] = $new_tracabilite;
    $open = fopen("../donnees/paiement/transaction_finis.json", 'w');
    fwrite($open, json_encode($tout_tracabilite, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_NUMERIC_CHECK));
    fclose($open);

    unset($info[$id_transaction]);
    file_put_contents("../donnees/paiement/transaction_en_cours.json", json_encode($info, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_NUMERIC_CHECK));

    $utilisateur["voyages"]["achetes"]["$identifiant_v"] = $utilisateur["voyages"]["en_cours_d_achat"][$identifiant_v];
    unset($utilisateur["voyages"]["consultes"][$identifiant_v]);
    unset($utilisateur["voyages"]["en_cours_d_achat"][$identifiant_v]);
    sauvegarderSessionUtilisateur($utilisateur);
    $voyage["nb_places_restantes"] = intval($voyage["nb_places_restantes"]) - $places_achetes;
    $voyage["email_personnes_inscrites"][$utilisateur["email"]] = $places_achetes;
    sauvegarder_voyage($voyage);

    header("Location: profil.php");
    exit;
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
                <h2>paiment refusé par manque d'argent sur le compte</h2><br>
                <div>
                    Si vous voulez tentez à nouveau de payez :
                </div></br>
                <a href="paiement.php?id=<?= $identifiant_v; ?>" class="lien">ICI</a></br></br>
                <div>
                    Si vous voulez changer des options pour réduire le prix :
                </div></br>
                <a href="details_voyage.php?id=<?= $identifiant_v; ?>" class="lien">ICI</a>
            </div>
        </div>
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

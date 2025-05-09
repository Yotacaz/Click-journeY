<?php
session_start();
require_once "../php-include/utilisateur.php";
$utilisateur = connexionUtilisateurRequise();
if ($utilisateur != null && !utilisateurValide($utilisateur)) {
    die("Erreur : Utilisateur invalide");
}


if (!isset($_GET["status"], $_GET["transaction"], $_GET["montant"], $_GET["vendeur"], $_GET["control"])) {
    die("Erreur : Paramètres incorrects.");
}
$id_transaction = $_GET["transaction"];
$montant = floatval($_GET["montant"]);
// achats en cours
$info = json_decode(file_get_contents("../../donnees/paiement/transaction_en_cours.json"), true);
// achats finis
$tout_tracabilite = json_decode(file_get_contents("../../donnees/paiement/transaction_finis.json"), true);
if (empty($info[$id_transaction])) {
    die("Erreur : Transaction introuvable.");
}
$achat = $info[$id_transaction];
$ids_voyages = $achat["ids_voyages"];

$type_achat = $achat["type_achat"];
$type_achat = $type_achat == "panier" ? "panier" : $ids_voyages[0];
$ids_voyages_encodes = urlencode(json_encode($ids_voyages));

if ($_GET["status"] != "accepted") {
    header("Location: ../paiement_refuse.php?raison=paiement_refuse&achat=$type_achat");
    exit;
}


require_once "../php-include/fonctions_voyages.php";
$voyages_a_acheter = chargerVoyagesParIds($ids_voyages);  //modifie aussi dans fonctions_voyages.php
if ($voyages_a_acheter == null) {
    die("Erreur : ID de voyages introuvables.");
}

$opt_voyages_deja_achetes = $utilisateur["voyages"]["achetes"];
$opt_voyages_consultes = $utilisateur["voyages"]["consultes"];

foreach ($ids_voyages as $id) {
    if (!empty($opt_voyages_deja_achetes[$id])) {
        header("Location:../paiement_refuse.php?raison=voyage_deja_achete&id_transaction=$id_transaction&achat=$type_achat");
        die("Erreur : Voyage $id déjà acheté, demandez un remboursement, id de transaction: $id_transaction");
    }
    if (empty($opt_voyages_consultes[$id])) {
        die("Erreur : Voyage $id introuvable dans la liste des voyages consultés.");
    }
    $places_achetes = intval($achat["options_enregistres"][$id]["nombre_personnes_totales"]);
    $places_restantes = intval($voyages_a_acheter[$id]['nb_places_restantes']);
    if ($places_achetes > $places_restantes) {
        header("Location:../paiement_refuse.php?raison=places_restantes_insuffisantes&id_transaction=$id_transaction&achat=$type_achat");
        die("Erreur : Vous avez dépassé le nombre de places restantes, demandez un remboursement. id transaction : $id_transaction");
    }
    if ($places_achetes < 0) {
        die("Erreur : Vous ne pouvez pas acheter un nombre négatif de places.");
    }
}
if (abs($montant - $achat["prix_total"]) > 0.005) {
    header("Location:../paiement_refuse.php?raison=montant_incoherent&achat=$type_achat");
    die("Erreur : Montant de la transaction incohérent.");
}

require_once "../../config.php";

$date = date("j_F_Y");

$vendeur = $_GET["vendeur"];
if ($vendeur != VENDEUR) {
    die("Erreur : Vendeur incorrect.");
}


$tout_tracabilite[$id_transaction] = $achat;
$open = fopen("../../donnees/paiement/transaction_finis.json", 'w');
fwrite($open, json_encode($tout_tracabilite, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_NUMERIC_CHECK));
fclose($open);

unset($info[$id_transaction]);
file_put_contents("../../donnees/paiement/transaction_en_cours.json", json_encode($info, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_NUMERIC_CHECK));

foreach ($ids_voyages as $id) {
    $places_achetes = intval($achat["options_enregistres"][$id]["nombre_personnes_totales"]);
    $places_restantes = intval($voyages_a_acheter[$id]['nb_places_restantes']);
    $voyages_a_acheter[$id]['nb_places_restantes'] = $places_restantes - $places_achetes;
    $voyages_a_acheter[$id]["email_personnes_inscrites"][$utilisateur["email"]] = $places_achetes;
    sauvegarder_voyage($voyages_a_acheter[$id]);

    $utilisateur["voyages"]["achetes"][$id] = $achat["options_enregistres"][$id];
    $utilisateur["voyages"]["achetes"][$id]["id_transaction"] = $id_transaction;
    $utilisateur["voyages"]["achetes"][$id]["date_achat"] = $date;
    unset($utilisateur["voyages"]["consultes"][$id]);

    if (isset($utilisateur["voyages"]["panier"][$id])) {
        unset($utilisateur["voyages"]["panier"][$id]);
    }
}

sauvegarderSessionUtilisateur($utilisateur);

header("Location: ../profil.php");
exit;

?>
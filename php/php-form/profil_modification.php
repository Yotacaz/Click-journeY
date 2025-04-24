<?php

session_start();
require_once "../php-include/utilisateur.php";
$utilisateur = connexionUtilisateurRequise();
if (!utilisateurValide($utilisateur)) {
    header("Location: ../profil.php?erreur=utilisateur_invalide");
    exit;
}
require_once "../php-include/utiles.php";

if (!isset($_POST["valider-modif"]) || $_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: ../profil.php?erreur=action_invalide");
    exit;
}
if (empty($_POST["mdp-actuel"])) {
    header("Location: ../profil.php?erreur=mdp_actuel_vide");
    exit;
} elseif (!est_mdp($_POST["mdp-actuel"])) {
    header("Location: ../profil.php?erreur=mdp_actuel_invalide");
    exit;
} elseif (!verifierMdpUtilisateur($utilisateur, $_POST["mdp-actuel"])) {
    header("Location: ../profil.php?erreur=mdp_actuel_incorrect");
    exit;
}


if (est_nom($_POST["nom"]) == false) {
    header("Location: ../profil.php?erreur=nom_invalide");
    exit;
} elseif (est_nom($_POST["prenom"]) == false) {
    header("Location: ../profil.php?erreur=prenom_invalide");
    exit;
} elseif ($_POST["genre"] != "homme" && $_POST["genre"] != "femme" && $_POST["genre"] != "autre") {
    header("Location: ../profil.php?erreur=genre_invalide");
    exit;
}
$utilisateur["info"]["nom"] = test_input($_POST["nom"]);
$utilisateur["info"]["prenom"] = test_input($_POST["prenom"]);
$utilisateur["info"]["sexe"] = test_input($_POST["genre"]);

if (est_date($_POST["date"])) {
    $utilisateur["info"]["date_naissance"] = test_input($_POST["date"]);
} else {
    header("Location: ../profil.php?erreur=date_invalide");
    exit;
}


if (est_mdp($_POST["mdp"])) {
    if (est_mdp($_POST["mdp2"]) && $_POST["mdp"] !== $_POST["mdp2"]) {
        header("Location: ../profil.php?erreur=mdp_different");
        exit;
    }
    //TODO : ne pas changer le mot de passe directement (ex envoyer un mail de confirmation)
    $utilisateur["mdp"] = test_input($_POST["mdp"]);
} else {
    if($_POST["mdp2"] != "") {
        header("Location: ../profil.php?erreur=mdp_invalide");
        exit;
    }
}


sauvegarderSessionUtilisateur($utilisateur);
header("Location: ../profil.php?modification=ok");
exit;


?>
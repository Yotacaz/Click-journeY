<?php

//Ce fichier permet la suppression d'un voyage du panier.
//Il est appelé par la bouton ajouter au panier dans panier.php

// on récupère les données via $_POST
// en cas de fetch (pas de redirection), on renvoie le prix du voyage supprimé du panier


// restauration de la session utilisateur.
session_start();
require_once "../php-include/utilisateur.php";
$utilisateur = restaurerSessionUtilisateur();

require_once "../php-include/utiles.php";
$redirection = isset($_POST["pas_de_redirection"]) ? false : true;

if ($utilisateur == null || !utilisateurValide($utilisateur)) {
    if ($redirection) {
        header("Location: ../connexion.php");
        exit;
    } else {
        http_response_code(403);    // 403 Forbidden
        die("Vous n'êtes pas connecté.");
    }
}

$prix = 0;
//màj du panier
if (isset($_POST["id_voyage"]) && $_SERVER['REQUEST_METHOD'] === "POST") {
    $id_voyage = intval($_POST["id_voyage"]);
    if (empty($utilisateur["voyages"]["panier"][$id_voyage])) {
        http_response_code(404);
        die("Erreur : Voyage introuvable parmis les voyages du panier.");
    }
    $opt_enr = $utilisateur["voyages"]["panier"][$id_voyage];
    $prix = $opt_enr["prix"];
    $utilisateur["voyages"]["consultes"][$id_voyage] = $opt_enr;
    unset($utilisateur["voyages"]["panier"][$id_voyage]);
    sauvegarderSessionUtilisateur($utilisateur);
} else {
    http_response_code(400);
    die("Erreur : Paramètres incorrects.");
}

if ($redirection) {
    header("Location:../panier.php");
} else {
    http_response_code(200);
    echo $prix; // Pour le fetch de panier.php.
}
exit;
?>
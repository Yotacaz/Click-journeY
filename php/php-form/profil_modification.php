<?php

//Permet la modification du profil utilisateur, appelé depuis profil.php

session_start();
require_once "../php-include/utilisateur.php";
$utilisateur = connexionUtilisateurRequise();
if (!utilisateurValide($utilisateur)) {
    http_response_code(400);
    header("Content-Type: application/json");
    echo json_encode([
        "erreur" => "Vous devez être connecté pour accéder à cette page. 
    La structure de l'utilisateur est potentiellement invalide."
    ]);
    exit;
}
require_once "../php-include/utiles.php";



//Verification générale de la requête 
if (!isset($_POST["valider-modif"]) || $_SERVER["REQUEST_METHOD"] !== "POST") {
    http_response_code(400);
    header("Content-Type: application/json");
    echo json_encode(["erreur" => "Action invalide."]);
    exit;
}
if (empty($_POST["mdp-actuel"])) {
    http_response_code(400);
    header("Content-Type: application/json");
    echo json_encode(["erreur" => "Le mot de passe actuel est requis."]);
    exit;
}
$mdp_actuel = test_input($_POST["mdp-actuel"]);
if (!est_mdp($mdp_actuel)) {
    http_response_code(400);
    header("Content-Type: application/json");
    echo json_encode(["erreur" => "Format du mot de passe actuel invalide."]);
    exit;
} elseif (!verifierMdpUtilisateur($utilisateur, $mdp_actuel)) {
    http_response_code(403); // 403 car l'utilisateur est bien connecté, mais non autorisé
    header("Content-Type: application/json");
    echo json_encode(["erreur" => "Mot de passe actuel incorrect."]);
    exit;
}

$nv_mdp = test_input($_POST["mdp"]);
$nv_mdp_conf = test_input($_POST["mdp2"]);
$nv_nom = test_input($_POST["nom"]);
$nv_prenom = test_input($_POST["prenom"]);
$nv_genre = test_input($_POST["genre"]);
$nv_date_naissance = test_input($_POST["date"]);

//on considère que l'utilisateur a tenté de modifié son mot de passe
//si celui ci est valide
$mdp_est_modifie = est_mdp($nv_mdp);

//verification du contenu du formulaire
if (est_nom($nv_nom) == false) {
    http_response_code(400);
    header("Content-Type: application/json");
    echo json_encode(["erreur" => "Nom invalide."]);
    exit;

} elseif (est_prenom($_POST["prenom"]) == false) {
    http_response_code(400);
    header("Content-Type: application/json");
    echo json_encode(["erreur" => "Prénom invalide."]);
    exit;

} elseif (!in_array($_POST["genre"], ["homme", "femme", "autre"])) {
    http_response_code(400);
    header("Content-Type: application/json");
    echo json_encode(["erreur" => "Genre invalide."]);
    exit;

} else if (!est_date($_POST["date"])) {
    http_response_code(400);
    header("Content-Type: application/json");
    echo json_encode(["erreur" => "Date de naissance invalide."]);
    exit;

} else if (!$mdp_est_modifie && !empty($nv_mdp_conf)) {
    //le nouveau mdp est invalide et sa confirmation est fournie
    http_response_code(400);
    header("Content-Type: application/json");
    echo json_encode(["erreur" => "Mot de passe invalide."]);
    exit;

} else if ($mdp_est_modifie && (!est_mdp($nv_mdp_conf) || $nv_mdp !== $nv_mdp_conf)) {
    //$_POST["mdp"] est valide mais pas $_POST["mdp2"]
    http_response_code(400);
    header("Content-Type: application/json");
    echo json_encode([
        "erreur" => "Les mots de passe ne correspondent pas 
    (ou le deuxième mot de passe est invalide)."
    ]);
    exit;
}

//Màj données utilisateurs
$utilisateur["info"]["nom"] = $nv_nom;
$utilisateur["info"]["prenom"] = $nv_prenom;
$utilisateur["info"]["sexe"] = $nv_genre;
$utilisateur["info"]["date_naissance"] = $nv_date_naissance;
if ($mdp_est_modifie) {
    $utilisateur["mdp"] = $nv_mdp;  //Màj mot de passe si un nouveau a été fourni
}


//Sauvegarde et réponse en cas de succès
sauvegarderSessionUtilisateur($utilisateur);
http_response_code(200);
header("Content-Type: application/json");
echo json_encode(["message" => "Modifications enregistrées avec succès."]);

exit;
?>
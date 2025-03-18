<?php

function utilisateurValide(array $utilisateur)
{
    if (isset($utilisateur["email"]) && isset($utilisateur["mdp"]) && isset($utilisateur["info"]["nom"]) && isset($utilisateur["info"]["prenom"]) && isset($utilisateur["info"]["sexe"]) && isset($utilisateur["info"]["date_naissance"]) && isset($utilisateur["autres"]["date_inscription"]) && isset($utilisateur["autres"]["date_derniere_connexion"])) {
        return true;
    }
    return false;
}

function listerUtilisateurs()
{
    $utilisateurs = [];
    $dossier = "../donnees/utilisateurs/";
    $fichiers = scandir($dossier);
    foreach ($fichiers as $fichier) {
        if ($fichier != "." && $fichier != ".." && pathinfo($fichier, PATHINFO_EXTENSION) === 'json') {

            $utilisateur = json_decode(file_get_contents($dossier . $fichier), true);
            if (utilisateurValide($utilisateur)) {
                $utilisateurs[] = $utilisateur;
            }
        }
    }
    return $utilisateurs;
}

?>
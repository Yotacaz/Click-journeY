<?php

/**
 * @param array $utilisateur tableau associatif contenant les informations de l'utilisateur
 * @return bool true si l'utilisateur est valide, false sinon
 */
function utilisateurValide(array|null $utilisateur)
{
    if ($utilisateur === null) {
        return false;
    }
    if (isset($utilisateur["email"]) && isset($utilisateur["mdp"]) && isset($utilisateur["info"]["nom"]) && isset($utilisateur["info"]["prenom"]) && isset($utilisateur["info"]["sexe"]) && isset($utilisateur["info"]["date_naissance"]) && isset($utilisateur["autres"]["date_inscription"]) && isset($utilisateur["autres"]["date_derniere_connexion"])) {
        return true;
    }
    return false;
}

/**
 * @return array tableau indexé de tous les utilisateur contenant un tableau associatif avec les informations de l'utilisateur
 */
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

/**
 * @param string $email email de l'utilisateur
 * @return array|null tableau associatif contenant les informations de l'utilisateur, null si l'utilisateur n'existe pas
 */
function chargerUtilisateurParEmail(string $email)
{
    $dossier = "../donnees/utilisateurs/";
    $fichiers = scandir($dossier);
    $email_formate = preg_replace('/[^a-z0-9]+/', '-', strtolower($email));
    foreach ($fichiers as $fichier) {
        if ($fichier != "." && $fichier != ".." && pathinfo($fichier, PATHINFO_EXTENSION) === 'json' && str_starts_with(haystack: basename($fichier), needle: $email_formate)) {
            $utilisateur = json_decode(file_get_contents($dossier . $fichier), true);
            if ($utilisateur["email"] === $email) {
                return $utilisateur;
            }
        }
    }
    return null;
}

/**
 * @param string $email email de l'utilisateur
 * @return string|null chemin du fichier de l'utilisateur, null si l'utilisateur n'existe pas
 */
function chercherFichierUtilisateurParEmail(string $email)
{
    $dossier = "../donnees/utilisateurs/";
    $fichiers = scandir($dossier);
    $email_formate = preg_replace('/[^a-z0-9]+/', '-', strtolower($email));
    foreach ($fichiers as $fichier) {
        if ($fichier != "." && $fichier != ".." && pathinfo($fichier, PATHINFO_EXTENSION) === 'json' && str_starts_with(haystack: basename($fichier), needle: $email_formate)) {
            $utilisateur = json_decode(file_get_contents($dossier . $fichier), true);
            if ($utilisateur["email"] === $email) {
                return $dossier . $fichier;
            }
        }
    }
    return null;
}

/**
 * @param string $email email de l'utilisateur
 * @return string|null chemin du fichier à créer pour l'utilisateur, null si l'utilisateur existe déjà
 */
function genererCheminFichierUtilisateur(string $email): string|null
{
    $i = 0;
    $dossier = "../donnees/utilisateurs/";
    $fichiers = scandir($dossier);
    $email_formate = preg_replace('/[^a-z0-9]+/', '-', strtolower($email));
    foreach ($fichiers as $fichier) {
        if ($fichier != "." && $fichier != ".." && pathinfo($fichier, PATHINFO_EXTENSION) === 'json' && str_starts_with($email_formate, basename($fichier))) {
            $utilisateur = json_decode(file_get_contents($dossier . $fichier), true);
            if ($utilisateur["email"] === $email) {
                return null;    // utilisateur déjà existant, on ne peut pas créer un nouveau fichier
            }
            $i++;
        }
    }
    return "$dossier$email_formate" . "_$i.json";
}

/**
 * @param array $utilisateur tableau associatif contenant les informations de l'utilisateur
 * @return bool true si l'écriture a réussi, false sinon (utilisateur invalide)
 */
function ecrireFichierUtilisateur(array $utilisateur): bool
{
    if (!utilisateurValide($utilisateur)) {
        return false;
    }
    $chemin = chercherFichierUtilisateurParEmail($utilisateur["email"]);
    if ($chemin === null) {
        $chemin = genererCheminFichierUtilisateur($utilisateur["email"]);
    }
    file_put_contents($chemin, json_encode($utilisateur, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
    return true;
}

?>
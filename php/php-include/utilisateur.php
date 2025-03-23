
<?php
/**
 * @file utilisateur.php
 * @brief Fichier contenant les fonctions pour gérer les utilisateurs
 */
/**
 * @var string $dossier_utilisateurs chemin du dossier contenant les fichiers des utilisateurs
 */
$dossier_utilisateurs = "../donnees/utilisateurs/";


//ATTENTION : les fonctions de ce fichier ne sont valables que pour des fichiers include dans le dossier php
//(A cause du chemin des fichiers utilisateurs hardcodé)

/**
 * @return string chemin du dossier contenant les fichiers des utilisateurs
 */
function nomDossierUtilisateur(): string
{
    global $dossier_utilisateurs;
    return $dossier_utilisateurs;
}

/**
 * Génère un ID de session unique
 * @return string ID de session
 */
function genererSessionIdUnique(): string
{
    $essais_max = 15;
    $essais = 0;
    do {
        if ($essais > $essais_max) {
            die("Erreur lors de la génération de l'id de session.");
        }
        $essais++;
        $id_session = bin2hex(random_bytes(32));
        $session_file = session_save_path() . "/sess_$id_session";
    } while (file_exists($session_file));

    return $id_session;
}

/**
 * @param array $utilisateur tableau associatif contenant les informations de l'utilisateur
 * @return bool true si l'utilisateur est valide, false sinon
 */
function utilisateurValide(array|null $utilisateur): bool
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
    global $dossier_utilisateurs;
    $fichiers = scandir($dossier_utilisateurs);
    foreach ($fichiers as $fichier) {
        if ($fichier != "." && $fichier != ".." && pathinfo($fichier, PATHINFO_EXTENSION) === 'json') {

            $utilisateur = json_decode(file_get_contents($dossier_utilisateurs . $fichier), true);
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
    if ($email === "") {
        return null;
    }
    global $dossier_utilisateurs;
    $fichiers = scandir($dossier_utilisateurs);
    $email_formate = preg_replace('/[^a-z0-9]+/', '-', strtolower($email));
    foreach ($fichiers as $fichier) {
        if ($fichier != "." && $fichier != ".." && pathinfo($fichier, PATHINFO_EXTENSION) === 'json' && str_starts_with(haystack: basename($fichier), needle: $email_formate)) {
            $utilisateur = json_decode(file_get_contents($dossier_utilisateurs . $fichier), true);
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
    global $dossier_utilisateurs;
    $fichiers = scandir($dossier_utilisateurs);
    $email_formate = preg_replace('/[^a-z0-9]+/', '-', strtolower($email));
    foreach ($fichiers as $fichier) {
        if ($fichier != "." && $fichier != ".." && pathinfo($fichier, PATHINFO_EXTENSION) === 'json' && str_starts_with(haystack: basename($fichier), needle: $email_formate)) {
            $utilisateur = json_decode(file_get_contents($dossier_utilisateurs . $fichier), true);
            if ($utilisateur["email"] === $email) {
                return $dossier_utilisateurs . $fichier;
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
    global $dossier_utilisateurs;
    $fichiers = scandir($dossier_utilisateurs);
    $email_formate = preg_replace('/[^a-z0-9]+/', '-', strtolower($email));
    foreach ($fichiers as $fichier) {
        if ($fichier != "." && $fichier != ".." && pathinfo($fichier, PATHINFO_EXTENSION) === 'json' && str_starts_with($email_formate, basename($fichier))) {
            $utilisateur = json_decode(file_get_contents($dossier_utilisateurs . $fichier), true);
            if ($utilisateur["email"] === $email) {
                return null;    // utilisateur déjà existant, on ne peut pas créer un nouveau fichier
            }
            $i++;
        }
    }
    return "$dossier_utilisateurs$email_formate" . "_$i.json";
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

/**
 * renvoie l'id de l'utilisateur suivant pour la création d'un nouvel utilisateur
 *  et incrémente le fichier ID.json
 * @return int id de l'utilisateur suivant
 */
function genererIdUtilisateur(): int
{
    global $dossier_utilisateurs;
    $id = json_decode(file_get_contents("$dossier_utilisateurs"."_ID.json"), true);
    $id++;
    file_put_contents("../donnees/utilisateurs/_ID.json", json_encode($id));
    return $id;
}

/**
 * @return bool true si l'utilisateur est connecté (id de session valide et non expire), false sinon
 */
function utilisateurEstConnecte(): bool
{
    if (!isset($_COOKIE['id_session']) || empty($_COOKIE['id_session'])) {
        return false;
    }
    $id_session = $_COOKIE['id_session'];
    // echo "session_id() = " . session_id() . " id_session = $id_session";
    if (session_id() != $id_session || !isset($_SESSION[$id_session])) {
        return false;
    }
    return true;
}


/**
 * Restaure la session de l'utilisateur à partir de l'id de session stocké dans les cookies
 * **A n'utiliser qu'avant toute balise html.**
 * @return array|null tableau associatif contenant les informations de l'utilisateur, null si l'utilisateur n'est pas connecté
 */
function restaurerSessionUtilisateur(){
    if (!utilisateurEstConnecte()) {
        return null;
    }
    $fichier_session = session_save_path() . "/sess_" . $_COOKIE['id_session'];
    if (!file_exists($fichier_session)) {
        return null;
    }
    session_write_close();
    session_id($_COOKIE['id_session']);
    session_start();
    return $_SESSION[$_COOKIE['id_session']];
}

/**
 * Assure que l'utilisateur est connecté, sinon redirige vers la page de connexion.
 * **A n'utiliser que pour les pages nécessitant une connexion 
 * et qu'avant les balises html.**
 * @return array tableau associatif contenant les informations de l'utilisateur.
 * Si l'utilisateur n'est pas connecté redirige vers la page de connexion
 */
function connexionUtilisateurRequise()
{
    if (!utilisateurEstConnecte()) {
        header("Location: connexion.php");
        exit;
    }
    return restaurerSessionUtilisateur();
}

function adminRequis()
{
    $utilisateur = connexionUtilisateurRequise();
    if ($utilisateur["role"] != "admin") {
        header("Location: profil.php");
        exit;
    }
    return $utilisateur;
}



?>
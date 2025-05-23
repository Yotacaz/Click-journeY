<?php
//Fichier pour les fonctions utilitaires pas nécessairement 
// liées à un type de données particulier

/**
 * Fonction pour traiter les données d'entrée  d'un formulaire (non numériques)  
 * @param string $data chaîne de caractères à traiter
 * @return string chaine de caractères traitée (vide si $data est null)
 */
function test_input($data)
{
    if ($data == null) {
        return "";
    }
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}



/**
 * vérifie si une date est valide au format YYYY-MM-DD
 * @param string $date la date à verifier
 * @return bool true si la date est valide, false sinon
 * @example est_date("2023-10-15") => true
 */
function est_date($date)
{
    $d = DateTime::createFromFormat('Y-m-d', $date);
    if ($d === false) {
        return false;
    }
    return $d->format('Y-m-d') === $date;
}

/**
 * Vérifie si une adresse e-mail est valide selon les critères suivants:
 * - Doit être au format valide (ex: exemple@domaine.fr)
 * - Doit avoir une longueur maximale de MAX_STRING_LENGTH caractères
 * @param string $email L'adresse e-mail à valider
 * @return bool true si l'adresse e-mail est valide, false sinon
 */
function est_email($email)
{
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false
        && strlen($email) <= MAX_STRING_LENGTH;
}


/**
 * Vérifie si le mot de passe est valide selon les critères suivants:
 * - Doit contenir au moins un chiffre
 * - Doit contenir au moins un caractère spécial parmi !@#$%^&*
 * - Doit avoir une longueur entre 6 et 16 caractères
 * @param string $motDePasse Le mot de passe à valider
 * @return bool true si le mot de passe est valide, false sinon
 */
function est_mdp($motDePasse)
{
    $regex = '/^(?=.*[0-9])(?=.*[!@#$%^&*])[a-zA-Z0-9!@#$%^&*]{6,16}$/';
    return preg_match($regex, $motDePasse) === 1;
}

/**
 * Vérifie si un nom est valide selon les critères suivants :
 * - Ne contient que des lettres, espaces ou tirets.
 * - A une longueur comprise entre MIN_STRING_LENGTH et MAX_STRING_LENGTH.
 *
 * @param string $nom Le nom à valider.
 * @return bool true si le nom est valide, false sinon.
 */
function est_nom($nom)
{
    return preg_match('/^[\p{L}\s\-]+$/u', $nom) === 1 && strlen($nom) >= MIN_STRING_LENGTH && strlen($nom) <= MAX_STRING_LENGTH;
}

/**
 * Vérifie si un prénom est valide (mêmes critères qu'un nom).
 *
 * @param string $prenom Le prénom à valider.
 * @return bool true si le prénom est valide, false sinon.
 */
function est_prenom($prenom)
{
    return est_nom($prenom);
}

const MIN_STRING_LENGTH = 2;
const MAX_STRING_LENGTH = 50;
const MIN_MDP_LENGTH = 6;
const MAX_MDP_LENGTH = 16;


function supprimer_caracteres_inutiles($texte)
{
    $texte = strtolower($texte);

    $texte = preg_replace('/[^\p{L}\s]/u', '', $texte); // Supprime les caractères non alphabétiques
    $texte = preg_replace('/\s+/', ' ', $texte);    // supprime espaces en trop

    return trim($texte);
}

/**
 * Nettoie une chaîne de caractères en supprimant les mots et les caractères inutiles
 * @param mixed $texte  chaîne de caractères à nettoyer
 * @return string[] tableau de mots nettoyés
 */
function nettoyer_chaine($texte): array
{
    if (!is_string($texte)) {
        return [];
    }
    $texte = supprimer_caracteres_inutiles($texte);
    $mots = explode(' ', $texte);
    $mots = array_diff($mots, stopWords); // Supprime les mots inutiles

    return array_values($mots);
}



/**
 * Fonction pour chercher un mot dans un tableau multidimensionnel 
 * (suffit qu'il soit en début d'un mot dans une chaine de caractères
 * si est de plus de 2 caractères ou égal à la chaîne de caractères sinon)
 * @param mixed $mot la valeur à rechercher
 * @param array $liste le tableau dans lequel rechercher
 * @return bool true si la valeur existe, false sinon
 */
function mot_dans_tableau_multiDim_rec($mot, $liste): bool
{
    if (is_array($liste)) {
        foreach ($liste as $item) {
            if (is_string($item)) {
                if (stripos($item, $mot) === 0) {
                    if ($mot === $item || strlen($mot) > 2) {
                        return true;
                    }
                }
            } else {
                if (mot_dans_tableau_multiDim_rec($mot, $item)) {
                    return true;
                }

            }
        }
    }
    return false;
}


const stopWords = [
    "le",
    "la",
    "les",
    "un",
    "une",
    "des",
    "du",
    "de",
    "d'",
    "au",
    "aux",
    "ce",
    "cet",
    "cette",
    "ces",
    "mon",
    "ton",
    "son",
    "notre",
    "votre",
    "leur",
    "ma",
    "ta",
    "sa",
    "mes",
    "tes",
    "ses",
    "nos",
    "vos",
    "leurs",
    "et",
    "ou",
    "mais",
    "donc",
    "or",
    "ni",
    "car",
    "a",
    "à",
    "en",
    "dans",
    "par",
    "pour",
    "avec",
    "sur",
    "sous",
    "chez",
    "vers",
    "que",
    "qui",
    "quoi",
    "dont",
    "où",
    "comme",
    "ne",
    "pas",
    "plus",
    "moins",
    "aucun",
    "aucune",
    "chaque",
    "tout",
    "tous",
    "toute",
    "toutes",
    "lui",
    "elle",
    "eux",
    "on",
    "nous",
    "vous",
    "ils",
    "elles",
    "y",
    "en",
    "cela",
    "ça",
    "ceci",
    "c'",
    "est",
    "être",
    "été",
    "sont",
    "fut",
    "était",
    "sera",
    "si",
    "afin",
    "ainsi",
    "alors",
    "après",
    "avant",
    "bien",
    "comme",
    "depuis",
    "encore",
    "jamais",
    "déjà",
    "toujours",
    "souvent",
    "très",
    "peu",
    "beaucoup",
    "autre",
    "autres",
    "même",
    "mêmes"
];

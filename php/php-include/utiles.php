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
 * @param string $date
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

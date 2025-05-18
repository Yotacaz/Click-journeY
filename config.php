<?php

//Fichier contenant diverse information/paramètres tels que les chemins absolus pour 
// les fichiers, les urls absolues, le nom du site (pas utilisé)...

define('CHEMIN_RACINE', realpath(__DIR__));
const CHEMIN_INCLUDE = CHEMIN_RACINE . '/php-include';
const CHEMIN_IMG = CHEMIN_RACINE . '/img';
const CHEMIN_DONNEES = CHEMIN_RACINE . '/donnees';
const CHEMIN_JS = CHEMIN_RACINE . '/js';
const CHEMIN_CSS = CHEMIN_RACINE . '/style.css';

//Trouvé sur stackoverflow
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
$host = $_SERVER['HTTP_HOST'];

$racineWeb = realpath($_SERVER['DOCUMENT_ROOT']);   //ex C:\wamp64\www

//A utiliser pour les images, redirections ...
define('URL_RELATIVE', str_replace("\\","/", str_replace($racineWeb, '', CHEMIN_RACINE)));
define('URL_BASE', $protocol . '://' . $host . URL_RELATIVE);



//paramètres du site (peu utilisé)
const NOM_SITE = 'PixelTravels';
const AUTEUR_SITE = ['Augustin AVELINE', 'CRISSOT Martin', 'DIOP Bineta'];
const DESCRIPTION_SITE = 'Site de réservation de voyages sur le thème du jeu vidéo';
const VENDEUR = "MI-3_A" 

?>
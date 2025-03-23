<?php
session_start();
require_once "php-include/utilisateur.php";
$utilisateur = connexionUtilisateurRequise($_SERVER["PHP_SELF"]);
if ($utilisateur != null && !utilisateurValide($utilisateur)) {
    die("Erreur : Utilisateur invalide");
}

require_once "php-include/fonctions_voyages.php";

$voyages= decodageDonnees("../donnees/voyage/voyages.json");
if (!is_array($voyages)) {
    die("Erreur: Impossible de charger les données des voyages.");
}

$identifiant=recup_id();
if (!isset($voyages[$identifiant])) {
    die("Erreur: Identifiant voyage non trouvé.");
}



if ($_SERVER["REQUEST_METHOD"] == "POST") {


    foreach ($voyages[$identifiant]['etapes'] as $etape_index => &$etape) {
        if (isset($_POST['date_debut_' . $etape_index])) {
            echo "changer date";
        $etape['dates']['debut'] = $_POST['date_debut_' . $etape_index];
    }else echo "pas de date";
    if (isset($_POST['date_fin_' . $etape_index])) {
        $etape['dates']['fin'] = $_POST['date_fin_' . $etape_index];
    }
        foreach ($etape['options'] as $option_index => &$option) {
            // Récupérer la valeur sélectionnée et le nombre de personnes
            $option_nom = 'option_' . $etape_index . '_' . $option_index;
            $nombre_personnes_nom = 'nombre_personnes_' . $etape_index . '_' . $option_index;

            // Vérifier si une modification a été envoyée
            if (isset($_POST[$option_nom])) {
                $option['valeur_choisie'] = $_POST[$option_nom];  // Enregistrer la valeur choisie pour l'option
            }

            if (isset($_POST[$nombre_personnes_nom])) {
                $option['nombre_personnes'] = $_POST[$nombre_personnes_nom];  // Enregistrer le nombre de personnes
            }
            echo $_POST[$nombre_personnes_nom];
        }
    }

    // Sauvegarder les modifications dans le fichier JSON !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
    
    if (file_put_contents("../donnees/voyage/voyages.json", json_encode($voyages, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT)) === false) {
    die("Erreur: Impossible d'écrire dans le fichier JSON ! veuillez modifier les droit d'ecriture sur voyages.json");
}

    // Rediriger vers la page récapitulative
    header("Location: recap_voyage.php?id=".$identifiant."");
    exit;
}else echo "pas de POST";
?>

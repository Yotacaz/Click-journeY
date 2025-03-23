<?php
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


    // Charger les données existantes du fichier JSON
    $voyages= decodageDonnees("../donnees/voyage/voyages.json");
    if (!is_array($voyages)) {
    die("Erreur: Impossible de charger les données des voyages.");
}
    $identifiant=recup_id();
    if (!isset($voyages[$identifiant])) {
    die("Erreur: Identifiant voyage non trouvé.");
}

    // Parcourir chaque étape et option pour récupérer les modifications envoyées
    foreach ($voyages[$identifiant]['etapes'] as $etape_index => &$etape) {
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
        }
    }

    // Sauvegarder les modifications dans le fichier JSON
    file_put_contents("../donnees/voyage/voyages.json", json_encode($voyages, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));

    // Rediriger vers la page récapitulative
    header("Location: recap_voyage.php?id=".$identifiant."");
    exit;
}
?>

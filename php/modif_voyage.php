<?php
session_start();
require_once "php-include/utilisateur.php";
require_once "php-include/fonctions_voyages.php";
require_once "php-include/utiles.php";


$identifiant_v = recup_id_voyage();
$utilisateur = connexionUtilisateurRequise('recap_voyage.php?id="' . $identifiant_v . '"');
if ($utilisateur != null && !utilisateurValide($utilisateur)) {
    die("Erreur : Utilisateur invalide");
}

if ($_SERVER["REQUEST_METHOD"] != "POST" || !isset($_POST["submit_voyage"])) {
    die("Erreur: pas de POST");
}

$voyage = chargerVoyageParId($identifiant_v);
if ($voyage == null) {
    die("Erreur : ID de voyage $identifiant_v  introuvable ou corrompu.");
}


if (!empty($utilisateur["voyages"]["achetes"][$identifiant_v])) {
    header("Location: recap_voyage.php?id=" . $identifiant_v . "");
    die("La modification d'un voyage ne devrait pas être possible 
    pour un utilisateur l'ayant déjà payer");
}

unset($_POST["submit_voyage"]);
$opt_enr = $_POST;

$opt_enr["nombre_personnes_totales"] = intval($opt_enr["nombre_personnes_totales"]);

$places = intval($voyage['nb_places_tot']);
$places_restantes = intval($voyage['nb_places_restantes']);
if ($places_restantes < 0) {
    die("Erreur : Nombre de places négatif.");
} else if ($places <= 0) {
    die("Erreur : Nombre de places total négatif ou nul.");
} else if ($places_restantes > $places) {
    die("Erreur : Nombre de places restantes supérieur au nombre total de places.");
} else if ($opt_enr["nombre_personnes_totales"] > $places_restantes) {
    // die("Erreur : nombre de personnes supérieur au nombre de places restantes.");
    // Rediriger vers la page de réservation avec un message d'erreur
    header("Location: details_voyage.php?id=" . $identifiant_v . "&erreur=places_insuffisantes");
    exit;
} else if ($opt_enr["nombre_personnes_totales"] <= 0) {
    die("Erreur : nombre de personnes négatif ou nul.");
}

echo "<pre>" . print_r($opt_enr) . "</pre>";

//calcul du prix total & verification des options
$prix = floatval($voyage['prix_total']) * $opt_enr["nombre_personnes_totales"];
foreach ($voyage['etapes'] as $etape_index => $etape) {
    foreach ($etape['options'] as $option_index => $option) {
        $nom_nb_personne_option_form = "nombre_personnes_$etape_index" . "_$option_index";
        $opt_enr[$nom_nb_personne_option_form] = intval($opt_enr[$nom_nb_personne_option_form]);
        if ($opt_enr[$nom_nb_personne_option_form] > $opt_enr["nombre_personnes_totales"]) {
            // die("Erreur : nombre de personnes supérieur au nombre de places en cours de reservation.");
            // Rediriger vers la page de réservation avec un message d'erreur
            header("Location: details_voyage.php?id=" . $identifiant_v . "&erreur=places_insuffisantes");
            exit;
        }
        if ($opt_enr[$nom_nb_personne_option_form] < 0) {
            die("Erreur : nombre de personnes négatif.");
        }

        $nom_option_form = "option_$etape_index" . "_$option_index";
        if (isset($opt_enr[$nom_option_form])) {
            $opt_enr[$nom_option_form] = test_input($opt_enr[$nom_option_form]);
            if (!in_array($opt_enr[$nom_option_form], $option['valeurs_possibles'])) {
                die("Erreur : valeur de l'option $nom_option_form(" . $opt_enr[$nom_option_form] . ") non valide.");
            }
        }
        $prix += floatval($option['prix_par_personne']) * $opt_enr[$nom_nb_personne_option_form];
    }
}
$opt_enr["prix"] = $prix;

//Sauvegarde des modification utilisateur
$utilisateur["voyages"]["consultes"][$identifiant_v] = $opt_enr;
sauvegarderSessionUtilisateur($utilisateur);


///!\Sauvegarde des modification sur le fichier voyages.json sera effectuée après le payement,
//même cela signifie qu'un problème peut survenir si un utilisateur commence à résa 
//un voyage pendant qu'un autre fini d'acheter la dernière place

// Rediriger vers la page récapitulative
header("Location: recap_voyage.php?id=" . $identifiant_v . "");
exit;
?>
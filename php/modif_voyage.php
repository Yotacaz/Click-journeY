<?php
session_start();
require_once "php-include/utilisateur.php";
require_once "php-include/fonctions_voyages.php";

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

//calcul du prix total
$prix = floatval($voyage['prix_total']);
foreach ($voyage['etapes'] as $etape_index => $etape) {
    foreach ($etape['options'] as $option_index => $option) {
        $nom_nb_personne_form = "nombre_personnes_$etape_index" . "_$option_index";
        $prix += floatval($option['prix_par_personne']) * floatval($opt_enr[$nom_nb_personne_form]);
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
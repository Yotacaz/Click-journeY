<?php
//ficher qui recois les donnees du formulaire au format JSON
//et calcul le prix total en fonctions des options et du nombre de personnes



header('Content-Type: application/json');
session_start();
require_once "../php-include/fonctions_voyages.php";



//reception donnees
$input = json_decode(file_get_contents('php://input'), true);

if (!$input || !isset($input['id'])) {
  echo json_encode(["erreur" => "Type de contenu incorrect id"]);
  exit; //quitter si les donnees sont incompletes
}

//charger les info du voyage grace a l'identifiant
$identifiant = $input["id"];
$voyage = chargerVoyageParId($identifiant);
if ($voyage == null) {
  echo json_encode(["erreur" => "Type de contenu incorrect voyage"]);
  exit; //quitter si les donnees sont incompletes
  //eventuelement ajouter message d'erreur
}

$nbPersTotal = intval($input['nombre_personnes_totales'] ?? 1);
$prixTotal = $nbPersTotal * floatval($voyage['prix_total']); //calcul prix sans options


//parcourir les option et faire la somme des prix successivement
foreach ($voyage["etapes"] as $index_etape => $etape) {
  foreach ($etape["options"] as $index_option => $option) {
    $nom_option = 'option_' . $index_etape . '_' . $index_option;
    $nom_nombre = 'nombre_personnes_' . $index_etape . '_' . $index_option;

    if (!isset($input[$nom_option]) || !isset($input[$nom_nombre])) {
      continue; //on retoune a la prochaine iteration si ces indexes ne sont pas dans la requete
    }
    $valeurOption = $input[$nom_option];
    $nbPersOption = intval($input[$nom_nombre]);

    if (isset($option['valeurs_possibles'][$valeurOption])) {
      $prixOption = floatval($option['valeurs_possibles'][$valeurOption]);
      $prixTotal += $nbPersOption * $prixOption;
    }
  }
}

//retouner le prix
echo json_encode($prixTotal);

?>
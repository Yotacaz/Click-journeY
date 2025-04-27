<?php

require_once realpath(__DIR__ . '/../../config.php');


const NOM_FICHIER = "voyages.json";
$chemin_voyage = realpath(CHEMIN_DONNEES . "/voyage/".NOM_FICHIER);
const DOSSIER_IMG_VOYAGE = CHEMIN_RACINE . "/img/voyage";
const URL_IMG_VOYAGE = URL_RELATIVE . "/img/voyage";
// die(URL_RELATIVE);

if (!file_exists($chemin_voyage)) {
    echo $chemin_voyage;
    die("Le fichier voyage n'existe pas");
}

$contenu_fichier_voyage = file_get_contents($chemin_voyage);

if ($contenu_fichier_voyage === false) {
    die("Problème lors de la lecture de $chemin_voyage");
}
$voyages = json_decode($contenu_fichier_voyage, true);
if ($voyages == null) {
    die("Problème lors de la lecture de $chemin_voyage");
}

function chargerJsonVoyages()
{
    global $contenu_fichier_voyage;
    return $contenu_fichier_voyage;
}
function chargerVoyages(): array
{
    global $voyages;
    return $voyages;
}

//TODO A supprimer (inutile)
function chargerVoyageParNom(string $nom_voyage)
{
    global $voyages;
    foreach ($voyages as $key => $value) {
        if ($value["titre"] === $nom_voyage) {
            return $value;
        }
    }
    return null;
}

/**
 * Charge un voyage grâce à un id
 * @param int $id id du voyage à charger
 * @return mixed le tableau associatif du voyage correspondant s'il existe, **null** sinon
 */
function chargerVoyageParId(int $id)
{
    global $voyages;
    if (empty($voyages[$id]) || $voyages[$id]["id"] != $id) {
        return null;
    }
    return $voyages[$id];
}

function formaterTitreVoyage(string $titre)
{
    return preg_replace('/[^a-z0-9]+/', '_', strtolower($titre));
}

function afficherResumeVoyage(array $voyage)
{
    
    $titre_formate = formaterTitreVoyage($voyage["titre"]);
    $chemin_image = realpath(DOSSIER_IMG_VOYAGE."/$titre_formate/$titre_formate.png");
    $url_img = URL_IMG_VOYAGE."/$titre_formate/$titre_formate.png";
    if (!file_exists($chemin_image)) {
        echo $chemin_image;
        die("Fichier image de $titre_formate.png inexistant");
    }
    $index = intval($voyage['id']);
    echo
        '<div class="carte-info">
            <img alt="' . $voyage["titre"] . '" src="' . $url_img . '">
            <div class="contenu-carte-info">
                <div class="flex">
                    <h2>' . $voyage["titre"] . '&nbsp</h2>
                    <b> - ' . $voyage["note"] . '/5 ⭐</b>
                </div>
                <p>' . $voyage["description"] . '</p>
                <p> Du ' . $voyage["dates"]["debut"] . ' au ' . $voyage["dates"]["fin"] . ' (' . $voyage["dates"]["duree"] . ' jours)</p>
                <p>' . $voyage["localisation"]["pays"] . ', ' . $voyage["localisation"]["ville"] . '</p>
                <p>' . count($voyage["etapes"]) . ' étape(s)</p>
                <p> Prix : ' . $voyage["prix_total"] . ' €</p>
            </div>
            <a href="details_voyage.php?id=' . $index . '">  <span class="lien-span"></span></a>
        </div>';
}


function trierVoyage($fichier)
{
    $voyages = chargerVoyages();
    if (is_array($voyages)) { //decodage reussi on peut trier

        usort(
            $voyages,
            function ($a, $b) {
                return $b["note"] - $a["note"];  // Tri par note du + au -
            }
        );
        return $voyages;
    } else {     // Si ce n'est pas un tableau, afficher le message d'erreur
        echo $voyages;
        exit();
    }
}

function recup_id_voyage()
{
    if (!isset($_GET["id"])) {
        header("Location: recherche.php");
        die("Erreur : Aucun identifiant de voyage spécifié");
    }
    global $voyages;
    $identifiant = intval($_GET["id"]);
    if (empty($voyages[$identifiant])) {
        header("Location: recherche.php");
        die("Erreur : Identifiant de voyage spécifié incorrect");
    }
    return $identifiant;
}

?>
<?php

require_once realpath(__DIR__ . '/../../config.php');
require_once "utiles.php";

const NOM_FICHIER = "voyages.json";
define('CHEMIN_FICHIER_VOYAGE', realpath(CHEMIN_DONNEES . "/voyage/" . NOM_FICHIER));
const DOSSIER_IMG_VOYAGE = CHEMIN_RACINE . "/img/voyage";
const URL_IMG_VOYAGE = URL_RELATIVE . "/img/voyage";
// die(URL_RELATIVE);

if (!file_exists(CHEMIN_FICHIER_VOYAGE)) {
    echo CHEMIN_FICHIER_VOYAGE;
    die("Le fichier voyage n'existe pas");
}

$contenu_fichier_voyage = file_get_contents(CHEMIN_FICHIER_VOYAGE);

if ($contenu_fichier_voyage === false) {
    die("Problème lors de la lecture de " . CHEMIN_FICHIER_VOYAGE);
}
$voyages = json_decode($contenu_fichier_voyage, true);
if ($voyages == null) {
    die("Problème lors de la lecture de " . CHEMIN_FICHIER_VOYAGE);
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

/**
 * Charge les voyages grâce à une liste d'ids
 * @param array $ids ids des voyages à charger
 * @return array|null tableau des voyages correspondants s'ils existent tous, **null** sinon
 */
function chargerVoyagesParIds(array $ids)
{
    global $voyages;
    $resultat = [];
    foreach ($ids as $id) {
        if (isset($voyages[$id])) {
            if (empty($voyages[$id]) || $voyages[$id]["id"] != $id) {
                return null;
            }
            $resultat[$id] = $voyages[$id];
        }
    }
    return $resultat;
}

function formaterTitreVoyage(string $titre)
{
    return preg_replace('/[^a-z0-9]+/', '_', strtolower($titre));
}

function afficherResumeVoyage(array $voyage)
{

    $titre_formate = formaterTitreVoyage($voyage["titre"]);
    $chemin_image = realpath(DOSSIER_IMG_VOYAGE . "/$titre_formate/$titre_formate.png");
    $url_img = URL_IMG_VOYAGE . "/$titre_formate/$titre_formate.png";
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


/**
 * Trie les voyages par note décroissante et limite optionnellement le nombre de résultats.
 * @param int $nb_elements Le nombre maximum d'éléments à retourner. -1 pour tous les éléments.
 * @return array|void Retourne un tableau trié des voyages, ou termine le script si $voyages n'est pas un tableau.
 */
function trierVoyageParNote($nb_elements = -1)
{
    global $voyages;
    if (is_array($voyages)) { //decodage reussi on peut trier

        usort(
            $voyages,
            function ($a, $b) {
                return $b["note"] - $a["note"];  // Tri par note du + au -
            }
        );
        if ($nb_elements != -1) {
            $voyages = array_slice($voyages, 0, $nb_elements);
        }
        return $voyages;
    } else {     // Si ce n'est pas un tableau, afficher le message d'erreur
        echo $voyages;
        exit();
    }
}

function recup_id_voyage()
{

    if (!isset($_GET["id"])) {
        //cas possible, rediriger vers la page de recherche
        header("Location: recherche.php");
        exit; //Erreur : Aucun identifiant de voyage spécifié
    }
    global $voyages;
    $identifiant = intval($_GET["id"]);
    if (empty($voyages[$identifiant])) {
        die("Aucun voyage correspondant à l'identifiant $identifiant");
    }
    return $identifiant;
}

function rechercheTextuelle(string $texte): array
{
    global $voyages;
    $mots = nettoyer_chaine($texte);

    $priorite = [];
    foreach ($voyages as $voyage) {
        $id = intval($voyage["id"]);
        $priorite[$id] = 0;
        unset($voyage["email_personnes_inscrites"], $voyage["image"]);
        array_walk_recursive($voyage, 'nettoyer_chaine');
        foreach ($mots as $mot) {

            if (mot_dans_tableau_multiDim_rec($mot, $voyage["titre"])) {
                $priorite[$id] += 10;
            }
            if (mot_dans_tableau_multiDim_rec($mot, $voyage["description"])) {
                $priorite[$id] += 5;
            }
            if (mot_dans_tableau_multiDim_rec($mot, $voyage["mots_cles"])) {
                $priorite[$id] += 3;
            }
            if (mot_dans_tableau_multiDim_rec($mot, $voyage)) {
                $priorite[$id]++;
            }
        }
    }
    $resultat = [];
    arsort($priorite);

    foreach ($priorite as $id => $valeur) {
        if ($valeur > 0) {
            $resultat[] = $voyages[$id];
        }
    }
    return $resultat;
}

/**
 * Sauvegarde les données d'un voyage modifié dans le fichier JSON
 * @param array $voyage Le tableau associatif du voyage à sauvegarder
 */
function sauvegarder_voyage($voyage)
{
    global $voyages;
    $voyages[$voyage["id"]] = $voyage;
    $contenu_json = json_encode($voyages, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    file_put_contents(CHEMIN_FICHIER_VOYAGE, $contenu_json);
}

/**
 * Supprime les données sensibles (email, image) d'un voyage
 * @param array $voyage Le tableau associatif du voyage à nettoyer
 * @return void
 */
function unset_donnees_sensibles_voyage(array $voyage): void
{
    unset($voyage["email_personnes_inscrites"], $voyage["image"]);
}


/**
 * Transforme un tableau de voyages en une chaîne de caractères pour l'affichage dans une page JavaScript.
 * et supprime les données sensibles (email, image) avant de l'echo
 * @param array $voyages 
 */
function transmission_voyages_js(array $voyages): void
{
    for ($i = 0; $i < count($voyages); $i++) {
        unset_donnees_sensibles_voyage($voyages[$i]);

    }
    $tableau_json = json_encode($voyages);
    echo "var voyages = $tableau_json;\n";
}

?>
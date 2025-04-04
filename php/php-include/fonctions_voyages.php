<?php
//ATTENTION : actuellement ce fichier ne peut pas être inclus dans un autre fichier que php 
$nom_fichier = "voyages.json";
$chemin_voyage = "../donnees/voyage/$nom_fichier";
$dossier_img_voyage = "../img/voyage";
if (!file_exists($chemin_voyage)) {
    echo $chemin_voyage;
    die("Le fichier voyage n'existe pas");
}

$voyages = json_decode(file_get_contents($chemin_voyage), true);
if ($voyages == null) {
    die("Problème lors de la lecture de $chemin_voyage");
}
function chargerVoyages(): array
{
    global $voyages;
    return $voyages;
}

//TODO A supprimer
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
    if (empty($voyages[$id]) || $voyages[$id]["id"] != $id){
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
    global $dossier_img_voyage;
    $titre_formate = formaterTitreVoyage($voyage["titre"]);
    $chemin_image = "$dossier_img_voyage/$titre_formate/$titre_formate.png";
    if (!file_exists($chemin_image)) {
        echo $chemin_image;
        die("Fichier image de $titre_formate.png inexistant");
    }
    $index = intval($voyage['id']);
    echo
        '<div class="carte-info">
            <img alt="' . $voyage["titre"] . '" src="' . $chemin_image . '">
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

//TODO Retirer : fonction dupliquée
function decodageDonnees($fichier)
{
    if (file_exists($fichier)) {

        $voyages = file_get_contents($fichier);
        //decoder le fichier json
        $donnees = json_decode($voyages, true);

        //verifier si le decodage a marche
        if ($donnees === null) {
            $donnees = "probleme de decodage";
        }
    } else { // le fichier n'existe pas
        $donnees = "le fichier" . $fichier . " n'existe pas";
    }
    return $donnees;
}



function trierVoyage($fichier)
{
    $voyages = decodageDonnees($fichier);
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
    if (empty($voyages[$identifiant])){
        header("Location: recherche.php");
        die("Erreur : Identifiant de voyage spécifié incorrect");
    }
    return $identifiant;
}

?>
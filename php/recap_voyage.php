<?php
session_start();
require_once "php-include/utilisateur.php";
$utilisateur = connexionUtilisateurRequise($_SERVER["PHP_SELF"] . "?id=" . $_GET["id"]);
if ($utilisateur != null && !utilisateurValide($utilisateur)) {
    die("Erreur : Utilisateur invalide");
}

print_r($_POST);
if (isset($_POST["submit_voyage"])) {

}

// Charger les données du fichier JSON
require_once "php-include/fonctions_voyages.php";
$voyages = decodageDonnees("../donnees/voyage/voyages.json");
$identifiant = recup_id();

if (is_array($voyages)) { //decodage reussi on peut trier
    $titre_page = $voyages[$identifiant]["titre"];
} else {     // Si ce n'est pas un tableau, afficher le message d'erreur
    echo $voyages;
    exit();
}
$v = array_splice($voyages, $identifiant, 1);  //ne garder que le jeu de la page concernee  
$v = $v[0];
$utilisateur['voyages']['consultes'][] = $v;
ecrireFichierUtilisateur($utilisateur);
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta name="auteur" content="DIOP Bineta" />
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../style.css">
    <link rel="icon" type="image/x-icon" href="../img/logo.png">
    <meta name="description" content="Page de présentation du site et recherche rapide" />
    <title><?php echo "$titre_page"; ?></title>
</head>

<body>
    <?php
    require_once "php-include/header.php";
    ?>
    <br><br><br>
    <main>
        <div class="texte-centre">
            <h1>Récapitulatif du voyage</h1>

            <!-- Affichage -->
            <div class="voyage-details">
                <h2><?php echo $v['titre']; ?> (Note: <?php echo $v['note']; ?>)</h2>
                <p><strong>Description:</strong> <?php echo $v['description']; ?></p>
                <p><strong>Dates:</strong> <?php echo $v['dates']['debut']; ?> - <?php echo $v['dates']['fin']; ?></p>
                <p><strong>Durée:</strong> <?php echo $v['dates']['duree']; ?> jours</p>
            </div>

            <h3>Étapes du voyage</h3>

            <?php
            foreach ($v['etapes'] as $etape) {
                echo '<div class="etape">';
                echo '<h4>' . $etape['nom'] . '</h4>';
                echo '<p><strong>Dates:</strong> ' . $etape['dates']['debut'] . ' - ' . $etape['dates']['fin'] . '</p>';
                echo '<p><strong>Durée:</strong> ' . $etape['dates']['duree'] . ' jours</p>';

                // Affichage des options modifiées
                foreach ($etape['options'] as $option) {
                    echo '<p><strong>' . $option['nom'] . ':</strong> ' . $option['nombre_personnes'] . ' personnes choisies';
                    if (isset($option['valeur_choisie'])) {
                        echo ' (Option choisie: ' . $option['valeur_choisie'] . ')';
                    }
                    echo '</p>';
                }
                echo '</div>';
            } ?>
            <div class="texte-centre">
                <button class="input-formulaire grand" name="valider-recherche"><a
                        href="paiement.php">payer</a></button><br><br>
            </div>
        </div>
    </main>
    <?php
    require_once "php-include/footer.php";
    ?>
</body>

</html>
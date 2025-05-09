<?php
session_start();
//Gestion de l'utilisateur
require_once "php-include/utilisateur.php";


$utilisateur = connexionUtilisateurRequise($_SERVER["PHP_SELF"]);
if ($utilisateur != null && !utilisateurValide($utilisateur)) {
    die("Erreur : Utilisateur invalide");
}

$panier = empty($utilisateur["voyages"]["panier"]) ? [] : $utilisateur["voyages"]["panier"];
$prix_total = 0;
$taille_panier = count($panier);
$opt_voyages_consultes = $utilisateur["voyages"]["consultes"];

$prix_total = 0;
foreach ($panier as $id_v => $opt) {
    $prix_total += $opt["prix"];
    if (isset($opt_voyages_consultes[$id_v])) { // suppression des doublons (pour affichage)
        unset($opt_voyages_consultes[$id_v]);
    }
}
$taille_consultes = count($opt_voyages_consultes);

require_once "php-include/fonctions_voyages.php";

?><!DOCTYPE html>
<html lang="fr">

<head>
    <meta name="auteur" content="CRISSOT Martin" />
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../style.css">
    <link rel="icon" type="image/x-icon" href="../img/logo.png">
    <meta name="description" content="modification profil utilisateur" />
    <title>Panier - PixelTravels</title>
</head>

<body>
    <?php
    require_once "php-include/header.php";
    ?>
    <main>

        <h1 class="bandeau">Panier</h1>
        <div class="contour-bloc">

            <h2 class="js-replier">Votre panier (<span id="nb-panier"><?= $taille_panier ?></span> items, <span
                    id="prix-total"><?= $prix_total ?></span> €) :</h2>
            <ul class="repliable" id="liste-panier">
                <?php
                if ($taille_panier == 0) {
                    echo "<li><em>Vous n'avez pas de voyages dans votre panier</em></li>";
                }
                foreach ($panier as $id_v => $opt) {
                    $voyage = chargerVoyageParId($id_v);

                    if ($voyage != null) {

                        echo
                            '<li id="voyage_' . $id_v . '" data-id="' . $id_v . '"><b><a href="details_voyage.php?id=' . $id_v . '">' . $voyage["titre"] . '</a></b> - ' . $voyage["localisation"]["ville"]
                            . ', ' . $voyage["localisation"]["pays"] . ', du ' . $voyage["dates"]["debut"] . ' au ' . $voyage["dates"]["fin"] . ' pour
                            ' . $opt["prix"] . '€ (' . $opt["nombre_personnes_totales"] . ' personnes) 
                            <button id="modifier_' . $id_v . '" data-type="supprimer" class="input-formulaire modifier_voyage">Supprimer du panier</button></li>';
                    } else {
                        die("Erreur : Voyage non trouvé ou invalide");
                    }

                }

                ?>
            </ul>
            <?php
            echo "<a id=\"acheter-panier\" href=\"paiement.php?achat_panier=oui\" class=\"input-formulaire\""
                . ($taille_panier <= 0 ? " hidden " : "") . "\">Acheter le contenu du panier</a>";
            ?>
        </div>
        <div class="contour-bloc">
            <h2 class="js-replier">Vos voyages consultés (<span id="nb-consultes"><?= $taille_consultes ?></span>) :
            </h2>
            <ul class="repliable" id="liste-consultes">
                <?php

                if ($taille_consultes == 0) {
                    echo "<li><em>Vous n'avez pas de voyages consultés (hors panier).</em></li>";
                }
                foreach ($opt_voyages_consultes as $id_v => $opt) {
                    $voyage = chargerVoyageParId($id_v);

                    if ($voyage != null) {
                        echo
                            '<li id="voyage-' . $id_v . '" data-id="' . $id_v . '"><b><a href="details_voyage.php?id=' . $id_v . '">' . $voyage["titre"] . '</a></b> - ' . $voyage["localisation"]["ville"]
                            . ', ' . $voyage["localisation"]["pays"] . ', du ' . $voyage["dates"]["debut"] . ' au ' . $voyage["dates"]["fin"] . ' pour
                            ' . $opt["prix"] . '€ (' . $opt["nombre_personnes_totales"] . ' personnes) 
                            <button id="modifier_' . $id_v . '" data-type="ajouter" class="input-formulaire modifier_voyage">Ajouter au panier</button></li>';
                    } else {
                        die("Erreur : Voyage non trouvé ou invalide");
                    }
                }
                ?>
            </ul>
        </div>
    </main>
    <?php
    require_once "php-include/footer.php";
    ?>
    <script src="../js/utiles.js"></script>
    <script type="text/javascript">
        //liste des ids des voyages du panier
        let taille_panier = <?= $taille_panier ?>;
        //taille des voyages consultés
        let taille_consultes = <?= $taille_consultes ?>;
        let prix_total = <?= $prix_total ?>;
    </script>
    <script src="../js/panier.js"></script>
</body>

</html>
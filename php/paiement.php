<?php
session_start();
//Gestion de l'utilisateur
require_once "php-include/utilisateur.php";
require_once "php-include/fonctions_voyages.php";

$utilisateur = null;
if (!isset($_GET["achat_panier"])) {
    $identifiant_v = recup_id_voyage();
    $utilisateur = connexionUtilisateurRequise($_SERVER["PHP_SELF"] . "?id=" . $identifiant_v);
} else {
    $utilisateur = connexionUtilisateurRequise($_SERVER["PHP_SELF"] . "?achat_panier=1");
}
if ($utilisateur == null || !utilisateurValide($utilisateur)) {
    die("Erreur : Utilisateur invalide");
}

//gestion des voyages
$identifiant_v = [];
$voyage = null;
$opt_enr = null;  //liste des options enregistrées pour chaque voyage à payer 
$voyages_achetes = [];
$prix_total = 0;

//Chargement des options déjà enregistrées du/des voyage
$type_achat = isset($_GET["achat_panier"]) ? "panier" : "unitaire";
if (isset($_GET["achat_panier"])) {
    //achat de voyages groupé dans panier
    $opt_enr = !empty($utilisateur["voyages"]["panier"]) ? $utilisateur["voyages"]["panier"] : [];
    if (empty($opt_enr)) {
        header("Location: panier.php");
        exit; //Erreur : aucune option dans le panier
    }

    foreach ($opt_enr as $id_v => $opt) {
        $voyages_achetes[$id_v] = chargerVoyageParId($id_v);
        if ($voyages_achetes[$id_v] == null) {
            die("Erreur : ID de voyage $id_v  introuvable ou corrompu.");
        } elseif (!empty($utilisateur["voyages"]["achetes"][$id_v])) {
            die("Erreur : L'achat d'un voyage ne devrait pas être possible 
            pour un utilisateur l'ayant déjà payer");
        }
        $prix_total += $opt["prix"];
        $identifiant_v[] = $id_v;
        if ($voyages_achetes[$id_v]["nb_places_restantes"] < $opt_enr[$id_v]["nombre_personnes_totales"]) {
            die("Erreur : Le voyage $id_v n'est plus disponible pour le nombre de personnes souhaité"); //TODO Mieux gérer cela 
        }
    }
} else {
    //Achat d'un seul voyage
    $identifiant_v = recup_id_voyage();
    $opt_enr = optionVoyageConsulte($utilisateur, $identifiant_v);
    if ($opt_enr == null) {
        header("Location: details_voyage.php?id=$identifiant_v");
        exit; //Erreur : Voyage introuvable dans les voyages consultés
    }
    $prix_total = $opt_enr["prix"];
    $opt_enr = [$identifiant_v => $opt_enr];

    //Verification : voyage déjà acheté ?
    $opt_achat = optionVoyageAchete($utilisateur, $identifiant_v);
    if ($opt_achat != null) {
        if (!empty($opt_enr)) {
            die("Le voyage d'id $identifiant_v est déjà présent chez l'utilisateur
            dans catégorie voyages acheté, ne devrait pas être consulté.");
        }

        header("Location: details_voyage.php?id=$identifiant_v");
        exit; //Voyage déjà acheté, ne devrait pas pouvoir être racheter
    }
    $voyage = chargerVoyageParId($identifiant_v);
    if ($voyage == null) {
        die("Erreur : ID de voyage $identifiant_v  introuvable ou corrompu.");
    }
    if ($voyage["nb_places_restantes"] < $opt_enr[$identifiant_v]["nombre_personnes_totales"]) {
        die("Erreur : Le voyage $identifiant_v n'est plus disponible pour le nombre de personnes souhaité"); //TODO Mieux gérer cela 
    }

    $voyages_achetes = [$identifiant_v => $voyage];
    $identifiant_v = [$identifiant_v];
}

?><!DOCTYPE html>
<html lang="fr">

<head>
    <title>Paiement voyage - PixelTravels</title>
    <meta name="auteur" content="Augustin Aveline" />
    <meta name="description" content="page de paiement au site" />
    <link rel="stylesheet" type="text/css" href="../style.css" />
    <link rel="icon" type="image/x-icon" href="../img/logo.png">
    <meta charset="UTF-8">
</head>

<body>
    <?php
    require_once "php-include/header.php";
    require_once "php-include/getapikey.php";
    require_once "../config.php";
    $date = date("Y-m-d");
    $info = json_decode(file_get_contents("../donnees/paiement/transaction_en_cours.json"), true);
    $passage = array("a", "z", "e", "r", "t", "y", "u", "i", "o", "p", "q", "s", "d", "f", "g", "h", "j", "k", "l", "m", "w", "x", "c", "v", "b", "n", "0", "1", "2", "3", "4", "5", "6", "7", "8", "9", "A", "Z", "E", "R", "T", "Y", "U", "I", "O", "P", "Q", "S", "D", "F", "G", "H", "J", "K", "L", "M", "W", "X", "C", "V", "B", "N");
    $nb1 = rand(10, 24);
    $vendeur = VENDEUR;
    $base_url = "http://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']);
    $retour = $base_url . "/php-form/retour_transaction";
    $transaction = $passage[rand(0, 61)];
    for ($i = 0; $i < $nb1 - 1; $i++) {
        $transaction = $passage[rand(0, 61)] . $transaction;
    }
    $api_key = getAPIKey("$vendeur");
    $control = md5($api_key . "#" . $transaction . "#" . $prix_total . "#" . $vendeur . "#" . $retour . "#");

    $cobanc = array("proprietaire" => "bob bob", "dates_expirations" => "2029-01-01", "valeur_controle" => "555", "num_carte" => "5555 1234 5678 9000");    //valeur de controle devrait pas être stocké ici
    $temp = array('ids_voyages' => $identifiant_v, 'options_enregistres' => $opt_enr, "utilisateur" => $utilisateur["email"], "date_transaction" => $date, "prix_total" => $prix_total, "type_achat" => $type_achat);

    if (!empty($info[$transaction])) {
        die("Erreur : L'id de transaction existe déjà.");
    }
    $info[$transaction] = $temp;
    if (!file_put_contents("../donnees/paiement/transaction_en_cours.json", json_encode($info, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE))) {
        die("Erreur : Impossible d'enregistrer le paiement.");
    }
    ?>

    <main>
        <div class="bandeau">
            <h1>
                Page de paiement
            </h1>
        </div>
        <div class="conteneur-image-texte">
            <div class="bloc" class="conteneur-texte">
                <h2>Coordonnée bancaire</h2><br>
                <form class="grille3" action='https://www.plateforme-smc.fr/cybank/index.php' method="post"
                    name="paiement" id="paiement">

                    <label for="num_carte" class="col1">Num Carte : </label>
                    <input class="col2" type="text" name="num_carte" id="num_carte" contenteditable="false"
                        placeholder="Num carte bancaire" maxlength="12"><br />

                    <label for="proprio" class="col1">Nom-Prénom propriétaire carte :</label>
                    <input class="col2" type="text" name="proprio" id="proprio" contenteditable="false"
                        placeholder="nom-prénom propriétaire carte"></br>

                    <label class="col1" for="date">Date d'expiration:</label>
                    <input class="col2" type="date" name="date" id="date" min="<?php echo "$date"; ?>"><br />

                    <label for="val_controle" class="col1">Valeur de contrôle :</label>
                    <input class="col2" type="text" name="valeur_controle" id="val_controle" contenteditable="false"
                        placeholder="valeur de contrôle" maxlength="3">

                    <input type='hidden' name='transaction' value="<?php echo "$transaction" ?>">
                    <input type='hidden' name='montant' value="<?php echo $prix_total ?>">
                    <input type='hidden' name='vendeur' value="<?php echo "$vendeur" ?>">
                    <input type='hidden' name='retour' value="<?php echo "$retour" ?>">
                    <input type='hidden' name='control' value="<?php echo "$control" ?>">

                    <br>
                    <p class="col1 message-erreur" id="msg-erreur"> </p>
                    <label class="col2">
                        <button type="submit" name="boutton" class="submit input-formulaire-2">valider et payer</button>
                    </label>
                </form>

            </div>
            <div>
                <div class="conteneur-texte">

                    <h2>Récapitulatif</h2>
                    <b><?= $prix_total ?> €</b>
                    <ul>
                        <?php
                        foreach ($opt_enr as $id_v => $opt) {
                            echo '<li>
                                    Vous participé à <em>' . $opt["nombre_personnes_totales"] . '</em> personne(s) au voyage
                                    <em>' . $voyages_achetes[$id_v]["titre"] . '</em></br>
                                Le voyage débuteras le <em>' . $voyages_achetes[$id_v]["dates"]["debut"] . '</em> et finiras le
                                <em>' . $voyages_achetes[$id_v]["dates"]["fin"] . '</em>.</br>
                                Pour un montant de <em>' . $opt["prix"] . ' €</em>.</li>';
                        }
                        ?>
                    </ul>

                </div>
            </div>
        </div>
    </main>
    <?php
    require_once "php-include/footer.php";
    ?>

    <script type="text/javascript">
        const form = document.getElementById('paiement');
        const valCarte = document.getElementById('num_carte');
        const valProprio = document.getElementById('proprio');
        const valDate = document.getElementById('date');
        const valControle = document.getElementById('val_controle');
        const elem_msgErreur = document.getElementById('msg-erreur');
        form.addEventListener('submit', function (event) {
            let errors = [];
            if (valCarte.value.trim() === '' || valProprio.value.trim() === '' || valDate.value.trim() === '' || valControle.value.trim() === '') {
                errors.push('Veuillez remplir tout le formulaire.');
            } else if (valCarte.value.trim().length < 12) {
                errors.push('un numéros de carte bancaire contient 12 caractères.');
            } else if (/[a-zA-Z!@#$%^&*]/.test(valCarte.value)) {
                errors.push("contenir que des chiffres.");
            } else if (/[0-9]/.test(valProprio)) {
                errors.push("Numéro ne doit pas contenir de chiffre.");
            } else if (valCarte.value.trim().length < 3) {
                errors.push('un numéros de carte bancaire contient 3 caractères.');
            }
            if (errors.length > 0) {
                event.preventDefault();
                displayErrors(errors);
            }
        });
        function displayErrors(errors) {
            elem_msgErreur.classList.add('message-erreur');
            elem_msgErreur.textContent = errors.join('\n');
        }
    </script>
</body>

</html>
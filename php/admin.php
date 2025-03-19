<!DOCTYPE html>
<html lang="fr">

<?php
session_start();
require_once "php-include/utilisateur.php";

//TODO A supprimer
$_SESSION["utilisateur"] = chargerUtilisateurParEmail("bob@bob.bob");
$_SESSION["utilisateur"]["role"] = "admin";
if (!isset($_SESSION["utilisateur"]) || !utilisateurValide($_SESSION["utilisateur"])) {
    header("Location: connexion.php");
    exit;
} else if ($_SESSION["utilisateur"]["role"] != "admin") {
    header("Location: profil.php");
    exit;
}
?>

<head>
    <meta name="auteur" content="CRISSOT Martin" />
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../style.css">
    <link rel="icon" type="image/x-icon" href="../img/logo.png">
    <meta name="description" content="Modification données client administrateur" />
    <title>Administrateur - PixelTravels</title>
</head>

<body>
    <?php
    require_once "php-include/header.php";
    ?>
    <?php
    $utilisateurs = listerUtilisateurs();


    ?>

    <main>

        <h1 class="bandeau">Page administrateur</h1>

        <div class="texte-centre">

            <b><em>Notez que tout abus sera punis.</em></b>
            <br>Sélectionnez le statut d'un utilisateur parmi :
            <ol>
                <li><b>VIP : </b>le client bénéficie de réduction</li>
                <li><b>Normal : </b>statut par défaut</li>
                <li><b>Banni : </b> le client a enfreint le <a href="#">règlement du site</a></li>
            </ol>
            <br>
            Merci de bien préciser la raison d'un changement de statut.


        </div>
        <div class="contour-bloc">
            <!-- formulaire de recherche -->

            <?php
            $elem_par_page = 10;
            $nb_utilisateurs = count($utilisateurs);
            $nb_page_tot = intdiv($nb_utilisateurs, 10);

            if (!isset($_SESSION["page"])) {
                $_SESSION["page"] = 0; // reset du compteur de page
            }
            if (isset($_POST["next"]) && $_SESSION["page"] < $nb_page_tot) {
                $_SESSION["page"]++;
            }
            if (isset($_POST["prev"]) && $_SESSION["page"] > 0) {
                $_SESSION["page"]--;
            }
            echo "<em> affichage de " . ($_SESSION["page"] * 10 + 1) . " à " . min($nb_utilisateurs, ($_SESSION["page"] + 1) * 10) . " / " . $nb_utilisateurs . " utilisateurs </em>";
            ?>
            <div class="grille3">
                <form action="admin.php" method="post" id="form-precedent">
                    <input class="input-formulaire" type="submit" name="prev" value="< précédent">
                </form>
                <?php
                echo "<p>Page " . ($_SESSION["page"] + 1) . "/" . ($nb_page_tot + 1) . "</p>";
                ?>
                <form action="admin.php" method="post" id="form-suivant">
                    <input class="input-formulaire" type="submit" name="next" value="> suivant">
                </form>
            </div>
            <br>


            <!-- Un formulaire par utilisateur  -->
            <?php
            foreach ($utilisateurs as $utilisateur) {
                echo '<form action="" method="post" id="form-' . $utilisateur["id"] . '"></form>';
            }

            // envoie des formulaires si besoin
            $j = $_SESSION["page"] * 10;
            for ($i = $j; $i < min($j + 10, $nb_utilisateurs); $i++) {
                $utilisateur = $utilisateurs[$i];
                $id = $utilisateur["id"];
                if (isset($_POST["form-$id"])) {

                    $date = date('d/m/Y h:i:s', time());
                    if (isset($utilisateur["modif_admin"][$date])) {
                        $n = 1;
                        while (isset($utilisateur["modif_admin"]["$date ($n)"])) {
                            $n++;
                        }
                        $date = "$date ($n)";
                    }
                    $utilisateur["modif_admin"][$date]["ancien status"] = $utilisateur["role"];
                    $utilisateur["modif_admin"][$date]["nouveau status"] = $_POST["status"];
                    $utilisateur["modif_admin"][$date]["motif"] = $_POST["motif"];
                    $utilisateur["modif_admin"][$date]["auteur"] = $_SESSION["utilisateur"]["email"];

                    $utilisateur["role"] = $_POST["status"];
                    ecrireFichierUtilisateur($utilisateur);

                }
            }

            ?>


            <div class="grille3">
                <label for="recherche" class="col1">
                    <em>Rechercher par ID :</em>
                </label>
                <input class="input-formulaire" form="form-ID" type="number" name="recherche-ID" id="ID"
                    placeholder="ID" min="0">
                <input class="input-formulaire" form="form-ID" type="submit" value="Rechercher">
                <label for="adresse" class="col1">
                    <em>Rechercher par e-mail :</em>
                </label>
                <input class="input-formulaire" form="form-email" type="email" name="recherche-email" id="email"
                    placeholder="e-mail">
                <input class="input-formulaire" form="form-email" type="submit" value="Rechercher">
            </div>
            <br>
            <br>
            <table class="tableau1">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>NOM</th>
                        <th>Prénom</th>
                        <th>E-mail</th>
                        <th>Statut</th>
                        <th>Motif</th>
                        <th>Valider</th>
                    </tr>
                </thead>
                <tbody class="scrollable">
                    <?php
                    $j = $_SESSION["page"] * 10;
                    echo "$j";
                    for ($i = $j; $i < min($j + 10, $nb_utilisateurs); $i++) {

                        $utilisateur = $utilisateurs[$i];

                        echo "<tr>";
                        echo '<td id="' . $utilisateur["id"] . '">' . $utilisateur["id"] . '<input type="hidden" name="id" value="' . $utilisateur["id"] . '"></td>';
                        echo '<td>' . $utilisateur["info"]["nom"] . '</td>';
                        echo '<td>' . $utilisateur["info"]["prenom"] . '</td>';
                        echo '<td id="' . $utilisateur["email"] . '">' . $utilisateur["email"] . '</td>';
                        echo '<td>
                                    <select class="input-formulaire" form="form-' . $utilisateur["id"] . '" name="status">';
                        switch ($utilisateur['role']):
                            case 'admin':
                                echo '<option value="admin" selected>Admin</option>';
                                echo '<option value="normal">Normal</option>';
                                echo '<option value="banni">Banni</option>';
                                break;
                            case 'normal':
                                echo '<option value="admin">Admin</option>';
                                echo '<option value="normal" selected>Normal</option>';
                                echo '<option value="banni">Banni</option>';
                                break;
                            case 'banni':
                                echo '<option value="admin">Admin</option>';
                                echo '<option value="normal">Normal</option>';
                                echo '<option value="banni" selected>Banni</option>';
                                break;
                            default:
                                echo '<option value="admin">Admin</option>';
                                echo '<option value="normal">Normal</option>';
                                echo '<option value="banni">Banni</option>';
                                break;
                        endswitch;
                        echo '</select>
                                </td>';
                        echo '<td>
                                    <input class="input-formulaire" form="form-' . $utilisateur["id"] . '" type="text" name="motif" placeholder="motif">
                                </td>';
                        echo '<td>
                                    <button class="input-formulaire" form="form-' . $utilisateur["id"] . '" type="submit" name="form-' . $utilisateur["id"] . '">Valider</button>
                                </td>';
                        echo "</tr>";
                    }
                    ?>
                </tbody>
                <tfoot></tfoot>
            </table>


        </div>

    </main>

    <?php
    require_once "php-include/footer.php";
    ?>

</body>

</html>
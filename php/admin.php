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
    $nom_validation = "valider-recherche";

    if (isset($_GET[$nom_validation])) {

        if ($_GET["recherche-ID"] != "") {
            $utilisateurs = array_filter($utilisateurs, function ($utilisateur) {
                return $utilisateur["id"] == $_GET["recherche-ID"];
            });
        }
        if ($_GET["recherche-email"] != "") {
            $utilisateurs = array_filter($utilisateurs, function ($utilisateur) {
                return $utilisateur["email"] === $_GET["recherche-email"];
            });
        }
    }
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
            $nb_elem = count($utilisateurs);
            $elem_par_page = 10;
            $form_id = "form-recherche";
            require_once "php-include/compteur_page.php";
            ?>
            <br>


            <!-- Un formulaire par utilisateur  -->
            <?php
            if ($nb_elem > 0) {
                foreach ($utilisateurs as $utilisateur) {
                    echo '<form action="" method="post" id="form-' . $utilisateur["id"] . '"></form>';
                }

                // envoi des formulaires si besoin
                $j = $page_active * $elem_par_page;
                for ($i = $j; $i < min($j + $elem_par_page, $nb_elem); $i++) {
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
            }
            ?>

            <form class="grille3" action="#" method="get" id="form-recherche">
                <label for="recherche" class="col1">
                    <em>Rechercher par ID :</em>
                </label>
                <input class="input-formulaire" type="number" name="recherche-ID" id="ID" placeholder="ID" min="0"
                value="<?php echo isset($_GET["recherche-ID"]) ? $_GET["recherche-ID"] : "" ?>">
                <input class="input-formulaire" type="submit" name=<?php echo $nom_validation; ?>>
                <label for="adresse" class="col1">
                    <em>Rechercher par e-mail :</em>
                </label>
                <input class="input-formulaire" type="email" name="recherche-email" id="email" placeholder="e-mail"
                value="<?php echo isset($_GET["recherche-email"]) ? $_GET["recherche-email"] : "" ?>">
                <input class="input-formulaire" type="submit" name=<?php echo $nom_validation; ?>>
            </form>

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
                    if ($nb_elem > 0) {
                        $j = ($page_active - 1) * $elem_par_page;
                        for ($i = $j; $i < min($j + $elem_par_page, $nb_elem); $i++) {
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
                    }else {
                        echo "<tr><td colspan='7'><em>Aucun utilisateur trouvé</em></td></tr>";
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
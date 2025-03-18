<!DOCTYPE html>
<html lang="fr">

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
    require_once "php-include/utilisateur.php";
    ?>
    <?php
    $utilisateurs = listerUtilisateurs();
    foreach ($utilisateurs as $utilisateur) {
        echo "Nom : " . $utilisateur["info"]["nom"] . "<br>";
        echo "Prénom : " . $utilisateur["info"]["prenom"] . "<br>";
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
            <form action="#666784" method="post" id="form-ID">
            </form>
            <form action="#SNOW" method="post" id="form-email">
            </form>

            <!-- Un formulaire par utilisateur  -->
            <form action="#" method="post" id="form-666784">
            </form>
            <form action="#" method="post" id="form-000000"></form>

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
                    foreach ($utilisateurs as $utilisateur) {
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
                                echo '<option value="client">Client</option>';
                                echo '<option value="banni">Banni</option>';
                                break;
                            case 'client':
                                echo '<option value="admin">Admin</option>';
                                echo '<option value="client" selected>Client</option>';
                                echo '<option value="banni">Banni</option>';
                                break;
                            case 'banni':
                                echo '<option value="admin">Admin</option>';
                                echo '<option value="client">Client</option>';
                                echo '<option value="banni" selected>Banni</option>';
                                break;
                            default:
                                echo '<option value="admin">Admin</option>';
                                echo '<option value="client">Client</option>';
                                echo '<option value="banni">Banni</option>';
                                break;
                        endswitch;
                        echo '</select>
                                </td>';
                        echo '<td>
                                    <input class="input-formulaire" form="form-' . $utilisateur["id"] . '" type="text" name="motif" placeholder="motif">
                                </td>';
                        echo '<td>
                                    <button class="input-formulaire" form="form-' . $utilisateur["id"] . '" type="submit">Valider</button>
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
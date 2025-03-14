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
    ?>
    <main>
        <div class="bandeau">
            <h1 class="bandeau">Page administrateur</h1>
        </div>
        <div class="texte-centre">

            <b><em>Notez que tout abus sera punis.</em></b>
            <br>Selectionnez le statut d'un utilisateur parmi :
            <ol>
                <li><b>VIP : </b>le client béneficie de réduction</li>
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
            <form action="#SNOW" method="post" id="form-nom">
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
                    <em>Rechercher par NOM :</em>
                </label>
                <input class="input-formulaire" form="form-nom" type="text" name="recherche-nom" id="nom"
                    placeholder="NOM" oninput="this.value = this.value.toUpperCase()">
                <input class="input-formulaire" form="form-nom" type="submit" value="Rechercher">
            </div>
            <br>
            <br>
            <table class="tableau1">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>NOM</th>
                        <th>Prénom</th>
                        <th>Statut</th>
                        <th>Motif</th>
                        <th>Valider</th>
                    </tr>
                </thead>
                <tbody class="scrollable">
                    <tr>
                        <td id="666784">666784 <input type="hidden" name="id" value="666784"></td>
                        <td id="VADOR">VADOR</td>
                        <td>Dark</td>
                        <td>
                            <select class="input-formulaire" form="form-666784" name="status">
                                <option value="normal">Normal</option>
                                <option selected value="VIP">VIP</option>
                                <option value="banni">Banni</option>
                            </select>
                        </td>
                        <td>
                            <input class="input-formulaire" form="form-666784" type="text" name="motif"
                                placeholder="motif" value="c'est mon père">
                        </td>
                        <td>
                            <button class="input-formulaire" form="form-666784" type="submit">Valider</button>
                        </td>
                    </tr>
                    <tr>
                        <td id="000000">000000<input type="hidden" name="id" value="000000"></td>
                        <td id="SNOW">SNOW</td>
                        <td>John</td>
                        <td>
                            <select class="input-formulaire" form="form-000000" name="status">
                                <option value="normal">Normal</option>
                                <option value="VIP">VIP</option>
                                <option selected value="banni">Banni</option>
                            </select>
                        </td>
                        <td>
                            <input class="input-formulaire" form="form-000000" type="text" placeholder="motif"
                                name="motif" value="tu ne sais rien john snow">
                        </td>
                        <td>
                            <button class="input-formulaire" form="form-000000" type="submit">Valider</button>
                        </td>
                    </tr>
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
<?php
//TODO utiliser identifiant de session

if (!isset($_SERVER["PHP_SELF"])) {
    die("Des variables serveurs ne sont pas définies.");
}
if (!isset($nb_elem, $elem_par_page)) {
    die("Des variables serveurs ne sont pas définies.");
}
$page_active = basename($_SERVER["PHP_SELF"]);

$nb_page_tot = intdiv($nb_elem, $elem_par_page) + 1;
if (!isset($_SESSION["page"][$page_active])) {
    $_SESSION["page"][$page_active] = 0; // reset du compteur de page
}
if (isset($_POST["next"]) && $_SESSION["page"][$page_active] + 1 < $nb_page_tot) {
    $_SESSION["page"][$page_active]++;
}
if (isset($_POST["prev"]) && $_SESSION["page"][$page_active] > 0) {
    $_SESSION["page"][$page_active]--;
}
echo "<em> affichage de " . ($_SESSION["page"][$page_active] * 10 + 1)
    . " à " . min($nb_elem, ($_SESSION["page"][$page_active] + 1) * 10) . " / " . $nb_elem . " utilisateurs </em>";
?>

<div class="grille3">
    <form action="<?php echo $page_active; ?> " method="post" id="form-precedent">
        <input class="input-formulaire" type="submit" name="prev" value="< précédent">
    </form>
    <?php
    echo "<p>Page " . ($_SESSION["page"][$page_active] + 1) . "/$nb_page_tot</p>";
    ?>
    <form action="<?php echo $page_active; ?>" method="post" id="form-suivant">
        <input class="input-formulaire" type="submit" name="next" value="suivant >">
    </form>
</div>
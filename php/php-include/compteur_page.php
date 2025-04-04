<?php

if (!isset($_SERVER["PHP_SELF"])) {
    die("Des variables serveurs ne sont pas définies.");
}
if (!isset($nb_elem, $elem_par_page, $form_id, $nom_validation)) {
    die("Des variables serveurs ne sont pas définies.");
}

$page_active = $nb_elem > 0 ? 1 : 0;


if (isset($_GET["page"])) {
    $page_active = $_GET["page"];
}

$nb_page_tot = intdiv($nb_elem, $elem_par_page) + ($nb_elem % $elem_par_page > 0 ? 1 : 0);
if ($nb_elem >0) {
echo "<em> affichage de " . min($nb_elem, (($page_active - 1) * $elem_par_page) + 1)
    . " à " . min($nb_elem, $page_active * $elem_par_page) . " / " . $nb_elem . " éléments </em>";
} else {
    echo "<em> Aucun élément à afficher </em>";
}
?>

<div>
    <input form="<?php echo $form_id; ?>" type="hidden" name=<?php echo $nom_validation; ?>>
    <div class="grille3">
        <button form="<?php echo $form_id; ?>" class="input-formulaire" name="page"
            value="<?php echo $page_active > 1 ? ($page_active - 1) : 1 ?>" <?php echo $page_active <= 1 ? "disabled" : "" ?>>
            Précédent < </button>
                <?php
                echo "<p>Page $page_active /$nb_page_tot </p>";
                ?>

                <button form="<?php echo $form_id; ?>" class="input-formulaire" name="page"
                    value="<?php echo $page_active < $nb_page_tot ? ($page_active + 1) : $page_active ?>" 
                    <?php echo $page_active >= $nb_page_tot ? "disabled" : "" ?>>
                    > Suivant
                </button>
    </div>
</div>
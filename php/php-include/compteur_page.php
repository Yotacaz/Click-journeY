<?php

//permet d'appliquer une pagination pour formulaire $_GET (ex : liste de voyage).
// le numéro de page active est stocké dans l'url
//les variables $nb_elem, $elem_par_page, $form_id, $nom_validation 
// doivent être correctement initialisées.

if (!isset($_SERVER["PHP_SELF"])) {
    die("Des variables serveurs ne sont pas définies.");
}
if (!isset($nb_elem, $elem_par_page, $form_id, $nom_validation)) {
    die("Des variables nécessaires au compteur de page ne sont pas définies.");
}

$page_active = $nb_elem > 0 ? 1 : 0;


if (isset($_GET["page"])) {
    $page_active = intval($_GET["page"]);
}

$nb_page_tot = intdiv($nb_elem, $elem_par_page) + ($nb_elem % $elem_par_page > 0 ? 1 : 0);
if ($nb_elem > 0) {
    echo '<em id="compteur-nb-elem"> affichage de ' . min($nb_elem, (($page_active - 1) * $elem_par_page) + 1)
        . " à " . min($nb_elem, $page_active * $elem_par_page) . " / " . $nb_elem . " éléments </em>";
} else {
    echo '<em id="compteur-nb-elem"> Aucun élément à afficher </em>';
}
?>

<div>
    <input form="<?php echo $form_id; ?>" type="hidden" name="<?php echo $nom_validation; ?>">
    <div class="grille3">
        <button id="page-pre" form="<?php echo $form_id; ?>" class="input-formulaire" name="page"
            value="<?php echo $page_active > 1 ? ($page_active - 1) : 1 ?>" <?php echo $page_active <= 1 ? "disabled" : "" ?>>
            Précédent &lt; </button>
        <?php
        echo "<p id=\"compteur-page\">Page $page_active /$nb_page_tot </p>";
        ?>

        <button id="page-sui" form="<?php echo $form_id; ?>" class="input-formulaire" name="page"
            value="<?php echo $page_active < $nb_page_tot ? ($page_active + 1) : $page_active ?>" <?= $page_active >= $nb_page_tot ? "disabled" : "" ?>>
            &gt; Suivant
        </button>
    </div>
</div>
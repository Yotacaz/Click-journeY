export { page_active, maj_nb_elem_total, reinitialiser_compteurs }


/* module permettant une pagination, équivalent de compteur_page.php (code dupliqué) 
mais en dynamique

Pour détecter un changement de page, utiliser :
window.addEventListener("page_change", function () {});
*/

/*  Modèle de code html pour les pages avec compteur de page :

<em id="compteur-nb-elem"> Affichage de  xx / xx éléments</em>
<div>
    <div class="grille3">
        <button form="form-recherche" id="page-pre" class="input-formulaire" type="button" name="page"
            value="xx">
            Précédent < </button>
                
        <p id="compteur-page">Page 1 / xx </p>";
            
        <button form="form-recherche" id="page-sui" class="input-formulaire" type="button" name="page"
            value="xx" >
            > Suivant
        </button>
    </div>
</div>
*/

if (typeof nb_elem === 'undefined' || typeof elem_par_page === 'undefined'
    || typeof form_id === 'undefined' || typeof nom_validation === 'undefined') {
    throw new Error('Des variables nécessaires au compteur de page ne sont pas déclarées.');
}

let page_active = nb_elem > 0 ? 1 : 0;
let nb_page_tot = Math.ceil(nb_elem / elem_par_page);

if (typeof form === 'undefined') {
    var form = document.forms[form_id];
}

if (!form) {
    throw new Error(`Le formulaire avec l'id "${form_id}" n'a pas été trouvé.`);
}

if (typeof donnes_formulaire === 'undefined') {
    var donnes_formulaire = new FormData(form);
    var url = new URL(window.location.href);
    var param_form = new URLSearchParams(donnes_formulaire);
}
if (url.searchParams.has('page')) {
    param_form.set('page', url.searchParams.get('page'));
    page_active = parseInt(url.searchParams.get('page'));
    
    if (page_active < 0 || page_active > nb_page_tot) {
        page_active = nb_elem > 0 ? 1 : 0;
        param_form.delete('page');
        console.warn("Numéro page invalide dans l'url. La page actuelle est réinitialisée.");
    }
}



let btn_pre = document.querySelector('#page-pre');
let btn_suiv = document.querySelector('#page-sui');
btn_pre.disabled = page_active <= 1;
btn_suiv.disabled = page_active >= nb_page_tot;

let page_change = new CustomEvent('page_change', { detail: page_active });

btn_pre.addEventListener('click', (event) => {

    if (page_active > 1) {

        page_active--;
        btn_pre.value = page_active - 1;
        btn_suiv.value = page_active + 1;
        if (page_active <= 1) {
            btn_pre.disabled = true;
        }
        btn_suiv.disabled = page_active >= nb_page_tot;
        maj_compteurs(page_active);

        window.dispatchEvent(page_change);
        param_form.set('page', page_active.toString());
        window.history.replaceState({}, "", url.pathname + "?" + param_form.toString());
    }
});

btn_suiv.addEventListener('click', (event) => {
    if (page_active < nb_page_tot) {
        page_active++;
        btn_suiv.value = page_active + 1;
        btn_pre.value = page_active - 1;
        if (page_active >= nb_page_tot) {
            btn_suiv.disabled = true;
        }
        btn_pre.disabled = page_active <= 1;
        maj_compteurs(page_active);

        window.dispatchEvent(page_change);
        param_form.set('page', page_active.toString());
        window.history.replaceState({}, "", url.pathname + "?" + param_form.toString());
    }
});

let compteur_page = document.querySelector('#compteur-page');
let compteur_nb_elem = document.querySelector('#compteur-nb-elem');
/**
 * Mise à jour des compteurs de page et des éléments affichés
 * @param {int} page_active numéro de page actuelle 
 */
function maj_compteurs(page_active) {
    let texte_compteur = `Page ${page_active} / ${nb_page_tot}`;
    compteur_page.textContent = texte_compteur;
    let texte_compteur_elem = "";
    if (nb_elem > 0) {
        texte_compteur_elem = `Affichage de ${Math.min(nb_elem, (page_active - 1) * elem_par_page + 1)} à ${Math.min(nb_elem, page_active * elem_par_page)} / ${nb_elem} éléments`;
    } else {
        texte_compteur_elem = "Aucun élément à afficher.";
    }
    compteur_nb_elem.textContent = texte_compteur_elem;
}

function maj_nb_elem_total(nv_nb_elem) {
    nb_elem = nv_nb_elem;
    page_active = nb_elem > 0 ? 1 : 0;
    nb_page_tot = Math.ceil(nb_elem / elem_par_page);
    reinitialiser_compteurs();
    return page_active;
}

function reinitialiser_compteurs() {
    page_active = nb_elem > 0 ? 1 : 0;
    btn_pre.disabled = true;
    btn_suiv.disabled = nb_page_tot <= 1;

    btn_pre.value = page_active - 1;
    btn_suiv.value = page_active + 1;

    window.dispatchEvent(page_change);
    param_form.set('page', page_active.toString());
    // window.history.replaceState({}, "", url.pathname + "?" + param_form.toString());
    maj_compteurs(page_active);
    return page_active;
}

maj_compteurs(page_active);

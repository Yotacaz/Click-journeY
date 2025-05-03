import { page_active, maj_nb_elem_total, reinitialiser_compteurs } from "./compteur_page.js";

if (typeof form === "undefined") {
    var form = document.getElementById("form-recherche");
}
if (typeof donnes_formulaire === "undefined") {
    var donnes_formulaire = new FormData(form);
    var url = new URL(window.location.href);
    var param_form = url.searchParams;
    param_form.set(nom_validation, "true");
}

let div_resultats = document.getElementById("resultats");

if (form === null) {
    console.error("Erreur : le formulaire de recherche n'a pas été trouvé.");
}
if (typeof voyages === "undefined") {

    console.error("Erreur : le tableau de voyages n'a pas été trouvé.");
}
if (donnes_formulaire === null) {
    console.error("Erreur : le FormData n'a pas été trouvé.");
}
if (typeof URL_IMG_VOYAGE === "undefined") {
    console.error("Erreur : le dossier d'images de voyage n'est pas renseigné.");

}
if (typeof evenement === undefined) {
    console.error("Erreur : variable d'événements n'a pas été trouvé.");
}



var resultats = voyages.slice();    // Copie du tableau voyages pour le filtrage


if (typeof nb_elem === "undefined") {
    nb_elem = resultats.length;
    console.warn("Aucun nombre d'élements dans le tableau de voyages n'a été renseigné.");
}
if (typeof elem_par_page === "undefined") {
    elem_par_page = nb_elem;
    console.warn("Aucun nombre d'élements par page n'a été renseigné.");
}
if (typeof page_active === "undefined") {
    page_active = param_form.get("page") || 1;
    console.warn("Aucune page active n'a été renseignée.");
}

// if (typeof btn_pre === "undefined") {
//     btn_pre = document.getElementById("page-pre");
//     console.warn("Aucun bouton précédent n'a été trouvé.");
// }

// if (typeof btn_sui === "undefined") {
//     btn_sui = document.getElementById("page-sui");
//     console.warn("Aucun bouton suivant n'a été trouvé.");
// }


window.addEventListener("page_change", function () {
    afficherResultats();
});

/*
exemple de donnes_formulaire :
<entries>
    0: "recherche-textuelle" → ""
    1: genre → "Tout"
    2: theme → "Tout"
    3: tri → "defaut"
    4: prix_min_range → "0"
    5: prix_min_nb → "0"
    6: prix_max_range → "10000"
    7: prix_max_nb → "10000"
    8: date_min → "2025-01-01"
    9: date_max → "2050-12-31"
    10: "lieu[]" → "france"
    11: "lieu[]" → "etats-unis"
    12: "lieu[]" → "japon"
    13: "lieu[]" → "chine"
    14: "lieu[]" → "autre"
    15: "valider-recherche" → ""
*/


var tri = document.getElementById("tri");
tri.addEventListener("change", function () {
    param_form.set("tri", this.value);
    param_form.set('page', page_active.toString());
    trier();
    reinitialiser_compteurs();
    
    afficherResultats();
    modifUrl();
});




// let inputs = form.getElementsByClassName("filtre");
// for (let i = 0; i < inputs.length; i++) {
//     inputs[i].addEventListener("change", function () {
//         if (this.type === "checkbox") {
//             if (this.checked) {
//                 param_form.append(this.name, this.value);
//             } else {
//                 param_form.delete(this.name, this.value);
//             }
//         }
//         else if (this.type === "radio") {
//             if (this.checked) {
//                 param_form.set(this.name, this.value);
//             }
//         } else {
//             param_form.set(this.name, this.value);
//         }
//         filterVoyages();
//         modifUrl();
//         afficherResultats();
//     });
// }

//filtre et tri au chargement de la page
// filterVoyages();
trier();
afficherResultats();

function filterVoyages() {
    resultats = [];
    let dateMin = new Date(param_form.get("date_min"));
    let dateMax = new Date(param_form.get("date_max"));
    let prixMin = parseFloat(param_form.get("prix_min_nb"));
    let prixMax = parseFloat(param_form.get("prix_max_nb"));
    let lieux = param_form.getAll("lieu[]");
    lieux = lieux.map(lieu => lieu.toLowerCase());
    for (let i = 0; i < voyages.length; i++) {
        let voyage = voyages[i];
        let date_voyage = new Date(voyage.dates.debut);
        let pays = voyage.localisation.pays.toLowerCase();
        if (param_form.get("recherche-textuelle") !== "" && !voyage.titre.toLowerCase().includes(param_form.get("recherche-textuelle").toLowerCase())) {
            continue;
        }
        if (param_form.get("genre") !== "Tout" && voyage.genre !== param_form.get("genre")) {
            continue;
        }
        if (param_form.get("theme") !== "Tout" && voyage.theme !== param_form.get("theme")) {
            continue;
        }
        if (date_voyage < dateMin || date_voyage > dateMax) {
            continue;
        }
        if (voyage.prix_total < prixMin || voyage.prix_total > prixMax) {
            continue;
        }
        if (!(lieux.includes(pays) || (lieux.includes("autre") && !(["france", "etats-unis", "japon", "chine"].includes(pays))))) {
            continue;
        }
        resultats.push(voyage);

    }
    nb_elem = resultats.length;
    maj_nb_elem_total();  // maj pour le js compteur de page
    trier();    // on trie résultat apres filtrage (on peut avoir ajouté des éléments)
}

function trier() {
    let tri = param_form.get("tri");

    switch (tri) {
        case "defaut":
            resultats = voyages.slice();
            break;
        case "note":
            resultats.sort((a, b) => { return b.note - a.note; });
            break;
        case "prix-croissant":
            resultats.sort((a, b) => { return a.prix_total - b.prix_total; });
            break;
        case "prix-decroissant":
            resultats.sort((a, b) => { return b.prix_total - a.prix_total; });
            break;
        case "date":    //date croissante
            resultats.sort((a, b) => { return new Date(a.dates.debut) - new Date(b.dates.debut); });
            break;

        default:
            console.error("Erreur dans le tri des voyages : " + this.value);
            break;
    }
}

function formaterTitreVoyage(titre) {
    return titre.toLowerCase().replace(/[^a-z0-9]+/g, '_');
}

function afficherResultats() {

    div_resultats.innerHTML = "";

    if (resultats.length === 0) {
        div_resultats.innerHTML = "<em>Aucun voyage ne correspond à votre recherche.</em>";
    } else if (page_active > 0) {
        let j = (page_active - 1) * elem_par_page;
        for (let i = j; i < Math.min(j + elem_par_page, nb_elem); i++) {
            afficherResumeVoyage(resultats[i]);
        }
    }
}


// Note : afficher de nouveau les voyages permet d'éviter de potentiels bugs
// où le tris ne serais pas correctement appliqué mais dont on ne se
// rendrait pas compte.

function afficherResumeVoyage(voyage) {
    if (voyage == null) {
        console.error("Erreur : tentative d'affichage d'un voyage inexistant.");
        return;
    }
    let index = voyage.id;
    let titre_formate = formaterTitreVoyage(voyage.titre);
    let url_img = URL_IMG_VOYAGE + "/" + titre_formate + "/" + titre_formate + ".png";
    let carte_info = document.createElement("div");

    carte_info.className = "carte-info";
    carte_info.id = "carte-info-" + index;
    carte_info.innerHTML = `
        <img alt="${voyage.titre}" src="${url_img}">
            <div class="contenu-carte-info">
                <div class="flex">
                    <h2> ${voyage.titre} &nbsp</h2>
                    <b> - ${voyage.note}/5 ⭐</b>
                </div>
                <p> ${voyage.description} </p>
                <p> Du  ${voyage.dates.debut} au ${voyage.dates.fin} (${voyage.dates.duree} jours)</p>
                <p> ${voyage.localisation.pays}, ${voyage.localisation.ville} </p>
                <p> ${voyage.etapes.length}  étape(s)</p>
                <p> Prix : ${voyage.prix_total} €</p>
            </div>
            <a href="details_voyage.php?id=${index}">  <span class="lien-span"></span></a>
        </div > `;
    div_resultats.appendChild(carte_info);
}

function modifUrl() {
    window.history.replaceState({}, "", url.pathname + "?" + param_form.toString());
}
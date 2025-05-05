import {
    verifiersInputs,
    estEmail,
    estNom,
    estPrenom,
    estDate,
    estDatePasse,
    est_mdp
} from "./form.js";

var en_modification = false;
var popup_mdp_ouverte = false;
var popup_mdp = document.getElementById("popup");
var contenu_popup = document.getElementById("popup-elem");

var evenement = new Event("input")
/**
 * Liste des champs modifiés
 * @type {string[]}
 */
let champs_modifies = [];



/**
 * Conteneur principal du profil
 * @type {HTMLElement}
 */
let div_profil = document.getElementById("modifiable");
let oeil = document.getElementById("oeil-mdp");


window.onbeforeunload = function (e) {
    if (en_modification) {
        e.preventDefault();
        e.returnValue = '';
    }
};

// Fermer la popup si on clique à l'extérieur
document.addEventListener("click", function (event) {
    if (
        !popup_mdp.hidden &&
        !contenu_popup.contains(event.target) && // si clic en dehors
        popup_mdp_ouverte
    ) {
        fermerPopup();
    }
});


/**
 * Ajoute un champ à la liste des champs modifiés
 * @param {string} id
 */
function ajouterModification(id) {
    if (!champs_modifies.includes(id)) {
        champs_modifies.push(id);
    }
    console.log(en_modification);
    console.log(champs_modifies);

    if (!en_modification) {
        permettreValidation();
        en_modification = true;
    }
}



/**
 * Retire un champ de la liste des champs modifiés
 * @param {string} id
 */
function retirerModification(id) {
    champs_modifies = champs_modifies.filter(function (champ) {
        return champ !== id;
    });
    if (champs_modifies.length === 0) {
        reinitialiserModifications();
    }
}

/**
 * Gère le bouton annuler pour un champ donné
 * @param {string} id
 * @param {boolean} afficher
 */
function gestionBoutonAnnuler(id, afficher) {
    let annuler = document.getElementById("annuler-" + id);
    if (annuler) {
        if (afficher) {
            annuler.removeAttribute("hidden");
        } else {
            annuler.setAttribute("hidden", "hidden");
        }
    }
    else {
        console.error("Bouton annuler introuvable pour l'id : " + id);
    }
}

let id_mdp = "mdp";
let id_confirmation_mdp = "mdp2";

let input_mdp = document.getElementById(id_mdp);
let input_confirmation_mdp = document.getElementById(id_confirmation_mdp);
let label_mdp = document.getElementById("label-" + id_mdp);
let label_confirmation_mdp = document.getElementById("label-" + id_confirmation_mdp);

let oeil_mdp = document.getElementById("oeil-" + id_mdp);
oeil_mdp.setAttribute("hidden", "hidden");
let oeil_confirmation_mdp = document.getElementById("oeil-" + id_confirmation_mdp);
let div_mdp2 = document.getElementById("div-" + id_confirmation_mdp);
let br_col3_mdp2 = document.getElementById("col3-" + id_confirmation_mdp);
/**
 * Met en readonly les champs de mot de passe 
 * cache la confirmation et le reste, sans modifier le
 * contenu des champs
 */
function cacherMdp() {
    oeil_mdp.setAttribute("hidden", "hidden");
    oeil_mdp.setAttribute("src", "../img/imgoeil.png");
    input_mdp.type = "password";
    input_mdp.setAttribute("readonly", "readonly");
    label_confirmation_mdp.setAttribute("hidden", "hidden");
    div_mdp2.setAttribute("hidden", "hidden");
    br_col3_mdp2.setAttribute("hidden", "hidden");
}

/**
 * Affiche les champs de mot de passe en modifiable
 * et les ajoute à la liste des champs modifiés
 */
function afficherMdp() {
    oeil_mdp.removeAttribute("hidden");

    label_mdp.innerHTML = "Nouveau mot de passe";
    input_mdp.removeAttribute("readonly");
    input_mdp.classList.add("js-a-verifier");
    input_mdp.focus();

    label_confirmation_mdp.removeAttribute("hidden");
    input_confirmation_mdp.removeAttribute("readonly");
    input_confirmation_mdp.classList.add("js-a-verifier");
    div_mdp2.removeAttribute("hidden");
    br_col3_mdp2.removeAttribute("hidden");

    if (!champs_modifies.includes(id_mdp)) {
        input_mdp.value = "";
        input_confirmation_mdp.value = "";

        // Maj de la longueur donc maj compteur de longueur (form.js) 
        input_mdp.dispatchEvent(evenement);
        input_confirmation_mdp.dispatchEvent(evenement);
    }

    ajouterModification(id_mdp);
    ajouterModification(id_confirmation_mdp);
    gestionBoutonAnnuler(id_mdp, true);
}

/**
 * Active ou désactive la modification du mot de passe
 */
function modifMDP() {

    if (input_mdp.hasAttribute("readonly")) {
        afficherMdp();
    } else {
        cacherMdp();
    }
}

/**
 * Réinitialise le mot de passe et son champ de confirmation
 */
function reinitialiserMDP() {
    cacherMdp();
    label_mdp.innerHTML = "Mot de passe";
    reinitialiserInput(id_mdp);
    reinitialiserInput(id_confirmation_mdp, false);
}

/**
 * Active ou désactive un champ input
 * @param {string} id
 */
function inputModifiable(id) {
    let input = document.getElementById(id);
    if (input) {
        if (input.readOnly || input.hasAttribute("readonly")) {
            input.removeAttribute("readonly");
            input.focus();
            ajouterModification(id);
            gestionBoutonAnnuler(id, true);
            input.classList.add("js-a-verifier");
            console.log(id + " maintenant modifiable");
        } else {
            input.setAttribute("readonly", "readonly");
            // retirerModification(id);
            gestionBoutonAnnuler(id, true);
            console.log(id + " maintenant non modifiable");
        }
    }
}

let btnRadioAffiche = false;

/**
 * Affiche ou masque les boutons radio et le bouton annuler associé
 * @param {string} idAnnuler
 * @param {string[]} ids liste des ids des boutons radio
 */
function afficherBtnRadio(idAnnuler, ids) {

    let annuler = document.getElementById(idAnnuler);
    if (annuler) {
        annuler.removeAttribute("hidden");
    }

    for (let i = 0; i < ids.length; i++) {
        let id = ids[i];
        let label = document.getElementById("label-" + id);
        let radio = document.getElementById(id);

        if (label && radio) {
            if (!btnRadioAffiche) {
                label.removeAttribute("hidden");
                radio.removeAttribute("hidden");
                ajouterModification(id);
                // radio.classList.add("js-a-verifier");
            } else {
                radio.hidden = true;
                if (!radio.checked) {
                    label.hidden = true;
                }
                // retirerModification(id);
            }
        }
    }

    btnRadioAffiche = !btnRadioAffiche;
}



/**
 * Réinitialise les boutons radio à leur valeur par défaut
 * @param {string} idAnnuler
 * @param {string[]} ids
 */
function reinitialiserRadio(idAnnuler, ids) {
    let annuler = document.getElementById(idAnnuler);
    if (annuler) {
        annuler.setAttribute("hidden", "hidden");
    }
    else {
        console.error("Bouton annuler introuvable pour l'id : " + idAnnuler);
    }

    for (let i = 0; i < ids.length; i++) {
        let id = ids[i];
        let label = document.getElementById("label-" + id);
        let radio = document.getElementById(id);

        if (label && radio) {
            radio.checked = radio.defaultChecked;
            radio.hidden = true;
            // radio.classList.remove("js-a-verifier");
            if (!radio.checked) {
                label.hidden = true;
            }
            else {
                label.removeAttribute("hidden");
            }
            retirerModification(id);
            console.log(id + " maintenant non modifiable");
        }
    }

    btnRadioAffiche = false;
}

/**
 * Réinitialise un champ input à sa valeur d’origine
 * @param {string} id
 * @param {boolean} avec_btn_annuler true si le bouton annuler (reset) doit être désactivé
 */
function reinitialiserInput(id, avec_btn_annuler = true) {
    let input = document.getElementById(id);
    let elem_message_erreur = input.parentNode.parentNode.querySelector(".message-erreur");
    if (input && elem_message_erreur) {
        input.value = input.defaultValue;
        input.setAttribute("readonly", "readonly");
        input.classList.remove("js-a-verifier");
        retirerModification(id);
        input.dispatchEvent(evenement);
        elem_message_erreur.innerHTML = "";
    }
    else {
        console.error("Impossible de réinitialiser le champ " + id);
    }
    if (avec_btn_annuler) {
        gestionBoutonAnnuler(id, false);
    }
}

/**
 * Réinitialise l’ensemble du formulaire
 */
function reinitialiserFormulaire() {
    document.getElementById("form-profil").reset();
    reinitialiserModifications();
}

function preEnvoiFormulaire() {
    let nb_err = verifiersInputs();

    if (popup_mdp && nb_err === 0) {
        popup_mdp.removeAttribute("hidden");
        popup_mdp_ouverte = false;
        // Empêche la fermeture immédiate en dehors de la popup
        setTimeout(() => {
            popup_mdp_ouverte = true;
        }, 100);
    }
}

/**
 * Réinitialise les états de modification au moment de l’envoi
 */
function envoyerFormulaire() {
    let elem_mdp_actuel = document.getElementById("mdp-actuel");
    elem_mdp_actuel.classList.add("js-a-verifier");
    let i = verifiersInputs();
    if (i > 0) {
        return;
    }

    en_modification = false;
    champs_modifies = [];

    document.getElementById("form-profil").submit();
}

/**
 * Active l’état de modification et affiche les boutons de validation/annulation
 */
function permettreValidation() {
    div_profil.style.borderColor = "#f39639";
    let div_modif = document.getElementById("valider");

    if (!document.getElementById("valider-preenvoi")) {
        let valider = document.createElement("button");
        valider.setAttribute("class", "input-formulaire-2");
        valider.setAttribute("id", "valider-preenvoi");
        valider.setAttribute("name", "valider-preenvoi");
        valider.innerHTML = "Sauvegarder";
        valider.onclick = preEnvoiFormulaire;
        valider.type = "button";
        div_modif.appendChild(valider);
    }

    if (!document.getElementById("reinitialiser-modif")) {
        let reinitialiser = document.createElement("button");
        reinitialiser.setAttribute("id", "reinitialiser-modif");
        reinitialiser.setAttribute("class", "input-formulaire-2");
        reinitialiser.innerHTML = "Annuler";
        reinitialiser.onclick = reinitialiserFormulaire;
        reinitialiser.type = "reset";
        div_modif.appendChild(reinitialiser);
    }
}

/**
 * Désactive tous les champs modifiables et cache les boutons d’action
 */
function reinitialiserModifications() {
    div_profil.style.borderColor = "#43aed4";
    if (champs_modifies.includes(id_mdp)) {
        reinitialiserMDP();
    }
    if (champs_modifies.includes("genreF")){
        reinitialiserRadio('annuler-genre', ['genreA','genreH','genreF'])
    }
    
    let inputs = document.getElementsByClassName("js-a-verifier");
    //il ne reste que les inputs avec contenu modifiable
    for (let i = 0; i < champs_modifies.length; i++) {
        reinitialiserInput(champs_modifies[i]);
    }
    en_modification = false;
    btnRadioAffiche = false;
    champs_modifies = [];

    let div_modif = document.getElementById("valider");
    let valider = document.getElementById("valider-preenvoi");
    let reinitialiser = document.getElementById("reinitialiser-modif");

    if (valider) div_modif.removeChild(valider);
    if (reinitialiser) div_modif.removeChild(reinitialiser);
    en_modification = false;
    let annuler = document.getElementsByClassName("btn-annuler");
    for (let i = 0; i < annuler.length; i++) {
        annuler[i].setAttribute("hidden", "hidden");
    }
}

/**
 * Ferme la popup de confirmation de mot de passe
 */
function fermerPopup() {
    let elem_mdp_actuel = document.getElementById("mdp-actuel");
    elem_mdp_actuel.classList.remove("js-a-verifier");
    popup_mdp.setAttribute("hidden", "hidden");
    popup_mdp_ouverte = false;
}


window.inputModifiable = inputModifiable;
window.afficherBtnRadio = afficherBtnRadio;
window.modifMDP = modifMDP;
window.reinitialiserMDP = reinitialiserMDP;
window.reinitialiserRadio = reinitialiserRadio;
window.reinitialiserInput = reinitialiserInput;
window.reinitialiserFormulaire = reinitialiserFormulaire;
window.preEnvoiFormulaire = preEnvoiFormulaire;
window.envoyerFormulaire = envoyerFormulaire;

window.fermerPopup = fermerPopup;
window.desactiverModification = reinitialiserModifications;

window.champs_modifies = champs_modifies;
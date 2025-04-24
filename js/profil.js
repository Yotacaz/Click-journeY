import {
    verifiersInputs,
    estEmail,
    estNom,
    estPrenom,
    est_genre,
    estDate,
    estDatePasse,
    est_mdp
} from "./form.js";

var en_modification = false;
var popup_mdp_ouverte = false;
var popup_mdp = document.getElementById("popup");
var contenu_popup = document.getElementById("popup-elem");

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
        desactiverModification();
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
    if (!en_modification) {
        permettreValidation();
        en_modification = true;
    }

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
                console.log(id + " maintenant modifiable");
            } else {
                radio.hidden = true;
                if (!radio.checked) {
                    label.hidden = true;
                }
                // retirerModification(id);
                console.log(id + " maintenant non modifiable");
            }
        }
    }

    btnRadioAffiche = !btnRadioAffiche;
}

/**
 * Active ou désactive la modification du mot de passe
 */
function modifMDP() {
    let id = "mdp";
    let id_confirmation = "mdp2";

    let input = document.getElementById(id);
    let label = document.getElementById("label-" + id);
    if (input) {
        if (input.readOnly || input.hasAttribute("readonly")) {
            input.removeAttribute("readonly");
            input.value = "";
            input.focus();
            input.classList.add("js-a-verifier");
            ajouterModification(id);
            gestionBoutonAnnuler(id, true);
            console.log(id + " maintenant modifiable");
            label.innerHTML = "Nouveau mot de passe";
        } else {
            input.setAttribute("readonly", "readonly");
            // retirerModification(id);
            gestionBoutonAnnuler(id, true);
            console.log(id + " maintenant non modifiable");
        }
    }

    let input2 = document.getElementById(id_confirmation);
    let label2 = document.getElementById("label-" + id_confirmation);
    let br_col3 = document.getElementById("col3-" + id_confirmation);
    let div_mdp2 = document.getElementById("div-" + id_confirmation);

    if (input2 && label2 && br_col3 && div_mdp2) {

        if (input2.readOnly || input2.hasAttribute("readonly")) {
            input2.removeAttribute("readonly");
            input2.removeAttribute("hidden");
            input2.value = "";
            label2.removeAttribute("hidden");
            br_col3.removeAttribute("hidden");
            div_mdp2.removeAttribute("hidden");
            input2.classList.add("js-a-verifier");
            ajouterModification(id_confirmation);
            console.log(id_confirmation + " maintenant modifiable");
        } else {
            input2.setAttribute("readonly", "readonly");
            input2.setAttribute("hidden", "hidden");
            label2.setAttribute("hidden", "hidden");
            br_col3.setAttribute("hidden", "hidden");
            div_mdp2.setAttribute("hidden", "hidden");
            // retirerModification(id_confirmation);
            console.log(id_confirmation + " maintenant non modifiable");
        }
    }
}

/**
 * Réinitialise le mot de passe et son champ de confirmation
 */
function reinitialiserMDP() {
    let id = "mdp";
    reinitialiserInput(id);

    let id_confirmation = "mdp2";
    let input2 = document.getElementById(id_confirmation);
    let label2 = document.getElementById("label-" + id_confirmation);
    let br_col3 = document.getElementById("col3-" + id_confirmation);
    let div_mdp2 = document.getElementById("div-" + id_confirmation);
    let label_mdp = document.getElementById("label-" + id);
    label_mdp.innerHTML = "Mot de passe";

    if (!(input2.readOnly || input2.hasAttribute("readonly"))) {
        input2.classList.remove("js-a-verifier");
        input2.setAttribute("readonly", "readonly");
        input2.setAttribute("hidden", "hidden");
        input2.value = "";
        label2.setAttribute("hidden", "hidden");
        br_col3.setAttribute("hidden", "hidden");
        div_mdp2.setAttribute("hidden", "hidden");
        retirerModification(id_confirmation);
        console.log(id_confirmation + " maintenant non modifiable");
    }
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
 */
function reinitialiserInput(id) {
    let input = document.getElementById(id);
    if (input) {
        input.value = input.defaultValue;
        input.setAttribute("readonly", "readonly");
        input.classList.remove("js-a-verifier");
        retirerModification(id);
        console.log(id + " maintenant non modifiable");
    }
    gestionBoutonAnnuler(id, false);
}

/**
 * Réinitialise l’ensemble du formulaire
 */
function reinitialiserFormulaire() {
    document.getElementById("form-profil").reset();
    desactiverModification();
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
function desactiverModification() {
    en_modification = false;
    champs_modifies = [];
    div_profil.style.borderColor = "#43aed4";
    btnRadioAffiche = false;

    let inputs = document.getElementsByTagName("input");
    for (let i = 0; i < inputs.length; i++) {
        let input = inputs[i];
        if (input.type === "radio") {
            if (!input.checked) {
                input.parentNode.setAttribute("hidden", "hidden");
            } else {
                input.setAttribute("hidden", "hidden");
            }
        }
        if (input.classList.contains("desactivable")) {
            input.setAttribute("readonly", "readonly");
        }
    }

    let div_modif = document.getElementById("valider");
    let valider = document.getElementById("valider-preenvoi");
    let reinitialiser = document.getElementById("reinitialiser-modif");

    if (valider) div_modif.removeChild(valider);
    if (reinitialiser) div_modif.removeChild(reinitialiser);

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
window.desactiverModification = desactiverModification;


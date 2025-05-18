export {
    verifiersInputs, estEmail, estNom, estPrenom, estDate,
    estDatePasse, est_mdp, fixerValeursParDefaut
};

//note : les regex ont été soit trouvés sur stackoverflow soit 
// générés par IA puis testés sur le site regex101.com
// pour utiliser ce script directement dans du html (/php), doit être importé comme module
/*Pour utiliser la fonction verifiersInputs, il faut que le fichier HTML résultant
 contienne :
    - un formulaire avec la classe js-form
    - chaque champ à vérifier doit avoir la classe js-a-verifier, et une classe
    parmi js-email, js-nom, js-prenom, js-date, js-date-passe, js-mdp 
    (pour indiquer le type de verification à effectuer) 
    - Le parent de chaque champ à vérifier doit avoir pour frère direct 
    un élément avec la classe message-erreur
    - si le champ à verifier possède un attribut maxlength, il faut que son frère direct
    ait la classe compteur (pour afficher le nombre de caractères restants)
    - Voir exemple pour proposition de mise en forme (ou profil.php)

    exemple : 
    <form class="js-form">
        <label for="email">Email :</label>
        <div class="enveloppe-input">
            <div class="fill-col">
                <input type="email" id="email" class="js-a-verifier js-email" placeholder="Email" maxlength="50" required>
                <span class="compteur">0/50</span>
            </div>
            <p class="message-erreur"></p>
        </div>
        ...
        <button type="submit">Envoyer</button>
    </form>

    n'hésitez pas à ajouter des classes à verifier dans la fonction verifiersInputs
*/
var forms = document.getElementsByClassName("js-form");

function verifiersInputs() {
    let nb_err = 0;
    const inputs = document.querySelectorAll(".js-a-verifier");
    for (let i = 0; i < inputs.length; i++) {
        let input = inputs[i];

        const ancienneErreur = input.parentElement.nextElementSibling;
        if (!ancienneErreur || !ancienneErreur.classList.contains("message-erreur")) {
            throw new Error("L'élément suivant d'un élement avec la classe js-a-verifier n'est pas un message d'erreur.");
        }

        let erreur = "";

        if (input.classList.contains("js-email")) {
            erreur = estEmail(input.value);
        } else if (input.classList.contains("js-nom")) {
            erreur = estNom(input.value);
        } else if (input.classList.contains("js-prenom")) {
            erreur = estPrenom(input.value);
        } else if (input.classList.contains("js-date")) {
            erreur = estDate(input.value);
        } else if (input.classList.contains("js-date-passe")) {
            erreur = estDatePasse(input.value);
        } else if (input.classList.contains("js-mdp")) {
            erreur = est_mdp(input.value);
        }

        if (erreur) {
            ancienneErreur.innerHTML = erreur;
            nb_err++;
        }
    }
    return nb_err;
}


/**
 * Vérifie si le mot de passe est valide selon les critères spécifiés: 
 * - Doit contenir entre 6 et 16 caractères
 * - Doit contenir au moins un chiffre
 * - Doit contenir au moins un caractère spécial (!@#$%^&*)
 * - Doit contenir au moins une lettre
 * @param {string} motDePasse - Le mot de passe à valider.
 * @returns {string} - Message d'erreur ou chaîne vide si valide.
 */
function est_mdp(motDePasse) {
    let erreurs = [];

    if (motDePasse.length < 6 || motDePasse.length > 16) {
        erreurs.push("contenir entre 6 et 16 caractères.");
    }

    if (!/[0-9]/.test(motDePasse)) {
        erreurs.push("contenir au moins un chiffre.");
    }

    if (!/[!@#$%^&*]/.test(motDePasse)) {
        erreurs.push("contenir au moins un caractère spécial (!@#$%^&*).");
    }

    if (!/[a-zA-Z]/.test(motDePasse)) {
        erreurs.push("contenir au moins une lettre.");
    }
    let msg_err = "Le mot de passe doit ";
    if (erreurs.length === 0) {
        return "";
    }
    else if (erreurs.length === 1) {
        return msg_err + erreurs[0];
    }

    msg_err += ":<ul>";
    for (let i = 0; i < erreurs.length; i++) {
        msg_err += "<li>" + erreurs[i] + "</li>";
    }
    return "<div>" + msg_err + "</ul></div>";
}


const MIN_STRING_LENGTH = 2;
const MAX_STRING_LENGTH = 50;

/**
 * Vérifie si une date est valide au format YYYY-MM-DD.
 * @param {string} date - La date à valider.
 * @returns {string} Message d'erreur ou chaîne vide si la date est valide.
 */
function estDate(date) {
    const regex = /^\d{4}-\d{2}-\d{2}$/;
    if (!regex.test(date)) {
        return "Format de date invalide (attendu: YYYY-MM-DD)";
    }

    const d = new Date(date);   //code trouvé sur stackoverflow
    if (!(d instanceof Date) || isNaN(d) || d.toISOString().slice(0, 10) !== date) {
        return "Date invalide ou inexistante";
    }

    return "";
}

/**
 * Vérifie si la date est une date valide passée (au format YYYY-MM-DD).
 * @param {string} date - La date à valider.
 * @returns {string} Message d'erreur ou chaîne vide si la date est valide et passée.
 */
function estDatePasse(date) {
    const message = estDate(date);
    if (message) return message;

    const aujourdHui = new Date().toISOString().slice(0, 10);
    if (date >= aujourdHui) {
        return "La date doit être antérieure à aujourd'hui.";
    }

    return "";
}

/**
 * Vérifie si une adresse e-mail est valide.
 * @param {string} email - L'adresse email à valider.
 * @returns {string} Message d'erreur ou chaîne vide si l'email est valide.
 */
function estEmail(email) {
    const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!regex.test(email)) {
        return "Adresse e-mail invalide";
    }
    if (email.length > MAX_STRING_LENGTH) {
        return `L'adresse e-mail ne doit pas dépasser ${MAX_STRING_LENGTH} caractères`;
    }
    return "";
}

/**
 * Vérifie si un nom est valide :
 * - lettres, espaces, tirets
 * - longueur adéquate
 * @param {string} nom - Le nom à valider.
 * @returns {string} Message d'erreur ou chaîne vide si le nom est valide.
 */
function estNom(nom) {
    const regex = /^[\p{L}\s\-]+$/u;
    if (!regex.test(nom)) {
        return "Le nom ne doit contenir que des lettres, espaces ou tirets";
    }
    if (nom.length < MIN_STRING_LENGTH) {
        return `Le nom doit contenir au moins ${MIN_STRING_LENGTH} caractères`;
    }
    if (nom.length > MAX_STRING_LENGTH) {
        return `Le nom ne doit pas dépasser ${MAX_STRING_LENGTH} caractères`;
    }
    return "";
}

/**
 * Vérifie si un prénom est valide (mêmes règles que pour un nom).
 * @param {string} prenom - Le prénom à valider.
 * @returns {string} Message d'erreur ou chaîne vide si le prénom est valide.
 */
function estPrenom(prenom) {
    return estNom(prenom);
}

/**
 * Fonction permettant de fixer les valeurs par défaut pour les champs d'un formulaire.
 * Utile si on souhaite utiliser un form.reset() avec de nouvelles valeurs
 * @param {object} form le formulaire dont on souhaite fixer les valeurs par défaut
 */
function fixerValeursParDefaut(form) {
    const elements = form.elements;
    for (let elem of elements) {
        if (elem.tagName === "INPUT" || elem.tagName === "TEXTAREA") {
            if (elem.type === "checkbox" || elem.type === "radio") {
                elem.defaultChecked = elem.checked;
            } else {
                elem.defaultValue = elem.value;
            }
        } else if (elem.tagName === "SELECT") {
            for (let option of elem.options) {
                option.defaultSelected = option.selected;
            }
        }
    }
}


document.addEventListener("DOMContentLoaded", function () {
    for (let i = 0; i < forms.length; i++) {
        forms[i].addEventListener("submit", function (e) {

            let i = verifiersInputs();
            // Si erreurs : empeche envoi formulaire
            if (i > 0) {
                e.preventDefault();
            }
        });
    }
});


let input_long_max = document.querySelectorAll('[maxlength]');
for (let i = 0; i < input_long_max.length; i++) {
    input_long_max[i].addEventListener("input", function () {
        let max = this.getAttribute("maxlength");
        let compteur = this.parentElement.querySelector(".compteur");
        if (compteur) {
            compteur.innerHTML = this.value.length + "/" + max;
        }
        else {
            console.error("Element de classe compteur non trouvé")
        }
        if (this.value.length > max) {
            this.value = this.value.slice(0, max);
        }
    });
}




//fonction oeuil mdp
// Fonction pour ajouter l'icône et gérer le clic
function ajouterIconeOeil(Id) {
    var inputMDP = document.getElementById(Id);

    if (inputMDP) {
        var Iconoeil = document.createElement('img'); // Change 'i' to 'img'
        Iconoeil.setAttribute('src', '../img/imgoeil.svg');
        Iconoeil.id = 'oeil-' + Id;
        Iconoeil.style.maxHeight = "1em";
        inputMDP.parentNode.style.position = 'relative';
        inputMDP.parentNode.insertBefore(Iconoeil, inputMDP.nextSibling);

        Iconoeil.addEventListener('click', function () {
            if (inputMDP.type === 'password') {
                inputMDP.type = 'text';
                Iconoeil.setAttribute('src', '../img/imgoeilbarre.png'); // Change pour oeil barré
            } else {
                inputMDP.type = 'password';
                Iconoeil.setAttribute('src', '../img/imgoeil.png'); // Reviens sur l'oeil icon original
            }
        });
    }
}

// Appliquer la fonction aux champs de mot de passe et de confirmation
ajouterIconeOeil('mdp');
ajouterIconeOeil('mdp2');
ajouterIconeOeil('mdp-actuel');

window.verifiersInputs = verifiersInputs;
window.est_mdp = est_mdp;
window.estDate = estDate;
window.estDatePasse = estDatePasse;
window.estEmail = estEmail;
window.estNom = estNom;
window.estPrenom = estPrenom;
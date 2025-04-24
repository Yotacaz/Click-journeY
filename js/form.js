//note : les regex ont été soit trouvés sur stackoverflow soit 
// générés par IA puis testés sur le site regex101.com

export function verifiersInputs() {
    let nb_err=0;
    const inputs = document.querySelectorAll(".js-a-verifier");
    for (let i = 0; i < inputs.length; i++) {
        let input = inputs[i];
        const ancienneErreur = input.nextElementSibling;
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
        } else if (input.classList.contains("js-genre")) {
            erreur = est_genre(input.value);
            // if (input.hasAttribute("selected")){
            // }
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
export function est_mdp(motDePasse) {
    let erreurs = [];

    if (motDePasse.length < 6 || motDePasse.length > 16) {
        erreurs.push("Le mot de passe doit contenir entre 6 et 16 caractères.");
    }

    if (!/[0-9]/.test(motDePasse)) {
        erreurs.push("Le mot de passe doit contenir au moins un chiffre.");
    }

    if (!/[!@#$%^&*]/.test(motDePasse)) {
        erreurs.push("Le mot de passe doit contenir au moins un caractère spécial (!@#$%^&*).");
    }

    if (!/[a-zA-Z]/.test(motDePasse)) {
        erreurs.push("Le mot de passe doit contenir au moins une lettre.");
    }

    return erreurs.join("\n");
}


const MIN_STRING_LENGTH = 2;
const MAX_STRING_LENGTH = 50;

/**
 * Vérifie si une date est valide au format YYYY-MM-DD.
 * @param {string} date - La date à valider.
 * @returns {string} Message d'erreur ou chaîne vide si la date est valide.
 */
export function estDate(date) {
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
export function estDatePasse(date) {
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
export function estEmail(email) {
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
export function estNom(nom) {
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
export function estPrenom(prenom) {
    return estNom(prenom);
}


export let genres = ["femme", "homme", "autre"];
/**
 * Vérifie si un genre est valide (femme, homme, autre).
 * @param {string} genre - Le genre à valider.
 * @returns {string} Message d'erreur ou chaîne vide si le genre est valide.
 */
export function est_genre(genre) {

    if (genres.includes(genre.toLowerCase())) {
        return "";
    }
    else {
        return "Genre invalide. Choisissez parmi : " + genres.join(", ");
    }
}
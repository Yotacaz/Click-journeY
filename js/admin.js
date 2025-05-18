async function reponse(formData){
    try {
        const response = await fetch("../php/php-form/Fetch_admin.php", {
            method: "POST",
            body: formData
        });
        const contenu = await response.text();

        if (response.ok) {
            console.log("Réponse du serveur :", contenu);
            return true;
        } else {
            console.error("La requête n'a pas abouti : " + response.status + " " + response.statusText);
            document.getElementById("msg_err").textContent = contenu;
            document.getElementById("msg_err").hidden = false;
            setTimeout(() => {
                document.getElementById("msg_err").hidden = true;
            }, 10000);
            return false;
        }
    } catch (e) {
        console.error("Erreur avec Fetch", e);
        return false;
    }
}

let btn_modif = document.getElementsByClassName("modif-utilisateur");

for (let i = 0; i < btn_modif.length; i++) {
    btn_modif[i].addEventListener("click", async function (e) {
        e.preventDefault();

        let id_form = this.getAttribute("form");
        let form = document.forms[id_form];

        // Bouton passe en mode chargement
        this.style.backgroundColor = "gray";
        this.style.color = "white";
        this.innerHTML = "En cours...";
        this.setAttribute("disabled", "disabled");
        document.body.style.cursor = "wait";

        const id = id_form.substr(-1, 1);
        const gif = document.getElementById("gif-chargement-" + id);
        if (gif) gif.hidden = false;

        const formData = new FormData(form);
        const success = await reponse(formData);

        // Une fois terminé, on remet l'état d'origine
        this.style.backgroundColor = ""; 
        this.style.color = "";
        this.innerHTML = success ? "Modifié" : "Erreur";
        this.removeAttribute("disabled");
        document.body.style.cursor = "default";
        if (gif) gif.hidden = true;
        document.getElementById("motif-" + id).value = "";

        // Revenir au texte initial après un court délai
        setTimeout(() => {
            this.innerHTML = "Valider";
        }, 2000);
    });
}

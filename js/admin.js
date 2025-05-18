async function reponse(formData){
    try{
        const response = await fetch("../php/php-form/Fetch_admin.php", {
            method: "POST",
            body: formData
        });
        if(response.ok){
            const contenue = await response.text();
            console.log("Réponse du serveur :", contenue);
        }
        else{
            console.error("La requête n'a pas abouti : " + response.status + " " + response.statusText);
        }
    }
    catch(e){
        console.error("Erreur avec Fetch", e);
    }
}

let btn_modif = document.getElementsByClassName("modif-utilisateur");

for (let i = 0; i < btn_modif.length; i++) {
    btn_modif[i].addEventListener("click", function (e) {
        e.preventDefault();
        let id_form = this.getAttribute("form");
        let form = document.forms[id_form];

        this.style.backgroundColor = "gray";
        this.style.color = "white";
        this.innerHTML = "En cours...";
        this.setAttribute("disabled", "disabled");
        document.body.style.cursor = "wait";
        document.getElementById("gif-chargement-"+ (i+1)).hidden = false;

        // Attente simulée
        setTimeout(() => {
            const formData = new FormData(form);
            reponse(formData);
        }, 2000);
    });
}

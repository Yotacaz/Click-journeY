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

        // Attente simulée
        setTimeout(() => {
            let formData = new FormData(form);

            fetch("../php/php-form/Fetch_admin.php", {
                method: "POST",
                body: formData
            })
            .then(response => response.text())
            .then(data => {
                console.log("Réponse du serveur :", data);
                this.innerHTML = "Modifié";
                this.style.backgroundColor = "green";
                document.body.style.cursor = "default";
            })
            .catch(error => {
                console.error("Erreur :", error);
                this.innerHTML = "Erreur";
                this.style.backgroundColor = "red";
                document.body.style.cursor = "default";
            });
        }, 2000);
    });
}

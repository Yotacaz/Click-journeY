
let btn_modif = document.getElementsByClassName("modif-utilisateur");
for (let i = 0; i < btn_modif.length; i++) {
    btn_modif[i].addEventListener("click", function () {
        let id_form = this.getAttribute("form");
        this.style.backgroundColor = "gray";
        this.style.color = "white";
        this.innerHTML = "En cours...";
        this.setAttribute("disabled", "disabled");
        document.body.style.cursor = "wait";
        setTimeout(() => {
            document.forms[id_form].submit();
        }, 2000);

    });
}

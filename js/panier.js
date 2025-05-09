if (typeof taille_panier === "undefined") {
    taille_panier = 0;
    console.warn("Aucune taille du panier n'a été renseignée.");
}
if (typeof taille_consultes === "undefined") {
    taille_consultes = 0;
    console.warn("Aucune taille des consultés n'a été renseignée.");
}
if (typeof prix_total === "undefined") {
    prix_total = 0;
    console.warn("Aucun prix total n'a été renseigné.");
}


let elem_liste_panier = document.getElementById("liste-panier");
let elem_liste_consultes = document.getElementById("liste-consultes");
let elem_prix_total = document.getElementById("prix-total");
let elem_nb_panier = document.getElementById("nb-panier");
let elem_nb_consultes = document.getElementById("nb-consultes");
let btn_acheter_panier = document.getElementById("acheter-panier");


let btn_modifier = document.getElementsByClassName("modifier_voyage");
for (let i = 0; i < btn_modifier.length; i++) {
    btn_modifier[i].addEventListener("click", async function () {
        this.disabled = true;
        let info_voyage = this.parentNode;
        const id_voyage = parseInt(info_voyage.dataset.id);

        if (this.dataset.type == "supprimer") {
            const prix = await supprimerVoyage(id_voyage);
            if (taille_consultes == 0) {
                elem_liste_consultes.innerHTML = "";
            }
            taille_consultes++;
            taille_panier--;

            elem_liste_consultes.appendChild(info_voyage);
            if (taille_panier == 0) {
                elem_liste_panier.innerHTML = "<li><em>Vous n'avez pas de voyages dans votre panier</em></li>";
                btn_acheter_panier.setAttribute("hidden", "hidden");
            }
            prix_total -= prix;
            this.dataset.type = "ajouter";
            this.textContent = "Ajouter au panier";
        }
        else if (this.dataset.type == "ajouter") {
            const prix = await ajouterVoyage(id_voyage);
            if (taille_panier == 0) {
                elem_liste_panier.innerHTML = "";
                btn_acheter_panier.removeAttribute("hidden");
            }
            taille_panier++;
            taille_consultes--;


            elem_liste_panier.appendChild(info_voyage);
            if (taille_consultes == 0) {
                elem_liste_consultes.innerHTML = "<li><em>Vous n'avez pas de voyages consultés (hors panier).</em></li>";
            }
            prix_total += prix;
            this.dataset.type = "supprimer";
            this.textContent = "Supprimer du panier";
        }
        else {
            console.error("Type de modification inconnu : ", this.dataset.id);
        }
        elem_prix_total.textContent = prix_total.toFixed(2);
        elem_nb_consultes.textContent = taille_consultes;
        elem_nb_panier.textContent = taille_panier;
        this.disabled = false;
    });
}

async function ajouterVoyage(id_voyage) {
    if (typeof id_voyage !== 'number' || isNaN(id_voyage)) {
        console.error("id_voyage invalide : ", id_voyage);
        return;
    }
    console.log("Ajout du voyage " + id_voyage + " au panier...  (Recalcule des prix...)");
    try {
        let requete = await fetch("php-form/panier_ajout.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/x-www-form-urlencoded",
            },
            body: new URLSearchParams({
                "id_voyage": id_voyage,
                "pas_de_redirection": "true"
            })
        });
        if (!requete.ok) {
            throw new Error("Erreur lors de l'ajout au panier : " + requete.status + " " + requete.statusText);
        }
        let prix = await requete.text();
        return await parseFloat(prix);
    } catch (error) {
        console.error("Erreur lors de la mise à jour du panier : ", error);
    }
}

async function supprimerVoyage(id_voyage) {
    if (typeof id_voyage !== 'number' || isNaN(id_voyage)) {
        console.error("id_voyage invalide : ", id_voyage);
        return;
    }
    console.log("Suppression du voyage " + id_voyage + " dans le panier...  (Recalcule des prix...)");

    try {
        let requete = await fetch("php-form/panier_suppression.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/x-www-form-urlencoded",
            },
            body: new URLSearchParams({
                "id_voyage": id_voyage,
                "pas_de_redirection": "true"
            })
        });
        if (!requete.ok) {
            throw new Error("Erreur lors de la suppression du panier : " + requete.status + " " + requete.statusText);
        }
        let prix = await requete.text();
        return await parseFloat(prix);
    } catch (error) {
        console.error("Erreur lors de la mise à jour du panier : ", error);
    }
}


const inputs = document.querySelectorAll(".nombre-personne-activite");
const total = document.querySelector("[data-total]");
const input_taille_groupe = document.getElementById("nombre_personnes_totales");

let taille_groupe = 0;


function updatePrix() {
  let sommes = 0;
  let prixTotal = 0;

  inputs.forEach((input) => {
    const prix = parseFloat(input.dataset.prix);
    const valeur = parseInt(input.value) || 0;


    // Vérifier les valeurs de prix et valeur

    if (isNaN(prix)) {
      console.warn("Prix invalide pour l'input avec data-prix:", input.dataset.prix);
    }
    if (isNaN(valeur)) {
      console.warn("Valeur invalide pour l'input avec value:", input.value);
    }
    sommes += prix * valeur; // caluler le total

  });


  // verification des valeur

  if (total && total.dataset.total) {
    const totalSansOption = parseFloat(total.dataset.total);
    if (!isNaN(totalSansOption)) {
      prixTotal = sommes + totalSansOption * taille_groupe; // ajouter le total initial sans les options
    } else {
      console.warn("Le prix total sans option n'est pas un nombre valide.");
    }
  }


  // afficher le prix total dynamique
  console.log("Prix total:" + prixTotal + " €");
  document.getElementById("prix_dynam").textContent = "Prix Total: " + prixTotal + " €";
  document.getElementById("prix_dynam_2").textContent = prixTotal + " €";
}

function maj_taille_groupe(nv_taille_groupe) {
  taille_groupe = parseInt(nv_taille_groupe);
  for (let i = 0; i < inputs.length; i++) {
    inputs[i].setAttribute("max", taille_groupe);
    if (inputs[i].value > taille_groupe) {
      inputs[i].value = taille_groupe;
      inputs[i].style = "border: 3px solid #f39639; border-radius: 3px; ";
      inputs[i].title = "Dépassement de la taille du groupe";
    }
  }

}

// si changement d'input alors mettre a jour le prix

inputs.forEach((input) => {
  input.addEventListener("input", function () {
    input.style = "";
    input.title = "";
    updatePrix();
  });
});

input_taille_groupe.addEventListener("change", function () {
  maj_taille_groupe(this.value);
  updatePrix();
});

maj_taille_groupe(input_taille_groupe.value);
updatePrix();





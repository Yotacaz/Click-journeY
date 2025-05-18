const inputs = document.querySelectorAll(".nombre-personne-activite");
const input_taille_groupe = document.getElementById("nombre_personnes_totales");
const selects = document.querySelectorAll("select");

let taille_groupe = 0;


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


// si changement de select alors mettre a jour le prix

selects.forEach((select) => {
  select.addEventListener("change", function () {
    updatePrix();
  });
});

input_taille_groupe.addEventListener("change", function () {
  maj_taille_groupe(this.value);
  updatePrix();
});

maj_taille_groupe(input_taille_groupe.value);
updatePrix();


// fonction maj du prix --------------------------------
function updatePrix() {

  //recuper les donnees du formulaire -----------------
  const form = document.querySelector("form");
  const formData = new FormData(form);

  //changer formData en un objet pour pouvoir les envoyer en requete -------
  let data = {};
  formData.forEach((elem, indice) => {
    data[indice] = elem;
  });
  data['id'] = voyage.id;


  //requete asyncrone ----------
  fetch("../php/php-form/calcul.php", {  // Envoie vers un fichier PHP
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify(data) //on convertit en json ------------------------
  })
    .then(response => response.json())  // On attend une réponse JSON
    .then(data => {//console.log(data)
      let prixTotal = data;



      // afficher le prix total dynamique-------------------------
      console.log("Prix total:" + prixTotal + " €");
      document.getElementById("prix_dynam").textContent = "Prix Total: " + prixTotal + " €";
      document.getElementById("prix_dynam_2").textContent = prixTotal + " €";
    });
}


const inputs = document.querySelectorAll("input[name^='nombre_personnes_']:not([name='nombre_personnes_totales'])");
  const total = document.querySelector("[data-total]");

  function updatePrix() {
    let sommes = 0;
    let prixTotal =0;

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
      prixTotal = sommes + totalSansOption; // ajouter le total initial sans les options
    } else {
      console.warn("Le prix total sans option n'est pas un nombre valide.");
    }
  }


// afficher le prix total dynamique
    console.log("Prix total:"+ prixTotal + " €");
    document.getElementById("prix_dynam").textContent = "Prix Total: " + prixTotal + " €";


 	}

// si changement d'input alors mettre a jour le prix

  inputs.forEach((input) => {
    input.addEventListener("input", updatePrix);
  });

  updatePrix();





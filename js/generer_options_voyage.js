//
if (typeof voyage === 'undefined') console.error("Aucun voyage n'a été fourni.");
if (typeof modifiable === 'undefined') {
	modifiable = true;
	console.warn("Pas de modifiabilité renseignée, on considère qu'elle est activée par défaut.");
}
if (typeof opt_enr === 'undefined' || opt_enr == null) {
	opt_enr = {};
	// console.warn("Pas de options d'enregistrement renseignées, on considère qu'aucune option est disponible.");
}

/**
 * Génère et injecte le formulaire de modification pour un voyage donné.
 * @param {HTMLElement} root - Le conteneur où injecter le formulaire
 * @param {Object} voyage - L'objet voyage (JS)
 * @param {boolean} modifiable - Indique si le voyage est modifiable
 */
function genererOptionsVoyages(root, voyage, modifiable = true) {
	// Bannière et titre
	const bandeauImage = document.createElement('div');
	bandeauImage.className = 'bandeau-image';
	bandeauImage.innerHTML = `
    <img src='../img/banieres/${voyage.image[0]}' alt='Bannière'>
    <h1 class='centre' style="color:#261C57">${voyage.titre} - Détails du voyage</h1>`;
	root.appendChild(bandeauImage);

	// Message d'erreur (si présent dans l'URL)
	const urlParams = new URLSearchParams(window.location.search);
	if (urlParams.has('erreur')) {
		const codes = {
			'places_insuffisantes': `Nombre de personnes spécifié supérieur à ${voyage.nb_places_restantes}`
		};
		const msg = codes[urlParams.get('erreur')];
		if (msg) {
			const divErr = document.createElement('div');
			divErr.className = 'erreur';
			divErr.innerHTML = `<h1>Erreur : ${msg}</h1>`;
			root.appendChild(divErr);
		}
	}

	// Informations générales
	const infoBloc = document.createElement('div');
	infoBloc.className = 'bandeau';
	infoBloc.innerHTML = `
    <div>
      <h3>Description</h3>
      <em>${voyage.description}</em>
      <ul>
        <li><b>Note : ${voyage.note}/5⭐</b></li>
        <li>🎮 ${voyage.genre}, ${voyage.theme}</li>
        <li>📅 Du <b>${voyage.dates.debut}</b> au <b>${voyage.dates.fin}</b> (${voyage.dates.duree} jours)</li>
        <li>🌍 ${voyage.localisation.ville} - ${voyage.localisation.pays}</li>
        <li title="prix sans les options pour une personne"><b>Prix unitaire</b> : ${voyage.prix_total} €</li>
        <li title="prix avec les options et tous les membres du groupe"><b>Prix total</b> : <span id='prix_dynam_2'>X€</span></li>
        <li>${voyage.nb_places_restantes} / ${voyage.nb_places_tot} places restantes</li>
      </ul>
    </div>`;
	root.appendChild(infoBloc);

	const titre_page = document.createElement('div');
	titre_page.className = 'bandeau';
	const message_modif_impossible = modifiable ? '' : '(modification impossible : &nbsp <b>voyage acheté</b>)'
	titre_page.innerHTML = `<h2>Réservation du voyage &nbsp</h2>
  ${message_modif_impossible}`;
	root.appendChild(titre_page);

	// Création du formulaire
	const form = document.createElement('form');
	form.method = 'post';
	if (modifiable) {
		form.action = `modif_voyage.php?id=${voyage.id}`;
	}

	// Nombre de personnes totales
	const bloc_nb_groupe = document.createElement('div');
	bloc_nb_groupe.className = 'contour-bloc';
	const totalPers = opt_enr.nombre_personnes_totales || 1;
	bloc_nb_groupe.innerHTML = `
    <label title="taille de votre groupe">
      <b>Nombre de personnes participant au voyage</b>
      <em>(max : ${voyage.nb_places_restantes})</em><br>
      <input type='number' 
             name='nombre_personnes_totales' 
             id='nombre_personnes_totales' 
             value='${totalPers}' 
             min='1' 
             max='${voyage.nb_places_restantes}' 
             ${modifiable ? '' : 'disabled'}>
    </label>`;

	const bloc_titre_etapes = document.createElement('div');
	bloc_titre_etapes.className = 'texte-centre';
	bloc_titre_etapes.innerHTML = `<h2>Étapes du voyage</h2>`;
	form.appendChild(bloc_titre_etapes);


	form.appendChild(bloc_nb_groupe);

	// Étapes et options
	voyage.etapes.forEach((etape, idx) => {
		const bloc = document.createElement('div');
		bloc.classList = 'contour-bloc';
		const bloc_texte = document.createElement('div');
		bloc_texte.classList = "texte-gauche repliable";
		bloc_texte.innerHTML = `
      <p>📅 Du <b>${etape.dates.debut}</b> au <b>${etape.dates.fin}</b> (${etape.dates.duree} jours)</p>
      <p><b>Options :</b></p>
      <ul class='liste-options'></ul>`;
		const ul = bloc_texte.querySelector('.liste-options');

		etape.options.forEach((opt, jdx) => {
			const li = document.createElement('li');
			const nom_opt = `option_${idx}_${jdx}`;
			const nomNb = `nombre_personnes_${idx}_${jdx}`;
			const valNb = opt_enr[nomNb] || 1;

			// Construction du select si valeurs possibles
			let selectHTML = "";
			if (Object.keys(opt.valeurs_possibles).length > 0) {
				selectHTML = `<select class="input-formulaire" id="${nom_opt}" name='${nom_opt}' ${modifiable ? '' : 'disabled'}>`;
				for (const [nom_valeur, prix] of Object.entries(opt.valeurs_possibles)) {
					selectHTML += `<option value='${nom_valeur}' ${opt_enr[nom_opt] === nom_valeur ? ' selected' : ''}>${nom_valeur} (${prix} €)</option>`;
				}
				selectHTML += `</select>`;
			}

			li.innerHTML = `
			<label for="${nom_opt}" class="flex">
				${opt.nom} &nbsp ${selectHTML}
			</label>
			
			<label title="si personne ne souhaite participer entrez 0" 
				for="${nomNb}">Nombre de personnes participant :
				<input type='number' 
					class='nombre-personne-activite' 
					name='${nomNb}' 
					id='${nomNb}' 
					value='${valNb}' 
					min='0' 
					max='${totalPers}' 
					${modifiable ? '' : ' disabled'}> 
			</label>
			<br>`;
			ul.appendChild(li);
		});
		if (etape.options.length == 0) {
			ul.innerHTML = "<li><em>Aucune étape trouvée.</em></li>";
		}
		const titre_bloc = document.createElement("h3");
		const numero = idx + 1;
		titre_bloc.className = "js-replier"
		titre_bloc.innerHTML = `${numero} - ${etape.nom}`;
		bloc.appendChild(titre_bloc);
		bloc.appendChild(bloc_texte);
		form.appendChild(bloc);
	});
	const bloc_prix_dynam = document.createElement("div");
	bloc_prix_dynam.className = "texte-centre";
	bloc_prix_dynam.innerHTML =
		`<h2 id="prix_dynam" 
		data-total=${voyage.prix_total}></h2>`;	//Temporaire, changé par un autre script
	form.appendChild(bloc_prix_dynam);
	// Bouton de soumission (si modifiable)
	if (modifiable) {
		const divBtn = document.createElement('div');
		divBtn.className = 'texte-centre';
		divBtn.innerHTML = `<button type='submit' name='submit_voyage' class='input-formulaire grand'>Réservez Maintenant !</button>`;
		form.appendChild(divBtn);
	}

	root.appendChild(form);

}

const main = document.querySelector('main');
genererOptionsVoyages(main, voyage, modifiable);
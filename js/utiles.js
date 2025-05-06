//Pour les fonctions utiles / js pour esthétique


/*contenu repliable
- l'endroit ou cliquer pour ouvrir/fermer le contenu doit avoir la classe js-replier
- contenu à replier doit être à la suite et avoir la classe repliable
*/
/*Exemple d'utilisation : 
<div> 
    <h2 class="js-replier">Titre de la section</h2>
    <div class="repliable">
        <p>Contenu à cacher</p>
        <div>Autre contenu</div>
    </div>
<div>
*/
document.querySelectorAll('.js-replier').forEach(titre => {
    titre.innerHTML = `<span>&#9207;</span> ${titre.innerHTML}`;
    titre.addEventListener('click', function () {
        const contenu = this.nextElementSibling;
        const span = this.querySelector('span');
        if (contenu && span && contenu.classList.contains('repliable')) {
            if (contenu.style.display === 'none') {
                contenu.style.display = '';
                span.innerHTML = '&#9207;';
            }
            else {
                span.innerHTML = '&#9205;';
                contenu.style.display = 'none';
            }
        }
        else {
            console.warn("contenu non trouvé ou non au format attendu (classe repliable)");
        }
    });
});
document.addEventListener('DOMContentLoaded', function() {
  // Fonction pour ajouter l'icône et gérer le clic
  function ajouterIconeOeil(Id) {
    var inputMDP = document.getElementById(Id);

    if (inputMDP) {
      var Iconoeil = document.createElement('img'); // Change 'i' to 'img'
      Iconoeil.setAttribute('src', '../img/imgoeil.svg');
      Iconoeil.style.cssText = 'cursor: pointer; position: absolute; margin-left: -31px; margin-top: 16px;margin-right:9px';

      inputMDP.parentNode.style.position = 'relative';
      inputMDP.parentNode.insertBefore(Iconoeil, inputMDP.nextSibling);

      Iconoeil.addEventListener('click', function() {
        if (inputMDP.type === 'password') {
          inputMDP.type = 'text';
          Iconoeil.setAttribute('src', '../img/imgoeilbarre.svg'); // Change pour oeil barré
        } else {
          inputMDP.type = 'password';
          Iconoeil.setAttribute('src', '../img/imgoeil.svg'); // Reviens sur l'oeil icon original
        }
      });
    }
  }

  // Appliquer la fonction aux champs de mot de passe et de confirmation
  ajouterIconeOeil('mdp');
});

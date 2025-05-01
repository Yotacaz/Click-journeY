document.addEventListener('DOMContentLoaded', function() {
  // Fonction pour ajouter l'icône et gérer le clic
  function ajouterIconeOeil(inputId) {
    var passwordInput = document.getElementById(inputId);

    if (passwordInput) {
      var eyeIcon = document.createElement('img'); // Change 'i' to 'img'
      eyeIcon.setAttribute('src', '/wp-content/uploads/2024/03/eye.svg'); // Set the src attribute for the SVG
      eyeIcon.style.cssText = 'cursor: pointer; position: absolute; margin-left: -31px; margin-top: 16px;margin-right:9px';

      passwordInput.parentNode.style.position = 'relative';
      passwordInput.parentNode.insertBefore(eyeIcon, passwordInput.nextSibling);

      eyeIcon.addEventListener('click', function() {
        if (passwordInput.type === 'password') {
          passwordInput.type = 'text';
          eyeIcon.setAttribute('src', '/wp-content/uploads/2024/03/hidden.svg'); // Change to the "eye-slash" icon
        } else {
          passwordInput.type = 'password';
          eyeIcon.setAttribute('src', '/wp-content/uploads/2024/03/eye.svg'); // Revert back to the original "eye" icon
        }
      });
    }
  }

  // Appliquer la fonction aux champs de mot de passe et de confirmation
  ajouterIconeOeil('password');
  ajouterIconeOeil('password_copy');
});

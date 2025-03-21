<?php
//TODO deconnexion plus proprement
session_start();
if (isset($_SESSION["utilisateur"])) {
    session_abort();
    unset($_SESSION["utilisateur"]);
    header("Location: accueil.php");
    exit;
}
else {
    session_abort();
    
    header("Location: connexion.php");
    exit;
}
?>
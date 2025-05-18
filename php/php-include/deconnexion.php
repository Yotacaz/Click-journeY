<?php

//déconnecte et renvoie vers la page d'accueil

//Important : aucune donnée importante rien n'est modifiée sur la session
//la fermer comme cela ne devrait donc donc pas poser de problème
session_start();

session_unset();


if (isset($_COOKIE['id_session'])) {
    setcookie('id_session', '', time() - 3600, '/');
}
header("Location: ../accueil.php");
exit;
?>
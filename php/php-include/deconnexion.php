<?php
session_start();

session_unset();


if (isset($_COOKIE['id_session'])) {
    setcookie('id_session', '', time() - 3600, '/');
}
header("Location: ../accueil.php");
exit;
?>
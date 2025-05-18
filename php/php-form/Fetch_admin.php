<?php

    require_once "../php-include/utilisateur.php";
    require_once "../php-include/utiles.php";

    $msg_err="";
    // validation des formulaires si besoin
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $email = isset($_POST['email']) ? test_input($_POST['email']) : "";
        echo "ffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffff $email";
        $utilisateur = chargerUtilisateurParEmail($email);
        if (!utilisateurValide($utilisateur)) {
            die("Erreur : Utilisateur non valide");
        }
        if (!isset($_POST["status"]) || empty($_POST["motif"])) {
            $msg_err = "Merci de remplir tous les champs.";
        } else {

            if ($utilisateur["role"] == test_input($_POST["status"])) {
                $msg_err = "Merci de choisir un statut différent.";
            }

            $date = date('d/m/Y h:i:s', time());
            if (isset($utilisateur["modif_admin"][$date])) {
                $n = 1;
                while (isset($utilisateur["modif_admin"]["$date ($n)"])) {
                    $n++;
                }
                $date = "$date ($n)";
            }
            $utilisateur["modif_admin"][$date]["ancien status"] = $utilisateur["role"];
            $utilisateur["modif_admin"][$date]["nouveau status"] = test_input($_POST["status"]);
            $utilisateur["modif_admin"][$date]["motif"] = test_input($_POST["motif"]);
            $utilisateur["modif_admin"][$date]["auteur"] = $admin["email"];

            $utilisateur["role"] = test_input($_POST["status"]);
            ecrireFichierUtilisateur($utilisateur);

        }

    }
    if (!empty($msg_err)) {
        echo "<div class=\"erreur\"> ⚠️ $msg_err</div>";
    }
?>



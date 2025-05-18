<?php

    require_once "../php-include/utilisateur.php";
    require_once "../php-include/utiles.php";
    // validation des formulaires si besoin
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $email = isset($_POST['email']) ? test_input($_POST['email']) : "";
        $utilisateur = chargerUtilisateurParEmail($email);
        if (!utilisateurValide($utilisateur)) {
            http_response_code(400);
            die("Erreur : Utilisateur non valide");
        }
        if (!isset($_POST["status"]) || empty($_POST["motif"])) {
            http_response_code(400);
            die("Merci de remplir tous les champs.");
        } else {
            if ($utilisateur["role"] == test_input($_POST["status"])) {
                http_response_code(400);
                die("Merci de choisir un statut différent.");
            }

            $date = date('d/m/Y h:i:s', time());
            if (isset($utilisateur["modif_admin"][$date])) {
                $n = 1;
                while (isset($utilisateur["modif_admin"]["$date ($n)"])) {
                    $n++;
                }
                $date = "$date ($n)";
            }
            $utilisateur["modif_admin"][$date]["ancien_status"] = $utilisateur["role"];
            $utilisateur["modif_admin"][$date]["nouveau_status"] = test_input($_POST["status"]);
            $utilisateur["modif_admin"][$date]["motif"] = test_input($_POST["motif"]);
            $utilisateur["modif_admin"][$date]["auteur"] = $admin["email"];

            $utilisateur["role"] = test_input($_POST["status"]);
            ecrireFichierUtilisateur($utilisateur);

        }

    }
    http_response_code(200);
    exit;
?>

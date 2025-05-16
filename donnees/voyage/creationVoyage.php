<?php
$voyage =
    array(
        "id" => 0,
        "titre" => "GTA-V",
        "note" => 5,
        "description" => "plongez vous dans le monde de GTA-V, en parcourant Los Angeles, Miami et plus",
        "genre" => "action",
        "theme" => "city",
        "mot_cle" => array(
            "Los Angeles",
            "Miami",
            "GTA-V",
            "action",
            "city"
        ),
        "localisation" => array(
            "pays" => "Etats-Unis",
            "ville" => "Miami"
        ),
        "dates" => array(
            "debut" => "2025-06-16",
            "fin" => "2025-06-26",
            "duree" => 10
        ),

        "etapes" => array(
            array(
                "nom" => "Los Angeles",
                "dates" => array(
                    "debut" => "2025-06-16",
                    "fin" => "2025-06-24",
                    "duree" => 8
                ),
                "guides" => array(
                    array(
                        "nom" => "Diop",
                        "prenom" => "Bineta"
                    )
                ),
                "options" => array(
                    array(
                        "nom" => "art urbain",
                        "valeurs_possibles" => array(
                            "peinture" => 5,
                            "graffiti" => 5
                        )
                    ),
                    array(
                        "nom" => "location voiture de luxe",
                        "valeurs_possibles" => array(
                            "Ferrari" => 125,
                            "Lamborghini" => 100,
                            "Maserati" => 150
                        )
                    ),

                )
            ),
            array(
                "nom" => "Miami",
                "dates" => array(
                    "debut" => "2025-06-24",
                    "fin" => "2025-06-26",
                    "duree" => 2
                ),
                "guides" => array(
                    array(
                        "nom" => "Turner",
                        "prenom" => "Taissa"
                    )
                ),
                "position_gps" => "",
                "options" => array(
                    array(
                        "nom" => "spectacle Hip-Hop",
                        "valeurs_possibles" => array(
                            "place assise" => 15,
                            "fosse" => 15
                        )
                    ),

                )
            ),
        ),

        "email_personnes_inscrites" => [
            "personne1@example.com" => 5   //ex personne inscrite avec 5 amis
        ],
        "prix_total" => 5000,
        "nb_places_tot" => 30,
        "nb_places_restantes" => 30,
        "image" => array(
            "image001.png",
            "image101.png",
            "image201.png",
            "image301.png"
        )
    );

/*
encoder le php en json dans une chaine
puis mettre la chaine dans un fichier
*/
$fichier = 'voyages.json';
$liste_voyages = json_decode($fichier, true);
array_push($liste_voyages, $voyage);
$donnees_json = json_encode($liste_voyages, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
file_put_contents($fichier, $donnees_json);

?>
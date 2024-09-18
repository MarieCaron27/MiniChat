<?php

    session_start();
    session_unset(); //Permet de retirer le pseudo de l'utilisateur de la variable $_SESSION
    session_destroy();
    header("location: ../Vues/index.php?error=deconnexionok");
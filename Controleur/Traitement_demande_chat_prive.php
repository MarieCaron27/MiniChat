<?php
    session_start();
    include '../Modele/fonctions.php';

    $mesPseudos=AffichagePseudos();

    if(isset($_POST['requeteCP']))
    {
        $erreur = [
            'pseudo' => ''
        ];

        $pseudo=Securite($_POST['pseudo']);

        if(empty($pseudo))
        {
            $erreur['pseudo']='<p>Le pseudo est un champ obligatoire...</p>';
        }
        else
        {
            $pseudoExistant=Pseudoexistant($pseudo);

            if($pseudoExistant===false)
            {
                $erreur['pseudo']='<p>Le pseudo que vous avez entré n\'existe pas...</p>';
            }
            else
            {
                if($pseudo===$_SESSION['pseudo'])
                {
                    $erreur['pseudo']='<p>Vous ne pouvez pas intéragir avec vous-même...</p>';
                }
            }

        }

        if(!array_filter($erreur))
        {
            header("location: ../Controleur/Traitement_chat_prive.php?PK=$pseudo");
        }
    }
    

    include '../Vues/vue_demande_chat_prive.php';
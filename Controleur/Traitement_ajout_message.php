<?php
    session_start(); 
    include '../Modele/fonctions.php';

    $offset = isset($_GET['offset']) ? intval($_GET['offset']) : 0;
    $messages = AffichageMessages($offset);
    
    if(isset($_POST['envoimessage']))
    {
        date_default_timezone_set('Europe/Paris');         
        $message=Securite($_POST['message']);
        $pseudo=Securite($_SESSION['pseudo']);
        $temps=date("Y-m-d H:i:s");

        $erreur = [            
            'message' => '',
            'pseudo' => '',
        ];

        if(empty($message))
        {
            $erreur['message']='<p>Le message est un champ obligatoire...</p>';
        }

        if(empty($pseudo))
        {
            $erreur['pseudo']='<p>Votre pseudo est vide...
            <br>Vérifiez que votre connexion a bien réussie!</p>';
        }

        if(!array_filter($erreur))
        {
            $ajoutermsg=AjoutMessage($pseudo,$message,$temps);

            if($ajoutermsg===false)
            {
                $erreur['message']='<p>Nous n\'avons pas réussi à ajouter votre message...</p>';
            }
            else
            {
                $offset = isset($_GET['offset']) ? intval($_GET['offset']) : 0;
                $messages=AffichageMessages($offset);
            }
        }
    }

    include '../Vues/vue_chat.php';


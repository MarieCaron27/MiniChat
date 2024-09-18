<?php
    session_start(); 
    include '../Modele/fonctions.php';

    $erreur = [            
        'message' => '',
        'emetteur' => '',
        'recepteur' => ''
    ];

    $recepteur=Securite($_GET['PK']);

    if(!isset($_GET['PK']))
    {
        $erreur['recepteur']='<p>Aucun recepteur...</p>';
    }
    else
    {
        $offset = isset($_GET['offset']) ? intval($_GET['offset']) : 0;
        $Privatemessages = AffichagePrivateMessages($_SESSION['pseudo'],$recepteur,$offset);

        if(isset($_POST['envoiPrivatemsg']))
        {
            date_default_timezone_set('Europe/Paris');         
            $message=Securite($_POST['message']);
            $pseudo=Securite($_SESSION['pseudo']);
            $recepteur=Securite($_GET['PK']);
            $temps=date("Y-m-d H:i:s");

            if(empty($message))
            {
                $erreur['message']='<p>Le message est un champ obligatoire...</p>';
            }

            if(empty($pseudo))
            {
                $erreur['emetteur']='<p>Votre pseudo est vide...
                <br>Vérifiez que votre connexion a bien réussie!</p>';
            }
            
            if(empty($recepteur))
            {
                $erreur['recepteur']='<p>Vous intéragissez tout seul...</p>';
            }

            if(!array_filter($erreur))
            {
                $ajoutermsg=AjoutPrivateMessage($pseudo,$message,$temps,$recepteur);

                if($ajoutermsg===false)
                {
                    $erreur['message']='<p>Nous n\'avons pas réussi à ajouter votre message...</p>';
                }
                else
                {
                    $offset = isset($_GET['offset']) ? intval($_GET['offset']) : 0;
                    $Privatemessages=AffichagePrivateMessages($pseudo,$recepteur,$offset);
                }
            }
        }
    }

    include '../Vues/vue_chat_prive.php';
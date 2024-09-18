<?php
    include '../Modele/fonctions.php';

    if(isset($_POST['seconnecter']))
    {
        $pseudo=Securite($_POST['pseudo']);
        $motdepasse=Securite($_POST['password']);

        $erreur = [            
            'password' => '',
            'pseudo' => '',
            'requete' => ''
        ];
            
        if(empty($pseudo))
        {
            $erreur['pseudo']='<p>Le pseudo est un champ obligatoire...</p>';
        }

        if(empty($motdepasse))
        {
            $erreur['password']='<p>Le mot de passe est un champ obligatoire...</p>';
        }
    
        if(!array_filter($erreur))
        {
            /* Fonction liée à la connexion d'un utilisateur : */

            $connexion = ConnexionBDD();
            $requete="SELECT * FROM utilisateur WHERE pseudo=?;";
            $statement=mysqli_stmt_init($connexion);
        
            if(!mysqli_stmt_prepare($statement,$requete))
            {
                $erreur['requete'] ='<p>Nous n\'arrivons pas à communiquer avec le système...</p>';
            }
            else
            {
                mysqli_stmt_bind_param($statement,"s",$pseudo);
                mysqli_stmt_execute($statement);
                $results=mysqli_stmt_get_result($statement);
                $tableau=mysqli_fetch_assoc($results);
                
                if($tableau)
                {
                    $pwdCheck=password_verify($motdepasse,$tableau['mdp']);
        
                    if($pwdCheck==false)
                    {
                        $erreur['password'] ='<p>Le mot de passe que vous avez entré est incorrecte.</p>';
                    }
                    elseif($pwdCheck==true)
                    { 
                        session_start(); 
                        
                        $_SESSION['pseudo']=$tableau['pseudo'];
                        header("location: ../Controleur/Traitement_ajout_message.php?error=bienvenu");
                    }
                    else
                    {
                        $erreur['password'] ='<p>Le mot de passe que vous avez entré est incorrecte.</p>';
                    }
                }
                else
                {
                    $erreur['pseudo']='<p>Le pseudo que vous avez entré est incorrecte.</p>';
                }
            }
        }
    }
    
    include '../Vues/vue_pour_se_connecter.php';
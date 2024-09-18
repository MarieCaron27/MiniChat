<?php

    /* Fonction liée à la connexion à la base de données : */ 

    function ConnexionBDD() 
    {
        $connexion = mysqli_connect('localhost', 'root', '', 'minichat');
        
        if(!$connexion)
        {
            die("Nous n'arrivons pas à vous connecter à la base de donéees :" .mysqli_connect_error());
            exit();
        }
        else
        {
            return $connexion;
        }
    }

    /* Fonction qui permet de supprimer les caractères inacceptés : Pas ok => htmlentities */

    function Securite($donnees1)
    {
        $donnees1=trim($donnees1); //Permet de supprimer des caractères inutiles (espaces, retours à la ligne, etc.)
        $donnees1=stripcslashes($donnees1); //Permet de supprimer des caractères inutiles (guillemets, slaches, etc.)
        $donnees1=strip_tags($donnees1); //Permet d'éviter le traitement et l'affichage des caracrtères HTML

        return ($donnees1);
    }

    /* Fonctions liées à l'ajout d'un utilisateur : */

    function Invalidpseudo($pseudo)
    {
        if(!preg_match("/^[[:print:]]{8,15}$/",$pseudo))
        {
            $resultat=false;
        }
        else
        {
            $resultat=true;
        }

        return ($resultat);
    }

    function Invalidmdp($motdepasse)
    {
        if(!preg_match("/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[&é~\"#'{(-|è`_ç^à@)°+=¤}¨£^$*µù%,?;.:!§<>€]).{8,15}$/",$motdepasse))
        {
            $resultat=false;
        }
        else
        {
            $resultat=true;
        }

        return ($resultat);
    }

    function PasswordMatch($motdepasse,$motdepasseconfirme) 
    {
        if($motdepasse !== $motdepasseconfirme)
        {
            $resultat=false;
        }
        else
        {
            $resultat=true;
        }

        return ($resultat);
    }
        
    function Pseudoexistant($pseudo) 
    {
        $connexion = ConnexionBDD(); 
        $requete = "SELECT * FROM utilisateur WHERE pseudo = ?;"; /*Il faut mettre des données comme ça parce
        ça permet de protéger les données et que si une requête sql est dedans, pas executée*/
        $statement = mysqli_stmt_init($connexion);

        if(!mysqli_stmt_prepare($statement,$requete))
        { 
            $requete=false;
            return($requete);
        }

        mysqli_stmt_bind_param($statement,"s", $pseudo);
        mysqli_stmt_execute($statement);
        $resultatDonnees=mysqli_stmt_get_result($statement);

        if($row = mysqli_fetch_assoc($resultatDonnees))
        {
            return($row);
        }
        else
        {
            $resultat=false;
            return ($resultat);
        }

        mysqli_stmt_close($statement);
    }

    function AffichagePseudos()
    {
        $connexion=ConnexionBDD();

        $requete="SELECT pseudo 
                  FROM utilisateur;";

        $resultat=mysqli_query($connexion,$requete);

        $mesPseudos = array();
        if($resultat && mysqli_num_rows($resultat) > 0)
        {
            while($line = mysqli_fetch_assoc($resultat)) 
            {
                $mesPseudos[] = $line;
            }
        }

        return($mesPseudos);
        mysqli_close($connexion);
    }

    function VerificationExtension($file,$fileName,$fileTmpName,$fileSize,$fileError,$fileType)
    {
        $fileExt=explode('.',$fileName);
        $fileActualExt=strtolower(end($fileExt));
        $ExtAllowed=array('jpg','jpeg','png');

        if(in_array($fileActualExt,$ExtAllowed))
        {
            if($fileError===0)
            {
                return($fileActualExt);
            }
            else
            {
                $fileActualExt=false;
                return($fileActualExt);
            }
        }
        else
        {
            $fileActualExt=false;
            return($fileActualExt);
        }
    }

    function VerificationDimensions($file,$fileName,$fileTmpName,$fileSize,$fileError,$fileType)
    {      
        $imageActuSize = getimagesize($fileTmpName);
        $imageWidth = $imageActuSize[0];
        $imageHeight = $imageActuSize[1];

        if ($imageWidth === 400 && $imageHeight === 400) 
        {
            $DimensionsOk = true;
        }
        else
        {
            $DimensionsOk = false;
        }
        
        return ($DimensionsOk);
    }

    function UploadImage($file,$fileName,$fileTmpName,$fileSize,$fileError,$fileType,$pseudo)
    {
        $fileNewName=$pseudo;
        $fileDestination='../avatar/'.$fileNewName;
     
        if(!move_uploaded_file($fileTmpName,$fileDestination))
        {
            $fileNewName=false;
            return($fileNewName);
        }
        else
        {
            return($fileNewName);
        }
    }

    function VerificationDate($datenaissance)
    {
        $timestamp=strtotime($datenaissance);
    
        if(!$timestamp) 
        {
            $datevalide=false;
            return($datevalide);
        }
        else
        {
            $datenaissance=date('Y-m-d', $timestamp);
            $aujourdhui = date('Y-m-d');
            $diff = date_diff(date_create($datenaissance), date_create($aujourdhui));
            $age = $diff->y;
            
            if ($age >= 14) 
            {
                $datevalide=true;   
            }
            else
            {
                $datevalide=false;
            }

            return($datevalide);
        }        
    }
  
    function creatUser($pseudo,$motdepasse,$genre,$datenaissance,$fileNewName)
    {
        $connexion = ConnexionBDD();
        $hashedpwd = password_hash($motdepasse, PASSWORD_DEFAULT);
        $requete = "INSERT INTO utilisateur(pseudo,mdp,genre,ddn,avatar) VALUES ('$pseudo','$hashedpwd','$genre','$datenaissance','$fileNewName');";
        $result = mysqli_query($connexion, $requete);
        
        if(!$result)
        {
            $result=false;
        }
        else
        {
            $result=true; 
        }
        
        return($result);
        mysqli_close($connexion);
    }

    /* Fonctions liées au mini-chat : */

    function AjoutMessage($pseudo,$message,$temps)
    {
        $connexion = ConnexionBDD();
        $requete = "INSERT INTO chatmessage (emetteur,message,temps) VALUES ('$pseudo','$message','$temps');";
        $result = mysqli_query($connexion, $requete);

        if(!$result)
        {
            $result=false;
        }
        else
        {
            $result=true; 
        }

        return($result);
        mysqli_close($connexion);
    }

    function AjoutPrivateMessage($pseudo,$message,$temps,$recepteur)
    {
        $connexion = ConnexionBDD();
        $requete = "INSERT INTO chatmessage (emetteur,message,temps,recepteur) VALUES ('$pseudo','$message','$temps','$recepteur');";
        $result = mysqli_query($connexion, $requete);

        if(!$result)
        {
            $result=false;
        }
        else
        {
            $result=true; 
        }

        return($result);
        mysqli_close($connexion);
    }

    function AffichageMessages($offset=0)
    {
        $connexion=ConnexionBDD();

        $requete="SELECT chatmessage.emetteur,chatmessage.message,chatmessage.temps,utilisateur.genre,utilisateur.avatar 
                  FROM chatmessage INNER JOIN utilisateur ON (chatmessage.emetteur=utilisateur.pseudo)
                  ORDER BY chatmessage.temps DESC
                  LIMIT 20 OFFSET $offset;";

        $resultat=mysqli_query($connexion,$requete);

        $messages = array();
        if($resultat && mysqli_num_rows($resultat) > 0)
        {
            while($line = mysqli_fetch_assoc($resultat)) 
            {
                $messages[] = $line;
            }
        }

        return($messages);
        mysqli_close($connexion);
    }

    function AffichagePrivateMessages($pseudo,$recepteur,$offset=0)
    {
        $connexion=ConnexionBDD();

        $requete="SELECT chatmessage.emetteur,chatmessage.message,chatmessage.temps,utilisateur.genre,utilisateur.avatar
                  FROM chatmessage INNER JOIN utilisateur ON (chatmessage.emetteur=utilisateur.pseudo)
                  INNER JOIN utilisateur ON (chatmessage.recepteur=utilisateur.pseudo)
                  WHERE chatmessage.emetteur='$pseudo' AND chatmessage.recepteur='$recepteur' 
                  OR chatmessage.recepteur='$pseudo' AND chatmessage.emetteur='$recepteur' 
                  ORDER BY chatmessage.temps DESC
                  LIMIT 20 OFFSET $offset;";

        $resultat=mysqli_query($connexion,$requete);

        $Privatemessages = array();
        if($resultat && mysqli_num_rows($resultat) > 0)
        {
            while($line = mysqli_fetch_assoc($resultat)) 
            {
                $Privatemessages[] = $line;
            }
        }

        return($Privatemessages);
        mysqli_close($connexion);
    }

?>
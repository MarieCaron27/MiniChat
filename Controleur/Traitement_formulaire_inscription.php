<?php

    include '../Modele/fonctions.php';

    if(isset($_POST["inscrire"]))
    {
        $erreur = [
            'pseudo' => '',
            'genre' => '',
            'datenaissance' => '',
            'motdepasse' => '',
            'motdepasseconfirme' => '',
            'avatar' => '',
            'ajoututilisateur'=>''
        ];

        $pseudo=Securite($_POST['pseudo']);
        $genre=Securite($_POST['genre']);
        $datenaissance=Securite($_POST['datenaissance']);
        $motdepasse=Securite($_POST['motdepasse']);
        $motdepasseconfirme=Securite($_POST['motdepasseconfirme']);
        $file=$_FILES['file'];

        if(empty($pseudo))
        {
            $erreur['pseudo']='<p>Le pseudo est un champ obligatoire...</p>';
        }
        else
        {
            $validationpseudo=Invalidpseudo($pseudo);
            $pseudoexiste=Pseudoexistant($pseudo);

            if($validationpseudo===false)
            {
                $erreur['pseudo']='<p>Le pseudo que vous avez entré est invalide.
                <br>Il ne contenir uniquement des caractères imprimables et doit en contenir entre 8 et 15.</p>';
            }
            
            if($pseudoexiste!==false)
            {
                $erreur['pseudo']='<p>Le pseudo que vous avez entré existe déjà.</p>';
            }
        }

        if(empty($genre))
        {
            $erreur['genre']='<p>Le genre est un champ obligatoire...</p>';
        }

        if(empty($datenaissance))
        {
            $erreur['datenaissance']='<p>La date de naissance est un champ obligatoire...</p>';
        }
        else
        {
            $datevalide=VerificationDate($datenaissance);

            if($datevalide===false)
            {
                $erreur['datenaissance']='<p>Le date que vous avez entré est invalide.
                <br>Vérifier qu\'elle est bien valide et que vous avez 14 ans avant de vous inscrire sur notre site.</p>';
            }
        }

        if(empty($motdepasse))
        {
            $erreur['motdepasse']='<p>Le mot de passe est un champ obligatoire...</p>';
        }
        else
        {
            $validationMDP=Invalidmdp($motdepasse);

            if($validationMDP===false)
            {
                $erreur['motdepasse']='<p>Le mot de passe que vous avez entré est invalide.
                <br>Il ne contenir uniquement des caractères imprimables dont une majuscule, une minuscule et un caractères spécial.
                <br>Sa taille doit être comprise entre 8 et 15 caractères.</p>';
            }
        }

        if(empty($motdepasseconfirme))
        {
            $erreur['motdepasseconfirme']='<p>La confirmation de votre mot de passe est obligatoire...</p>';
        }
        else
        {
            $MDPmatch=PasswordMatch($motdepasse,$motdepasseconfirme);

            if($MDPmatch===false)
            {
                $erreur['motdepasseconfirme']='<p>Il n\'y a pas de correspondance entre les deux mots de passe entrés.</p>';
            }
        }
        

        if(empty($file['name']))
        {
            $erreur['avatar']='<p>L\'avatar est un champ obligatoire...</p>';
        }
        else
        {
            if(!array_filter($erreur))
            {
                $fileName=$file['name'];
                $fileTmpName=$file['tmp_name']; //Actual location of the file
                $fileSize=$file['size'];
                $fileError=$file['error'];
                $fileType=$file['type'];

                $fileActualExt=VerificationExtension($file,$fileName,$fileTmpName,$fileSize,$fileError,$fileType);

                if($fileActualExt)
                {
                    $DimensionsOk=VerificationDimensions($file,$fileName,$fileTmpName,$fileSize,$fileError,$fileType);

                    if($DimensionsOk===true)
                    {
                        $fileNewName=UploadImage($file,$fileName,$fileTmpName,$fileSize,$fileError,$fileType,$pseudo);

                        if($fileNewName===false)
                        {
                            $erreur['avatar']='<p>Nous n\'avons pas pu uploader votre avatar dans le bon dossier.</p>';
                        }
                    }
                    else
                    {
                        $erreur['avatar']='<p>Les dimensions de votre avatar sont incorrectes (400*400 pixels).</p>';
                    }
                }
                else
                {
                    $erreur['avatar']='<p>L\'extension de votre avatar est incorrecte.
                    <br>Seules les extensions .JPEG et .PNG sont accéptées.</p>';
                } 
            }  
        }

        if(array_filter($erreur))
        {
            $UserCreated=false;
        }
        else
        {
           $UserCreated=creatUser($pseudo,$motdepasse,$genre,$datenaissance,$fileNewName);

            if($UserCreated===false)
            {
                $erreur['ajoututilisateur']='<p>Nous n\'avons pas pu vous ajouter...</p></div>';
            }
            else
            {
                if(!isset($_SESSION)) 
                { 
                    session_start(); 
                } 
                
                $_SESSION['pseudo']=$pseudo;
                header("location: ../Controleur/Traitement_ajout_message.php?error=bienvenu");
            }
        }        
    }

    include '../Vues/index.php';








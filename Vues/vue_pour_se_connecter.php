<!DOCTYPE html>
<html>
    <head>
        <meta charset=utf-8 />
        <title>Vue_connexion</title>
        <link href="../style/style.css" rel="stylesheet" type="text/css"/>
    </head>

    <body>
        <?php 
            require '../Annexes/header.php';

            if(isset($_GET["error"]))
            {
                if($_GET["error"] == "success")
                {
                    echo '<div class="toutvabien"><p>Vous avez bien été ajouté à notre site.
                    <br>Il ne vous reste plus qu\'à vous connectez pour y accéder!</p></div>';
                }
            }
        ?> 
        <main>
            <section class="connexion">
                    <article id="connexion">
                        <form  method = "POST" id="form2" action="../Controleur/Traitement_formulaire_connexion.php">
                            <h3>Me connecter</h3>
                            
                            <div class="form_sign_in">
                                <input type="text" name ="pseudo" class="form_control" placeholder="Entrez votre pseudo" <?php if(isset($_POST['seconnecter']) && !empty($_POST['pseudo'])){echo 'value= "' . $_POST['pseudo'] . '"';}?>>
                                <div class="erreur">
                                    <?php
                                        if(!empty($erreur['pseudo']))
                                        {
                                            echo $erreur['pseudo'];
                                        }
                                    ?>
                                </div>
                            </div>
            
                            <div class="form_sign_in">                 
                                <input type="password"  name ="password" class="form_control" placeholder="Entrez votre mot de passe" <?php if(isset($_POST['seconnecter']) && !empty($_POST['password'])){echo 'value= "' . $_POST['password'] . '"';}?>>
                                <div class="erreur">
                                    <?php
                                        if(!empty($erreur['password']))
                                        {
                                            echo $erreur['password'];
                                        } 
                                    ?>
                                </div>
                            </div>
                            
                            <div class="form_sign_in">
                                <button type = "submit" name="seconnecter" id="seconnecter">Se connecter</button>
                            </div>
                        </form>
                    </article>
            </section>
        </main>

        <?php 
            require '../Annexes/footer.php';
        ?>
    </body>
</html>
<!DOCTYPE html>
<html>
    <head>
        <meta charset=utf-8 />
        <title>Vue_demande_CP</title>
        <link href="../style/style.css" rel="stylesheet" type="text/css"/>
    </head>

    <body>
        <?php 
            require '../Annexes/header_connecter.php';
        ?> 
        <main>
            <section class="demandeChatPrive">
                    <article id="demandeChatPrive">
                        <form  method = "POST" id="form6" action="../Controleur/Traitement_demande_chat_prive.php">
                            <h3>Avec qui voulez-vous converser en privé?</h3>
                            
                            <div class="form_private_chat"> 
                                <label for="pseudo">Entrez le pseudo de la personne avec laquelle vous voulez intéragir</label>    
                                <input list="pseudo" name="pseudo">
                                    <datalist id="pseudo">
                                        <?php
                                            if(!empty($mesPseudos))
                                            {
                                                foreach($mesPseudos as $tableau)
                                                {
                                        ?>
                                                    <option value="<?php echo $tableau['pseudo']?>"><?php echo $tableau['pseudo']?></option>
                                        <?php
                                                }
                                            }
                                        ?>
                                    </datalist>                            
                                </input>
                                <div class="erreur">
                                    <?php 
                                        if(!empty($erreur['pseudo']))
                                        {
                                            echo $erreur['pseudo'];
                                        } 
                                    ?>
                                </div>
                            </div>

                            <div class="form_private_chat">
                                <button type = "submit" name="requeteCP" id="requeteCP">Envoyer ma requête</button>
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
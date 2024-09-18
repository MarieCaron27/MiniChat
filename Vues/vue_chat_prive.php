<!DOCTYPE html>
<html>
    <head>
        <meta charset=utf-8 />
        <title>vue_pour_le_chat_prive</title>
        <link href="../style/style.css" rel="stylesheet" type="text/css"/>
    </head>

    <body>
        <?php 
            require '../Annexes/header_connecter.php';
        ?>
       <main>
            <section class="form-section">
                <form action="../Controleur/Traitement_chat_prive.php?PK=<?php echo $_GET['PK'];?>" method="post">
                    <div class="chatBox">
                        <div class="message">
                            <?php
                                if(!empty($Privatemessages))
                                {
                                    foreach ($Privatemessages as $tableau) 
                                    { 
                                        if(!empty($tableau['recepteur']))
                                        {
                                            echo '<div class="mymessages">';
                                                if($tableau['genre']==='M')
                                                {                
                                                    echo '<div class="classM"><p>' . $tableau['emetteur'] . '</p></div>';
                                                    echo '<div class="classM"><p>' . $tableau['recepteur'] . '</p></div>';
                                                }
                                                elseif($tableau['genre']==='F')                
                                                {
                                                    echo '<div class="classF"><p>' . $tableau['emetteur'] . '</p></div>';
                                                    echo '<div class="classF"><p>' . $tableau['recepteur'] . '</p></div>';
                                                }
                                                else
                                                {
                                                    echo '<div class="classS"><p>' . $tableau['emetteur'] . '</p></div>';
                                                    echo '<div class="classS"><p>' . $tableau['recepteur'] . '</p></div>';
                                                }

                                                echo '<p>'.$tableau['message']. '</p>';
                                                echo '<p><span>' . $tableau['temps'] . '</span></p>';
                                                echo '<p><img src="../avatar/'.$tableau['avatar'].'" width="40" height="40"></p>';
                                            echo '</div>';
                                        }
                                    }
                                }
                                else
                                {
                                    echo '<p>Aucun messages à afficher...</p>';
                                }
                            ?>
                        </div>
                    </div>
                    <div class="lesoffsets">
                        <div class="offsetpos">
                            <a href="../Controleur/Traitement_ajout_message.php?offset=<?php echo $offset-20; ?>">Afficher les 20 messages suivants</a>
                        </div>
                        <div class="offsetneg">
                            <a href="../Controleur/Traitement_ajout_message.php?offset=<?php echo $offset+20; ?>">Afficher les 20 messages précédents</a>
                        </div>
                    </div>
                    
                    <div class="chatbox_input">
                        <input type="text" name="message" placeholder="Type your message">
                        <button type="submit" name="envoiPrivatemsg"><ion-icon name="navigate-outline"></ion-icon></button> 
                    </div>

                    <div class="erreur">
                        <?php
                            if(!empty($erreur['message']))
                            {
                                echo $erreur['message'];
                            }

                            if(!empty($erreur['emetteur']))
                            {
                                echo $erreur['emetteur'];
                            }

                            if(!empty($erreur['recepteur']))
                            {
                                echo $erreur['recepteur'];
                            }
                        ?>
                    </div>
                </div>
            </section>
       </main>
        <?php 
            require '../Annexes/footer.php';
        ?> 
        <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
        <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
    </body>
</html>

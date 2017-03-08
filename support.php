<?php
include('include/init.php');

//Si on envoi une nouvelle réponse
if(isset($_GET['id']) && !empty($_GET['id'])) {
    
    //Affichage du ticket
    $id = $_GET['id'];
    
    //Requête SQL
    $req_selectTicket = 'SELECT * FROM tickets WHERE id = :id';
    
    //Si on est utilisateur, on affiche pas le ticket s'il est privé
    if(RANG == 1)  $req_selectTicket .= ' AND private = 0';
    
    //On exécute notre requête
    $req_selectTicket = $connexion->prepare($req_selectTicket);
    $req_selectTicket->execute(array(
        'id' => $id,
    ));
    $nbr_selectTicket = $req_selectTicket->rowCount();
    
    //Si le ticket existe
    if($nbr_selectTicket == 1) {
        
        //On récupère les informations du ticket sous forme de tableau
        $selectTicket = $req_selectTicket->fetch();
        
        //Si on envoi une nouvelle réponse
        if(!empty($_POST)) {
            $contenu = secure($_POST['contenu']);
            $id_ticket = $selectTicket['id'];

            //On envoi les informations dans la base de donnée
            $insertReponse = $connexion->prepare('INSERT INTO tickets_reponses SET id_user=:id_user, id_ticket=:id_ticket, contenu=:contenu, date=:date, heure=:heure');
            $insertReponse->execute(array(
                'id_user' => ID,
                'id_ticket' => $id_ticket,
                'contenu' => $contenu,
                'date' => $date,
                'heure' => $heure,
            ));     
            
            //Redirection
            header('location: support.php?id='.$id_ticket);
            exit;
        }
    }

} else {
    
    if(!empty($_POST)) {
    
        //Déclarations
        $private = 0;
        
        //Si le sujet et le contenu n'est pas vide
        if(!empty($_POST['sujet']) && !empty($_POST['contenu'])) {
            
            $sujet = $_POST['sujet'];
            $contenu = $_POST['contenu'];
            
            if(!empty($_POST['private']) && $_POST['private'] == '1') $private = 1;
            
            //On protége les informations
            $sujet = secure($sujet);
            $contenu = secure($contenu);
            
            //On envoi dans la base de donnée
            $insertTicket = $connexion->prepare('INSERT INTO tickets SET id_user = :id_user, sujet = :sujet, contenu = :contenu, date=:date, heure=:heure, private=:private');
            $insertTicket->execute(array(
                'id_user' => ID,
                'sujet' => $sujet,
                'contenu' => $contenu,
                'date' => $date,
                'heure' => $heure,
                'private' => $private,
            ));
            
            //On récupère l'identifiant du dernier ticket posté
            $req_selectLastTicket = $connexion->prepare('SELECT id FROM tickets WHERE id_user = :id_user ORDER BY id DESC LIMIT 1');
            $req_selectLastTicket->execute(array(
                'id_user' => ID,
            ));
            $selectLastTicket = $req_selectLastTicket->fetch();
            
            //On récupère l'identifiant
            $lastTicket = $selectLastTicket[0];
            
            //Redirection
            header('location: support.php?id='.$lastTicket);
            exit;
            
        } else {
            echo "Veuillez renseigner tous les paramètres";
        }
        exit;
    }
}

include('include/header.php');
?>

<style>
    
    #ticketSeul h3{
        margin: 3px 0;
    }
    
    
    
</style>



<div id="contenu">
    <div id="news">
        <div class="news_content">
            
            <?php
            //Si un ticket est mentionné
            if((isset($_GET['id']) && !empty($_GET['id'])) && (isset($nbr_selectTicket) && !empty($nbr_selectTicket))) {
                
                //Si notre ticket existe
                if($nbr_selectTicket == 1) {
    
                    //On récupère le pseudo
                    $pseudo = getUser('id', $selectTicket['id_user'], 'pseudo');
                    
                    echo '<div id="ticketSeul">';
                    
                    echo '<a href="./support.php">Revenir à la liste des tickets</a><hr>';
                    
                    echo '<h3>'.$selectTicket['sujet'].'</h3>';
                    echo 'Envoyé par '.$pseudo.', le '.$selectTicket['date'].' à '.$selectTicket['heure'].'.';
                    echo '<hr>';
                    echo nl2br($selectTicket['contenu']);
                    
                    echo '</div>';
                    ?>
                    
                    <hr>
                    <div id="reponseTicket" style="border:1px solid #333; padding: 10px;">
                        
                        <h2>Ajouter une réponse au ticket</h2>
                        
                        <form method="post">
                            <textarea name="contenu" style="width: 100%;" placeholder="Veuillez insérer votre réponse..." rows="5"></textarea><br>
                            <input type="submit" value="Envoyer la réponse">
                        </form>
                        
                        <h2>Réponses apportées sur le ticket</h2>
                        
                        <?php
                        $req_selectReponses = $connexion->prepare('SELECT * FROM tickets_reponses WHERE id_ticket = :id_ticket');
                        $req_selectReponses->execute(array(
                            'id_ticket' => $selectTicket['id'],
                        ));
                        $nbr_selectReponses = $req_selectReponses->rowCount();
                        
                        
                        //Si nous avons au moins une réponse
                        if($nbr_selectReponses > 0) {
                            
                            //On affiche toutes les réponses
                            while($selectReponses = $req_selectReponses->fetch()) {
                                
                                $pseudo = getUser('id', $selectReponses['id_user'], 'pseudo');
                                $contenu = $selectReponses['contenu'];
                                $dateTicket = $selectReponses['date'];
                                $heureTicket = $selectReponses['heure'];
                                
                                //Affiche de la réponse
                                echo '<b>'.$pseudo.'</b>, le '.$date.' à '.$heure.':<br>'.$contenu.'<hr>';
                            }
                        }
                        ?>
                    </div>     
    
                    <?php
                } else {
                    echo 'exist pas';
                }
            } else {
            
                echo '<h3>Création d\'un ticket</h3>';
                
                ?>

                <form method="post">
                    
                    Sujet du ticket:<br>
                    <input name="sujet"><br><br>
                    
                    Contenu du ticket:<br>
                    <textarea name="contenu"></textarea><br><br>
                    
                    <input type="checkbox" name="private" value="1"> Ticket privé ?<br><br>
                    
                    <input type="submit" value="Envoyer le ticket">
                    
                    
                    
                </form>
                
                
                
                
                <?php            
                //Affichage de la liste des tickets
                echo '<h3>Liste des tickets</h3>';
            
                //Si on est administrateur, on affiche tous les tickets, sinon, on affiche que les tickets publiques
                if(RANG == 3) {
                    $req_selectTicket = 'SELECT * FROM tickets';
                } else {
                    $req_selectTicket = 'SELECT * FROM tickets WHERE private = 0';
                }
                $req_selectTicket .= ' ORDER BY id DESC';
                
                $req_selectTickets = $connexion->query($req_selectTicket);
                $nbr_selectTickets = $req_selectTickets->rowCount();
                
                if($nbr_selectTickets > 0) {
                    
                    echo '<table border="1" style="width: 100%;">';
                    echo '<tr>';
                    echo '<td>Pseudo</td>';
                    echo '<td>Sujet du ticket</td>';
                    echo '<td>Date</td>';
                    echo '<td>Etat</td>';
                    echo '</tr>';
                    
                
                    while($selectTickets = $req_selectTickets->fetch()) {
                        
                        $id = $selectTickets['id'];
                        $etat = $selectTickets['etat'];
                        
                        /*
                        //Condition de base
                        if($etat == 0) {
                            $etat = 'En cours';
                        } else {
                            $etat = 'Résolu';
                        }
                        */

                        //condition ternaire
                        ($etat == 0) ? $etat = 'En cours' : $etat = 'Résolu';
                        
                        $pseudo = getUser('id', $selectTickets['id_user'], 'pseudo');
                        
                        echo '<tr>';
                        echo '<td>'.$pseudo.'</td>';
                        echo '<td><a href="./support.php?id='.$id.'">'.$selectTickets['sujet'].'</a></td>';
                        echo '<td>'.$selectTickets['date'].' '.$selectTickets['heure'].'</td>';
                        echo '<td>'.$etat.'</td>';
                        
                        echo '</tr>';
                        
                    }
                    
                    echo '</table>';
                }
            }
            ?>
        </div>
    </div>
<?php
include('include/menudroite.php');
include('include/footer.php');
?>
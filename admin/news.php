<?php

//Si le get delete n'est pas vide
if(!empty($_GET['delete'])) {
    $id = $_GET['delete'];
    
    //Supprimer la news
    $deleteNews = $connexion->prepare('DELETE FROM news WHERE id=:id');
    $deleteNews->execute(array(
        'id' => $id
    ));
    
    header('location: index.php?tab=news');
    exit;
}


//Si aucune news n'est sélectionnée
if(empty($_GET['id'])) {



    //Site "addNews" est présent dans l'URL
    if(strstr($_SERVER['REQUEST_URI'], 'addNews')) {
        //Si un formulaire est envoyé 
        if(!empty($_POST['news'])) {
            //Convertit les caractères spéciaux en caractère html
            $news = htmlspecialchars($_POST['news'], ENT_QUOTES);
         
            //Ajout du titre (facultatif)
            if(!empty($_POST['titre'])) {
                $titre = htmlspecialchars($_POST['titre'], ENT_QUOTES);
            } else {
                $titre = false;
            }
         
            //Envoi dans la base de donnée
            $addNews = $connexion->prepare('INSERT INTO news SET pseudo=:pseudo, titre=:titre, content=:content, date=:date, heure=:heure');
            $addNews->execute(array(
                'pseudo' => $pseudo,
                'titre' => $titre,
                'content' => $news,
                'date' => $date,
                'heure' => $heure,
            ));
            
            //Redirection
            header('location: index.php?tab=news');
        } else {   ?>
            
            <form method="post">
                <input type="text" name="titre" placeholder="Titre de votre news (facultatif)..."><br>
                <textarea rows="10" style="width:100%;" name="news" placeholder="Contenu de votre news..."></textarea><br>
                <input type="submit" value="Envoyer votre nouvelle news">
            </form>
        
        <?php
        }
    } else {
    
    
        echo '<a href="?tab=news&addNews">Créer une nouvelle news</a><hr>';
    
        $req_selectNews = $connexion->query('SELECT * FROM news ORDER BY id DESC');
        $nbr_selectNews = $req_selectNews->rowCount();
        
        if($nbr_selectNews > 0) {
        
            while($selectNews = $req_selectNews->fetch()) {
                
                $content = str_replace("\n", '<br>', $selectNews['content']);
                
                echo '<div class="news_content">';
                
                if(!empty($selectNews['titre'])) {
                    echo '<strong>'.$selectNews['titre'].'</strong><br>';
                }
                
                echo $content.'<br>';
                
                
                echo '<br>[<a href="?tab=news&id='.$selectNews['id'].'">Modifier</a>] [<a href="?tab=news&delete='.$selectNews['id'].'">Supprimer</a>]';
                
                
                echo '</div>';
                
            }
            
        }
    }
} else {
    

    
    if(!empty($_POST)) {
        if(!empty($_POST['content'])) {
            $content = htmlspecialchars($_POST['content'],  ENT_QUOTES);
            
            //Ajout du titre (facultatif)
            if(!empty($_POST['titre'])) {
                $titre = htmlspecialchars($_POST['titre'], ENT_QUOTES);
            } else {
                $titre = false;
            }
         
            
            $id = $_GET['id'];
            
            $updateNews = $connexion->prepare('UPDATE news SET content=:content, titre=:titre WHERE id=:id');
            $updateNews->execute(array(
                'content' => $content,
                'titre' => $titre,
                'id' => $id
            ));
            
            echo 'News modifiée: <a href="/lite/index.php">Retourner à l\'accueil</a><hr>';
        }
    }
    
    $req_selectNews = $connexion->prepare('SELECT * FROM news WHERE id=:id');
    $req_selectNews->execute(array(
        'id' => $_GET['id']
    ));
    $nbr_selectNews = $req_selectNews->rowCount();
    
    
    //Si la news demandée existe
    if($nbr_selectNews == 1) {
        $selectNews = $req_selectNews->fetch(); ?>
        
        <div class="news_content">
            <form method="post">
                <input type="text" name="titre" placeholder="Titre de votre news..." value="<?php echo $selectNews['titre']; ?>">
                <textarea rows="10" style="width:100%;" name="content" placeholder="Contenu de votre news..."><?php echo $selectNews['content']; ?></textarea>
                <input type="submit" value="Modifier votre news...">
            </form>
        </div>    
        
        <?php
    } else {
        echo 'Cette news n\'existe pas';
    }
}

?>
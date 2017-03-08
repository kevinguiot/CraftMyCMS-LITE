<?php
include('include/init.php');
include('include/header.php');
?>
<div id="contenu">
    <div id="news">
        <?php
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
                echo '<br>Publiée par <strong>'.$selectNews['pseudo'].'</strong>, le '.$selectNews['date'].' à '.$selectNews['heure'].'.';
                echo '</div>';
            }
        }
        ?>
    </div>
    <?php
    include('include/menudroite.php');
    include('include/footer.php');
    ?>

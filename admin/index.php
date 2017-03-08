<?php
include('../include/init.php');
include('../include/header.php');
?>

<div id="contenu">
    <div id="news">
            <?php
            if(connect() && $rang == 3) {
                if(!empty($_GET['tab'])) {
                    switch($_GET['tab']) {
                        case 'news':
                            $tab = 'news';
                            break;
                        case 'membres':
                            $tab = 'membres';
                            break;
                        case 'site':
                            $tab = 'site';
                            break;
                        default:
                            echo 'ERROR';
                            break;
                    }
                    
                    if(isset($tab)) {
                        include($tab.".php");
                    }
                } else {
                    echo 'Que voulez-vous faire ?';
                }
            } else {
                echo "Vous n'avez pas les droits nécessaires.";
            }
            ?>
    </div>
    <?php
    include('../include/menudroite.php');
    include('../include/footer.php');
    ?>

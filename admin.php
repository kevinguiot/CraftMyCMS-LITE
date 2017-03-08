<?php
include('include/init.php');

if($rang != 3) {
    die("Vous devez être connecté en tant qu'administrateur.");
}

include('include/header.php');
?>
<div id="contenu">
    <div id="news">
        
        <div class="news_content">
            <h1>Espace administration</h1>
        </div>
        
    </div>
    <?php
    include('include/menudroite.php');
    include('include/footer.php');
    ?>
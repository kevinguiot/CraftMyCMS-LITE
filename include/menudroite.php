<div id="sidebar">
    <div class="widget">
        <h3 class="gradient">Informations du serveur</h3>
        <div class="widget_text">informations...</div>
    </div>
    <div class="widget">
        <?php if(!connect()) { ?>
        <h3 class="gradient">Connexion au site</h3>
        <div class="widget_text connexion">
            <form method="post" action="connexion.php">
                Identifiant:<br>
                <input type="text" name="pseudo"><br>
                Mot de passe:<br>
                <input type="password" name="password"><br>
                <span style="float: left; margin-top:2px"><input type="checkbox"> Rester connecté</span>
                <span style="float:right;"><input type="submit" class="gradient" value="Connexion"></span>
                <br style="clear: both;"><br>
            </form>
        </div>
        <?php } else { ?>
        <h3 class="gradient">Espace membre</h3>
        <div class="widget_text">
            Bonjour pseudo,
            <ul>
                <li><a href="compte.php">Modifier vos informations</a></li>
                <li><a href="connexion.php?logout=1">Déconnexion</a></li>
            </ul>
        </div>
        <?php } ?>
    </div>
    <div class="widget">
        <div class="widget_text social">
            <a href=""><img src="/lite/images/facebook.png"></a>
            <a href=""><img src="/lite/images/twitter.png"></a>
            <a href=""><img src="/lite/images/youtube.png"></a>
        </div>
    </div>
    <div class="widget"></div>
</div>
<p style="clear: both;"></p>
<?php
include('include/init.php');
if(connect()) {
    if($_POST) {
        if(!empty($_POST['mdp1'])) {
            if($_POST['mdp1'] == $_POST['mdp2']) {
                $mdp = md5($_POST['mdp1']);
                $updateMembre = $connexion->prepare('UPDATE '.$prefixe.'membres SET passe=:passe WHERE pseudo=:pseudo');
                $updateMembre->execute(array(
                    'passe' => $mdp,
                    'pseudo' => $pseudo,
                ));            
            } else {
                header('location: compte.php?msg=erreur');
                exit;
            }
        }
        if($_POST['email']!=null) {
            $updateMembre = $connexion->prepare('UPDATE '.$prefixe.'membres SET email=:email, prenom=:prenom, nom=:nom WHERE pseudo=:pseudo');
            $updateMembre->execute(array(
                'email' => htmlspecialchars($_POST['email'],  ENT_QUOTES),
                'prenom' => htmlspecialchars($_POST['prenom'],  ENT_QUOTES),
                'nom' => htmlspecialchars($_POST['nom'],  ENT_QUOTES),
                'pseudo' => $pseudo,
            ));        
            header('location: compte.php?msg=ok');
        } else {
            header('location: compte.php?msg=mail');
        }
    }
    
    
    include('include/header.php');
    ?>
    <div id="contenu">
    <div id="news">
        <div class="news_content">
    
        <?php
        
        //Gestion des erreurs
        if(!empty($_GET['msg'])) {
            if($_GET['msg']=="ok") {
                echo '<div class="warning_v">Votre compte a bien été modifié</div>';
            }
            if($_GET['msg']=="erreur") {
                echo '<div class="warning_r">Veuillez renseigner des mots de passe identiques</div>';
            }
            if($_GET['msg']=="mail") {
                echo '<div class="warning_r">Votre adresse mail ne doit pas être vide</div>';
            }
        }
        ?>

        <form method="post">
            <p style="text-align: center;">Modifier les informations de votre compte</p>
            <table>
                <tr>
                    <td>Pseudo</td>
                    <td><input type="text" value="<?php echo $pseudo; ?>" readonly></td>
                </tr>
                
                <tr>
                    <td>Mot de passe<br><small>Laissez vide pour pas changer</small></td>
                    <td><input type="password" name="mdp1"></td>
                </tr>
        
                <tr>
                    <td>Mot de passe x2</td>
                    <td><input type="password" name="mdp2"></td>
                </tr>
        
                <tr>
                    <td>Adresse e-mail</td>
                    <td><input type="text" value="<?php echo $email; ?>" name="email"></td>
                </tr>
                
                <tr>
                    <td>Prénom</td>
                    <td><input type="text" value="<?php echo $prenom; ?>" name="prenom"></td>
                </tr>
                    
                <tr>
                    <td>Nom</td>
                    <td><input type="text" value="<?php echo $nom; ?>" name="nom"></td>
                </tr>        
                
                <tr name="last">
                    <td colspan="2"><input type="submit" class="gradient" value="Modifier votre compte"></td>
                </tr>
            </table>
        </form>
        
        </div>
    </div>
    <?php
    include('include/menudroite.php');
    include('include/footer.php');
} else {
    header('location: index.php');
}
?>
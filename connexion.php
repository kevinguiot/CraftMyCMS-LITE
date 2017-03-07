<?php
include('include/init.php');

//Si un formulaire est rempli
if(!empty($_POST)) {
    
    //Si les informations de connexion ne sont pas vides
    if(!empty($_POST['pseudo']) && !empty($_POST['password'])) {
        
        //On récupère les valeurs
        $pseudo = $_POST['pseudo'];
        $password = md5($_POST['password']);
        
        //On récupère dans la base de donnée
        $req_nbrMembre = $connexion->prepare('SELECT * FROM membres WHERE pseudo=:pseudo AND passe=:password');
        $req_nbrMembre->execute(array(
            'pseudo' => $pseudo,
            'password' => $password
        ));
        
        //On récupère le nombre de valeurs
        $nbrMembre = $req_nbrMembre->rowCount();
            
        //Si le membre existe
        if($nbrMembre == 1) {
            
            //On récupère les informations du membre
            $selectMembre = $req_nbrMembre->fetch();
     
            //On génère une session
            $session = md5(rand());
            
            //On mets à jour la session de l'utilisateur pour les informations récupérées
            $updateMembre = $connexion->prepare('UPDATE membres SET session=:session WHERE id=:id');
            $updateMembre->execute(array(
                'session' => $session,
                'id' => $selectMembre['id'],
            ));
            
            //On mets à jour la session
            $_SESSION['session'] = $session;
            
            //On redirige la personne
            header('location: connexion.php');      
        }
    }
}

//On affiche le formulaire de connexion à l'espace membre
?>

<form method="post">
    Pseudo: <input type="text" name="pseudo"><br>
    Mot de passe: <input type="password" name="password"><br>
    <input type="submit" value="Connexion">
</form>
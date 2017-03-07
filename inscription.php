<?php
include 'include/init.php';

//Si un formulaire est soumis
if(!empty($_POST)) {
    
    //On regarde si un utilisateur n'est pas incrit avec le même pseudo
    $req_selectPseudo = $connexion->prepare('SELECT * FROM membres WHERE pseudo=:pseudo');
    $req_selectPseudo->execute(array(
        'pseudo' => $_POST['pseudo']
    ));
    $selectPseudo = $req_selectPseudo->rowCount();
    
        //On regarde si un utilisateur n'est pas incrit avec le même e-mail
    $req_selectEmail = $connexion->prepare('SELECT * FROM membres WHERE email=:email');
    $req_selectEmail->execute(array(
        'email' => $_POST['email']
    ));
    $selectEmail = $req_selectEmail->rowCount();

    if($selectEmail == "0" && $selectPseudo == "0") {
        
        //On prépare les informations
        $pseudo = htmlspecialchars($_POST['pseudo'], ENT_QUOTES);
        $password = md5($_POST['password']);
        $email = htmlspecialchars($_POST['email'],  ENT_QUOTES);
        $session = md5(rand());
        
        //On inscrit l'utilisateur
        $addMembre = $connexion->prepare('INSERT INTO membres SET pseudo=:pseudo, passe=:passe, email=:email, session=:session');
        $addMembre->execute(array(
            'pseudo' => $pseudo,
            'passe' => $password,
            'email' => $email,
            'session' => $session
        ));
        
        //On redirige l'utilisateur sur la page du success
        header('location: inscription.php?msg=ok');
    } else {
        
        //erreur
        header('location: inscription.php?msg=erreur');
    }
}

//Gestion des erreurs
if(!empty($_GET['msg'])) {
    if($_GET['msg']=="ok") {
        echo 'Votre compte a bien été créé';
    }
    if($_GET['msg']=="erreur") {
        echo 'Ce pseudo ou cette adresse e-mail sont déjà utilisés';
    }
}
?>

<form method="post">
    <table>
        <tr>
            <td>Pseudo</td>
            <td><input type="text" name="pseudo"></td>
        </tr>
        <tr>
            <td>Mot de passe</td>
            <td><input type="password" name="password"></td>
        </tr>
        <tr>
            <td>Adresse mail</td>
            <td><input type="text" name="email"></td>
        </tr>
        <tr name="last">
            <td colspan="2"><input type="submit" value="Inscrivez-vous"></td>
        </tr>
    </table>
</form>
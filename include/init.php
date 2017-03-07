<?php
//On prépare les sessions
session_start();

//On inclut le fichier de configuration
include 'config.inc.php';

//On inclut les fonctions
include 'functions.php';

//Récupération de la date
date_default_timezone_set('Europe/Paris');
$date = date('d/m/Y');
$heure = date('H:i:s');

//On teste la connexion à la base de données
try {
    $connexion = new PDO('mysql:host='.$host.';dbname='.$db.'; charset=utf8', $user, $password, array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'));
    
    //Si la session n'est pas vide
    if(!empty($_SESSION['session'])) {
        
        //On récupère les informations de l'utilisateur s'il est connecté
        $req_selectMembre = $connexion->prepare('SELECT * FROM membres WHERE session=:session');
        $req_selectMembre->execute(array(
            'session' => $_SESSION['session']
        ));
        $selectMembre = $req_selectMembre->fetch();
        
        //On récupère les informations
        $pseudo = $selectMembre['pseudo'];
        $email = $selectMembre['email'];
        $prenom = $selectMembre['prenom'];
        $nom = $selectMembre['nom'];
        $rang = $selectMembre['rang'];
        
        //Déclarations de constante
        DEFINE('ID', $selectMembre['id']);
        DEFINE('PSEUDO', $selectMembre['pseudo']);
        DEFINE('RANG', $selectMembre['rang']);
        
    } else {
        DEFINE('RANG', 1);
    }
} catch (PDOException $e) {
    echo 'Impossible de se connecter à la base de donnée';
}
?>
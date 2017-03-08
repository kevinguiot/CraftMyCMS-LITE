<?php

//Fonction pour vérifier si un utilisateur est connecté
function connect() {
    if(empty($_SESSION['session'])) {
        return false;
    } else {
        return true;
    }
}

//Fonction pour récupérer une information d'un utilisateur
function getUser($avant, $info, $apres) {
    global $connexion;
    
    //Déclarations
    $valeurDemandee = false;
    
    //On fait la requête pour récupérer l'information de l'utilisateur
    $req_selectUser = $connexion->prepare('SELECT * FROM membres WHERE '.$avant.' = :info');
    $req_selectUser->execute(array(
        'info' => $info,
    ));
    $nbr_selectUser = $req_selectUser->rowCount();
    
    //Si l'utilisateur existe
    if($nbr_selectUser == 1) {
        //On récupère les informations
        $selectUser = $req_selectUser->fetch();
        
        //On récupère la valeur de la donnée demandée
        $valeurDemandee = $selectUser[$apres];
    }
    
    return $valeurDemandee;
}

//Fonction pour protéger les valeurs envoyées
function secure($text) {
    $text = htmlspecialchars($text, ENT_QUOTES);
    
    return $text;
}
?>
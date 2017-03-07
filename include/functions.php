<?php

//Fonction pour vérifier si un utilisateur est connecté
function connect() {
    if(empty($_SESSION['session'])) {
        return false;
    } else {
        return true;
    }
}

?>
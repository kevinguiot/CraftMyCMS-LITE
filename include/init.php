<?php

//On inclut le fichier de configuration
include 'config.inc.php';

//On teste la connexion à la base de données
try {
    $bdd = new PDO('mysql:host='.$host.';dbname='.$db.'; charset=utf8', $user, $password, array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'));
} catch (PDOException $e) {
    echo 'Impossible de se connecter à la base de donnée';
}
?>
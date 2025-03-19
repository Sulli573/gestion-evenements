<?php
//configuration de la connexion à la base de données

$host="localhost";
$dbname="gestion_evenements";
$username="root";
$password="";

try{
    $db=new PDO("mysql:host=$host;dbname=$dbname;charset=utf8",$username,$password); //nouvelle instance de la classe PDO
    $db->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
    $db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE,PDO::FETCH_ASSOC);

    echo "Connexion reussie à la base de données";
}catch(PDOException $e){
    die("Erreur de connexion à la base de données " . $e->getMessage());
}

?>
<?php

function getAccesBDD(){
	
	try{
		$bdd = new PDO('mysql:host=mysql.hostinger.fr;dbname=nope;charset=utf8', 'nope', 'nope');
	}
	catch (Exception $e){
		die('Erreur lors de la connexion à la base de données');
	}
	return  $bdd;
}


?>
<?php
if(isset($_GET['pays'])) {
	// connexion à la base de données
	include("modelesql.php");
	$bdd=getAccesBDD();
	$json = array();
	$query = 'SELECT DISTINCT UNIVERSITE FROM localisation WHERE PAYS="' . $_GET['pays'] .'"';
	$reponse = $bdd->query($query);
	while ($donnees = $reponse->fetch()){
		$json[] = $donnees["UNIVERSITE"];
	}
	$reponse->closeCursor(); 
	// envoi du résultat au success
	echo json_encode($json);
}
?>
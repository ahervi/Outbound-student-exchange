<?php
require 'modelesql.php'; 
$bdd = getaccesBDD();
	$listedesid=array();
	$listedesuniversitesold=array();
	$listedesuniversitesnew=array();
	$anneeold = date('Y')-4;


	$reponse = $bdd->query('SELECT ID FROM general WHERE ANNEE< ' . $anneeold .'');
	while ($donnees = $reponse->fetch()){
		$listedesid[] = $donnees['ID'];
		
	}
	$reponse->closeCursor();

	$reponse = $bdd->query('SELECT DISTINCT UNIVERSITE FROM general WHERE ANNEE>= ' . $anneeold .'');
	while ($donnees = $reponse->fetch()){
		$listedesuniversitesnew[] = $donnees['UNIVERSITE'];
	}
	$reponse->closeCursor();

	$reponse = $bdd->query('SELECT DISTINCT UNIVERSITE FROM general WHERE ANNEE< ' . $anneeold .'');
	while ($donnees = $reponse->fetch()){
		$listedesuniversitesold[] = $donnees['UNIVERSITE'];
	}
	$reponse->closeCursor();

	if (isset($listedesid)){



		foreach ($listedesid as $key => $value) {
			$sql = "DELETE FROM general WHERE ID =  :ID";
			$stmt = $bdd->prepare($sql);
			$stmt->bindParam(':ID', $value, PDO::PARAM_INT);   
			$stmt->execute();
			$sql2 = "DELETE FROM particulier WHERE ID =  :ID";
			$stmt2 = $bdd->prepare($sql2);
			$stmt2->bindParam(':ID', $value, PDO::PARAM_INT);   
			$stmt2->execute();
			

			$dossier_traite = "Plans_de_parcours_initiaux";
			$dossier_traite2 = "Plans_de_parcours_finaux";

$repertoire = opendir($dossier_traite); // On définit le répertoire dans lequel on souhaite travailler.

while (false !== ($fichier = readdir($repertoire))) // On lit chaque fichier du répertoire dans la boucle.
{
	

// Si le fichier n'est pas un répertoire…
	if ($fichier != ".." AND $fichier != "." AND !is_dir($fichier) AND $fichier == "Plan_de_parcours_initial_" . $value . ".pdf")
	{ 
       $chemin = $dossier_traite."/".$fichier; // On définit le chemin du fichier à effacer.
       unlink($chemin); // On efface.
       $chemin2 = $dossier_traite2."/Plan_de_parcours_final_" . $value . ".pdf"; // On définit le chemin du fichier à effacer.
       unlink($chemin2); // On efface.
   }
}
closedir($repertoire); // Ne pas oublier de fermer le dossier ***EN DEHORS de la boucle*** ! Ce qui évitera à PHP beaucoup de calculs et des problèmes liés à l'ouverture du dossier.


}

if (isset($listedesuniversitesold)){
	foreach ($listedesuniversitesold as $key => $value) {
		if (!(in_array($value, $listedesuniversitesnew))){
			$sql3 = "DELETE FROM localisation WHERE UNIVERSITE =  :UNIVERSITE";
			$stmt3 = $bdd->prepare($sql3);
			$stmt3->bindParam(':UNIVERSITE', $value, PDO::PARAM_INT);   
			$stmt3->execute();

		}
	}
}
}
?>
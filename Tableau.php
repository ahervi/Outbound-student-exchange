
<?php 
include("modelesql.php");
require "modelemain.php";
$bdd=getAccesBDD();
//Affichage par défaut




  //On prépare les entrées pour la requête
$_GET=preparationvar($_GET);

  //requête en fonction des entrées : les informations laissées par défaut sont gérées dans le WHERE par une condition du type UNIVERSITE==UNIVERSITE qui est toujours vrai.
$query = "SELECT LANGUE,DUREE,TRANSFERT,PAYS,localisation.UNIVERSITE,OPTIONECOLE,ANNEE, particulier.NOM, particulier.PRENOM,CONTENU, DEMARCHE, COMMENTAIRE, ANONYME FROM general JOIN particulier ON (general.ID = particulier.ID) JOIN localisation ON (localisation.UNIVERSITE=general.UNIVERSITE) WHERE  PAYS = " . $_GET['pays'] . " AND TRANSFERT = " . $_GET['transfert'] . " AND DUREE = " . $_GET['duree'] . " AND OPTIONECOLE = " . $_GET['optionecole'] . " AND ANNEE >= " . $_GET['annee'] . " AND localisation.UNIVERSITE = " . $_GET['universite'] . " AND LANGUE = " . $_GET['langue'] . " ORDER BY ANNEE DESC, PAYS , localisation.UNIVERSITE , OPTIONECOLE, TRANSFERT, DUREE";

$reponse = $bdd->query($query);

while ($donnees = $reponse->fetch()){
  $json[] = $donnees;
}

if (isset($json)){
  $reponse->closeCursor(); 
  $json = modificationanonyme($json);
  $j=0;
  foreach($json[0] as $key => $element){
    if(is_string($key)&&($key!='ANONYME')){
      $jsonmieux[0][$j]=$key;
      $j=$j+1;
    }
  }
  $rows=count($json,0);

  for ($i=0;$i<$rows;$i++){
    $j=0;
    foreach($json[0] as $key => $element){
      if (is_string($key)&&($key!='ANONYME')){
        $jsonmieux[$i+1][$j]=$json[$i][$key];
        $j=$j+1;
      }
    }
  }
  echo json_encode($jsonmieux);
}
else{
  echo json_encode(array());
}
?>
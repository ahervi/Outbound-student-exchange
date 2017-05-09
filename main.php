<!DOCTYPE html*>
<html>
<head>
<meta charset="utf-8" />
<link rel="stylesheet" href="MainTemplate.css" />
<link rel="shortcut icon" href="http://www.outboundstudentexchanges.esy.es/favicon.ico" /> 
<!--On se connecte à ajax-->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
<title>OSE</title>
</head>
<body onload="start()">
<?php

include("entete.php");
//Contient toutes les fonctions nécéssaires
require 'modelemain.php';
//Acces à la base de données
require 'modelesql.php'; 
$bdd = getaccesBDD();

//On prend toutes les listes 
$listedesoptions = getlistedesoptions($bdd);
$listedespays = getlistedespays($bdd);
$listedesuniversites = getlistedesuniversites($bdd);
$listedeslangues=getlistedeslangues($bdd); 


?>
<form method="post" action="main.php">
<span class="boites">
<?php 
//On affiche tout les menus
echo '<span class="wrapper">';
echo '<span class="wrapperboiteduhaut">
<span class="boiteduhaut">
<span class="boite">';
getcheckboxtransfert();
echo'</span>';//fin de la classe boite
echo'<span class="boite">';
getcheckboxduree();
echo'</span>';//fin de la classse boite

echo'</span>';//fin de la classe boiteduhaut
echo'</span>';//fin de la classe wrapperboiteduhaut
echo'<span class="wrapperboitedubas">
<span class="boitedubas">
<span class="boite">';
getmenuderoulantpays($listedespays);
echo'</span>';//fin de la classe boite

echo'<span class="boite">';
getmenuderoulantoption($listedesoptions);
echo'</span>';//fin de la classe boite
echo'<span class="boite">';
getmenuderoulantlangue($listedeslangues);
echo'</span>';//fin de la classe boite
echo'<span class="boite">';
getmenuderoulantannee(); 
echo'</span>';//fin de la classe boite
echo'<span class="boite">';
// Ici le javascript va afficher le menu des université qui dépend du pays choisi dans le menu des pays
echo'<p><label for="universite">Dans quelle université ?</label><br />
<select name="universite" id="universite">
</select></p>';
echo'</span>';//fin de la classe boite
echo'</span>';//fin de la classe boitedubas
echo'</span>';//fin de la classe wrapperboitedubas
echo'</span>';//fin de la classe wrapperboitedubas
?>
</span><!--fin de la classe boites-->
<section> 

</form><br />
<span id="tableau">
<!--Ici on va mettre le tableau dynamique en javascript-->
</span><!--fin de l'id tableau-->
</section>
<?php
include("pied_de_page.php");
//On initialise le javascript pour le menu des université (pour le premier chargement de la page )
$univ=(isset($_POST['universite'])) ? $_POST['universite'] : 'tous';

$univnom=(isset($_POST['universite'])) ? $_POST['universite'] : 'Toutes';
//Ce menu permet d'afficher les universités en fonction du pays rentré
require "menudynamiquejavascriptmain.php"; ?>
</body>
</html>
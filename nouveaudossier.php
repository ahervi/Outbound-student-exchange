<!DOCTYPE html*>
<html>
<head>
  <meta charset="utf-8" />
  <link rel="stylesheet" href="MainTemplate.css" />
  <title>OSE nouveau dossier</title>
</head>

<body>

  <?php include("entete.php"); ?>

  <?php
  
require 'modelenouveaudossier.php'; //Contient toutes les fonctions nécéssaires
require 'modelesql.php'; //Pour avoir getAccesBDD
$bdd=getAccesBDD();
//On trouve toutes les listes dont on a besoin pour faire les menus
try{
  //On prend toutes les listes 
  $listedesoptions = getlistedesoptions($bdd);
  $listedespays = getlistedespays($bdd);
  $listedesuniversites = getlistedesuniversites($bdd);
  $listedeslangues=getlistedeslangues($bdd); 
}
catch (Exception $e){
       //Traite le cas de la base de données vide
}
echo'<h1 class ="merci"><em>Merci de contribuer à l\'amélioration du site !</em></h1>
<section>';
// On utilise le format enctype="multipart/form-data" car il y a des uploads de pdf
  echo'<form class="formulaire" method="post" action="nouveaudossier.php" enctype="multipart/form-data">';
  //Si aucunes des données nécéssaires aux update de la base de données principale et secondaire on affiche les menus principaux
  if ((!isset($_POST['pays']))&&(!isset($_POST['contenu']))){
    echo'<p class = "merci">Votre plan de parcours initial et votre plan de parcours final vous seront demandés à la page suivante, vérifiez si vous les avez dès maintenant.<br/> Si vous les avez perdus ou si vous les avez effacés, laissez juste les zones d\'upload vides.</p>';;
    echo'<span class="questions">';
    getchampnewnomprenom();
    getcheckboxanonyme();
    getcheckboxtransfert();
    getcheckboxduree();
    getmenuderoulantannee();
    getmenuderoulantoption($listedesoptions);
    echo"<span class='merci'><p>Si vous avez été dans un pays qui ne figure pas dans la liste choisissez l'option 'J'ai été dans un autre pays'. De même pour l'université ou la langue.</p></span>";
    getmenuderoulantpays($listedespays);
      //On affiche le menu dynamique en fonction du pays sélectionné
    echo'<span class="question">';
    ?>
    <!-- le menus des universités dépend du pays choisi-->
    <p>
      <!--On crée le menu avec seulement la valeur J'ai été dans une autre université. -->
      <label for="universite">Dans quelle université ? (Il faut avoir renseigné le pays pour répondre)</label><br />
      <select name="universite" id="universite">
        <option value="noentry">J'ai été dans une autre université.</option>
      </select>
    </p>
    <?php

    echo'</span>';
    getmenuderoulantlangue($listedeslangues);

      //On affiche le bouton submit
    echo"<input class='subnew' type=\"Submit\" value=\"La suite !\" />
  </form>";
      echo'</span>';//Fin de la class questions
    }

    //On étudie le cas où les menus principaux ont été remplis mais l'utilisateur ne trouve pas son pays, université ou langue
    elseif(isset($_POST['pays'])&&(($_POST['pays']=="noentry")||($_POST['universite']=="noentry")||($_POST['langue']=="noentry"))){
      //Pays inconnu (et donc université inconnue)
      if ($_POST['pays']=="noentry"){ 
      //On enregistre les données des menus principaux (sauf pays, université et langue) pour ne pas provoquer une erreur dans le else ligne 67
        savemaindata();
        echo'<span class="questions">';
        getchampnewpays();
        getchampnewuniversite();
        //Pays inconnu et langue inconnu
        if ($_POST['langue']=="noentry"){
          echo'<span class="questions">';
          getchampnewlangue();
        }
        //Pays inconnu et langue connu
        else{
          echo '<input type="hidden" name="langue" value="' . $_POST['langue'] . '"/>';
        }
      }
      //Pays connu
      else{
        //Pays connu et université inconnue
        if ($_POST['universite']=="noentry"){
          savemaindata();
          echo '<input type="hidden" name="pays" value="' . $_POST['pays'] . '"/>
          <span class="questions">';
            getchampnewuniversite();
        //Pays connu et université inconnue et langue inconnu
            if ($_POST['langue']=="noentry"){
              echo'<span class="questions">';
              getchampnewlangue();
            }
          //Pays connu et université inconnue et langue connu
            else{
              echo '<input type="hidden" name="langue" value="' . $_POST['langue'] . '"/>';
            }
          }
        //Pays connu et université connue 
          else{
            savemaindata(); 
            echo '<input type="hidden" name="pays" value="' . $_POST['pays'] . '"/>';
            echo '<input type="hidden" name="universite" value="' . $_POST['universite'] . '"/>';
          //Pays connu et université connue et langue inconnu
            if ($_POST['langue']=="noentry"){
              echo'<span class="questions">';
              getchampnewlangue();
            }
          //Pays connu et université connue et langue connu
            else{
              echo '<input type="hidden" name="langue" value="' . $_POST['langue'] . '"/>';
            }
          }
        }
      //On affiche le bouton submit
        echo"<input class='subnew' type=\"Submit\" value=\"J'ai fini !\" />
      </form>";
      echo'</span>';
    }
         //On passe aux menus secondaires.
    else{
      //Si les menus secondaires n'ont pas été remplis.
      if (!isset($_POST['commentaire'])){
        //On enregistre les données des menus principaux pour ne pas provoquer une erreur dans le else ligne 67.
        savemaindata(); 
        echo '<input type="hidden" name="pays" value="' . $_POST['pays'] . '"/>
        <input type="hidden" name="universite" value="' . $_POST['universite'] . '"/>
        <input type="hidden" name="langue" value="' . $_POST['langue'] . '"/>';
        //On ouvre les menus secondaires.
        if ($_POST['transfert']=='Transfert de crédit'){


         echo 'Déposer ici votre plan de parcours initial <strong>au format PDF</strong> :
         <input type="file" name="Plan_de_parcours_initial" id="Plan_de_parcours_initial">
         <br /> <br />Déposer ici votre plan de parcours final <strong>au format PDF</strong> : 
         <input type="file" name="Plan_de_parcours_final" id="Plan_de_parcours_final"><br/><br/>(Le dépôt de ces fichiers sur Campus reste obligatoire si vous ne l\'avez pas déjà fait !)<br /><br />';


         getchampnewdemarche();
         echo'<br /><br />
         Le champ suivant est facultatif mais reste important pour les futurs TCistes et DDistes. N\'hésitez pas s\'il vous plaît.<br />
         ';
         getchampnewcommentaire();
        //On affiche le bouton submit
         
       }
       else{
        getchampnewcontenu();
        getchampnewdemarche();
        
        echo'<br /><br />
        Le champ suivant est facultatif mais reste important pour les futurs TCistes et DDistes. N\'hésitez pas s\'il vous plaît.<br />';
        getchampnewcommentaire();
        //On affiche le bouton submit
        
      }
      echo"<input class='subnew' type=\"Submit\" value=\"J'ai fini !\" /></span>
    </form> Veuillez patienter quelques instants après avoir cliqué, vous serez redirigé vers une autre page lorsque vos informations auront été enregistrées.";
  }
  else{
    //Ici on s'interesse au remplissage du contenu de la formation :  sous la forme d'un champ de texte pour les DD et sous la forme de l'upload des palns de parcours initiaux et finaux pour les TC
    if(!isset($_POST['contenu'])){
      $idparticulier = getidparticulier($bdd);
      $idgeneral=getidgeneral($bdd);
      // on vérifie qu'il n'y a pas de désalignement des id de la bdd
      if ($idparticulier==$idgeneral){
        //On fait les uploads
        $initial=uploadpdfinitial($bdd);
        $final=uploadpdffinal($bdd);
        // on renomme les fichiers et on les replace aux bons endroits
        $filename1="Plan_de_parcours_initial_" . $idparticulier . ".pdf";
        $chemin1="Plans_de_parcours_initiaux/" . $filename1 . "";
        $newfilename1="Plan_de_parcours_initial_" . $_POST['universite'] . "_" . $_POST['optionecole'] . "_" . $_POST['annee'] . ".pdf";
        $filename2="Plan_de_parcours_final_" . $idparticulier . ".pdf";
        $chemin2="Plans_de_parcours_finaux/" . $filename2 . "";
        $newfilename2="Plan_de_parcours_final_" . $_POST['universite'] . "_" . $_POST['optionecole'] . "_" . $_POST['annee'] . ".pdf";
        if($initial&&$final){
          $_POST['contenu']='<a href="' . $chemin1 . '" download="' . $newfilename1 . '">Plan de parcours initial</a><br /><a href="' . $chemin2 . '" download="' . $newfilename2 . '">Plan de parcours final</a>';
        }
        elseif($initial&&(!$final)){
          $_POST['contenu']='<a href="' . $chemin1 . '" download="' . $newfilename1 . '">Plan de parcours initial</a>';
        }
        elseif($final&&(!$initial)){
          $_POST['contenu']='<a href="' . $chemin2 . '" download="' . $newfilename2 . '">Plan de parcours final</a>';
        }
        else{
          $_POST['contenu']='Non renseigné';
        }
        majBDD($bdd);
        echo '<h1 class="merci"><em>Vous avez terminé ! Merci beaucoup !</em></h1>';

      }
      //On gère les cas extraordinaires de déclage des id (impossible en théorie mais on ne sait jamais)
      else{
        echo'Une erreur de corruption des bases de données est apparue, contactez le Webmaster de toute urgence.';

// on envoie un mail en cas d'erreur
        $msg = "Erreur de décalage de l'id de la base de données détectée.";

// use wordwrap() if lines are longer than 70 characters
        $msg = wordwrap($msg,70);

// send email
        mail("webmaster.emn.ose@gmail.com","Erreur " . date('r') . "",$msg);

        $_POST['contenu']='EXTREME ERROR';

        
      }

    }

  else{
    $_POST['contenu']=htmlspecialchars($_POST['contenu']);
    majBDD($bdd);
    echo '<h1 class="merci"><em>Vous avez terminez ! Merci beaucoup !</em></h1>';
  }


}

}
echo'</section>';
include("pied_de_page.php");
require "menudynamiquejavascriptnouveaudossier.php"; ?>
<script type="text/javascript">
//Cette fonction evite que on ait l'envoie des données si l'utilisateur appui sur entrée
function noenter(){
  return !(window.event && window.event.keyCode==13);
}

//Ce type de fonction permet de compter les caractères rentrés et d'éviter que ceux ci dépassent une limite choisie (notamment pour éviter les problèmes dans la bdd)
$(document).ready(function () {

  $('#nom').keypress(function (event) {
    var max = 30;
    var len = $(this).val().length;

    if (event.which < 0x20) {
      // e.which < 0x20, then it's not a printable character
      // e.which === 0 - Not a character
      return; // Do nothing
    }

    if (len >= max) {
      event.preventDefault();
      $(this).val($(this).val().substr(0, max));
    }

  });

  $('#nom').keyup(function (event) {
    var max = 30;
    var len = $(this).val().length;
    var char = max - len;

    $('#charNumNom').html('<br />Au plus '+char + ' caractères restants.');

  });
    // Et on recommence pour chaque champ

  });
$(document).ready(function () {

  $('#prenom').keypress(function (event) {
    var max = 30;
    var len = $(this).val().length;

    if (event.which < 0x20) {
      // e.which < 0x20, then it's not a printable character
      // e.which === 0 - Not a character
      return; // Do nothing
    }

    if (len >= max) {
      event.preventDefault();
      $(this).val($(this).val().substr(0, max));
    }

  });

  $('#prenom').keyup(function (event) {
    var max = 30;
    var len = $(this).val().length;
    var char = max - len;

    $('#charNumPrenom').html('<br />Au plus '+char + ' caractères restants.');

  });

});

$(document).ready(function () {

  $('#commentaire').keypress(function (event) {
    var max = 1000;
    var len = $(this).val().length;

    if (event.which < 0x20) {
      // e.which < 0x20, then it's not a printable character
      // e.which === 0 - Not a character
      return; // Do nothing
    }

    if (len >= max) {
      event.preventDefault();
      $(this).val($(this).val().substr(0, max));
    }

  });

  $('#commentaire').keyup(function (event) {
    var max = 1000;
    var len = $(this).val().length;
    var char = max - len;

    $('#charNumCommentaire').html('<br />Au plus '+char + ' caractères restants.');

  });

});

$(document).ready(function () {

  $('#demarche').keypress(function (event) {
    var max = 1000;
    var len = $(this).val().length;

    if (event.which < 0x20) {
      // e.which < 0x20, then it's not a printable character
      // e.which === 0 - Not a character
      return; // Do nothing
    }

    if (len >= max) {
      event.preventDefault();
      $(this).val($(this).val().substr(0, max));
    }

  });

  $('#demarche').keyup(function (event) {
    var max = 1000;
    var len = $(this).val().length;
    var char = max - len;

    $('#charNumDemarche').html('<br />Au plus '+char + ' caractères restants.');

  });

});

$(document).ready(function () {

  $('#contenu').keypress(function (event) {
    var max = 2000;
    var len = $(this).val().length;

    if (event.which < 0x20) {
      // e.which < 0x20, then it's not a printable character
      // e.which === 0 - Not a character
      return; // Do nothing
    }

    if (len >= max) {
      event.preventDefault();
      $(this).val($(this).val().substr(0, max));
    }

  });

  $('#contenu').keyup(function (event) {
    var max = 2000;
    var len = $(this).val().length;
    var char = max - len;

    $('#charNumContenu').html('<br />Au plus '+char + ' caractères restants.');

  });

});

$(document).ready(function () {

  $('#pays').keypress(function (event) {
    var max = 20;
    var len = $(this).val().length;

    if (event.which < 0x20) {
      // e.which < 0x20, then it's not a printable character
      // e.which === 0 - Not a character
      return; // Do nothing
    }

    if (len >= max) {
      event.preventDefault();
      $(this).val($(this).val().substr(0, max));
    }

  });

  $('#pays').keyup(function (event) {
    var max = 20;
    var len = $(this).val().length;
    var char = max - len;

    $('#charNumPays').html('<br />Au plus '+char + ' caractères restants.');

  });

});

$(document).ready(function () {

  $('#langue').keypress(function (event) {
    var max = 20;
    var len = $(this).val().length;

    if (event.which < 0x20) {
      // e.which < 0x20, then it's not a printable character
      // e.which === 0 - Not a character
      return; // Do nothing
    }

    if (len >= max) {
      event.preventDefault();
      $(this).val($(this).val().substr(0, max));
    }

  });

  $('#langue').keyup(function (event) {
    var max = 20;
    var len = $(this).val().length;
    var char = max - len;

    $('#charNumLangue').html('<br />Au plus '+char + ' caractères restants.');

  });

});

$(document).ready(function () {

  $('#universite').keypress(function (event) {
    var max = 60;
    var len = $(this).val().length;

    if (event.which < 0x20) {
      // e.which < 0x20, then it's not a printable character
      // e.which === 0 - Not a character
      return; // Do nothing
    }

    if (len >= max) {
      event.preventDefault();
      $(this).val($(this).val().substr(0, max));
    }

  });

  $('#universite').keyup(function (event) {
    var max = 60;
    var len = $(this).val().length;
    var char = max - len;

    $('#charNumUniversite').html('<br />Au plus '+char + ' caractères restants.');

  });

});
</script>
</body>
</html>

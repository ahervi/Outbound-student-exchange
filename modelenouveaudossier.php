<?php
function uploadpdfinitial($bdd){
    $result = false;
    $target_dir = "Plans_de_parcours_initiaux/";
    $id=getidparticulier($bdd);
    $_FILES["Plan_de_parcours_initial"]["name"]="Plan_de_parcours_initial_" . $id . ".pdf";
    
    $target_file = $target_dir . basename($_FILES["Plan_de_parcours_initial"]["name"]);
    if(is_uploaded_file($_FILES['Plan_de_parcours_initial']['tmp_name'])){
        // création de l'objet finfo
        $infos = new finfo(FILEINFO_MIME);
        //récupération des infos du fichier
        $type = $infos->file($_FILES['Plan_de_parcours_initial']['tmp_name']);
        //extraction du type MIME
        $mime = substr($type, 0, strpos($type, ';'));

        if($mime === 'application/pdf'){ 

            if (!($_FILES["Plan_de_parcours_initial"]["size"] > 500000)) {

                $result =  move_uploaded_file($_FILES['Plan_de_parcours_initial']['tmp_name'],$target_file);

                
            }
            
        }   
        
    }

    return $result;
}

function uploadpdffinal($bdd){
    $result = false;
    $target_dir = "Plans_de_parcours_finaux/";
    $id=getidparticulier($bdd);
    $_FILES["Plan_de_parcours_final"]["name"]="Plan_de_parcours_final_" . $id . ".pdf";

    $target_file = $target_dir . basename($_FILES["Plan_de_parcours_final"]["name"]);
    if(is_uploaded_file($_FILES['Plan_de_parcours_final']['tmp_name'])){
        // création de l'objet finfo
        $infos = new finfo(FILEINFO_MIME);
        //récupération des infos du fichier
        $type = $infos->file($_FILES['Plan_de_parcours_final']['tmp_name']);
        //extraction du type MIME
        $mime = substr($type, 0, strpos($type, ';'));
        if($mime === 'application/pdf'){ 

            if (!($_FILES["Plan_de_parcours_final"]["size"] > 500000)) {

              $result =  move_uploaded_file($_FILES['Plan_de_parcours_final']['tmp_name'],$target_file);


          }

      }   

  }

  return $result;
}

function getidgeneral($bdd){
    $query = "SELECT ID FROM general ORDER BY ID DESC LIMIT 1 ";
    $reponse = $bdd->query($query);
    if($donnees = $reponse->fetch()){
        $id = $donnees['ID'];
        $reponse->closeCursor(); 
    }
    if (!(isset($id))){$id=0;}
    return $id+1;
}
function getidparticulier($bdd){
    $query = "SELECT ID FROM particulier ORDER BY ID DESC LIMIT 1 ";
    $reponse = $bdd->query($query);
    if($donnees = $reponse->fetch()){
        $id = $donnees['ID'];
        $reponse->closeCursor(); 
    }
    if (!(isset($id))){$id=0;}
    return $id+1;
}
//Sauvegarde les données des menus principaux
function savemaindata(){
 echo '<input type="hidden" name="annee" value="' . $_POST['annee'] . '"/>
 <input type="hidden" name="duree" value="' . $_POST['duree'] . '"/>
 <input type="hidden" name="transfert" value="' . $_POST['transfert'] . '"/>
 <input type="hidden" name="optionecole" value="' . $_POST['optionecole'] . '"/>
 <input type="hidden" name="nom" value="' . $_POST['nom'] . '"/>
 <input type="hidden" name="prenom" value="' . $_POST['prenom'] . '"/>
 <input type="hidden" name="anonyme" value="' . $_POST['anonyme'] . '"/>';   
}

function getlistedesuniversites($bdd){
    $listedesuniversites=array();
    $reponse = $bdd->query('SELECT DISTINCT UNIVERSITE FROM localisation ORDER BY UNIVERSITE ASC');
    while ($donnees = $reponse->fetch()){
        $listedesuniversites[] = $donnees['UNIVERSITE'];
    }
    $reponse->closeCursor(); 

    return $listedesuniversites;
}

function getlistedesoptions(){
    $listedesoptions=array();
    $listedesoptions[]='AII';
    $listedesoptions[]='GE';
    $listedesoptions[]='GOPL';
    $listedesoptions[]='GIPAD';
    $listedesoptions[]='GSE';
    $listedesoptions[]='GSI';
    $listedesoptions[]='MPR (QSF)';
    $listedesoptions[]='NTSE';
    $listedesoptions[]='OMTI';
    $listedesoptions[]='SEE';
    $listedesoptions[]='STAR';

    return $listedesoptions;
}



function getlistedespays($bdd){
    $listedespays=array();
    $reponse = $bdd->query('SELECT DISTINCT PAYS FROM localisation ORDER BY PAYS ASC');
    while ($donnees = $reponse->fetch()){
       $listedespays[] = $donnees['PAYS'];
   }
   $reponse->closeCursor(); 

   return $listedespays;
}

function getlistedeslangues($bdd){
    $listedeslangues=array();
    $reponse = $bdd->query('SELECT DISTINCT LANGUE FROM general ORDER BY LANGUE ASC');
    while ($donnees = $reponse->fetch()){
       $listedeslangues[] = $donnees['LANGUE'];
   }
   $reponse->closeCursor();

   return $listedeslangues;
}

function majBDD($bdd){
    $req = $bdd->prepare('INSERT INTO general(LANGUE, DUREE, TRANSFERT, UNIVERSITE, OPTIONECOLE, ANNEE) VALUES(:LANGUE, :DUREE,:TRANSFERT, :UNIVERSITE, :OPTIONECOLE,:ANNEE)');

    $req->execute(array(
        'LANGUE' => htmlspecialchars($_POST['langue']),
        'DUREE' => $_POST['duree'],
        'TRANSFERT' => $_POST['transfert'],
        'UNIVERSITE' => htmlspecialchars($_POST['universite']),
        'OPTIONECOLE' => htmlspecialchars($_POST['optionecole']),
        'ANNEE' => $_POST['annee']
        ));

    $req2 = $bdd->prepare('INSERT INTO particulier(NOM, PRENOM, ANONYME,CONTENU,DEMARCHE,COMMENTAIRE) VALUES(:NOM, :PRENOM,:ANONYME,:CONTENU,:DEMARCHE,:COMMENTAIRE)');

    $req2->execute(array(
        'NOM' => htmlspecialchars($_POST['nom']), 
        'PRENOM' => htmlspecialchars($_POST['prenom']),
        'ANONYME' => $_POST['anonyme'],
        'CONTENU' => $_POST['contenu'],
        'DEMARCHE' => htmlspecialchars(($_POST['demarche']!='') ? $_POST['demarche']:'Non renseignée'), 
        'COMMENTAIRE' => htmlspecialchars(($_POST['commentaire']!='') ? $_POST['commentaire']:'Non renseigné')
        ));

    $universiteDansLocalisation=true;
    $query = 'SELECT UNIVERSITE FROM localisation';
    $reponse = $bdd->query($query);

    while ($donnees = $reponse->fetch()) {

        $universiteDansLocalisation= $universiteDansLocalisation&&($donnees['UNIVERSITE']!=$_POST['universite']);
    }
    $reponse->closeCursor(); 

    if ($universiteDansLocalisation){

        $req3 = $bdd->prepare('INSERT INTO localisation(PAYS,UNIVERSITE) VALUES(:PAYS,:UNIVERSITE)');

        $req3->execute(array(
            'PAYS' => htmlspecialchars($_POST['pays']), 
            'UNIVERSITE' => htmlspecialchars($_POST['universite'])
            ));
    }

}

function getmenuderoulantannee(){
	echo '<span class="question">
	<p>
        <label for="annee">En quelle année avez-vous fait votre TC ?</label><br />
        <select name="annee" id="annee">';
            for($i=date(Y);$i>date(Y)-5;$i--){
             echo '<option value="'. $i .'">' . $i . '</option>';
         }
         echo '</select></p></span>';
     }

     function getmenuderoulantoption($listedesoptions){
         echo '<span class="question"><p>
         <label for="option">Quelle est votre option ?</label><br />
         <select name="optionecole" id="optionecole">';      
            for($i=0;$i<count($listedesoptions);$i++){
             echo '<option value="'. $listedesoptions[$i] .'">' . $listedesoptions[$i] . '</option>';
         }
         echo'</select></p></span>';
     }

     function getmenuderoulantpays($listedespays){
         echo '<span class="question"><p>
         <label for="pays">Dans quel pays êtes-vous parti ?</label><br />
         <select name="pays" id="pays">
            <option value="noentry">J\'ai été dans un autre pays.</option>';
            for($i=0;$i<count($listedespays);$i++){
             echo '<option value="'. $listedespays[$i] .'">' . $listedespays[$i] . '</option>';
         }
         echo'</select></p></span>';
     }

     function getmenuderoulantlangue($listedeslangues){
         echo '<span class="question"><p>
         <label for="langue">Dans quelle langue avez-vous reçu vos cours ?</label><br />
         <select name="langue" id="langue">
            <option value="noentry">J\'ai eu mes cours dans une autre langue.</option>';
            for($i=0;$i<count($listedeslangues);$i++){
             echo '<option value="'. $listedeslangues[$i] .'">' . $listedeslangues[$i] . '</option>';
         }
         echo'</select></p></span>';
     }

     function getchampnewpays(){
         echo '<span class="question"><p>
         <label for="pays">Dans quel pays êtes-vous parti ?</label>
         <input type="text" name="pays" id="pays" placeholder="Espagne" size="30" maxlength="100" onkeypress="return noenter()" required/><span id="charNumPays"></span></p></span>';
     }

     function getchampnewuniversite(){
         echo '<span class="question">
         <p>
            <label for="universite">Dans quelle université ?</label><br/>
            <input type="text" name="universite" id="universite" placeholder="Universidad de Madrid" size="30" maxlength="100" onkeypress="return noenter()" required/><span id="charNumUniversite"></span></p></span>';
        }

        function getchampnewlangue(){
         echo '<span class="question">
         <p>
            <label for="langue">Dans quelle langue avez-vous reçu vos cours ?</label>
            <input type="text" name="langue" id="langue" placeholder="Espagnol" size="30" maxlength="100" onkeypress="return noenter()" required/><span id ="charNumLangue"></span></p></span>';
        }
        function getchampnewnomprenom(){
         echo '<span class="question"><p>
            <label for="prenom">Quel est votre prénom ?</label>
            <br /><input type="text" name="prenom" id="prenom" placeholder="Antoine" size="30" maxlength="100" required onkeypress="return noenter()"/><span id="charNumPrenom"></span></p>
         <p>
         <label for="nom">Quel est votre nom ?</label>
         <br /><input type="text" name="nom" id="nom" placeholder="Hervieu" size="30" maxlength="100" required onkeypress="return noenter()"/><span id="charNumNom"></span></p>
         </span>';
        }

        function getchampnewcontenu(){
         echo '<p>
         <label for="contenu">Quels cours avez-vous reçu ?</label><br />
         <textarea cols ="80" rows="15" name="contenu" id="contenu" required></textarea>
         <span id="charNumContenu"></span></p>';
     }

     function getchampnewcommentaire(){
        echo '<p>
        <label for="commentaire"><ul>Ici, vous pouvez écrire tout ce que vous voulez : <li>Si ça vous a plu</li> <li>Une astuce que vous auriez aimé connaître avant de partir</li> <li>Autre...</li>
        </ul>
    </label>
    <textarea cols ="80" rows="15" name="commentaire" id="commentaire"></textarea>
    <span id ="charNumCommentaire"></span></p>';
}
function getchampnewdemarche(){
	echo '<p>
    <label for="demarche">Expliquez ici la démarche administrative qui vous a permis de partir :</label><br />
    
    <textarea cols ="80" rows="15" name="demarche" id="demarche" placeholder="J\'ai vu que les cours m\'intéressais sur le site de l\'université. J\'ai pris contact avec le mail de ce même site puis j\'ai complété le dossier en allant voir la Graduate quand je ne savais pas comment remplir certains passage."></textarea>
    <span id="charNumDemarche"></span></p>';
}

function getcheckboxtransfert(){
    echo'<span class="question">
    <p>
       Avez-vous fait un TC ou un DD ?<br />
       <input type="radio" name="transfert" value="Transfert de crédit" id="Transfert de crédit" checked/> <label for="Transfert de crédit">Transfert de crédit</label><br />
       <input type="radio" name="transfert" value="Double diplôme" id="Double diplôme" /> <label for="Double diplôme">Double diplôme</label><br />
   </p></span>
   ';
}
function getcheckboxanonyme(){
    echo'<span class="question">
    <p>
        Souhaitez-vous rester anonyme ?<br />
        <input type="radio" name="anonyme" value="1" id="Oui" /> <label for="Oui">Je souhaite rester anonyme.</label><br />
        <input type="radio" name="anonyme" value="0" id="Non" checked/> <label for="Non">Je veux bien être contacté par les newfs.</label><br />
    </p></span>';
}

function getcheckboxduree(){
	echo'<span class="question">
    <p>
       Combien de temps êtes-vous parti ?<br />
       <input type="radio" name="duree" value="Un semestre" id="Un semestre" checked/> <label for="Un semestre">Un semestre</label><br />
       <input type="radio" name="duree" value="Un an" id="Un an" /> <label for="Un an">Un an</label><br />
       <input type="radio" name="duree" value="Deux ans ou plus" id="Deux ans ou plus"/> <label for="Deux ans ou plus">Deux ans ou plus</label><br />
   </p></span>';
}
?>
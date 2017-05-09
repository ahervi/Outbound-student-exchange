<?php
//La fonction getliste renvoie la liste associée à une requète dans la base de donnée, si la requète ne renvoie rien getliste affiche un message
function getliste($bdd,$query){
    $reponse = $bdd->query($query);
    while ($donnees = $reponse->fetch()){
        $liste[]=$donnees;
    }
    $reponse->closeCursor(); 

    if (isset($liste)){
        return $liste;
    }

    else{
        echo "<p class='rien' ><br /><strong>La recherche n'a pas retourné de résultat.</strong></p>";
    }

}
//Permet de transformer les noms et prénoms des personnes souhaitant rester anonymes
function modificationanonyme($liste){
    $rows = count($liste,0);
    for ($i=1;$i<$rows;$i++){
        if ($liste[$i]['ANONYME']=='1'){
            $liste[$i]['NOM']='Anonyme';
            $liste[$i]['PRENOM']='Anonyme';
        }
    }
    return $liste;
}
//Construt un tableau html à partir d'une requete dans la base de données
function gettableau($bdd,$query){

    $liste=getliste($bdd,$query);
    $liste=modificationanonyme($liste);
    $rows =count($liste,0);
    echo "<table>";
    echo "<tr>";
    if(isset($liste)){
        foreach($liste[0] as $key => $element){
            if (is_string($key)&&($key!='ANONYME')){
                echo "<th>";
                echo $key =($key=='OPTIONECOLE') ? 'OPTION' : $key;
                echo "</th>";
            }
        }   
        echo " </tr>";
        for ($i=0;$i<$rows;$i++){
            echo "<tr>";
            foreach($liste[0] as $key => $element){
                if (is_string($key)&&($key!='ANONYME')){
                    echo "<td>";
                    echo $liste[$i][$key];
                    echo "</td>";
                }
            }
            echo"</tr>";
        }
        echo"</table>";
    }
}
//L'emploi du php permet de sélectionner la valeur par défaut selon la valeur sélectionnée précemment (initialisée à tous).
function getcheckboxtransfert(){
    ?>
    <p>TC ou DD ?<br />

        <input checked type="radio" name="transfert" value="tous" id="toustransfert"
        /> <label for="toustransfert">Les deux</label><br />

        <input type="radio" name="transfert" value="Transfert de crédit" id="Transfert de crédit"  
        /> <label for="Transfert de crédit">Transfert de crédit</label><br />

        <input type="radio" name="transfert" value="Double diplôme" id="Double diplôme"/> <label for="Double diplôme">Double diplôme</label><br /><br/>
    </p>
    <?php
}

function getcheckboxduree(){

    ?><p>Pour combien de temps ?<br />

    <input checked type="radio" name="duree" value="tous" id="tousduree"/> <label for="tousduree">Toutes durées</label><br />

    <input type="radio" name="duree" value="Un semestre" id="Un semestre"/> <label for="Un semestre">Un semestre</label><br />

    <input type="radio" name="duree" value="Un an" id="Un an"/> <label for="Un an">Un an</label><br />
    <input type="radio" name="duree" value="Deux ans ou plus" id="Deux ans ou plus"/> <label for="Deux ans ou plus">Deux ans ou plus</label><br />
</p>
<?php
}
//Les fonctions getliste renvoient des listes associées aux noms des fonctions
function getlistedesuniversites($bdd){
    $listedesuniversites=array();
    $reponse = $bdd->query('SELECT DISTINCT UNIVERSITE FROM general ORDER BY UNIVERSITE ASC');
    while ($donnees = $reponse->fetch()){
        $listedesuniversites[] = $donnees['UNIVERSITE'];
    }
    $reponse->closeCursor(); 
    return $listedesuniversites;
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
//Permet de faire des requêtes qui peuvent prendre en copte la sélection de l'option tous.
function preparationvar($entree){
    foreach ($entree as $key => $element){
        if ($element=="tous"){
            if($key!="universite"){
                $entree[$key]=strtoupper($key);
            }
            else{
                $entree[$key]="localisation." . strtoupper($key) . "";
            }
        }
        
        else{
            $entree[$key]="'$entree[$key]'";
        }
    }
    return $entree;
}
//Affiche les informations sélectionnées par l'utilisateur
function displayinfo($entree){
    echo"En sélectionnant les informations suivantes :<ul>";
    foreach ($entree as $key => $element){
     if ($element!='tous'){
        $key=($key=='optionecole') ? 'option' : $key;
        echo "<li>" . strtoupper($key) ." = " . $element . "</li>";
    }
}
echo "</ul>";
}

//Le php permet de faire la même chose que pour les checkbox
function getmenuderoulantannee(){
    ?>
    <!-- ON CREE UN MENU DEROULANT POUR LES ANNEES-->
    <p>
        <label for="annee">A partir de quelle année ?</label><br />
        <select name="annee" id="annee">
            <?php
            for($i=date('Y');$i>date('Y')-3;$i--){
                echo '<option value="'. $i .'">' . $i . '</option>';
            }
            $anneeactuellemoins4=date('Y')-3;
            echo '<option value="'. $anneeactuellemoins4 .'" selected>' . $anneeactuellemoins4 . '</option>';
            for($i=date('Y')-4;$i>date('Y')-5;$i--){
                echo '<option value="'. $i .'">' . $i . '</option>';
            }
            echo '</select></p>';
        }

        function getmenuderoulantoption($listedesoptions){
            echo '<p>
            <label for="option">Quelle option ?</label><br />
            <select name="optionecole" id="optionecole">';
                echo'<option value="tous" selected>Toutes</option>';     
                for($i=0;$i<count($listedesoptions);$i++){ 
                    echo '<option value="'. $listedesoptions[$i] .'">' . $listedesoptions[$i] . '</option>';
                }
                echo'</select></p>';
            }

            function getmenuderoulantpays($listedespays){
                echo '<p>
                <label for="pays">Quel pays ?</label><br />
                <select name="pays" id="pays">';
                    echo'<option value="tous" selected>Tous</option>';
                    for($i=0;$i<count($listedespays);$i++){
                        echo '<option value="'. $listedespays[$i] .'">' . $listedespays[$i] . '</option>';
                    }
                    echo'</select></p>';
                }

/*function getmenuderoulantuniversite($listedesuniversites){
    echo '<p>
    <label for="universite">Quelle université ?</label><br />
    <select name="universite" id="universite">
    ';
    echo'<option value="tous">Toutes</option>';
    for($i=0;$i<count($listedesuniversites);$i++){
        $check = (isset($_POST['universite'])&&$_POST['universite']==$i)  ? 'selected' : '';
        echo '<option value="'. $listedesuniversites[$i] .'" ' . $check . '>' . $listedesuniversites[$i] . '</option>';
     }
     echo'</select></p>';
 }*/

 function getmenuderoulantlangue($listedeslangues){
    echo '<p>
    <label for="langue">Quelle langue pour vos cours ?</label><br />
    <select name="langue" id="langue">';
        echo'<option value="tous" selected>Tous</option>';
        for($i=0;$i<count($listedeslangues);$i++){
         echo '<option value="'. $listedeslangues[$i] .'">' . $listedeslangues[$i] . '</option>';
     }
     echo'</select></p>';
 }
 ?>
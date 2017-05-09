<!DOCTYPE html>
<html>

<!--A chaque chargment de la page on actualise les universités en fonction du pays rentré-->



<!--Ce script permet d'obtenir les universités en fonction du pays -->
<script>
/*On initialise le menu déroulant des universités avec les valeurs de la pages précédentes si elles existent et sinon on pren par défaut le choix tous.
Ceci permet de créer un menu dont la fonction updateUniversites va pouvoir prendre la valeur, ce qui va éviter un mélange php javascript douteux
On sécurise les entrées pour éviter les failles XSS*/
$('#universite').append('<option value="'+ <?php echo json_encode($univ); ?>+'">'+ <?php echo json_encode($univnom); ?>+'</option>');
//Cette fonction permet d'obtenir la liste des universités en fonction du pays mis dans valpays
function updateUniversites() {
	var $universite = $('#universite');
	var valpays = $('#pays').val();
	var valuniversite = $universite.val();

	if(valpays != "tous") {
		$universite.empty(); // on vide la liste des universites
		
		$.ajax({
			url: 'Universites.php',
			data: 'pays=' + valpays,
			dataType: 'json',
			error: function(){
				document.getElementById("tableau").innerHTML = "Une erreur est survenue, contactez le webmaster.";
			},
			success: function(json) {

				$universite.append('<option value="tous" selected>Toutes</option>');
				$.each(json, function(index, value) {
					//la variable check sert comme d'habitude à garder la sélection après la recherche
					
					$universite.append('<option value="'+ value+'">'+ value+'</option>'); // on remplie le menu deroulant des universites grace au tableau retourné par Universite.php
					
				});
				
			}

			
		});
	}
		//Si l'utilisateur choisit tous pour pays, le menus des universites présentent tout les choix possibles
		else{
		$universite.empty(); // on vide la liste des universites
		var listesdesuniversitesjs = <?php echo json_encode($listedesuniversites); ?>;
		$universite.append('<option value="tous" selected>Toutes</option>');
		
		$.each(listesdesuniversitesjs, function(index, value) {
			$universite.append('<option value="'+ value+'">'+ value+'</option>'); // on remplie le menu deroulant des universites grace au tableau retourné par Universites.php
			
		});
	}
}
// à la sélection d'un pays dans chaque menu deroulant on change les universités
$('#pays').on('change', function() {
	updateUniversites();
});

function start(){
	updateUniversites();
	updateTableau();
}

function updateTableau() {

	var valpays = $('#pays').val();
	var valuniversite = ($('#universite').val()===null) ? 'tous' : $('#universite').val();
	var valannee = $('#annee').val();
	var valoptionecole = $('#optionecole').val();
	var valtransfert = $("input[name='transfert']:checked").val();
	var vallangue = $('#langue').val();
	var valduree = $("input[name='duree']:checked").val();
	//on utilise ajax parceque on fait des requetes sans recharges
	$.ajax({
		url: 'Tableau.php',
		data: 'pays=' + valpays + '&universite=' + valuniversite + '&duree=' + valduree + '&annee=' + valannee + '&optionecole=' + valoptionecole + '&transfert=' + valtransfert + '&langue=' + vallangue,
		dataType: 'json',
		
			// Le code suivant permet de mieux détecter les errurs de ajax:
		/*error:function(XMLHttpRequest, textStatus, errorThrown) { 
        	alert("Status: " + textStatus); alert("Error: " + errorThrown);
        	document.getElementById("tableau").innerHTML = "Une erreur est survenue, contactez le webmaster.";
        }*/
        error:function() { 
        	document.getElementById("tableau").innerHTML = "Une erreur est survenue, contactez le webmaster.";
        },
        success: function(json){
        	if (json != ''){
        		document.getElementById("tableau").innerHTML = "";
        		$('<table>').appendTo('#tableau');
        		$('<tr>').appendTo('table');
        		for (j = 0;j<json[0].length;j++){
        			$('<th>').text((json[0][j]!='OPTIONECOLE') ? ((json[0][j]!='LANGUE') ? json[0][j] : 'LANGUE DES COURS') : 'OPTION').appendTo('tr:last');
        		}
        		for (i = 1;i<json.length;i++){	
        			$('<tr>').appendTo('table');
        			for (j = 0;j<json[0].length;j++){
        				$('<td>').html('<div style="max-height:400px; overflow:auto">' + json[i][j]+'</div>').appendTo('tr:last');	
        			}
        			
        		}
        		
        	}
        	//On gère le cas où la recherche ne retourne rien
        	else{
        		document.getElementById("tableau").innerHTML = "La recherche n'a retourné aucun résultat.";
        	}
        }
    });	
}




// à la sélection d'un pays dans chaque menu deroulant on change les universités
$('#pays').on('change', function() {
	updateTableau();
});
$('#universite').on('change', function() {
	updateTableau();
});
$('#optionecole').on('change', function() {
	updateTableau();
});
$('#duree').on('change', function() {
	updateTableau();
});
$('#transfert').on('change', function() {
	updateTableau();
});
$('#annee').on('change', function() {
	updateTableau();
});
$('#langue').on('change', function() {
	updateTableau();
});

$(function(){

	$('input:radio').change(function(){
		updateTableau();   
	});          

});
</script>

</html>
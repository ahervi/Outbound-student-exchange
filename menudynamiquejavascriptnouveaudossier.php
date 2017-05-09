<!DOCTYPE html>
<html>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>


<script>
	var $universite = $('#universite');
	
	function updateUniversites() {
		var val1 = $('#pays').val();
		

		if(val1 != "noentry") {
			$universite.empty(); // on vide la liste des universités
			
			$.ajax({
				url: 'Universites.php',
				data: 'pays=' + val1, //on envoie les données en GET à Universites.php qui va recupérer la valeur du pays
				dataType: 'json',
				error: function(){document.write("Une erreur est survenue, contactez le webmaster.")},
				success: function(json) {
					//On remet la valeur J'ai été dans une autre université.
					$universite.append('<option value="noentry" selected>J\'ai été dans une autre université.</option>');
					$.each(json, function(index, value) {
						$universite.append('<option value="'+ value +'">'+ value +'</option>'); // on remplie le menu deroulant des universités grace au tableau retourné par Universites.php
						
					});
					
				}
				
			});
		}
		else{
			// Si on met pays à J'ai été dans un autre pays, le menu universite ne contient plus que J'ai été dans une autre université.
			$universite.empty(); // on vide la liste des universités
			$universite.append('<option value="noentry" selected>J\'ai été dans une autre université.</option>');
		}
	}
	
	// à la sélection d'une option dans le menu deroulant des pays
	$('#pays').on('change', function() {
		updateUniversites();
	});
	


</script>



</html>
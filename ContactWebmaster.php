<!DOCTYPE html*>
<html>
<head>
	<meta charset="utf-8" />  
	<link rel="stylesheet" href="MainTemplate.css" />
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
	<title>OSE Contact</title>
</head>
<body>
	<?php include("entete.php"); ?>
	<section>
		<?php if (!(isset($_POST['contact']))){ ?>
		
		<form class="formulairecontact" method="post" action="ContactWebmaster.php">
			<p><label for="contact">Un bug ? Un problème ? Un compliment ? Une déclaration d'amour ?<br /><br />
				Envoyer un message au webmaster : </label><br />
				<textarea cols ="80" rows="15" name="contact" id="contact"></textarea><span id ="charNumContact"></span></p>
				<input class='subnew' type="Submit" value="J'envoie mon message !" />
			</form>
			<?php } 
			else{ ?>
			<h1 class="merci">Merci pour votre message !</h1>
		<?php // the message
		$msg = htmlspecialchars($_POST['contact']);

// use wordwrap() if lines are longer than 70 characters
		$msg = wordwrap($msg,70);

// send email
		mail("webmaster.emn.ose@gmail.com","Message " . date('r') . "",$msg);
	} ?>
</section>
<?php include("pied_de_page.php"); ?>
<script type="text/javascript">
	$(document).ready(function () {

		$('#contact').keypress(function (event) {

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

		$('#contact').keyup(function (event) {
			var max = 1000;
			var len = $(this).val().length;
			var char = max - len;
			
			$('#charNumContact').html('<br />Au plus '+char + ' caractères restants.');

		});

	});
</script>
</body>

</html>

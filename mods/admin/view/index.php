<!DOCTYPE html>
<html lang="fr">
	<head>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1" />
		<link href="/tools/bootstrap/css/bootstrap.min.css" rel="stylesheet" />
		<?php echo($head); ?>

		<?php include($templatePath.'head.php');?>	
	</head>
	<body class="<?php echo($stylePage); ?>">
		
		<?php include($templatePath.'body.php');?>	
	
		<script type="text/javascript" src="tools/jquery.min.js"></script>	
		<script type="text/javascript" src="core/controller/nav.js"></script>
		<script type="text/javascript" src="core/controller/plasmide.js"></script>
		<script type="text/javascript" src="tools/bootstrap/js/bootstrap.min.js"></script>
		<?php echo($scripts); ?>	
		<script type="text/javascript" >
			$( document ).ready(function() {
				plasmide.start();			
			});		
		</script>	
	</body>	
</html>

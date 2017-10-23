<!DOCTYPE html>
<html lang="fr">
	<head>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<link href="/tools/bootstrap/css/bootstrap.min.css" rel="stylesheet" />
		<?php echo($head);?>
		<link rel="shortcut icon" href="/mods/admin/style/images/plasmide.png" />	
		<link rel="stylesheet" href="/tools/jquery-ui/jquery-ui.theme.min.css" type="text/css" media="screen" >
		<link rel="stylesheet" href="/tools/jquery-ui/jquery-ui.min.css" type="text/css" media="screen" >
		<link rel="stylesheet" href="/tools/elfinder/css/elfinder.min.css" type="text/css" media="screen"  />
		<link href="/mods/admin/style/admin.css" rel="stylesheet" type="text/css" media="screen" />		
		<title>Administration</title>

	</head>
	<body class="<?php echo($stylePage); ?>">
		
		        <div id="barreAdmin"></div>	
		
		        <div class="container">			
		            <div id="conteneurPage" class="pageAdmin">
			        <!-- ici les articles -->				
		            </div> 			
		        </div>	

		<footer>
                    <div class="d-flex justify-content-center">
		        <div class="p-1">
                            <a href="http://plasmide.selenith.ovh" target="_blank"><img src="mods/admin/style/images/plasmide.png" />Plasmide</a>  - Créé par <a href="http://selenith.ovh" target="_blank">Selenith</a>
                        </div>
                    </div>
                </footer>
		
		<script type="text/javascript" src="/tools/jquery.min.js"></script>
		<script type="text/javascript" src="/tools/popper.min.js"></script>	
		<script type="text/javascript" src="/tools/bootstrap/js/bootstrap.min.js"></script>
		<script type="text/javascript" src="/core/controller/nav.js"></script>
		<script type="text/javascript" src="/core/controller/plasmide.js"></script>	
		<?php echo($scripts); ?>	
		<script type="text/javascript" >
			$( document ).ready(function() {
				plasmide.start();			
			});		
		</script>	
	</body>	
</html>

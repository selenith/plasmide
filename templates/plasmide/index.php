<!DOCTYPE html>
<html lang="fr">
	<head>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<link href="/tools/bootstrap/css/bootstrap.min.css" rel="stylesheet" />		
		<link href="/<?php echo($templatePath);?>style/style.css" rel="stylesheet" type="text/css" media="screen" />
		<link href="/<?php echo($templatePath);?>style/articles.css" rel="stylesheet" type="text/css" media="screen" />
		<link rel="shortcut icon" href="/<?php echo($templatePath);?>style/images/favicon.png" />
		
		<?php echo($head); ?>	
			
	</head>
	<body class="<?php echo($stylePage); ?>">		
		
				
		<nav class="navbar navbar-expand-lg navbar-light bg-light border mb-2">
		  <a class="navbar-brand" href="/">
		    <img src="/<?php echo($templatePath);?>style/images/header.png" width="90" height="auto" alt="Plasmide">
		  </a>
		  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
		    <span class="navbar-toggler-icon"></span>
		  </button>

		  <div class="collapse navbar-collapse" id="navbarSupportedContent">
		    <ul class="navbar-nav ml-auto">
		      <?php echo($menu); ?>
		    </ul>
		  </div>
		</nav>
		<div class="container" id="conteneurPage" >				
			<!-- ici les articles -->					
			<?php  echo($body);?>									
		</div> 	
				
		
		<footer>
                    <div class="d-flex justify-content-between">			
			<div class="p-1"><a  href="/admin"> Administration</a> </div>
            		<div class="p-1"><a href="http://plasmide.selenth.ovh"  target="_blank">Plasmide</a> de <a href="http://selenith.ovh"  target="_blank">Selenith</a> est mis à disposition selon les termes de la <a  href="http://www.gnu.org/licenses/gpl.html" target="_blank">Licence GNU GPLv3</a>.</div>
			 <div class="p-1"><a  href="/feed/index.xml"><img title="RSS" alt="RSS" src="/<?php echo($templatePath);?>style/images/rss32.png"></a> </div>
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


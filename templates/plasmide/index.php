<!DOCTYPE html>
<html lang="fr">
	<head>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1" />
		<link href="/tools/bootstrap/css/bootstrap.min.css" rel="stylesheet" />		
		<link href="/<?php echo($templatePath);?>style/style.css" rel="stylesheet" type="text/css" media="screen" />
		<link href="/<?php echo($templatePath);?>style/articles.css" rel="stylesheet" type="text/css" media="screen" />
		<link rel="shortcut icon" href="/<?php echo($templatePath);?>style/images/favicon.png" />
		
		<?php echo($head); ?>	
			
	</head>
	<body class="<?php echo($stylePage); ?>">		
		<div class="wrap">
			<div class="clearfix mainContainer">
				<!-- barre navigation -->
				<nav class="navbar navbar-default navbar-static-top">
					<div class="container-fluid">
					<!-- Brand and toggle get grouped for better mobile display -->
						<div class="navbar-header">
							<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-navbar-general" aria-expanded="false">
								<span class="sr-only">Toggle navigation</span>
								<span class="icon-bar"></span>
								<span class="icon-bar"></span>
								<span class="icon-bar"></span>
							</button>
							<a class="navbar-brand" href="/"><img alt="Brand" class="image-header" src="/<?php echo($templatePath);?>style/images/header.png"></a>
						</div>
						<div class="collapse navbar-collapse" id="bs-navbar-general">
							<ul class="nav navbar-nav navbar-right">				
							<?php echo($menu); ?>
							
							</ul>      
						</div><!-- /.navbar-collapse -->
					</div><!-- /.container-fluid -->
				</nav>
				<!-- fin barre navigation -->

				<div class="container" id="conteneurPage" >				
					<!-- ici les articles -->					
					<?php  echo($body);?>									
				</div> 	
				
			</div>
		</div>
		<footer>			
			<span class="pull-left">&nbsp;&nbsp; <a  href="/admin"> Administration</a> </span>
            		<a href="http://plasmide.selenth.ovh"  target="_blank">Plasmide</a> de <a href="http://selenith.ovh"  target="_blank">Selenith</a> est mis à disposition selon les termes de la <a  href="http://www.gnu.org/licenses/gpl.html" target="_blank">Licence GNU GPLv3</a>.
			 <span class="pull-right"><a  href="/feed/index.xml"><img title="RSS" alt="RSS" src="/<?php echo($templatePath);?>style/images/rss32.png"></a> </span>
		</footer>
	
		<script type="text/javascript" src="/tools/jquery.min.js"></script>	
		<script type="text/javascript" src="/core/controller/nav.js"></script>
		<script type="text/javascript" src="/core/controller/plasmide.js"></script>
		<script type="text/javascript" src="/tools/bootstrap/js/bootstrap.min.js"></script>
		<?php echo($scripts); ?>	
		<script type="text/javascript" >
			$( document ).ready(function() {
				plasmide.start();			
			});		
		</script>	
	</body>	
</html>


<?php
class SiteConfig{
	
	//nombre d'article a afficher par page;
	public static $artParPage = 3;
	
	
	//--------------------------------------------------Squelette graphique-----------------------------------------------------


	//generation du code html correspondant a un block
	public static function forgerBlock($titre, $contenu){
		$html ='
					<div class="article" >
						<div class="titreArticle">
							<h1>'.$titre.'</h1>
						</div>
						<div class="corpsArticle">
							'.$contenu.'
						</div>
					</div>
					';
		return $html;
	}
}
?>
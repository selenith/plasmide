<?php

class VueModuleNotFound{

	public static function getBody(){
		return '<div class="article">
				<div class="titreArticle">Erreur</div>
				<div class="corpsArticle"><p>
					Module inexistant.
				</div>
			</div>';
	}

	public static function getHead($nomSite){
		return '<title>'.$nomSite.' - Erreur</title>'.PHP_EOL;
	}
}
?>
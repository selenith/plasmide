<?php 

class Article extends Archivable{


	public static $dbSite = 'data/';
	
	public static function arbreMenu($id_parent){
	
		$arch = new Archiviste();

		$menu = new Archivable('Menu');
		$menu->set('id_parent', $id_parent);
	
		$liste_menu = $arch->restituer($menu);
		for($i=0; $i< count($liste_menu); $i ++){
			array_merge($liste_menu, Article::arbreMenu($liste_menu[$i]->get('id')));
		}	
	
		return $liste_menu;
	}


	public static function lister($categ){
		$arch = new Archiviste(Article::$dbSite);
		$article = new Archivable('Article');

		//on recupere l'id de la categorie selectionnée
			
		//recuperation de tous les article correspondants
		$articles = array();
		$article->set('id_menu', $categ);
		$articles = $arch->restituer($article);		
		$articles = $arch->trier($articles, 'date', false);
		$articles = $arch->trier($articles, 'ordre', true);
		
		$nbArticles = count($articles);
		for($i=0;$i<$nbArticles;$i++){
			if($articles[$i]->get('comment') =="oui"){
				$comment = new Archivable("Commentaire");
				$comment->set('id_article', $articles[$i]->get('id'));
				$listeComm = $arch->restituer($comment);
				
				$articles[$i]->set('nbrComment', count($listeComm));
			}
		}
		
		
	
		return $articles;
	}

	public static function extraireArticle($idArticle){
		$arch = new Archiviste(Article::$dbSite);
		$article = new Archivable('Article');

		//on recupere l'id de la categorie selectionnée
			
		//recuperation de tous les article correspondants
		$articles = array();	
		$article->set('id', $idArticle);
		$articles = $arch->restituer($article);
		
		if(count($articles)<1){
			return false;
		}

		$article = $articles[0];
		
		return $article;
		
	
	}
	
	public static function news(){
		$article = new Archivable('Article');
		$article->set('pageNews', 'oui');
		$arch = new Archiviste(Article::$dbSite);

		//recuperation de tous les article
		
		
		
		$articles = $arch->restituer($article);
		$articles = $arch->trier($articles, 'date', false);
		
		$nbArticles = count($articles);
		for($i=0;$i<$nbArticles;$i++){
			if($articles[$i]->get('comment') =="oui"){
				$comment = new Archivable("Commentaire");
				$comment->set('id_article', $articles[$i]->get('id'));
				$listeComm = $arch->restituer($comment);
				
				$articles[$i]->set('nbrComment', count($listeComm));
			}
		}
		
		
		
		return $articles;
	
	}
}

?>

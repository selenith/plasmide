<?php

class Commentaire{
	
	public static $dataCom = array();
	public static $dbSite = 'mods/site/data/';
	
	
	
	public static function loadComms(){
		$arch = new Archiviste(Commentaire::$dbSite);
		$comm = new Archivable('Commentaire');
		Commentaire::$dataCom = $arch->restituer($comm);
		
	}
	
	
	public static function getComForArt($id){
		$coms = array();
		
		$nbCom = count(Commentaire::$dataCom);
		for($i = 0; $i< $nbCom; $i++){
			if(Commentaire::$dataCom[$i]->get('id_article') == $id ){
				array_push($coms,Commentaire::$dataCom[$i] );
			}			
		}
		
		return $coms;
	}
	
	public static function enregistrerComment($id, $pseudo, $message){
		$nomSite = Config::getVal('nom', 'core/data/');
		$adresseSite = Config::getVal('adresse', 'core/data/');
		
                /*
		$message = str_replace("<", "&lt;", $message);
		$message = str_replace(">", "&gt;", $message);
		$message = str_replace("\'", "'", $message);
		$message = str_replace('\"', '"', $message);
		$message = str_replace(CHR(10), "<br>", $message);	
                */
		$pseudo = htmlspecialchars($pseudo);
                
                $parser = new \SBBCodeParser\Node_Container_Document();

                $smilePath = $_SERVER['SERVER_NAME'].'/tools/sceditor/emoticons/';
                $parser->add_emoticons(array(
                    ':)' => $smilePath.'smile.png',
                    ':D' => $smilePath.'grin.png'
                    ));

                $message = $parser->parse($message)
                    ->detect_links()
                    ->detect_emails()
                    ->detect_emoticons()
                    ->get_html();
 

		//date_default_timezone_set('Europe/Paris');
		//$date = date('Y-m-d H\hi');
		$date = time();

		$comment = new Archivable('Commentaire');
		$comment->set('ip', $_SERVER['REMOTE_ADDR']);
		$comment->set('id_article', $id);
		$comment->set('pseudo', $pseudo);	
		$comment->set('texte', $message);

		//verification que le commentaire n'a as déja été enregistré
		$arch = new Archiviste(Commentaire::$dbSite);
		$commsTest = $arch->restituer($comment);
		
		$retour = false;
		if(count($commsTest) == 0){
			$comment->set('date', $date);
			$arch->archiver($comment);
	
			//on informe l'admin qu'un commentaire a été posté
			$corps_message = 'Nouveau commentaire de '.$pseudo.' (<a href="http://'.$adresseSite.'/site/art/'.$id.'">lien vers l\'article</a>)'.PHP_EOL.
							'<br />'.PHP_EOL.$message;
	
			$headers = 'Content-type: text/html; charset=UTF-8' . "\r\n"
							.'From: "'.$nomSite.'"<robot@'.$adresseSite.'>'."\r\n";
			mail ( Config::getVal('mail', 'core/data/') , 'Nouveau commentaire' ,  $corps_message, $headers);
	
	
			$retour = true;
		}
		
		
	
	
		return $retour ;
	}

	public static function recupComs($idArt){

		$arch = new Archiviste(Commentaire::$dbSite);
		$commentaire = new Archivable('Commentaire');
		$commentaire->set('id_article', $idArt); 
		$commentaires = $arch->restituer($commentaire);

		$commentaires = $arch->trierNumCroissant($commentaires, 'date');
		$nbCom = count($commentaires);
		
		$coms = array();
		
		for($i = 0 ; $i < $nbCom ; $i ++){
			$coms[$i]=array(
			'pseudo'=>$commentaires[$i]->get('pseudo'),
			'date'=>$commentaires[$i]->get('date'),
			'texte'=>$commentaires[$i]->get('texte')
			);
		}

		return $coms;
	}


	public static function recupArticle($id){

		$arch = new Archiviste(Commentaire::$dbSite);
		$article = new Archivable('Article');
		$article->set('id', $id);
		$articles = $arch->restituer($article);
	
	
		$retour = false;

		//si l'article est present dans la base de donnée
		if(count($articles)>0){
		

			$article = $articles[0];
			$commActif =$article->get('comment');
			
			$retour = array('statut'=>'ok');
			$retour['article'] = array(
			'nom'=>$article->get('nom'),
			'code'=>$article->get('code'),
			'date'=>$article->get('date'),
			'idCateg'=>$article->get('id_menu'),
			'pageNews'=>$article->get('pageNews'),
			'commActif'=>$commActif
			);
			
			
			$retour['coms'] = array();
			
			if($commActif == "oui"){
				$commentaire = new Archivable('Commentaire');
				$commentaire->set('id_article', $id); 
				$commentaires = $arch->restituer($commentaire);

				$commentaires = $arch->trierNumCroissant($commentaires, 'date');
				$nbCom = count($commentaires);
			
				
				for($i = 0 ; $i < $nbCom ; $i ++){
					$retour['coms'][$i]=array(
					'pseudo'=>$commentaires[$i]->get('pseudo'),
					'date'=>$commentaires[$i]->get('date'),
					'texte'=>$commentaires[$i]->get('texte')
					);
				}
			}		
	
		}
		
	
		return $retour;
	}
}
	
?>

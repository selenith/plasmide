<?php
class VueSite{

	private $body ='';
	private $head =' <link rel="stylesheet" href="/mods/site/style/site-mod.css" type="text/css" media="screen" />'.PHP_EOL;
        private $siteName ='';
	private $pageName = '';
	private $script ='';
	private $idMenu = false;
	private $numPage = 1;
	private $nbPage = 1;
	private $article;
	private $afficherCom = false;
	private $formComplet = false;
	private $coms = array();
	private $formInfos = false;
	private $auteur = false;
	private $message = false;
	
	
	public function getHead(){
		return '<title>'.$this->siteName.' - '.$this->pageName.'</title>'.PHP_EOL.$this->head;
	}

	public function getBody(){
		return $this->body;
	}
	
	public function getScript(){
		return $this->script;
	}
	
	public function setSiteName($siteName){
		$this->siteName = $siteName;
	}
	
	public function setPageName($pageName){
		$this->pageName = $pageName;
	}
	
	public function setIdMenu($idMenu){
		$this->idMenu = $idMenu;
	}

	public function setNumPage($numPage){
		$this->numPage = $numPage-1;
	}	
	
	public function enableComs(){		
			$this->afficherCom =true;		
	}
	
	public function setComs($coms){		
			$this->coms = $coms;		
	}
	
	public function setVarsForm($auteur,$message){
		
		$this->auteur = $auteur;
		$this->message = $message;
		
	}
	
	public function setArticle($article){
		if($article){
			$this->article = $article;
		}else{
			$this->article = new Archivable('Article');
		}		
	}
	

	public function forgerPageArticle(){
		
		$this->idMenu = $this->article->get('id_menu');
		$this->pageName = $this->article->get('nom');

		$code = $this->article->get('code');

		$html = SiteConfig::forgerBlock($this->article->get('nom'), $code);	
		
		
		if($this->afficherCom){
			$html.= $this->displayCom();
			
			if($this->formComplet){
				
				$html.= $this->displayForm();
			}else{
				$html.= $this->displayReponseForm();
			}
		}
		$this->body = $html ;		
	}
	
	
	public function forgerPageCategorie(){
		
		$categ = $this->idMenu;	
		
		$articles = Article::lister($categ);		
		
		$limiteBasse = SiteConfig::$artParPage*$this->numPage;		
		
		$nbTot = count($articles);
		$articles = array_slice($articles, $limiteBasse, SiteConfig::$artParPage);
		$this->nbPage =  floor($nbTot/ SiteConfig::$artParPage);
		if($nbTot% SiteConfig::$artParPage != 0){
			$this->nbPage ++;
		}
		
		$html = '';		
		
		$nbArticles = count($articles);
		for($i=0; $i< $nbArticles; $i++){
						
			$code = $articles[$i]->get('code');
			
			if( $articles[$i]->get('comment') == "oui"){			
				$code.= '<div class="text-right datePlusCom">Publié le '. date('d\/m\/Y',$articles[$i]->get('date')) .' - <a href="/site/art/'.$articles[$i]->get('id').'" >Commentaires <span class="badge">'.$articles[$i]->get('nbrComment').'</span> </a></div>';
			}
			$html.= SiteConfig::forgerBlock($articles[$i]->get('nom'), $code);
		}		
		
		$pagination = '
				<div class="container text-center">';

		if($this->nbPage > 1){
			$pagination .= '
				<nav>
				  <ul class="pagination">';

			for($i=0 ; $i<$this->nbPage ; $i++){			
				
				
				//mise en couleur de la page courante	
				if($i == $this->numPage){
					$pagination.= '<li class="disabled" ><a>'. ($i+1) .'</a></li>';
				}else{
					$pagination.='<li><a href="/site/'. $categ .'/'.$i.'" > '. ($i+1) .'</a></li> ';
				}													
			}		
			

			$pagination .='
				  </ul>
				</nav>';
		}		

		


		$pagination .='
			</div>';

		$this->body = $html.$pagination ;

		
	}

	public function forgerPageNews(){
		
		$categ = $this->idMenu;	
		
		$articles = Article::news();		
		
		$limiteBasse = SiteConfig::$artParPage*$this->numPage;		
		
		$nbTot = count($articles);
		$articles = array_slice($articles, $limiteBasse, SiteConfig::$artParPage);
		$this->nbPage =  floor($nbTot/ SiteConfig::$artParPage);
		if($nbTot% SiteConfig::$artParPage != 0){
			$this->nbPage ++;
		}
		
		$html = '';		
		
		$nbArticles = count($articles);
		for($i=0; $i< $nbArticles; $i++){
						
			$code = $articles[$i]->get('code');
			
			if( $articles[$i]->get('comment') == "oui"){			
				$code.= '<div class="text-right datePlusCom">Publié le '. date('d\/m\/Y',$articles[$i]->get('date')) .' - <a href="/site/art/'.$articles[$i]->get('id').'" >Commentaires <span class="badge">'.$articles[$i]->get('nbrComment').'</span> </a></div>';
			}else{			
				$code.= '<div class="text-right datePlusCom">Publié le '. date('d\/m\/Y',$articles[$i]->get('date')) .' - <a href="/site/art/'.$articles[$i]->get('id').'" >Lien permanent </a></div>';
			}
			$html.= SiteConfig::forgerBlock($articles[$i]->get('nom'), $code);
		}		
		
		$pagination = '
				<div class="container text-center">';

		if($this->nbPage > 1){
			$pagination .= '
				<nav>
				  <ul class="pagination">';

			for($i=0 ; $i<$this->nbPage ; $i++){			
				
				
				//mise en couleur de la page courante	
				if($i == $this->numPage){
					$pagination.= '<li class="disabled" ><a>'. ($i+1) .'</a></li>';
				}else{
					$pagination.='<li><a href="/news/'.($i+1).'" > '. ($i+1) .'</a></li> ';
				}													
			}		
			

			$pagination .='
				  </ul>
				</nav>';
		}		

		


		$pagination .='
			</div>';

		$this->body = $html.$pagination ;

		
	}

		
	public function setFormBase(){
		$this->formInfos = '<div id="infoComm" class="panel panel-default" > <div class="panel-body"> Pour poster un commentaire, utilisez le formulaire ci-dessous :</div></div>';
		$this->formComplet = true;
	}
	
	public function setFormSendOK(){
		$this->formInfos = '<div id="infoComm" class="alert alert-success" role="alert">Message envoyé avec succes !</div>';
		$this->formComplet = false;		
	}
	
	public function setFormDoubleSend(){
		$this->formInfos = '<div id="infoComm" class="alert alert-danger" role="alert">Message déja présent.</div>';
		$this->formComplet = true;		
	}

        
        private function addSceditorHead(){
            $this->head.='       <link rel="stylesheet" href="/tools/sceditor/minified/themes/default.min.css" type="text/css" />'.PHP_EOL;
        } 

        private function addSceditorScript(){
            $this->script.='        <script type="text/javascript" src="/tools/sceditor/minified/jquery.sceditor.bbcode.min.js"></script>'.PHP_EOL.
                            '       <script type="text/javascript" src="/mods/site/view/sceditorStart.js"></script>'.PHP_EOL;
            
        }

	private function displayCom(){
		$html='';
		$coms = $this->coms;
		$nbComs = count($coms);		
		for($i=0; $i<$nbComs; $i++){
			$html.=	'<div class="panel panel-default">
				  <div class="panel-heading">Par '.$coms[$i]['pseudo'].', le '.date('d\/m\/Y',$coms[$i]['date']).' </div>
				  <div class="panel-body">
				   '.$coms[$i]['texte'].'
				  </div>
				</div>';
		}
		return $html;
	}	
	
	private function displayForm(){
		$this->addSceditorHead();
                $this->addSceditorScript();

                $content ='
			<form method="post" action="./'.$this->article->get('id').'#infoComm">					
				<div class="form">
					'.$this->formInfos.'
					<fieldset >
						<legend>Votre pseudo</legend>					
						<div >		
							<input class="champForm" name="auteur" type="text" value="'.$this->auteur.'" placeholder="Pseudonyme" required >
						</div>								
					</fieldset>
					<fieldset>
						<legend>Votre message</legend>
						<div >
							Message :
						</div>
						<textarea name="message" class="form-control" id="editor" rows="5"  required>'.$this->message.'</textarea>
					</fieldset>
					<div>
						<input type="hidden" name="action" value="envoi" /> 
						<input type="submit" class="btn btn-default" value="Envoi" />
					</div>
				</div></form>';
			
			
			
		return SiteConfig::forgerBlock('Réagissez à cet article',$content);
		
	}
	
	private function displayReponseForm(){
			
		$content ='
			<div id="contact">
				'.$this->formInfos.'
			</div>';
			
		return SiteConfig::forgerBlock('Réagissez à cet article',$content);
		
	}
}

?>

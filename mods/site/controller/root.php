<?php
include('mods/site/model/Article.php');
include('mods/site/view/VueSite.php');
include('mods/site/model/Commentaire.php');
include('mods/site/config.php');

$numPage = 1;
$mod = 'news';
$article = false;




include('core/controller/menu.php');


$vueSite = new VueSite();



//param 1 : idMenu for categ or "art"
//param 2 : id article. Is set if param 1 = "art"
if(isset($param[2]) && $param[1]=='art'){
	$idArticle = $param[2];
	$vueSite = new VueSite();
	
	
	$article = Article::extraireArticle($idArticle);
	$vueSite->setArticle($article);
	$vueSite->setSiteName($nomSite);
	
	if($article){
		$displayCom = $article->get('comment');
		
		if($displayCom=='oui'){
			
			$vueSite->enableComs();
			$vueSite->setFormBase();		
					
			//gestion de l'envoi d'un com
			if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'envoi' &&
				isset($_REQUEST['auteur'])  && isset($_REQUEST['message']) ){
					
				$noSend = true;
				$auteur = $_REQUEST['auteur'];
				$message = $_REQUEST['message'];
				
			        //$vueSite->setFormBase();				
				
				
				if($auteur !='' || $message !=''){					
					
					
					$vueSite->setVarsForm($auteur,$message);
					
					$vueSite->setFormSendOK();
					$enregistreOk = Commentaire::enregistrerComment($idArticle, $auteur, $message);
					
					if(!$enregistreOk){
						$vueSite->setFormDoubleSend();
						
					}
				}
				
				
				
			}

                        $coms = Commentaire::recupComs($idArticle);
                        $vueSite->setComs($coms);
		}
	}
	
	
	
	$vueSite->forgerPageArticle();
	
    
}else if(isset($param[1])){
	
	$id=$param[1];
	if(isset($param[2])){
		$numPage = $param[2];		
	}
	
	$vueSite->setPageName(Menu::getNom($id));

	$vueSite->setSiteName($nomSite);
	$vueSite->setIdmenu($id);
	$vueSite->setNumPage($numPage);
	
	$vueSite->forgerPageCategorie();
	
	
}

//Page display
$head .= $vueSite->getHead();
$body = $vueSite->getBody();
$scripts .= $vueSite->getScript();
include($templatePath.'index.php');


?>

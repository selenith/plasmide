<?php
include('core/model/Menu.php');

function getSubMenu($idParent, $listeMenu){
	
	$reponse = [];
	
	$nbMenu = count($listeMenu);
	
	
	for($i = 0; $i < $nbMenu ; $i++){
		if($listeMenu[$i]->get('id_parent') == $idParent){
			
			$reponse[] = $listeMenu[$i];
		}
	
	}
	
	return $reponse;
}





function forgerMenu($listeMenu){
		
	$html ='';
	$mouvement = false;
	$nbMenu = count($listeMenu);
	
    
	for($i = 0; $i < $nbMenu ; $i++){			
		
		if( $listeMenu[$i] != null && !$listeMenu[$i]->get('id_parent')){				
		
			$id =  strval($listeMenu[$i]->get('id'));
			
			$sousMenus = getSubMenu($id, $listeMenu);
			
			if(count($sousMenus)>0){
				$html .= '
					<li class="dropdown">
						<a href="/'.$listeMenu[$i]->get('mod').$listeMenu[$i]->get('idString').'" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">'.$listeMenu[$i]->get('nom').' <span class="caret"></span></a>
						<ul class="dropdown-menu">';
				
				for($j = 0; $j < count($sousMenus) ; $j++){
					$html .= '
							<li> <a href="/'.$sousMenus[$j]->get('mod').$sousMenus[$j]->get('idString').'">'.$sousMenus[$j]->get('nom').'</a></li>';
				}
				
				$html .= '
						</ul>
					</li>';
				
			}else{
				
				$html .= '
					<li><a href="/'.$listeMenu[$i]->get('mod').$listeMenu[$i]->get('idString').'" >'.$listeMenu[$i]->get('nom').'</a></li>';	
			}
			
		
			
			//on supprime l'id d'un menu deja placé
			//$listeMenu[$i] = null;				
			
			//recursion de la fonction si au cas ou l'élément a des enfants
			//$html .= forgerMenu($id, $listeMenu) ;
			
			
		}
	}		
			
	if($mouvement){
		$html .= '</ul>';
	}		
	
	return $html;
}

//====================== MAIN ===============================
$menus = Menu::lister();
$menu = forgerMenu($menus);
$stylePage ='';
$styleMenu = '';
$categ = 0;
$nomPage = '';


//on utilise les atributs specifiques au menu pour le header pour tous les mods sauf le forum et l'agenda
if(isset($param[1])){
	
	
	$categ = $param[1];

	$infosPage = new Archivable('Menu');
	$infosPage->set('id', $categ);
	$arch = new Archiviste();
	$infosMenusRecup = $arch->restituer($infosPage);
	
	if(count($infosMenusRecup) ==1){
		$infosPage = $infosMenusRecup[0];
	}
	
	if($infosPage->get('metaDesc')){
		$metaDesc = $infosPage->get('metaDesc');
		$head .= '<meta name="description" content="'.$infosPage->get('metaDesc').'">
		';
	}
	
	$nomPage = $infosPage->get('nom');
	
	$styleMenu= $infosPage->get('stylePage');
	
	if($styleMenu){
		$stylePage = $styleMenu;
	}
	
	
}
?>

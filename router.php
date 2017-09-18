<?php
include('core/model/Ribosome.php');
include('core/model/Archiviste.php');
include('core/model/Archivable.php');
include('core/model/Config.php');

#get Params from url rewriting
$param = array_merge(array_filter(explode('/',parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH))));
$nbParams = count($param);


//configuration des mods actifs
$modList = scandir('mods/');


//initialization of variables site
$switcher = null;
$scripts = '';
$head ='';
$nomSite =  Config::getVal('nom');
$metaDesc= false;
$indexPath = 'index.php';
$stylePage='';

//fonction d'importation des variables de template
function getTemplatePath(){
	$templatePath = Config::getVal('template');
	if($templatePath == false){
		$templatePath = 'plasmide';
	}
	return 'templates/'.$templatePath.'/';
}


if($nbParams>0){		
    $switcher = $param[0]; 
}else{
	$idHome = Config::getVal('idHome');
	$arch = new Archiviste();
	$menu = new Archivable('Menu');
		
	$menu->set('id', $idHome );
	$menus = $arch->restituer($menu);
	
	if(count($menus)>0){
		$menu = $menus[0];
		$switcher = $menu->get('mod');

                header("Status: 301 Moved Permanently", false, 301);
                header("Location: ./".$switcher);

		//$_GET['id'] = $idHome ;
		//$_GET['mod'] =$switcher;
	}	
}

$templatePath = getTemplatePath();

//inclusion du module selectionné
if(in_array ($switcher, $modList)){
	include('mods/'.$switcher.'/controller/root.php');	

}else{
	//inclusion de la page d'erreur
	
	
	include('core/controller/menu.php');
	include('core/view/VueModuleNotFound.php');
	$body = VueModuleNotFound::getBody();
	$head = VueModuleNotFound::getHead($nomSite);
}

//include($templatePath.'index.php');
?>

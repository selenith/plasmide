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



	
if(isset($param[1])){
	$numPage = $param[1];
}

$vueSite->setPageName('News');
$vueSite->setSiteName($nomSite);
$vueSite->setNumPage($numPage);
	
$vueSite->forgerPageNews();
	
	


//Page display
$head .= $vueSite->getHead();
$body = $vueSite->getBody();
include($templatePath.'index.php');
?>

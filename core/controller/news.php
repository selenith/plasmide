<?php
include('metier/Article.php');
include('vue/VueCategorie.php');




$numPage = 0;
$mod = 'news';


if(isset($_GET['mod'])){
	$mod =$_GET['mod'];
}
if(isset($_GET['page'])){
	$numPage = $_GET['page'];		
}

$vueCateg = new VueCategorie();
$vueCateg->setSiteName($nomSite);
$vueCateg->setPageName($nomPage);

$vueCateg->forgerPageArticle($categ, $mod, $numPage, $ariane);


$head .= $vueCateg->getHead();
$corps = $vueCateg->getBody();

?>
<?php
include('../../../core/model/Archivable.php');
include('../../../core/model/Archiviste.php');
include('../../../core/model/Ribosome.php');
include('../../../core/model/Config.php');
include('../../auth/model/User.php');
include('../../site/model/Article.php');
include('../model/RssGen.php');

function gestionArticle(){
	
	$arch = new Archiviste('../../site/data/');
	$archMenu = new Archiviste('../../../core/data/');
	$retour = array();
	$retour['statut'] ='ok';
	//liste des articles
	$article = new Archivable('Article');
	$articles = $arch->restituer($article);	
	//tri des articles par ordre chronologique
	$articles = $arch->trierNumCroissant($articles, 'date');
	$nb = count($articles);
	
	
	$retour['article'] = array();	
	for($i = 0 ; $i < $nb ; $i++){		
		$retour['article'][$i]['id'] =$articles[$i]->get('id');
		$retour['article'][$i]['idMenu'] =$articles[$i]->get('id_menu');
		$retour['article'][$i]['nom'] =$articles[$i]->get('nom');
		$retour['article'][$i]['date'] =date('j\/m\/Y',$articles[$i]->get('date'));
		$retour['article'][$i]['news'] =$articles[$i]->get('pageNews');
		$retour['article'][$i]['comment'] =$articles[$i]->get('comment');
		$retour['article'][$i]['ordre'] =$articles[$i]->get('ordre');
	}


	$menu = new Archivable('Menu');
	$menu->set('mod', 'site');
	$menus = $archMenu->restituer($menu);
	$nb = count($menus);
	$retour['menus'] = array();
	
	$compteur =0;
	for($i = 0 ; $i < $nb ; $i++){
		$retour['menus'][$i] = array();
		$retour['menus'][$i]['id'] =$menus[$i]->get('id');
		$retour['menus'][$i]['nom'] =$menus[$i]->get('nom');
		$compteur = $i+1;
	}
	
	$menu->set('mod', 'colonnes');
	$menus = $archMenu->restituer($menu);
	$nb = count($menus);
	for($i = 0 ; $i < $nb ; $i++){
		$retour['menus'][$i+$compteur] = array();
		$retour['menus'][$i+$compteur]['id'] =$menus[$i]->get('id');
		$retour['menus'][$i+$compteur]['nom'] =$menus[$i]->get('nom');
		
	}
	
	
	return $retour;
}


function editionArticle(){
	
	$retour =array();
	$retour['statut'] = 'ok';
	$id_art = $_REQUEST['id'];
	$arch = new Archiviste('../../site/data/');
	$archMenu = new Archiviste('../../../core/data/');
	//liste des articles
	$article = new Archivable('Article');
	$article->set('id', $id_art);
	$articles = $arch->restituer($article);	
	
	
	$compteur =0;
	$menu = new Archivable('Menu');
	$menu->set('mod', 'site');
	$menus = $archMenu->restituer($menu);	
	$nb = count($menus);
	$retour['menu'] = array();
	for($i = 0 ; $i < $nb ; $i++){
		$retour['menu'][$i]['id']=$menus[$i]->get('id');
		$retour['menu'][$i]['nom']=$menus[$i]->get('nom');
		$retour['menu'][$i]['idParent']=$menus[$i]->get('id_parent');
		$compteur = $i+1;
	}
	$menu->set('mod', 'colonnes');
	$menus = $archMenu->restituer($menu);
	$nb = count($menus);
	for($i = 0 ; $i < $nb ; $i++){
		$retour['menu'][$i+$compteur] = array();
		$retour['menu'][$i+$compteur]['id'] =$menus[$i]->get('id');
		$retour['menu'][$i+$compteur]['nom'] =$menus[$i]->get('nom');
		$retour['menu'][$i+$compteur]['idParent']=$menus[$i]->get('id_parent');
		
	}
	
	
	
	
	$retour['article'] = array(
	'texte'=>$articles[0]->get('code'),
	'titre'=>$articles[0]->get('nom'),
	'idMenu'=>$articles[0]->get('id_menu'),
	'ordre'=>$articles[0]->get('ordre'),
	'id'=>$articles[0]->get('id')	
	);
	return $retour;
}

function creationArticle(){
	
	$retour =array();
	$retour['statut'] ='ok';
	
	
	$arch = new Archiviste('../../../core/data/');
	
	$compteur =0;
	$menu = new Archivable('Menu');
	$menu->set('mod', 'site');
	$menus = $arch->restituer($menu);	
	$nb = count($menus);
	$retour['menu'] = array();
	for($i = 0 ; $i < $nb ; $i++){
		$retour['menu'][$i] = array(
			'id'=>$menus[$i]->get('id'),
			'nom'=>$menus[$i]->get('nom'),
			'idParent'=>$menus[$i]->get('id_parent')
		);
		$compteur = $i+1;		
	}
	
	$menu->set('mod', 'colonnes');
	$menus = $arch->restituer($menu);
	$nb = count($menus);
	for($i = 0 ; $i < $nb ; $i++){
		$retour['menu'][$i+$compteur] = array(
			'id'=>$menus[$i]->get('id'),
			'nom'=>$menus[$i]->get('nom'),
			'idParent'=>$menus[$i]->get('id_parent')
		);
	}
	return $retour;
}

function validerCreation(){
		
	$texte = $_POST['texte'];
	$idMenu = $_POST['id'];
	$titre = $_POST['titre'];
	$ordre = $_POST['ordre'];
	
	$texte = str_replace('%gronk', '&', $texte);
	$texte = str_replace('%grank', '+', $texte);
	$texte = str_replace("\'", "'", $texte);
	$texte = str_replace('\"', '"', $texte);
	
	$titre = str_replace('%gronk', '&', $titre);
	$titre = str_replace('%grank', '+', $titre);
	$titre = str_replace("\'", "'", $titre);
	$titre = str_replace('\"', '"', $titre);
	
	$ordre = str_replace('%gronk', '&', $ordre);
	$ordre = str_replace('%grank', '+', $ordre);
	$ordre = str_replace("\'", "'", $ordre);
	$ordre = str_replace('\"', '"', $ordre);
	
	$arch = new Archiviste('../../site/data/');
	$article = new Archivable('Article');
	$article->set('id_menu',  $idMenu);
	$article->set('code',  $texte);
	$article->set('nom',  $titre);
	$article->set('pageNews', 'non');
	$article->set('comment', 'non');
	$article->set('ordre', $ordre);
	
	//date_default_timezone_set('Europe/Paris');
	//$date = date('Y-m-d H\hi');
	$date = time();
	$article->set('date',  $date);
	
	$arch->archiver($article);
	
	return array('statut'=>'ok');
	
}

function validerEdition(){
	
	$texte = $_POST['texte'];
	$idArt = $_POST['idArt'];
	$idMenu = $_POST['idMenu'];
	$titre = $_POST['titre'];
	$ordre = $_POST['ordre'];
	
	$titre = str_replace('%gronk', '&', $titre);
	$titre = str_replace('%grank', '+', $titre);
	$titre = str_replace("\'", "'", $titre);
	$titre = str_replace('\"', '"', $titre);
	
	$texte = str_replace('%gronk', '&', $texte);
	$texte = str_replace('%grank', '+', $texte);
	$texte = str_replace("\'", "'", $texte);
	$texte = str_replace('\"', '"', $texte);	
	
	$ordre = str_replace('%gronk', '&', $ordre);
	$ordre = str_replace('%grank', '+', $ordre);
	$ordre = str_replace("\'", "'", $ordre);
	$ordre = str_replace('\"', '"', $ordre);
	
	
	$arch = new Archiviste('../../site/data/');
	$articleAncien = new Archivable('Article');	
	$articleAncien->set('id', $idArt);
	$articleNouveau = new Archivable('Article');
	$articleNouveau->set('id_menu',  $idMenu);
	$articleNouveau->set('code',  $texte);
	$articleNouveau->set('nom',  $titre);
	$articleNouveau->set('ordre',  $ordre);
	/*
	date_default_timezone_set('Europe/Paris');
	$date = date('Y-m-d H\hi');
	$articleNouveau->set('date',  $date);*/
	
	$arch->modifier($articleAncien, $articleNouveau);
	
	return array('statut'=>'ok');
}



function supprArticle(){
	
	if(isset($_REQUEST['id']) && $_REQUEST['id'] !=''){
	
		$arch = new Archiviste('../../site/data/');
		$article = new Archivable('Article');
		$article->set('id', $_REQUEST['id']);
	
		$arch->supprimer($article);
		
		$commentaire= new Archivable('Commentaire');
		$commentaire->set('id_article',$_REQUEST['id'] );
		$arch->supprimer($commentaire);
		
	}
	
	return array('statut'=>'ok');

}

//inversion ddu statut d'affichage sur la page de news ou non
function inversionNews(){
	
	$retour = array();
	$retour['statut'] ='ok';
	if(isset($_REQUEST['id'])){
		$arch = new Archiviste('../../site/data/');
		$article = new Archivable('Article');
		$article->set('id', $_REQUEST['id']);
		
		$articles = $arch->restituer($article);
		
		$articleChange = new Archivable('Article');
		$articleChange->set('id', $_REQUEST['id']);
		
		$news = $articles[0]->get('pageNews');
		if($news == 'oui'){
			$articleChange->set('pageNews', 'non');
			$retour['etatCase']='decoche';
		}else{
			$articleChange->set('pageNews', 'oui');
			$retour['etatCase']='coche';
		}
		
		$arch->modifier($article, $articleChange);
		
	}	
	
	return $retour;
}

//inversion de l'affichage des commentaires pour un article
function inversionComment(){
	
	$retour = array();
	$retour['statut'] ='ok';
	if(isset($_REQUEST['id'])){
		
		$arch = new Archiviste('../../site/data/');
		$article = new Archivable('Article');
		$article->set('id', $_REQUEST['id']);
		
		$articles = $arch->restituer($article);
		
		$articleChange = new Archivable('Article');
		$articleChange->set('id', $_REQUEST['id']);
		
		$comment = $articles[0]->get('comment');
		if($comment == 'oui'){
			$articleChange->set('comment', 'non');
			$retour['etatCase']='decoche';
		}else{
			$articleChange->set('comment', 'oui');
			$retour['etatCase']='coche';
		}
		
		$arch->modifier($article, $articleChange);
		
	}	
	
	return $retour;
}

function listerMenu(){
	$retour = array('statut'=> 'ok');
	$arch = new Archiviste('../../../core/data/');
	$menu = new Archivable('Menu');	
	$menus = $arch->restituer($menu);
	
	
	$menus = $arch->trier($menus, 'ordre', true);
	$menus = $arch->trier($menus, 'id_parent', true);
	$nb = count($menus);
	$retour['menu'] = array();
	for($i = 0 ; $i < $nb ; $i++){
		$retour['menu'][$i] = array(
			'id'=>strval($menus[$i]->get('id')),
			'nom'=>$menus[$i]->get('nom'),
			'idParent'=>$menus[$i]->get('id_parent'),
			'mod'=>$menus[$i]->get('mod'),
			'metaDesc'=>$menus[$i]->get('metaDesc'),
			'stylePage'=>$menus[$i]->get('stylePage'),
			'ordre'=>$menus[$i]->get('ordre')
		);			
	}
	return $retour;
}



function listeMods(){
	$retour = array('statut'=> 'ok');
	$modlist = scandir('../..');
	unset($modlist[array_search(".", $modlist)]);
	unset($modlist[array_search("..", $modlist)]);
	$modlist = array_merge($modlist);
	$retour['modsList'] = $modlist;
	return $retour;
}
function ajoutMenu(){
	$retour = array('statut'=> 'ok');
	
	$nom = $_POST['nom'];
	$nom = str_replace("\'", "'", $nom);
	$nom = str_replace('\"', '"', $nom);
	$arch = new Archiviste('../../../core/data/');
	$menu = new Archivable('Menu');
	//----------ajoutmeta
	$metaDesc = $_POST['metaDesc'];
	$metaDesc = str_replace("\'", "'", $metaDesc);
	$metaDesc = str_replace('\"', '"', $metaDesc);
	$menu->set('metaDesc', $metaDesc);
	//----------finmeta
	$stylePage = $_POST['stylePage'];
	$stylePage = str_replace("\'", "'", $stylePage);
	$stylePage = str_replace('\"', '"', $stylePage);
	$menu->set('stylePage', $stylePage);
	
	$menu->set('id_parent', $_POST['idParent']);	
	$menu->set('nom', $nom);
	$menu->set('mod', $_POST['mod']);	
	$menu->set('ordre',  $_POST['ordre']);
	$arch->archiver($menu);
	return $retour;
}

function supprMenu(){
	$retour = array('statut'=> 'ok');
	$arch = new Archiviste('../../../core/data/');
	$menu = new Archivable('Menu');	
	
	if(isset($_GET['idMenu'])){
		$menu->set('id', $_GET['idMenu']);		
		$menus = $arch->supprimer($menu);
	}
	
	return $retour;
}

function editMenu(){
	$retour = array('statut'=> 'ok');
	$arch = new Archiviste('../../../core/data/');
	$menu = new Archivable('Menu');	
	$menuAncien = new Archivable('Menu');	
	$menuAncien->set('id', $_POST['idMenu']);
	
	$nom = $_POST['nom'];
	$nom = str_replace("\'", "'", $nom);
	$nom = str_replace('\"', '"', $nom);
	//----------ajoutmeta
	$metaDesc = $_POST['metaDesc'];
	$metaDesc = str_replace("\'", "'", $metaDesc);
	$metaDesc = str_replace('\"', '"', $metaDesc);
	$menu->set('metaDesc', $metaDesc);
	//----------finmeta
	$stylePage = $_POST['stylePage'];
	$stylePage = str_replace("\'", "'", $stylePage);
	$stylePage = str_replace('\"', '"', $stylePage);
	$menu->set('stylePage', $stylePage);
	
	$menu->set('nom', $nom);
	$menu->set('mod', $_POST['mod']);
	$menu->set('ordre',  $_POST['ordre']);
	$menu->set('id_parent', $_POST['idParent']);
	$menus = $arch->modifier($menuAncien, $menu);
	return $retour;
}

function getConfig(){
	
	$dataBase = '../../../core/data/';
	$dataBaseUser = '../../auth/data/';
	
	$retour = array('statut'=> 'ok');
	$arch = new Archiviste($dataBaseUser);
	$user = new Archivable('User');
	$users =  $arch->restituer($user);
	$user = $users[0];
	
	$retour['nomHome'] = 'Aucune';
	
	$idHome = Config::getVal('idHome',$dataBase);
	
	if($idHome){
		$arch = new Archiviste($dataBase);
		$menu = new Archivable('Menu');
		$menu->set('id', $idHome);
		$menus = $arch->restituer($menu);
		
		if(count($menus)>0){
			$menu=$menus[0];
			$retour['nomHome']=$menu->get('nom');
		}
	}
		
	$retour['login']=$user->get('login',$dataBase);	
	$retour['nom']=Config::getVal('nom',$dataBase);
	$retour['adresse']=Config::getVal('adresse',$dataBase);
	$retour['mail']=Config::getVal('mail',$dataBase);
	$retour['template']=Config::getVal('template',$dataBase);
	$retour['idHome']=$idHome;
	
	
	
	
	return $retour;
}

function validEditConf(){
	$retour = array('statut'=> 'ok');
	$arch = new Archiviste();
	
	$champ = $_REQUEST['champ'];
	$valeur = $_REQUEST['valeur'];
	$valeur = str_replace("\'", "'", $valeur);
	$valeur = str_replace('\"', '"', $valeur);
           
    $oldConfig = new Config();    
    $newConfig = new Config();
    
    $oldConfig->set('nom', $champ);
    $newConfig->set('val', $valeur);
    
    
    $arch->modifier($oldConfig, $newConfig);
    
	return $retour;
}

function getComm(){
	$retour = array('statut'=> 'ok');
	$arch = new Archiviste('../../site/data/');
	
	$comm = new Archivable('Commentaire');
	$comms =$arch->restituer($comm);
	$comms =$arch->trierNumCroissant($comms,'date');
	$nbComms = count($comms);
	
	$retour['commentaire'] = array();
	for($i=0; $i<$nbComms; $i++){
		$retour['commentaire'][$i]= array(
		'id'=>$comms[$i]->get('id'),
		'ip'=>$comms[$i]->get('ip'),
		'pseudo'=>$comms[$i]->get('pseudo'),
		'idArticle'=>$comms[$i]->get('id_article'),
		'titre'=>$comms[$i]->get('titre'),
		'date'=> date('j\/m\/Y',$comms[$i]->get('date')),
		'texte'=>$comms[$i]->get('texte')
		);
	}
	
	
	$article = new Archivable('Article');
	$articles =$arch->restituer($article);
	$nbArticles = count($articles);
	
	
	for($i=0; $i<$nbArticles; $i++){
		$retour['article'][$articles[$i]->get('id')]['nom']= $articles[$i]->get('nom');
	}
	
	
	return $retour;
}


function supprComm(){
	$retour = array('statut'=> 'ok');
	$arch = new Archiviste('../../site/data/');
	$comm = new Archivable('Commentaire');
	
	$idComm = $_REQUEST['idComm'];
	
	$comm->set('id', $idComm);
	$arch->supprimer($comm);
	
	return $retour;
}


function validConfAccueil(){
	$retour = array('statut'=> 'ok');
	
	$idHome = $_REQUEST['idMenu'];
	
	
	$arch=new Archiviste('../../../core/data/');
	
	$conf = new Archivable('Config');
	$newConf = new Archivable('Config');
	$conf->set('nom', 'idHome');
	
	$newConf->set('val', $idHome);
	
	$arch->modifier($conf, $newConf);
	
	return $retour;
}

//liste des utilisateur
function listUser(){
	$retour = array('statut'=> 'ok');
		
	$arch=new Archiviste('../../auth/data/');
	
	$user = new Archivable('User');
	$listUser = $arch->restituer($user);
	$listUser = $arch->trier($listUser, 'login', true);
	$nbUser = count($listUser);
	
	$retour['users'] = array();
	
	for($i=0;$i<$nbUser;$i++){
		$retour['users'][$i]['id'] = $listUser[$i]->get('id');
		$retour['users'][$i]['login'] = $listUser[$i]->get('login');
		$retour['users'][$i]['droits'] = $listUser[$i]->get('droits');
	}
	
	return $retour;
}

function ajoutUser(){	
	$retour = array('statut'=> 'ok');

	$login = $_REQUEST['login'];
	$pass = md5($_REQUEST['pass']);
	$droits = $_REQUEST['droits'];	
	
	$arch =  new Archiviste('../../auth/data/');
	
	$user = new Archivable('User');
	$user->set('login', $login);
	
	$users = $arch->restituer($user);
	
	if(count($users)> 0){
		$retour = array('statut'=> 'doublon');
	}else{
		$user->set('pass', $pass);
		$user->set('droits', $droits);
		
		
		$arch->archiver($user);
	}
	return $retour;	
}

function supprUser(){	
	if(isset($_REQUEST['idUser'])){
	
		$idUser = $_REQUEST['idUser'];
		$arch =  new Archiviste('../../auth/data/');
	
		$user = new Archivable('User');
		$user->set('id', $idUser);
		
		$arch->supprimer($user);
	}
	
	$retour = array('statut'=> 'ok');	
	return $retour;
}

function editUser(){
	$retour = array('statut'=> 'ok');

	$login = $_REQUEST['login'];
	$id = $_REQUEST['id'];
	$droits = $_REQUEST['droits'];
	$pass = $_REQUEST['pass'];	
	
	$arch =  new Archiviste('../../auth/data/');	
	$user = new Archivable('User');
	$user->set('id', $id);
	
	$userNew = new Archivable('User');
	$userNew->set('login', $login);
	$userNew->set('droits', $droits);
	
	if($pass!=''){
		$userNew->set('pass', md5($pass));
	}
	
	$arch->modifier($user,$userNew);
	return $retour;
}

function listeTemplates(){
	$retour = array('statut'=> 'ok');
	
	$templates = scandir('../../../templates');	
	$retour['templates'] = array_splice($templates,2,count($templates));
	return $retour;
}

function validConfTemplate(){
	$retour = array('statut'=> 'ok');
	$nomTemplate = $_REQUEST['nomTemplate'];
	
	$arch =  new Archiviste('../../../core/data/');	
	$config = new Archivable('Config');
	$config->set('nom', 'template');
	
	$newConfig = new Archivable('Config');
	$newConfig->set('val', $nomTemplate);
	
	$arch->modifier($config, $newConfig);
	
	return $retour;
}

function genererRss(){
	$article = new Article();
	$article->set('pageNews', 'oui');
	
	$arch = new Archiviste();
	$articles = $arch->restituer($article);

	$rssGen = new RssGen();
	$rssGen->setArtList($articles);

	$rssGen->generer();
}

//------main--------
$reponse;

session_start();

$login = false;
$droits = false;
if(isset($_SESSION['login'])){
	$login = $_SESSION['login'];
}

if(isset($_SESSION['droits'])){
	$droits = $_SESSION['droits'];
}

if($login && $droits =='maitre'){		
	$action = $_REQUEST['action'];	
	
	if ($action == 'gestion'){
		$reponse = gestionArticle();
		
	}else if($action == 'edition'){		
		$reponse = editionArticle();
		
	}else if($action == 'creation'){	
		$reponse = creationArticle();
		
	}else if($action == 'validerCreation'){
		$reponse = validerCreation();
		genererRss();
		
	}else if($action == 'validerEdition'){
		$reponse = validerEdition();
		genererRss();
		
	}else if($action == 'suppr'){
		$reponse = supprArticle();
		genererRss();
		
	}else if($action == 'inversionNews'){
		$reponse = inversionNews();
		genererRss();
		
	}else if($action == 'inversionComment'){
		$reponse = inversionComment();
		
	}else if($action == 'listeMenu'){
		$reponse = listerMenu();
		
	}else if($action == 'listeMods'){
		$reponse = listeMods();
		
	}else if($action == 'ajoutMenu'){
		$reponse = ajoutMenu();
		
	}else if($action == 'supprMenu'){
		$reponse = supprMenu();
		
	}else if($action == 'editMenu'){
		$reponse = editMenu();
		
	}else if($action == 'getConfig'){
		$reponse = getConfig();
		
	}else if($action == 'validEditConf'){
		$reponse = validEditConf();
		
	}else if($action == 'validConfAccueil'){
		$reponse = validConfAccueil();
		
	}else if($action == 'getComm'){
		$reponse = getComm();
		
	}else if($action == 'supprComm'){
		$reponse = supprComm();
		
	}else if($action == 'listUser'){
		$reponse = listUser();
		
	}else if($action == 'ajoutUser'){
		$reponse = ajoutUser();
		
	}else if($action == 'supprUser'){
		$reponse = supprUser();
		
	}else if($action == 'editUser'){
		$reponse = editUser();
		
	}else if($action == 'listeTemplates'){
		$reponse = listeTemplates();
	
	}else if($action == 'validConfTemplate'){
		$reponse = validConfTemplate();
	}
	
}else{
	$reponse = array('statut'=>'deco');
}

echo(json_encode($reponse));
?>

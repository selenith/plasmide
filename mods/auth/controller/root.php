<?php
include('mods/site/config.php');
include('mods/auth/model/Auth.php');
include('mods/auth/view/VueAuth.php');
include('mods/auth/model/User.php');
include('mods/auth/model/Token.php');
include('core/model/Mail.php');


function checkStatut(&$vueAuth){
	$auth = new Auth();
	$bundle = $auth->checkStatut();
	if($bundle['statut']=='deco'){
		$vueAuth->setContentDeco();		
	}else if($bundle['statut']=='connect'){
		$vueAuth->setContentConnect();
	}
}

function connexion(&$vueAuth){
	$auth = new Auth();	
	
	$login = $password = '';
	if(isset($_REQUEST['login']) && isset($_REQUEST['pass'])){
		$login = $_REQUEST['login'];
		$password = $_REQUEST['pass'];	
	}	

	$bundle = $auth->connexion($login, $password);
	
	if($bundle['statut'] == 'connect'){
		$vueAuth->setContentConnectOK();
	}else if($bundle['statut'] == 'noPass'){
		$vueAuth->setContentBadPass();
	}else if($bundle['statut'] == 'noUser'){
		$vueAuth->setContentNoUser();
	}
}


function deconnexion(&$vueAuth){
	$auth = new Auth();
		
	$auth->deconnexion();
	$vueAuth->setContentDecoOk();
}

function validNew(&$vueAuth){
	
	$auth = new Auth();
	
	$login = $_REQUEST['login'];
	$pass = $_REQUEST['pass'];
	$addrMail = $_REQUEST['mail'];
	
	
	
	
	$tokenPresent = $auth->checkToken('mail', $addrMail);
	if($tokenPresent){
		$vueAuth->demandeDejaEnCours();
	}else{
		//si pas de demande deja en cours
		$resultMail = Mail::checkMail($addrMail);
		if($resultMail=="ok"){
			
			$token = $auth->genererToken(20);
			
			$result = $auth->enregistrement($login, $pass, $addrMail, $token);
			
			if($result=='ok'){
				$nomSite=Config::getVal('nom');
				$adresseSite=Config::getVal('adresse');
				
				$expediteur = '"'.$nomSite.'"<robot@'.$adresseSite.'>';			
				$sujet = "Inscription";			
				$message = 'Bienvenue, et merci de votre enregistrement sur '.$nomSite.PHP_EOL.'<br />'.
					'Pour finaliser votre inscription, cliquez sur le lien suivant : <a href="http://'.$adresseSite.'/auth/activation?t='.$token.'">http://'.$adresseSite.'/auth/activation?t='.$token.'</a>'.PHP_EOL.PHP_EOL.'<br />'.'<br />'.
					'Vos identifiants sont les suivant :'.PHP_EOL.'<br />'.
					' - Login: '.$login.PHP_EOL.'<br />'.
					' - Password: '.$pass.PHP_EOL.PHP_EOL.'<br />'.'<br />'.
					'Vous pouvez changer à tout moment votre Password dans la section "Compte".'.PHP_EOL.'<br />'.PHP_EOL.'<br />'.
					'Ceci est un message automatique. Merci de ne pas y repondre.';
							
		
				Mail::envoyer($addrMail, $expediteur, $sujet, $message);
				$vueAuth->setNewAccountOk();
			}else if($result=='loginUsed'){
					$vueAuth->setLoginUsed();
			}else if($result=='mailUsed'){
					$vueAuth->setMailUsed();
			}
		}else{
			
			$vueAuth->setContentMailFail();
		}
		
	}
	
}

function creation(&$vueAuth){	
	$vueAuth->newAccount();
}

function activation(&$vueAuth){
	
	
	$token = $_REQUEST['t'];
	
	
	$auth = new Auth();
	$activation = $auth->checkActivation($token);
	
	if($activation == 'ok'){
		$vueAuth->activationOK();
	}else if($activation == 'duplicate'){
		$vueAuth->activationDup();
	}else{
		$vueAuth->activationNonOK();
	}
}

function reinitPass(&$vueAuth){
	
	$addrMail = '';
	if(isset($_REQUEST['mail'])){
		$addrMail =	$_REQUEST['mail'];
	}
	
	
	$arch = new Archiviste();
	
	$user = new User();
	$user->set('mail', $addrMail);
	
	$users = $arch->restituer($user);
	
	
	if(count($users) == 1){
	
		$userTrouve = $users[0];
		$auth = new Auth();
		
		//on verifie qu'il n'y a pas deja une demande en cours
		$tokenPresent = $auth->checkToken('mail', $addrMail);
		
		if($tokenPresent){			
			$vueAuth->doublonRecupPass();
		}else{
		
			
			
			
			$nomSite=Config::getVal('nom');
			$adresseSite=Config::getVal('adresse');
			$expediteur = '"'.$nomSite.'"<robot@'.$adresseSite.'>';
		
			$sujet = "Demande de reinitialisation de votre mot de passe";
			
		
			$cleToken = $auth->genererToken(20);
			$message = 'Bonjour '.$userTrouve->get('login').', <br />'.PHP_EOL.
				'<br />'.PHP_EOL.
				'Vous avez fait une demande de récupération de mot de passe:<br />'.PHP_EOL.
				'<br />'.PHP_EOL.
				'Pour generer un nouveau mot de passe et le recevoir cliquez sur le lien suivant : <a href="http://'.$adresseSite.'/auth/validRecupPass?t='.$cleToken.'">http://'.$adresseSite.'/auth/validRecupPass?t='.$cleToken.'</a><br />'.PHP_EOL.
				'<br />'.PHP_EOL.
				'Ceci est un message automatique. Merci de ne pas y repondre.';
							
			
			Mail::envoyer($addrMail, $expediteur, $sujet, $message);
			
			$auth->enregistrerRecuPass($addrMail, $userTrouve->get('login'), $cleToken);
			
			$vueAuth->recupPassOK();
		}
	}else{
		$vueAuth->recupPassNonValide();
	}
	
}

function validRecupPass(&$vueAuth){

	$auth = new Auth();
	$cleToken = 'null';
	if(isset($_REQUEST['t'])){
		$cleToken = $_REQUEST['t'];
	}		
	$token = $auth->checkToken('cle', $cleToken);
	
	if($token){			
		
		$arch = new Archiviste();
	
		$newPass = $auth->genererToken(12);
		
		$user = new User();
		$userNew = new User();
		
		$user->set('mail', $token->get('mail'));
		$userNew->set('pass', md5($newPass));
		
		$nomSite=Config::getVal('nom');
		$adresseSite=Config::getVal('adresse');
		$expediteur = '"'.$nomSite.'"<robot@'.$adresseSite.'>';
	
		$sujet = "Votre nouveau mot de passe";		
		$message = 'Bonjour '.$token->get('login').', <br />'.PHP_EOL.
			'<br />'.PHP_EOL.
			'Suite a votre demande de récupération, voici votre nouveau de mot de passe : <b>'.$newPass.'</b><br />'.PHP_EOL.
			'<br />'.PHP_EOL.
			'Ceci est un message automatique. Merci de ne pas y repondre.';
						
		
		Mail::envoyer($token->get('mail'), $expediteur, $sujet, $message);
		
		$arch->modifier($user, $userNew);
		$arch->supprimer($token);
		
		$vueAuth->validRecupPassOk();
	}else{
		$vueAuth->validRecupPassNOk(); ;
	}
}


function pageReinitPass(&$vueAuth){
	$vueAuth->reinitPass();
}

function pageProfil(&$vueAuth){
	
	$auth = new Auth();
	
	
	
	$user = $auth->infoUser();
	
	if($user){
		$vueAuth->pageProfil($user);
	}else{
		$vueAuth->setContentDeco();
	}
}


function majProfil(&$vueAuth){

	$auth = new Auth();
	$user = $auth->infoUser();	
	
	if($user){
		$traitementOk = true;
		$message='';

		$arch = new Archiviste();
		$userMAJ = new User();

			
		//recuperation de la requete
		$passAncien = '';
		$pass1 = '';
		$pass2 = '';
		
		$adresseMail = '';
		
		
		if(isset($_REQUEST['passAncien'])){
			$passAncien = $_REQUEST['passAncien'];
		}
		if(isset($_REQUEST['pass1'])){
			$pass1 = $_REQUEST['pass1'];
		}
		if(isset($_REQUEST['pass2'])){
			$pass2 = $_REQUEST['pass2'];
		}
		
		if(isset($_REQUEST['adresseMail'])){
			$adresseMail = $_REQUEST['adresseMail'];
		}
		
		
			
		
		

		if($pass1 && $pass2 ==$pass1){
			if(md5($passAncien) == $user->get('pass')){
				$userMAJ->set('pass', md5($pass1));
			}else{
				$message = '<div class="alert alert-danger" role="alert">Votre ancien mot de passe n\'est pas bon.</pdiv>';
				$traitementOk = false;
			}	
			
		}else if($pass1){
			$message = '<div class="alert alert-warning" role="alert">Les deux nouveaux mots de passes ne correspondent pas.</div>';
			$traitementOk = false;
		}

				
		

		if($adresseMail && $traitementOk){
		
			if(Mail::checkMail($adresseMail)=='ok'){
				$userMAJ->set('mail', $adresseMail);
			}else{
				$traitementOk = false;
				$message = '<div class="alert alert-danger" role="alert">Adresse mail non valide.</div>';
			}
			
		}
	
		if($traitementOk){
            
			$arch->modifier($user, $userMAJ); 
			$message = '<div class="alert alert-success" role="alert">Mise a jour effectuée.</div>';
		}
		
		
		
	
		$user = $auth->infoUser();
		$vueAuth->pageProfil($user, $message);
	}
	
	
}


function supprimerCompte(&$vueAuth){
	$auth = new Auth();
	
	
	
	$user = $auth->checkStatut();
	
	if($user && $retour['statut']='connect'){
		$arch=new Archiviste();
		$userD = new User();
		
		$userD->set('id', $user['id']);
		$eventInscrit = new Archivable('EventInscrit');
		$eventInscrit->set('idUser', $user['id']);
		
		$arch->supprimer($userD);
		$arch->supprimer($eventInscrit);
		deconnexion($vueAuth);
	}else{
		$vueAuth->setContentDeco();
	}
}

//========MAIN=============



include('core/controller/menu.php');


session_start();

$vueAuth = new VueAuth();
$vueAuth->setSiteName($nomSite);
 
if(isset($param[1])){

		
	$action = $param[1];	
		
	if($action == 'connexion'){		
		connexion($vueAuth);
		
	}else  if($action == 'deconnexion'){
		deconnexion($vueAuth);
	
	}else  if($action == 'validerCreation'){
		validNew($vueAuth);
		
	}else  if($action == 'creation'){
		creation($vueAuth);
		
	}else if($action=="pageReinitPass"){
		pageReinitPass($vueAuth);
		
	}else if($action=="reinitPass"){
		reinitPass($vueAuth);
		
	}else if($action=="validRecupPass"){
		validRecupPass($vueAuth);
		
	}else if($action == 'activation'){
		activation($vueAuth);
		
	}else if($action == 'profil'){
		pageProfil($vueAuth);
		
	}else if($action == 'majProfil'){
		majProfil($vueAuth);
		
	}else if($action == 'pageSuppress'){
		$vueAuth->pageSuppress();
		
	}else if($action == 'supress'){
		supprimerCompte($vueAuth);
	}
	
}else{
	checkStatut($vueAuth);	
}


$head = $vueAuth->getHead();
$body= $vueAuth->getBody();
$scripts= $vueAuth->getScript();

include($templatePath.'index.php');

?>

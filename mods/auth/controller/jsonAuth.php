<?php
include('../../../core/model/Archivable.php');
include('../../../core/model/Archiviste.php');
include('../../../core/model/Ribosome.php');
include('../../../core/model/Config.php');
include('../../../core/model/Mail.php');
include('../model/Auth.php');
include('../model/User.php');






function enregistrement(){
	$retour = Array();
	
	$login = $_REQUEST['login'];
	$pass = $_REQUEST['pass'];
	$addrMail = $_REQUEST['mail'];
	
	
	
	$resultMail = Mail::checkMail($addrMail);
	
	
	
	if($resultMail=="ok"){
		$nomSite=Config::get('nomSite');
		$adresseSite=Config::get('adresseSite');
		$expediteur = '"'.$nomSite.'"<robot@'.$adresseSite.'>';
	
		$sujet = "Inscription";
		
		$auth = new Auth();
		$token = $auth->genererToken(20);
		$message = 'Bienvenue, et merci de votre enregistrement sur '.$nomSite.PHP_EOL.'<br />'.
			'Pour finaliser votre inscription cliquez sur le lien suivant : <a href="http://'.$adresseSite.'/#p:activation&t:'.$token.'">http://'.$adresseSite.'/#p:activation&t:'.$token.'</a>'.PHP_EOL.PHP_EOL.'<br />'.'<br />'.
			'Vos identifiants sont les suivant :'.PHP_EOL.'<br />'.
			' - Login: '.$login.PHP_EOL.'<br />'.
			' - Password: '.$pass.PHP_EOL.PHP_EOL.'<br />'.'<br />'.
			'Vous pouvez changer a tout moment votre Password dans la section "Profil".'.PHP_EOL.'<br />'.PHP_EOL.'<br />'.
			'Ceci est un message automatique. Merci de ne pas y repondre.';
						
		
		
		
		$retour['pbEnregistrement'] = $pbEnregistrement = $auth->enregistrement($login, $pass, $addrMail, $token);
		
		if($pbEnregistrement=='n'){
			Mail::envoyer($addrMail, $expediteur, $sujet, $message);
		}
		
			
	}
	
	$retour['checkMail'] = $resultMail;
	return $retour;
}


function demandeRecupPass(){

	$addrMail = '';
	if(isset($_REQUEST['mail'])){
		$addrMail =	$_REQUEST['mail'];
	}
	$retour;
	
	$arch = new Archiviste();
	
	$user = new Archivable('User');
	$user->set('mail', $addrMail);
	
	$users = $arch->restituer($user);
	
	
	if(count($users) == 1){
	
		$userTrouve = $users[0];
		$auth = new Auth();
		
		
		//on verifie qu'il n'y a pas deja une demande en cours
		$tokenPresent = $auth->checkToken('mail', $addrMail);
		
		if($tokenPresent){
			$retour = ['recup'=>'doublon'];			
		}else{
		
			
			
			
			$nomSite=Config::get('nomSite');
			$adresseSite=Config::get('adresseSite');
			$expediteur = '"'.$nomSite.'"<robot@'.$adresseSite.'>';
		
			$sujet = "Demande de reinitialisation de votre mot de passe";
			
		
			$cleToken = $auth->genererToken(20);
			$message = 'Bonjour '.$userTrouve->get('login').', <br />'.PHP_EOL.
				'<br />'.PHP_EOL.
				'Vous avez fait une demande de récupération de mot de passe:<br />'.PHP_EOL.
				'<br />'.PHP_EOL.
				'Pour generer un nouveau mot de passe et le recevoir cliquez sur le lien suivant : <a href="http://'.$adresseSite.'/#p:recuPass&t:'.$cleToken.'">http://'.$adresseSite.'/#p:recuPass&t:'.$cleToken.'</a><br />'.PHP_EOL.
				'<br />'.PHP_EOL.
				'Ceci est un message automatique. Merci de ne pas y repondre.';
							
			
			Mail::envoyer($addrMail, $expediteur, $sujet, $message);
			
			$auth->enregistrerRecuPass($addrMail, $userTrouve->get('login'), $cleToken);
			
			$retour = ['recup'=>'envoiOK'];			
		}
	}else{
		$retour = ['recup'=>'userNOK'];
	}
	
	return $retour;
}

function validRecupPass(){
	$cleToken = $_REQUEST['token'];
	
	$auth = new Auth();
	
	$arch = new Archiviste($dbPath);
	$token = new Archivable('Token');
	
	$token->set('cle', $cleToken);
	
	$tokens = $arch->restituer($token);
	
	if(count($tokens)==1){
		$token = $tokens[0];
	
		$newPass = $auth->genererToken(12);
		
		$user = new Archivable('User');
		$userNew = new Archivable('User');
		
		$user->set('mail', $token->get('mail'));
		$userNew->set('pass', md5($newPass));
		
		$nomSite=Config::get('nomSite');
		$adresseSite=Config::get('adresseSite');
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
		
		$retour = ['etatReinit'=>'ok'];
	}else{
		$retour = ['etatReinit'=>'noToken'];
	}
	
	return $retour;
}

function activation(){
	$retour = Array();
	$retour['statut']='ok';
	
	$token = $_REQUEST['token'];
	
	
	$auth = new Auth();
	$retour['statut']= $auth->checkActivation($token);
	
	return $retour;
}


function connexion($auth){
	$login = $_REQUEST['login'];
	$pass = $_REQUEST['pass'];
	
	return $auth->connexion($login, $pass);
}

//###############################################
//############### MAIN ##########################
session_start();

$retour = [];
 
if(isset($_REQUEST['action'])){

		
	$action = $_REQUEST['action'];
	
	$auth = new Auth();
		
	if($action == 'connexion'){		
		$retour = connexion($auth);
		
	}else if($action == 'statut'){
		$retour = $auth->checkStatut();		
		
	}else  if($action == 'deconnexion'){
		$retour = $auth->deconnexion();
	
	}else  if($action == 'enregistrement'){
		$retour = enregistrement();
		
	}else if($action=="demandeRecupPass"){
		$retour = demandeRecupPass();
		
	}else if($action=="validRecupPass"){
		$retour = validRecupPass();
		
	}else if($action == 'activation'){
		$retour = activation();
	}
	
	
	$retour['checkProc']='ok';
}


//return
echo(json_encode($retour));
?>

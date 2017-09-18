<?php

//Connecte un utilisateur
class Auth {


	
	public function connexion($login, $password){
		$retour = Array('statut'=>'noUser');
		
				 
		$arch = new Archiviste();
		$user = new User(); ;
		$user->set('login', $login);
		$users = $arch->restituer($user);
		$user = false;
		if(count($users) > 0){
			$user = $users[0];
		}
	
	
		if($user && $user->get('pass') == md5($password) ){
		
			$droits = $user->get('droits');
			$id = $user->get('id');
			$_SESSION['login'] = $login;
			$_SESSION['droits'] = $droits;
			$_SESSION['id']= $id;
			$retour['id']=$id; 
			$retour['statut'] = 'connect';
			$retour['droits'] = $droits;
			$retour['login']=$login;
				
		}else{
			$retour['statut']='noPass';
		}
	
		return $retour;
	}

	//Vide la session
	public function deconnexion(){
		
		$retour = Array();
		$retour['statut']='ok';
	
		session_destroy();
		unset($_SESSION);
		
		return $retour;
	
	}



	//verifie si l'admin est donnecté
	public function checkStatut(){
		
		$retour = Array();
		$retour['statut']='deco';
		if(isset($_SESSION['login'])){
			$retour['statut']='connect';
			$retour['login']=$_SESSION['login'];
			$retour['droits']=$_SESSION['droits'];
			$retour['id']=$_SESSION['id'];
		}
	
		return $retour;
	
	}
	
	public function enregistrerRecuPass($mailAddr, $login, $tokenVal){
		//on en profite pour nettoyer les demande de comptes expirés.
		$this->cleanExpire();
		
		$arch = new Archiviste();
		$token = new Token();;
		$token->set("cle", $tokenVal);
		$token->set("login", $login);
		$token->set("mail", $mailAddr);
		
		$nextDay = time() + (24 * 60 * 60);
		$token->set("expire", $nextDay);	
		
		
		$arch->archiver($token);
	}
	
	public function enregistrement($login, $pass, $mail, $tokenVal){
		//on en profite pour nettoyer les demande de comptes expirés.
		
		
		$this->cleanExpire();
	
		$arch = new Archiviste();
		
		
		$user = new User();
		$user->set('login', $login);
		$users1 = $arch->restituer($user);
		
		
		$user = new User();;
		$user->set('mail', $mail);
		$users2 = $arch->restituer($user);
		
		
	
		if(count($users1) == 0 && count($users2)==0){
			
			
			$result='ok';
			$token = new Token();;
			$token->set("cle", $tokenVal);
			$token->set("login", $login);
			$token->set("pass", $pass);
			$token->set("mail", $mail);
			
			$nextDay = time() + (24 * 60 * 60);
			$token->set("expire", $nextDay);	
			
			
			$arch->archiver($token);
			
		}else if(count($users1) > 0){
			$result='loginUsed';
		}else if(count($users2) > 0){
			$result='mailUsed';		
		}
		
		
		return $result;
	}
	
	public function listerUsers(){
		//creation des objets qui vont contenir leur fonctions respectives
		
		 
		$arch = new Archiviste();
		$users = new User();		
		
		//ici on va recuperer les section dans la BDD
		$usersListe = $arch->restituer($users);
		
		
		
		return $usersListe;	
	}
	
	
	public function infoUser(){	
		
		$userFound = false;
		if(isset($_SESSION['id'])){
				
			 
			$arch = new Archiviste();
			$user = new User();		
			$user->set('id', $_SESSION['id']);
			
			//ici on va recuperer les section dans la BDD
			$usersListe = $arch->restituer($user);
			
			if(count($usersListe) == 1){
				$userFound = $usersListe[0];
			}
		}
		
		
		//creation des objets qui vont contenir leur fonctions respectives
		
			
		return $userFound ;
	}
	
	
	public function checkActivation($cle){
	
		$this->cleanExpire();
		
		$retour ='nok';
		
		$arch = new Archiviste();
		$token = new Token();;
		$token->set('cle', $cle);
		$tokens = $arch->restituer($token);
		
		if(count($tokens) >0){
			$login = $tokens[0]->get('login');
			$pass = $tokens[0]->get('pass');
			$mail = $tokens[0]->get('mail');
			
			
			$user = new User();
			$user->set('login', $login);
			$users1 = $arch->restituer($user);			
			
			$user = new User();
			$user->set('mail', $mail);
			$users2 = $arch->restituer($user);
			
			if(count($users1) == 0 && count($users2)==0){
				$user->set('login', $login);
				$user->set('pass', md5($pass));
				$user->set('droits', "standard");
				$arch->archiver($user);
				$arch->supprimer($token);
				$retour ='ok';
			}else{
				$retour ='duplicate';
			}
			
		}
		return $retour;
	}
	
	//genere une chaine de caracteres aleatoire.
	public function genererToken($nbCar){
		$string = "";
		$chaine = "abcdefghijklmnpqrstuvwxy0123456789";
		srand((double)microtime()*time());
		for($i=0; $i<$nbCar; $i++) {
			$string .= $chaine[rand()%strlen($chaine)];
		}
		return $string;	
	}
	
	//true si token trouvé. false sinon.
	public function checkToken($champ, $addrMail){
		
		$retour =true;
		$arch = new Archiviste();
		$token = new Token();
		$token->set($champ, $addrMail);
		
		
		$tokens = $arch->restituer($token);
		
		if(count($tokens)==0){
			$retour =false;
		}else{
			$retour =$tokens[0];
		}
		
		return $retour;
	}
	
	
	//---------fonction privées----------
	
	
	private function cleanExpire(){	
		$arch = new Archiviste();
		
		$token = new Token();
		$tokens = $arch->restituer($token);
		
		$toDay = time();
		
		foreach($tokens as $tokenTemp){
			$token = new Token();
			$token->setAttributs($tokenTemp->getAttributs());
			$expire = $token->get("expire");			
			if($expire < $toDay){
				
				$arch->supprimer($token);
			}
		}
		
		
	}
	
	
}
?>
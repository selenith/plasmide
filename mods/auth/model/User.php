<?php

//Connecte un utilisateur
class User extends Archivable{

	public function __construct(){
		
		$this->type = 'User';
		
	}
	
	public static function getDbPath(){
		
		if(preg_match("/auth\/controller/", $_SERVER['PHP_SELF'])){
			return '../data/';
		}else if(preg_match("/mods/", $_SERVER['PHP_SELF'])){
			return '../../auth/data/';
		}else if(preg_match("/router\.php/", $_SERVER['PHP_SELF'])){
			return 'mods/auth/data/';
		}else {
			return false;
		}
		
	}
	
	
	public function listerUsers(){
		//creation des objets qui vont contenir leur fonctions respectives
		
		 
		$arch = new Archiviste();
		$user = new User();		
		
		//ici on va recuperer les section dans la BDD
		$usersListe = $arch->restituer($user);
		$usersListe = $arch->trier($usersListe , 'login', true);
		
		
		return $usersListe;	
	}
	
	
	
}
?>

<?php

class Token extends Archivable{

	public function __construct(){
		
		$this->type = 'Token';
		
	}
	
	public static function getDbPath(){
		
		if(preg_match("/auth\/controller/", $_SERVER['PHP_SELF'])){
			return '../data/';
		}else if(preg_match("/mods/", $_SERVER['PHP_SELF'])){
			return '../../auth/data/';
		}else if(preg_match("/index\.php/", $_SERVER['PHP_SELF'])){
			return 'mods/auth/data/';
		}else {
			return false;
		}
		
	}
	
}

?>
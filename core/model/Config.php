<?php
class Config extends Archivable{
    
    
    public function __construct(){
		
		$this->type = 'Config';
		
	}
	
	public static function getDbPath(){
		
		if(preg_match("/mods/", $_SERVER['PHP_SELF'])){
			return '../../../core/data/';
		}else if(preg_match("/index\.php/", $_SERVER['PHP_SELF'])){
			return 'core/data/';
		}else {
			return false;
		}
		
	}
    
    
	public static function getConf(){
		$arch = new Archiviste();
		$conf = new Archivable('Config');
		$confs = $arch->restituer($conf);
		$conf = $confs[0];
		
		return $conf;
	}	
	
	public static function getVal($champ, $dataBase = 'core/data/'){
		
				
		$arch = new Archiviste($dataBase);
		$conf = new Config('Config');
		$conf->set('nom', $champ);
		$confs = $arch->restituer($conf);
				
		$valeur = false;
		
		if(count($confs)>0){
			$valeur = $confs[0]->get('val');
		}
		
		return $valeur;
	}
	
	public static function setVal($champ, $valeur){
			
		$arch = new Archiviste();
		$conf = new Archivable('Config');
		$conf->set('nom', $champ);
		$newConf = new Archivable('Config');
		$newConf->set('val', $valeur);
		
		$confs = $arch->modifier($conf, $newConf);
	}
	
	
}
?>
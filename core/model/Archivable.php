<?php
/*	
	-Classe Archivable
	Objet generique permettant d'enregistrer des données
	pouvant etre utilisées avec le systeme de persistance
	
	-version : 2.1
	-date : 2012
	-auteur : Selenith
	-license : Creative commons 2.0 CC-BY-SA
	
------------------------
fonctions 

	-Constructeur(type) : le parametre est optionnel
		Prend en parametre le nom de l'objet a archiver
	
	-getType()
		indique le nom de l'objet archivable
	
	-set(champ, valeur)
		Modifie la valeur du champ passé en paramètre
	
	-get(champ)
		Renvoi la valeur du champ passé en paramètre
		
	-getAttributs()
		Fonction permettant de donner à l'archiviste la liste des attributs a archiver
	
	-setAttributs(attributs)
		Fonction permettant de donner à l'archivable la liste des attributs fournit par l'archiviste
*/
class Archivable{
	
	protected $type;
	protected $attributs = array();
	
	public static function getDbPath(){
		return false;		
	}
	
	public function getType(){
		return $this->type;
	}	
	
	public function __construct($type = 'default'){		
		$this->type = $type;		
	}	
	
	public function setAttributs($attributs){		
		$this->attributs =  $attributs;
	}
	
	public function getAttributs(){
		return $this->attributs;
	}
	
	public function set($champ, $valeur){		
		$this->attributs[$champ] = $valeur;		
	}

	public function get($champ){	
		$valeur = null;		
		if(isset($this->attributs[$champ])){		
			$valeur = $this->attributs[$champ];
		}			
		return $valeur ;
	}
	
	
}

?>
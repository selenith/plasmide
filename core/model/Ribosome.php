<?php
/*
-Classe Ribosome
	Objet generique permettant l'interfacage entre le systeme
	de persistance et le programme.
	
	
	-version : 2.1 2012
	-auteur : Selenith
	-license : Creative commons 2.0 CC-BY-SA


*/

class Ribosome{	
	
	//attributs	
	private $cheminBase ;	
	private $cheminTable;	
	private $nomTable;
	
	public function __construct($cheminBase = '../data/'){	
		$this->cheminBase = $cheminBase;
	}
	
	
	/**
	Permet de se connecter au fichier.
	Retourne le contenu du fichier.
	*/
	public function connexion($nomTable){
		$this->cheminTable = $this->cheminBase.$nomTable;
		$this->nomTable =$nomTable;
		if(!file_exists($this->cheminTable)){			
                        die('<b>Error in '.$_SERVER['PHP_SELF'].'</b> : Le dossier '.$this->cheminTable.' n\'est pas present dans la base de données. <br />'.PHP_EOL);
                        
		}
	}
	
	
	/**
		Inclue les fichier
	*/
	public function donnerTraduction($attributs){
		
		//chargement de la conf de la table
        	require($this->cheminBase.'/'.$this->nomTable.'.php');
		
		$key  = $structure;
		$nbKey = count($key);
		
		//print_r($key);
		$masque = "";
		if(isset($attributs['id'])){		
			$masque = $attributs['id'];			
		}else{					
			$masque="*";
			
		}
					
		for($i=0; $i<$nbKey; $i++){				
			if(isset($attributs[$key[$i]])){
				
				$masque.='-'.$attributs[$key[$i]];
			}else{
				$masque.='-*';
			}
		}
				
		$masque.='.php';
		
		
		$HISTONE = array();				
		$compteur = 0;
		
		$listeFiles = glob($this->cheminTable.'/'.$masque);
				
		if($listeFiles){
			foreach ($listeFiles as $file) {
				
				include($file);		
				$champs = $this->extractKey($file);				
				$histone['id'] = $champs[0];
				for($i=0; $i<$nbKey; $i++){	
				
					$histone[$key[$i]] = $champs[$i+1];				
				}			
				$HISTONE[$compteur] = $histone;
				$compteur++;
			}	
		}		
			
		return $HISTONE;	
	}
	
	
	/**
	Ecrit le tableau de table de hachage passé en parametre dans le fichier
	*/
	public function ecrire($attributs){
		//chargement de la conf de la table
        	require($this->cheminBase.'/'.$this->nomTable.'.php');
		
		$key  = $structure;
		$nbKey = count($key);
		
		$trans = array('\\' => '\\\\', "'" => "\'");		
				
		$nomFichier = $attributs['id'];
		unset($attributs['id']);
		
		$nbAttr = count($attributs);
		
		for($i = 0 ; $i < $nbKey ;  $i++){
			if(isset($attributs[$key[$i]])){
					
				$nomFichier.='-'.$attributs[$key[$i]];				
				unset($attributs[$key[$i]]);
			}else{
				$nomFichier.='-n';
			}
		}
				
		$nomFichier .='.php' ;		
		$contenuFicher = '<?php'.PHP_EOL.'$histone = array('.PHP_EOL;		
		$virgule = false;			
		foreach($attributs as $attribut => $valeur){
			
			$valeur = strtr($valeur,$trans);
			if($virgule){
				$contenuFicher .=','.PHP_EOL.'\''.$attribut.'\'=>\''.$valeur.'\'';
			}else{
				$contenuFicher .= '\''.$attribut.'\'=>\''.$valeur.'\'';
				$virgule = true;
			}
			
		}			
		$contenuFicher .= ');'.PHP_EOL.'?>';				
		file_put_contents($this->cheminTable.'/'.$nomFichier, $contenuFicher, LOCK_EX);		
	}
		
	
	public function supprimer($id){
			
		//chargement de la conf de la table
        	require($this->cheminBase.'/'.$this->nomTable.'.php');
		
		$key  = $structure;
		$nbKey = count($key);
		
		$nbSuppr = 0;		
		$masque = $id;				
		if($nbKey > 0){
			$masque .= '-*';		
		}
		$masque.='.php';
		foreach (glob($this->cheminTable.'/'.$masque) as $file) {
			unlink($file);
			$nbSuppr ++;
		}
		return $nbSuppr;
	}
	
	public function indexMax(){

		$listeId = array();
		
		$dir = dir($this->cheminTable);					
			while($nom = $dir->read()){
				if($nom != '.' && $nom != '..'){
					//on prend la partie id du nom du fichier
					
					$nom = substr ( $nom , 0 , strlen($nom)-4 );
					$attr = explode("-", $nom);
					$listeId[] = $attr[0];
				}
			}
		
		$indexMax = 0;
		if(count($listeId) > 0){
			$indexMax = max($listeId);
		}
		return $indexMax;
	}
	
	
	private function extractKey($nom){
		$nom = str_replace($this->cheminTable.'/', '', $nom);
		$nom = str_replace('.php', '', $nom);
		
		$key = explode("-", $nom);
		return $key;
	}
}

?>

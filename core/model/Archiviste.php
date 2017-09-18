<?php

/**
Classe Archiviste
Permet de manipuler les données des fichiers ainsi que de faire des recherches
en utilisant des objets issue de la classe Archivable.

/!\ Pensez a modifier l'attribut $cheminBase en fonction de l'architecture 
de votre programme

-auteur : Selenith
-version : 2.1  2012
-license : Creative commons 2.0 CC-BY-SA

------------------------
fonctions :
	-archiver($archivable) :
		Enregistre l'objet dans la table correspondante.
		Le champ "id" est auto-incrementé.
		Renvoi l'id de l'objet enregistré.
	
	-restituer(archivable) :
		Renvoi la liste des objet dont les attributs correspondent au attributs
		non null de l'objet passé en parametre		
		Renvoi Archivable[] la liste des objet contenu dans la table en fonction des criteres de recherche.
		
	-rechercher($archivable) :
		Renvoi la liste des objet dont les attributs ressemblent au attributs
		non null de l'objet passé en parametre		
		Renvoi Archivable[] la liste des objet contenu dans la table en fonction des criteres de recherche.

	-modifier($archivableAncien, $archivableNouveau) :
		Modifie les attributs des objets archivables $archivableAncien par les attributs de l'objet $archivableNouveau
		La recherche des objets et basé sur les attributs non vides et non null de l'objet $archivableAncien.
		Seul les attribut non nulls du nouvel objet ecraseron les attributs de l'ancien		
		Renvoi true si une modification à été effectuée.
		
	-supprimer($archivable) :
		Fonction supprimant du systeme de persistance les objet
		correspondant a l'archivable passé en parametre
		Renvoi true si une suppression à été effectuée.
		
	-trier($liste, $critere, $sens)
		Permet de trier une liste d'objet archivables en fonction
		d'un de ses attributs.
		si $sens = true : ordre alphabetique
		si $sens = false : ordre inverse
		Retourne la liste des objets trié.
			

*/
class Archiviste{	
	
	//A modifier au besoin
	private $cheminBase = '../data/';
	
	function Archiviste($dataPath = 'core/data/'){
		//initialisation de la position de la database
		// data/ si l'index du site est utilisé.
		// ../data/ si c'est une requete asynchrone vers un controleur.
			
		
		$this->cheminBase = $dataPath;
	}
		
	
	//enregistre l'objet dans la table correspondante
	public function archiver($archivable){
		
		//recuperation du nom de la table
		$nomTable = $archivable->getType();	
		
		$dbPath = $this->cheminBase;
		if($archivable::getDbPath()){
			$dbPath = $archivable::getDbPath();
		}
		
		//creation d'un accesseur a la base de donnée
		$ribosome = new Ribosome($dbPath);
		//etablisement de la connexion et verouillage de la table
		$ribosome->connexion($nomTable);		
		$index = $archivable->get('id');
		if(isset($index)){
				
			$archivable->set('id', $index);
		}else{
			$index = $ribosome->indexMax() +1;		
			$archivable->set('id', $index);
		}
		
		$attributs = $archivable->getAttributs();	
		
		//$elements[$index] = $attributs;
				
		//$ribosome->ecrire($elements);	
		$ribosome->ecrire($attributs);	
		//le retour est l'index de l'élément créé
		return $index;
	}
	
	/**
	Renvoi la liste des objet dont les attributs correspondent au attributs
	non null de l'objet passé en parametre
	@return Archivable[] la liste des objet contenu dans la table en fonction des criteres de recherche.
	*/
	public function restituer($archivable){
	
		$nomTable = $archivable->getType();
		$dbPath = $this->cheminBase;
		if($archivable::getDbPath()){
			$dbPath = $archivable::getDbPath();
		}
		
		//creation d'un accesseur a la base de donnée
		$ribosome = new Ribosome($dbPath);
		$ribosome->connexion($nomTable);
		
		$elemsTrouve = array();		
			
		$attributs = $archivable->getAttributs();
		
		
		$elements = $ribosome->donnerTraduction($attributs);			
		
				
		$nbCles = count($elements);	
		
		$index = 0;
		
		for($i = 0; $i< $nbCles; $i++){			
			if($this->correspond($elements[$i], $attributs)){				
			
				$elemsTrouve[$index] = new Archivable($nomTable);
				$elemsTrouve[$index]->setAttributs($elements[$i]);
				$index++;					
			}
		}
					
	
		return $elemsTrouve;
	}
	
	
	
	/**
	Renvoi la liste des objet dont les attributs ressemblent au attributs
	non null de l'objet passé en parametre
	@return Archivable[] la liste des objet contenu dans la table en fonction des criteres de recherche.
	*/
	public function rechercher($archivable, $chaine_recherche_avancee = NULL){
				
		$nomTable = $archivable->getType();
		$dbPath = $this->cheminBase;
		if($archivable::getDbPath()){
			$dbPath = $archivable::getDbPath();
		}
		
		//creation d'un accesseur a la base de donnée
		$ribosome = new Ribosome($dbPath);
		$ribosome->connexion($nomTable);
		
		$elemsTrouve = array();		
			
		$attributs = $archivable->getAttributs();
		
		
		$elements = $ribosome->donnerTraduction($attributs);			
		
				
		$nbCles = count($elements);	
		
		$index = 0;
		
		for($i = 0; $i< $nbCles; $i++){			
			//if($this->contient($elements[$i], $attributs)){				
			if(($chaine_recherche_avancee == NULL && $this->contient($elements[$i], $attributs)) || ($chaine_recherche_avancee <> NULL && $this->contient_avance($elements[$i], $attributs, $chaine_recherche_avancee))){
	
				$elemsTrouve[$index] = new Archivable($nomTable);
				$elemsTrouve[$index]->setAttributs($elements[$i]);
				$index++;					
			}
		}
					
	
		return $elemsTrouve;
	}	
	
	
	
	/**
	Modifie les attributs des objets archivables $ancien par les attributs de l'objet $nouveau
	La recherche des objets et basé sur les attributs non vides et non null de l'objet $actuel.
	Seul les attribut non nulls du nouvel objet ecraseron les attributs de l'ancien
	renvoi true si une modification à été effectuée
	*/
	public function modifier($ancien, $nouveau){		
		//recuperation du nom de la table
		$nomTable = $nouveau->getType();				
		
		$dbPath = $this->cheminBase;
		if($nouveau::getDbPath()){
			$dbPath = $nouveau::getDbPath();
		}
		
		//creation d'un accesseur a la base de donnée
		$ribosome = new Ribosome($dbPath);
		$ribosome->connexion($nomTable);	
		
		$attributsNew = $nouveau->getAttributs();	
						
		$attributsAncien = $ancien->getAttributs();						
		//recuperation de la traduction de la table sous forme de tableau
		$elements = $ribosome->donnerTraduction($attributsAncien);
					
		
		
		$nbElements = count($elements);
		$nbModif = 0;
		
		for($i = 0; $i< $nbElements; $i++){			
			if($this->correspond($elements[$i], $attributsAncien)){				
				//recuperation et modification des attributs
				$nbModif++;
				$ribosome->supprimer($elements[$i]['id']);
				
				$attributs = $attributsNew;
				foreach($elements[$i] as $cle => $valeur){
					if(!isset($attributs[$cle])){
						$attributs[$cle] = $valeur;
					}
				}
				
				
				$ribosome->ecrire($attributs);	
				
											
			}
		}				
				
		return $nbModif;
	}

	
	/**
	Fonction supprimant du systeme de persistance les objet
	correspondant a l'archivable passé en parametre
	renvoi true si une suppression à été effectuée
	*/
	public function supprimer($archivable){	
		
		$nomTable = $archivable->getType();					
		
		$dbPath = $this->cheminBase;
		if($archivable::getDbPath()){
			$dbPath = $archivable::getDbPath();
		}
		
		//creation d'un accesseur a la base de donnée
		$ribosome = new Ribosome($dbPath);
		$ribosome->connexion($nomTable);		
		
		//on verifie si on peut utilisé l'index de l'objet
		$id = $archivable->get('id');
		
		$nbSuppr = 0;
		
		//si on cherche l'element par son id
		if (isset($id)){			
			
			$nbSuppr = $ribosome->supprimer($id);
			
		}else{		
			//si on cherche l'element par autre chose			
			//recuperation des attributs sous forme de tableau
			$attributs = $archivable->getAttributs();
					
			
			$elements = $ribosome->donnerTraduction($attributs);				
			$nbElem = count($elements);			
							
			for($i = 0; $i< $nbElem; $i++){			
				if($this->correspond($elements[$i], $attributs)){					
					
					$nbSuppr += $ribosome->supprimer($elements[$i]['id']);
				}
			}						
		}		
		
		return $nbSuppr;
	}
	
	
	//Permet de trier une liste d'objet archivables en fonction
	//d'un de ses attributs.
	//si $sens = true : ordre alphabetique
	//si $sens = false : ordre inverse
	public function trier($liste, $critere, $sens){	
	//si sens = true, alpha, sinon tri inverse
		$temp ;	
		$est_modif = true;
		
		while($est_modif){
			$est_modif = false;
			for($i = 1 ; $i < count($liste); $i++){
				
				$comparaison = strcmp ($liste[$i]->get($critere),  $liste[($i-1)]->get($critere));
				if(($sens && $comparaison < 0 ) || (!$sens && $comparaison > 0 )){
					$temp = $liste[$i];
					$liste[$i] = $liste[($i-1)];
					$liste[($i-1)] = $temp ;
					$est_modif = true;
				}
			}
		}
		return $liste; 
	}
	
	public function trierNumCroissant($liste, $critere){	
	
		$temp ;	
		$est_modif = true;
		
		while($est_modif){
			$est_modif = false;
			for($i = 1 ; $i < count($liste); $i++){				
				
				if(intval($liste[$i]->get($critere)) < intval($liste[($i-1)]->get($critere))){
					$temp = $liste[$i];
					$liste[$i] = $liste[($i-1)];
					$liste[($i-1)] = $temp ;
					$est_modif = true;
				}
			}
		}
		return $liste; 
	}
	
	public function trierNumDecroissant($liste, $critere){	
	
		$temp ;	
		$est_modif = true;
		
		while($est_modif){
			$est_modif = false;
			for($i = 1 ; $i < count($liste); $i++){				
				
				if(intval($liste[$i]->get($critere)) > intval($liste[($i-1)]->get($critere))){
					$temp = $liste[$i];
					$liste[$i] = $liste[($i-1)];
					$liste[($i-1)] = $temp ;
					$est_modif = true;
				}
			}
		}
		return $liste; 
	}
	
	private function correspond($elemBase, $elemNouv){		
		$egal = true;		
		foreach($elemNouv as $cle => $valeur){
			if(isset($elemBase[$cle])){
				if($valeur != $elemBase[$cle]){						
					$egal = false;						
				}
			}else{
				$egal = false;
			}			
		}		
		return $egal ;
	}	
	
	private function contient($elemBase, $elemNouv){
		
		
		$egal = false;		
		foreach($elemNouv as $cle => $valeur){
			if(isset($elemBase[$cle])){			
				if(preg_match('/.*'.$valeur.'.*/i', $elemBase[$cle]) ){					
					$egal = true;					
				}				
			}			
		}		
		return $egal ;
	}
	
	
	//#############fontions pour recherche avancée###################
	
	private function exploding_multi_reg($chaine){
		$returnarray = array();
		$increment = 0;
		$parenthese = 0;
		$returnarray[0]='';
		
		$taille_chaine = strlen($chaine);
		for($i = 0; $i < $taille_chaine ; $i++){
			$current = substr($chaine, $i, 1);
			if($current == '(' && substr($chaine, $i-1, 1)<> '\\' ){
				$returnarray[$increment].=$current;
				$parenthese++;
			}elseif($current == ')' && substr($chaine, $i-1, 1)<> '\\' ){
				$returnarray[$increment].=$current;
				$parenthese--;
			}elseif($current == ' ' && $parenthese==0){
				$increment++;
				$returnarray[$increment]='';
			}else{
				$returnarray[$increment].=$current;
			}
		}
		return $returnarray;
	}

	
	private function string_to_multi_reg($chaine_recherche){
		
		// On échappe les caractères spéciaux
		$chaine_recherche = preg_quote($chaine_recherche);
		$chaine_recherche = str_replace('/', '\/', $chaine_recherche);
		
		// Remplacement des " par des ( et des )
		$chaine_recherche = preg_replace('/"([^"]+)"/', ' ( \1 ) ', $chaine_recherche);
		
		// remplacement de ' OR ' par '|'
		$chaine_recherche = preg_replace('/ +OR +/', '|', $chaine_recherche);
		
		// remplacement de ' - ' par ' -'
		$chaine_recherche = preg_replace('/ +- +/', ' -', $chaine_recherche);
		
		//A l'intérieur des "" les OR sont entourés de ()
		$chaine_recherche = preg_replace('/ ([^\(\) |]+(\|[^\(\) |]+)+)(?= )/', ' (\1)', $chaine_recherche);

		//suppression des espaces en trop
		$chaine_recherche = preg_replace('/  +/', ' ', $chaine_recherche);
		$chaine_recherche = preg_replace('/(\A )|( \z)/', '', $chaine_recherche);
		$chaine_recherche = str_replace('( ', '(', $chaine_recherche);
		$chaine_recherche = str_replace(' )', ')', $chaine_recherche);

		return $this->exploding_multi_reg($chaine_recherche);
	}
	
	private function Sup_Accents($texte){
		$texte = strtolower($texte);
		$texte = str_replace(array('à', 'â', 'ä', 'á', 'ã', 'å', 'î', 'ï', 'ì', 'í', 'ô', 'ö', 'ò', 'ó', 'õ', 'ø', 'ù', 'û', 'ü', 'ú', 'é', 'è', 'ê', 'ë', 'ç', 'ÿ', 'ñ'			),
							array('a', 'a', 'a', 'a', 'a', 'a', 'i', 'i', 'i', 'i', 'o', 'o', 'o', 'o', 'o', 'o', 'u', 'u', 'u', 'u', 'e', 'e', 'e', 'e', 'c', 'y', 'n'			),
							$texte
							);
		return $texte;        
	}
	
	private function contient_avance($elemBase, $elemArchi, $chaine_recherche){

		//transformation de la chaine
		$liste_reg = $this->string_to_multi_reg($chaine_recherche);
		
		$nbAttrib = count($elemArchi);
		$clefs=array_keys($elemArchi);
		$nbReg = count($liste_reg);
		
		$tous_ok = true;
		for($i=0; $i< $nbReg && $tous_ok; $i++){
			if (substr($liste_reg[$i], 0, 1)=='-'){
				$trouver = false;
				$liste_reg[$i] = substr($liste_reg[$i], 1, strlen($liste_reg[$i]));
			}else{
				$trouver = true;
			}
			$egal = !$trouver;
			//##########################################

			
			for($j=0; $j< $nbAttrib && $egal == !$trouver; $j++){
				if(isset($elemBase[$clefs[$j]]) && $elemArchi[$clefs[$j]] <> 'id'){ //si un id est spécifié, on le saute
					if( preg_match('/.*'.$this->Sup_Accents($liste_reg[$i]).'.*/i', $this->Sup_Accents($elemBase[$clefs[$j]])) ){
						$egal = $trouver;
					}
				}
			}
			//###########################################
			$tous_ok = $egal;
		}
		
		return $tous_ok ;
		
	}
	
	//###############################################################
	
	
	
	
}
?>
<?php 

class Mail{

	public static function envoyer($destinataire, $expediteur, $sujet, $message){
				
		
		$headers = 'MIME-Version: 1.0' . "\r\n";
		$headers.= 'Content-type: text/html; charset=UTF-8' . "\r\n";
		$headers.= 'From: '.$expediteur."\r\n";
		
		
		
		mail ($destinataire, $sujet, $message, $headers);
	
	}
	
	public static function checkMail($email){
	
			$retour;
			$format_valide = preg_match('/^(\w|-|\.)+@((\w|-)+\.)+[a-z]{2,6}$/i', $email);
	
	
			//verification serveur
			
			
			if($format_valide){
				$domaine_valide = false;
				$domaine = false;
				list($compte, $domaine)=explode('@', $email, 2);
		
				if($domaine){
					$domaine_valide = checkdnsrr($domaine, "MX") || checkdnsrr($domaine, "A");
				}
				
				if($domaine_valide){
					$retour = 'ok';	
				}else{
					$retour = 'badDomain';
				}
			}else{
				$retour = 'mailPourri';	
			}
				
		return $retour;
	}
	
}

?>

<?php
//mise en place du script lié au module de forum
class VueAuth{
	
	private $body ='';
	private $head ='';
	private $nomSite ='';
	private $script ='';

	private $formAuth = '
                <div class="formAuth text-center">
                    <form method="post" action="/auth/connexion">
                     <div class="form-group">
                        <label for="login">Login</label>
                        <input type="text" class="form-control" id="login" name="login" placeholder="Login">
                    </div>
                    <div class="form-group">
                        <label for="pass">Password</label>
                        <input type="password" class="form-control" id="pass"  name="pass" placeholder="Pass">
                    </div>
                    <input type="submit" class="btn btn-default" value="Connexion" id="envoi">
                    </form>
                </div>';
				
	private $formEnregistrement = '
                <div class="formAuth text-center">
                    <form method="post" action="/auth/validerCreation">
                        <div class="form-group">
                            <label for="login">Login</label>
                            <input type="text" class="form-control" id="login" name="login" placeholder="Login">
                        </div>
                        <div class="form-group">
                            <label for="pass">Password</label>
                            <input type="password" class="form-control" id="pass"  name="pass" placeholder="Pass">
                        </div>
                        <div class="form-group">
                            <label for="mail">Adresse mail</label>
                            <input type="email" class="form-control" id="mail" name="mail" placeholder="Email">
                        </div>
                        <input type="submit" class="btn btn-default" value="S\'enregistrer" id="envoi">
                    </form>
                </div>';
				
				
	public function getHead(){
		return $this->head.'
                    <link rel="stylesheet" href="mods/auth/style/authMod.css" type="text/css" media="screen"  />';	
	}

	public function getBody(){
		return '<div class="pageAuth" >'.$this->body.'</div>';
	}
	
	public function getScript(){
		return $this->script;
	}
	
	public function setSiteName($siteName){
		$this->nomSite = $siteName;
	}
	
	public function setContentDeco(){
		
		
		
		$this->body ='<div id="conteneurAuth">'.SiteConfig::forgerBlock('Connexion',$this->formAuth).'
		</div>';

		$this->head = '<title>'.$this->nomSite.' - Authentification </title>'.PHP_EOL;
	}

	public function setContentConnect(){		
		$this->body ='<div id="conteneurAuth">'.SiteConfig::forgerBlock('Connecté','vous etes connecté').'
		</div>';

		$this->head = '<title>'.$this->nomSite.' - Authentification </title>'.PHP_EOL;
	}

	public function setContentConnectOK(){
		
		$texte ='vous êtes maintenant connecté. <br /><br />
			<a href="/auth/profil"> Voir mon Compte </a> <br /><br />';
			
		//pour la redirection on verifie que l'utilisateur ne vient pas deja de cette page

		if(isset($_SERVER['HTTP_REFERER'])){
			$dejaIci = strstr($_SERVER['HTTP_REFERER'], 'mod=auth');
			if(!$dejaIci){
				$texte.='Vous pouvez <a href="'.$_SERVER['HTTP_REFERER'].'">retourner à la page précédente</a>';
				$this->script = '<script type="text/javascript" > $(document).ready(function(){history.back();});</script>';
			}			
		}			
	
		$this->body ='<div id="conteneurAuth">'.SiteConfig::forgerBlock('Connecté',$texte).'
		</div>';

		$this->head = '<title>'.$this->nomSite.' - Connexion effectuée </title>'.PHP_EOL;
	}
	
	public function setContentBadPass(){

		$formulaire = '
                <div class="alert alert-danger" role="alert">Mauvais Password </div>
                '.$this->formAuth.'
                <p class="text-center">
                    <a href="/auth/pageReinitPass">J\'ai oublié mon mot de passe</a>
                </p>'; 
				
				
		$this->body =SiteConfig::forgerBlock('Erreur',$formulaire);
		$this->head = '<title>'.$this->nomSite.' - Erreur </title>'.PHP_EOL;
	}

	public function setContentNoUser(){

		$formulaire = '<div class="alert alert-danger" role="alert">Compte utilisateur inconnu </div>'.$this->formAuth; 
				
				
		$this->body =SiteConfig::forgerBlock('Erreur',$formulaire);
		$this->head = '<title>'.$this->nomSite.' - Erreur </title>'.PHP_EOL;
	}
	
	
	public function setContentDecoOk(){
		$texte ='Vous êtes a présent déconnecté. <br /><br />';
		if(isset($_SERVER['HTTP_REFERER'])){
				$texte.='Vous pouvez <a href="'.$_SERVER['HTTP_REFERER'].'">retourner à la page précédente</a>';
				$this->script = '<script type="text/javascript" > $(document).ready(function(){history.back();});</script>';
			}
		$this->body =SiteConfig::forgerBlock('Déconnexion',$texte);
		$this->head = '<title>'.$this->nomSite.' - Déconnexion </title>'.PHP_EOL;
	}
	
	public function setContentMailFail(){
		$texte ='<div class="alert alert-danger" role="alert">Format d\'adresse mail invalide.</div> ';
		
		$this->body =SiteConfig::forgerBlock('Nouveau compte',$texte);
		$this->head = '<title>'.$this->nomSite.' - Nouveau compte </title>'.PHP_EOL;
	}
	
	
	public function setNewAccountOk(){
		$texte ='<div class="alert alert-success" role="alert">Merci de votre inscription.</div>
				<p>Un mail de validation vient de vous etre envoyé.</p>';
		
		$this->body =SiteConfig::forgerBlock('Nouveau compte',$texte);
		$this->head = '<title>'.$this->nomSite.' - Nouveau compte </title>'.PHP_EOL;
	}
	
	public function setMailUsed(){
		$texte ='<div class="alert alert-danger" role="alert">Adresse mail deja utilisée. </div>
				<p>'.$this->formEnregistrement.'</p>';
		
		$this->body =SiteConfig::forgerBlock('Nouveau compte - Erreur',$texte);
		$this->head = '<title>'.$this->nomSite.' - Nouveau compte </title>'.PHP_EOL;
	}
	public function setLoginUsed(){
		$texte ='<div class="alert alert-danger" role="alert">Login deja utilisé. </div>
				<p>'.$this->formEnregistrement.'</p>';
		
		$this->body =SiteConfig::forgerBlock('Nouveau compte - Erreur',$texte);
		$this->head = '<title>'.$this->nomSite.' - Nouveau compte </title>'.PHP_EOL;
	}
	
	
	public function newAccount(){
		$texte ='<p>'.$this->formEnregistrement.'</p>';
		
		$this->body =SiteConfig::forgerBlock('Nouveau compte',$texte);
		$this->head = '<title>'.$this->nomSite.' - Nouveau compte </title>'.PHP_EOL;
	}
	
	public function activationOK(){
		$texte ='<div class="alert alert-success" role="alert">Votre compte à bien été activé.</div>';
		
		$this->body =SiteConfig::forgerBlock('Nouveau compte ',$texte);
		$this->head = '<title>'.$this->nomSite.' - Activation de compte </title>'.PHP_EOL;
	}
	
	public function demandeDejaEnCours(){
		$texte ='<div class="alert alert-warning" role="alert">Une demande avec cette adresse mail est deja en cours. Consultez votre boite mail. Pensez à verifier les courriers indesirables.</div>';
		
		$this->body =SiteConfig::forgerBlock('Nouveau compte ',$texte);
		$this->head = '<title>'.$this->nomSite.' - Activation de compte </title>'.PHP_EOL;
	}
	
	public function activationDup(){
		$texte ='<div class="alert alert-danger" role="alert">Un compte avec ce login ou cette adresse mail existe deja.</div>';
		
		$this->body =SiteConfig::forgerBlock('Erreur ',$texte);
		$this->head = '<title>'.$this->nomSite.' - Activation </title>'.PHP_EOL;
	}
	
	public function activationNonOK(){
		$texte ='<div class="alert alert-info"> Aucun compte à activer. Ce lien est invalide ou a expiré.</div>';
		
		$this->body =SiteConfig::forgerBlock('Erreur ',$texte);
		$this->head = '<title>'.$this->nomSite.' - Erreur </title>'.PHP_EOL;
	}
	
	public function doublonRecupPass(){
		$texte ='<div class="alert alert-warning" >Une demande est deja en cours pour ce compte. Consultez vos mails.</div>';
		
		$this->body =SiteConfig::forgerBlock('Erreur ',$texte);
		$this->head = '<title>'.$this->nomSite.' - Erreur </title>'.PHP_EOL;
	}
	
	
	public function recupPassNonValide(){
		$texte ='<div class="alert alert-danger" >Aucun compte lié à cette adresse mail.</div>
							<div class="formAuth">
								<p>Indiquez l\'adresse mail associée a votre compte: </p>
								<form method="post" action="/auth/reinitPass" class="text-center">				
								<input type="email" class="form-control" id="mail"  name="mail"><br />
								<input type="submit" class="btn btn-default" value="Envoyer" id="envoi">
								</form>
							</div>';
		
		$this->body =SiteConfig::forgerBlock('Erreur ',$texte);
		$this->head = '<title>'.$this->nomSite.' - Erreur </title>'.PHP_EOL;
	}
	
	public function recupPassOK(){
		$texte ='<div class="alert alert-success" >Demande de réinitialisation prise en compte. Consultez votre boite mail.</div>';
		
		$this->body =SiteConfig::forgerBlock('Réinitialisation ',$texte);
		$this->head = '<title>'.$this->nomSite.' - Réinitialisation </title>'.PHP_EOL;
	}
	
	public function reinitPass(){
		$texte ='			<div class="formAuth">
								<p>Indiquez l\'adresse mail associée a votre compte: </p>
								<form method="post" action="/auth/reinitPass" class="text-center">				
								<input type="email" class="form-control" id="mail"  name="mail"><br />
								<input type="submit" class="btn btn-default" value="Envoyer" id="envoi">
								</form>
							</div>';
		
		$this->body =SiteConfig::forgerBlock('Réinitialisation ',$texte);
		$this->head = '<title>'.$this->nomSite.' - Réinitialisation </title>'.PHP_EOL;
	}
	
	public function validRecupPassOk(){
		$texte ='<div class="alert alert-success" >Votre nouveau mot de passe est généré. Conlustez votre boite mail pour le connaître.</div>';
		
		$this->body =SiteConfig::forgerBlock('Réinitialisation ',$texte);
		$this->head = '<title>'.$this->nomSite.' - Réinitialisation </title>'.PHP_EOL;
	}
	
	public function validRecupPassNOk(){
		$texte ='<div class="alert alert-danger" >Erreur de la demande de récupération de moit de passe. Ce lien est invalide ou a expiré.</div>';
		
		$this->body =SiteConfig::forgerBlock('Erreur ',$texte);
		$this->head = '<title>'.$this->nomSite.' - Erreur </title>'.PHP_EOL;
	}
	
	
	public function pageSuppress(){
		$texte ='<div class="alert alert-info" role="alert">Etes vous certain de vouloir <b>supprimer votre compte ?</div>';
		$texte .='<div class="text-center"><a href="/auth/supress" class="btn btn-default">Oui</a>  <a href="/auth/profil" class="btn btn-default">Non</a></div>';
		
		$this->body =SiteConfig::forgerBlock('Supprimer mon compte ',$texte);
		$this->head = '<title>'.$this->nomSite.' - Erreur </title>'.PHP_EOL;
	}
	
	public function pageProfil($user, $message =''){
		
		$mail = $user->get('mail');
		
		$notifEvent = $user->get('notifEvent');
		if($notifEvent && $notifEvent =='n'){
			$notifEvent = false;
		}else{
			$notifEvent = true;
		}
		
		$notifComEvent = $user->get('notifComEvent');
		if($notifComEvent && $notifComEvent =='n'){
			$notifComEvent = false;
		}else{
			$notifComEvent = true;
		}
		
		
		$html = '<div class="text-right" ><a href="/auth/pageSuppress" class="btn btn-default">Supprimer mon compte</a></div>';
		
		$html .= $this->creerFormProfil($mail, $notifEvent, $notifComEvent);
		
		$this->body = SiteConfig::forgerBlock('Compte', $message.$html);	
	
		$this->head = '<title>'.$this->nomSite.' - Compte </title>'.PHP_EOL;
		
		$this->addCkeLibs();
	}
	
	
	
	private function creerFormProfil($mail = '', $notifsEvent = true, $notifsComEvent = true){
	
		
		$eventCheck='';
		$comCheck='';
		
		
		if($notifsEvent){
			$eventCheck= 'checked ';
		}
		if($notifsComEvent){
			$comCheck= 'checked ';
		}
		
		$html ='
							<form class="form-horizontal" method="post" action="/auth/majProfil" >
								<fieldset>
									<legend>Changement de mot de passe</legend>
										<div class="form-group">
										<label for="inputPasswordProfil" class="col-sm-3 col-md-offset-2 control-label">Ancien password :</label>
										<div class="col-sm-5">
											<input name="passAncien" type="password" placeholder="password" class="form-control" id="inputPasswordProfil">
										</div>
									</div>
									<div class="form-group">
										<label for="inputPasswordProfil1" class="col-sm-3 col-md-offset-2 control-label">Nouveau password :</label>
										<div class="col-sm-5">
											<input name="pass1" type="password" placeholder="password" class="form-control" id="inputPasswordProfil1">
										</div>
									</div>
									<div class="form-group">
										<label for="inputPasswordProfil2" class="col-sm-3 col-md-offset-2 control-label">Confirmez le password :</label>
										<div class="col-sm-5">
											<input name="pass2" type="password" placeholder="password" class="form-control" id="inputPasswordProfil2">
										</div>
									</div>
								</fieldset>
								<fieldset>
									<legend>Votre adresse mail</legend>			
									<div>			
										Votre adresse ne sera pas visible et ne sera communiquée a personne. <br />
										La renseigner vous permettra de recevoir des messages privés et les news du site.<br /><br />
									</div>			
									<div class="col-sm-4 col-md-offset-4">
										<input class="form-control" name="adresseMail" type="email" value="'.$mail.'" placeholder="exemple@domaine.tld" />
									</div>
								</fieldset>
								<div>
									<br />
									<br />
									<input type="submit" class="btn btn-default" value="Enregistrer" />
								</div>	
							</form>
							';

		return $html;
	}
	
	
	private function addCkeLibs(){
		
		$this->script.='<script type="text/javascript" src="tools/jquery-migrate.min.js"></script>
		<script type="text/javascript" src="tools/jquery-ui/jquery-ui.min.js"></script>
		<script type="text/javascript" src="tools/elfinder/js/elfinder.min.js"></script>
		<script type="text/javascript" src="tools/elfinder/js/i18n/elfinder.fr.js"></script>
		<script type="text/javascript" src="tools/ckeditor/ckeditor.js"></script>
		<script type="text/javascript" > $(document).ready(function(){CKEDITOR.replace( \'champSignature\');});</script>
		';
		
		$this->head.='<link rel="stylesheet" href="tools/jquery-ui/jquery-ui.theme.min.css" type="text/css" media="screen" >
		<link rel="stylesheet" href="tools/jquery-ui/jquery-ui.min.css" type="text/css" media="screen" >
		<link rel="stylesheet" href="tools/elfinder/css/elfinder.min.css" type="text/css" media="screen"  />
		';
	}
	
}

?>

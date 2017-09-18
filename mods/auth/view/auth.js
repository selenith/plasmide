(function(window) {



function Auth(){
	
	this.controleur = 'mods/auth/controller/jsonAuth.php';
	
	
	//fonction vide => rien a initialiser par plasmide.
	this.start = function(){
		
	}
	
	
	this.statut = function(callback){	
		
		$.get(this.controleur, {action:'statut'}, function(data){	
			if(data.checkProc == "ok"){
				callback(data);
			}else{
				alert(data);
			}
		 
		}, 'json');
	}	
	
	this.connexion = function(login, pass, callback){		
		$.post(this.controleur, {action:'connexion', login:login, pass:pass}, function(data){
			if(data.checkProc == "ok"){
				callback(data);
			}else{
				alert(data);
			}			
		}, 'json');				
	}
	
	this.deconnexion = function(callback){		
		$.get(this.controleur, {action:'deconnexion'}, function(data){
			if(data.checkProc == "ok"){
				callback(data);
			}else{
				alert(data);
			}
		}, 'json');					
	}
	
	this.demandeCompte = function(login, pass, mail, callback){
		$.post(this.controleur, {action:'enregistrement', login:login, pass:pass, mail:mail}, function(data){
			if(data.checkProc == "ok"){
				callback(data);
			}else{
				alert(data);
			}	
		}, 'json');			
	}
	
	this.activation = function(token, callback){
		
		$.post(this.controleur, {action:'activation', token:token}, function(data){
			if(data.checkProc == "ok"){
				callback(data);
			}else{
				alert(data);
			}
		}, 'json');	
	}
	
	this.demandeRecupPass = function(mail,callback){
		
		$.post(this.controleur, {action:'demandeRecupPass', mail:mail}, function(data){
			if(data.checkProc == "ok"){
				callback(data);
			}else{
				alert(data);
			}			
		}, 'json');	
	}
	
	this.validRecupPass = function(token, callback){
		
		$.post(this.controleur, {action:'validRecupPass', token:token}, function(data){
			if(data.checkProc == "ok"){
				callback(data);
			}else{
				alert(data);
			}
		}, 'json');	
	}
}





	var auth = new Auth();
	plasmide.addSequence(auth, 'auth');
})(window);
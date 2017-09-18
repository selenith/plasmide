 /*
-Nom :		Nav
-Version :	1.0
-Date : 	24/02/2015
-Auteur : 	Selenith - http://selenith.ovh
-License : 	[GPL v3] http://www.gnu.org/licenses/gpl.html

Requiert jQuery 1.6+
*/
(function(window) {

function Nav(){
	
	this.app;
	var pages = new Array();
	var pageRoot = false;
	var pageNotFound = false;
	
	//A lancer pour initialiser la navication.
	//faire la declaration des pages avant d'appeler cette fonction
	this.start = function(){
		$(window).on('hashchange', function() {
			flip(location.hash);
		});
		flip(location.hash);
	}
		
	this.addPage = function(nomPage, fonctionPage){
		pages[nomPage] = fonctionPage;
	}
		
	this.setRoot = function(fonc){
		pageRoot = fonc;
	}
	
	this.setNotFound = function(fonc){
		pageNotFound = fonc;
	}
	
	this.setApp = function(app){
		this.app = app;		
	}
	
	
	//---la navigation coté client se fait ici---
	function flip(hash){
		var params = splitParams(hash);
		
		if(params.idPage){
			var page = params.idPage;
					
			if(pages[page]){
				pages[page](params);
			}else{
				if(pageNotFound){
					pageNotFound();
				}
			}
		}else{
			if(pageRoot){
				pageRoot();
			}
		}
		
	}	
	
	function splitParams(hash){
	
		var params = new Object();
		//recuperation du Hash
		var hash = hash.replace( /^#/, "" );
		
		//recuperation de la page
		var page = hash.split('?', 1);			
		params.idPage = page[0];
		
		//recuperation des parametres
		var args = hash.substring(params.idPage.length+1)
		var tuples = args.split("&");
		var nbTuples = tuples.length;
		
		var tuple;
		var nom = "";
		var valeur = "";
		for(var i=0; i<nbTuples; i++){
			
			tuple = tuples[i].split(':');
			params[tuple[0]] = tuple[1];
		}
		
		return params;
	}

	//-----------------------------------------------------
	//------requetes vers le serveur en mode JSON----------
	
	this.get = function(cible, args, fonc){
		$.get(cible, args, function(data){			
			fonc(data);			
		}, 'json');
	}
	
	this.post = function(cible, args, fonc){
		$.post(cible, args, function(data){
			fonc(data);			
		}, 'json');
	}
	
}
var nav = new Nav();
window.nav = nav;
})(window);
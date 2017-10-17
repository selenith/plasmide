/*
-Nom :		Plasmide.js
-Version :	1.3
-Date : 	25/04/2016
-Auteur : 	Selenith - http://selenith.ovh
-License : 	[GPL v3] http://www.gnu.org/licenses/gpl.html

Requiert jQuery 1.6+
Permet l'integration de modules javascript au systeme plasmide (http://plasmide.selenith.ovh).
*/
(function(window) {


function Plasmide(){
	
	this.sequence = new Array();
	
	this.start = function(){
				
		
		//chargement des differentes sequences
		for(var i in this.sequence){
			this.sequence[i].start();
		}
		
		//chargement du systeme de navigation
		nav.start();
		
		
	}
	
	this.addSequence = function(newSequence, sequenceName){
		this.sequence[sequenceName] = newSequence; 		
	}
	

	
	this.accentTueur = function (chaine) {
	  var temp = chaine.replace(/[àâä]/gi,"a");
	  temp = temp.replace(/[éèêë]/gi,"e");
	  temp = temp.replace(/[îï]/gi,"i");
	  temp = temp.replace(/[ôö]/gi,"o");
	  temp = temp.replace(/[ùûü]/gi,"u");
	  temp = temp.replace(/[\s\?,]/gi,"_");
	  return temp;
	}
	
	
	this.convertDataSent = function(data){
		data = data.replace(/&/g, '%gronk' );
		data = data.replace(/\+/g, '%grank' );
		
		return data;
	}
	
	
}


var plasmide = new Plasmide();
window.plasmide = plasmide;

})(window);

var TEMPLATE = new Object();
//---------------------------------------------configuration generale-----------------------------------------

//nombre d'articles par page
TEMPLATE.artParPage = 3;

//----------------------------------------------Config CKeditor-----------------------------------------------
TEMPLATE.ckeconfig={
		filebrowserBrowseUrl : 'elfinder.html',
		uiColor : '#525c6b', // couleur de fond modifiable
    	height:"400px" //hauteur CKeditor
		}

//-------------------------------------------------Articles---------------------------------------------------
TEMPLATE.articleTete = 
'<div class="article">'+
	'<div class="titreArticle">';
				
TEMPLATE.articleSeparation = 
	'</div>'+
	'<div class="corpsArticle">	';
			
TEMPLATE.articlePied = 		
	'</div>'+
'</div>';
				
				
//-------------------------------------------------Blocks---------------------------------------------------
TEMPLATE.blockTete = 
'<div class="article">'+
	'<div class="titreArticle">';
				
TEMPLATE.blockSeparation = 
	'</div>'+
	'<div class="corpsArticle">	';
			
TEMPLATE.blockPied = 		
	'</div>'+
'</div>';

//-------------------------------------------------Mini Blocks---------------------------------------------------
TEMPLATE.miniBlockTete = 
'<div class="miniBlock">';
				
TEMPLATE.miniBlockSeparation = 
	'<span class="numeroPage">';				
			
TEMPLATE.miniBlockPied =
	'</span>'+				
'</div>	';


//-------------------------------------------------boutons---------------------------------------------------
//forge des objets 
//generation du code html correspondant a un article
TEMPLATE.forgerArticle = function(titre, contenu){
	return TEMPLATE.articleTete+ titre +TEMPLATE.articleSeparation + contenu +TEMPLATE.articlePied ;		
}

//generation du code html correspondant a un block
TEMPLATE.forgerBlock = function(titre, contenu){
	return TEMPLATE.blockTete + titre + TEMPLATE.blockSeparation + contenu + TEMPLATE.blockPied ;
}

//generation du code html correspondant a un block
TEMPLATE.forgerMiniBlock = function(titre, contenu){
	return TEMPLATE.miniBlockTete + titre + TEMPLATE.miniBlockSeparation + contenu + TEMPLATE.miniBlockPied ;
}

//generation du code html correspondant a un bouton
TEMPLATE.forgerBouton = function(id, nom){	
	return '<a id="'+id+'"class="bouton" >'+nom+'</a>';	
}

//integration du code html dans l'emplacement des articles
TEMPLATE.integrerArticle = function(html){
	$('#conteneurPage').html(html);
}




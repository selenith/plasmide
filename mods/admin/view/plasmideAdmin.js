(function(window) {

var document = window.document;

function Admin(){
	
	var modCtrlPath = '/mods/admin/controller/admin.php';
	
	this.menusCourants ;
	var idArtEdit;
	var barreAdmin=false;
	
	var ckeConfig = {
		filebrowserBrowseUrl : '/tools/elfinder/elfinder.html',
    	height:"400px", //hauteur CKeditor
		extraPlugins :'video',
		toolbar :
		[
			{ name: 'document',    items : [ 'Source','-','Save','NewPage','DocProps','Preview','Print','-','Templates' ] },
			{ name: 'clipboard',   items : [ 'Cut','Copy','Paste','PasteText','PasteFromWord','-','Undo','Redo' ] },
			{ name: 'editing',     items : [ 'Find','Replace','-','SelectAll','-','SpellChecker', 'Scayt' ] },
			{ name: 'tools',       items : [ 'Maximize', 'ShowBlocks','-','About' ] },
			'/',
			{ name: 'basicstyles', items : [ 'Bold','Italic','Underline','Strike','Subscript','Superscript','-','RemoveFormat' ] },
			{ name: 'paragraph',   items : [ 'NumberedList','BulletedList','-','Outdent','Indent','-','Blockquote','CreateDiv','-','JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock','-','BidiLtr','BidiRtl' ] },
			'/',
			{ name: 'styles',      items : [ 'Styles','Format','Font','FontSize' ] },
			{ name: 'colors',      items : [ 'TextColor','BGColor' ] },
			{ name: 'insert',      items : [ 'Image', 'Video', 'Flash','Table','HorizontalRule','Smiley','SpecialChar','PageBreak','Iframe','Link','Unlink' ] }
		]
	};
	
	this.start = function(){
		
		plasmide.sequence.auth.statut(function(data){
			var statut = data.statut;
			var droits = data.droits;
			if(statut == 'connect' && droits =='maitre'){
				admin.activerBarreAdmin();				
			}else if(statut == 'connect' && droits !='maitre'){				
				ecranDeco('Vous n\'avez pas les droits d\'acces.');
			}else if(statut == 'deco'){				
				ecranDeco();
			}
		});	
		
	
		nav.setRoot(ecranGestionArticle);
		nav.addPage('gestArticle', ecranGestionArticle);
		nav.addPage('gestMenu', ecranGestionMenu);
		nav.addPage('gestCom', ecranGestionComm);
		nav.addPage('gestConf', ecranGestionConf);
		nav.addPage('gestMedia', ecranGestionMedia);
		nav.addPage('gestMembre', ecranGestionMembre);
		nav.addPage('deconnexion', deconnexion);
						
	}
	
	
	function droitsLisible(droits){
		
		if(droits =='maitre'){
			droits='Administrateur';
		}else if(droits =='moderateur'){
			droits='Moderateur';
		}else if(droits =='authentif'){
			droits ='Authentifé';
		}
		
		return droits;
	}
	
	this.activerBarreAdmin = function(){
		$('#barreAdmin').html(
			
			'<nav class="navbar navbar-expand-lg navbar-light bg-light border mb-2">'+
			'   <a class="navbar-brand" href="/"><img alt="Brand" width="90" height="auto" src="/mods/admin/style/images/plasmideLogo.png"></a>'+
			'   <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">'+
                        '       <span class="navbar-toggler-icon"></span>'+
			'   </button>'+
			'   <div class="collapse navbar-collapse" id="navbarSupportedContent">'+
			'       <ul class="navbar-nav ml-auto">		'+					
			'           <li class="nav-item"><a class="nav-link" href="#gestArticle" >Articles</a></li>'+					
			'           <li class="nav-item"><a class="nav-link" href="#gestMenu" >Menus</a></li>'+  
			'           <li class="nav-item"><a class="nav-link" href="#gestCom" >Commentaires</a></li>'+  
			'           <li class="nav-item"><a class="nav-link" href="#gestConf" >Parametres</a></li>'+  
			'           <li class="nav-item"><a class="nav-link" href="#gestMedia" >Medias</a></li>'+  
			'           <li class="nav-item"><a class="nav-link" href="#gestMembre" >Utilisateurs</a></li>'+  
			'           <li class="nav-item"><a class="nav-link" href="#deconnexion" >Deconnexion</a></li>'+ 
			'       </ul>  '+
                        '   </div>'+      
			'</nav>'
		).addClass('barreAdminVisible');		
		
	}
	
	function desactiverBarreAdmin(){
		$('#barreAdmin').html('').removeClass('barreAdminVisible');
		TEMPLATE.integrerArticle(TEMPLATE.forgerArticle('Deconnexion', 
        '<p>Vous avez été déconnecté avec succès. Vous pouvez retourner au <a href="/">l\'index du site</a> ou du <a href="/admin"> module d\'administration</a>'));
		
	}
	
	function deconnexion(){
		
		plasmide.sequence.auth.deconnexion(function(data){
			if(data.statut=='ok'){
				location.href='#deco';
				
				desactiverBarreAdmin();
				
			}
		});
	}
	
	
	function ecranDeco(msg){
		var prefix ='';
		if(msg){
			alert(msg);
		}
		
		var label;
		var formGroup;
		
		$('#conteneurPage').empty();
		
		var conteneurLogo = document.createElement('div');
		conteneurLogo.className = "logoPageAdmin";
		$('#conteneurPage').append(conteneurLogo);
		
		var logo = document.createElement('img');
		logo.src = '/mods/admin/style/images/plasmideLogo.png';
		
		conteneurLogo.appendChild(logo);
		
		var conteneurPageAuth = document.createElement('div');
		conteneurPageAuth.className = 'adminCadreLogin text-center';
		
		var formulaire = document.createElement("form");
		formulaire.setAttribute('align', 'center');		
		
		conteneurPageAuth.appendChild(formulaire);
		
		//login		
		formGroup = document.createElement('div');
		formGroup.className='form-group';
		formulaire.appendChild(formGroup);
		
		label = document.createElement('label');
		label.setAttribute('for','login');
		label.appendChild(document.createTextNode('Login :'));
		formGroup.appendChild(label);
		
		var champ = document.createElement('input');
		champ.type='text';
		champ.id='login';
		champ.className='form-control';
		champ.setAttribute('placeholder', 'Login');
		formGroup.appendChild(champ);
		
		//password
		formGroup = document.createElement('div');
		formGroup.className='form-group';
		formulaire.appendChild(formGroup);
		
		label = document.createElement('label');
		label.setAttribute('for','pass');
		label.appendChild(document.createTextNode('Password :'));
		formGroup.appendChild(label);
		
		champ = document.createElement('input');
		champ.type='password';
		champ.className='form-control';
		champ.id='pass';
		champ.setAttribute('placeholder', 'Mot de passe');
		formGroup.appendChild(champ);
		
		//btn 
		champ = document.createElement('input');
		champ.type='submit';
		champ.className='btn btn-default';
		champ.value='Connexion';
		champ.id='btConnect';
		formulaire.appendChild(champ);
		
		
		
		$('#conteneurPage').append(conteneurPageAuth);
		
		var html = prefix+'<p align="center">'+					
					'<input type="hidden" value="connect" name="auth"  />'+
					'<span>Login :</span><br />'+
					'<input class="champForm" type="text" id="login" placeholder="login"  /><br />'+
					'<span>Mot de passe :</span><br />'+
					'<input class="champForm" type="password"  id="pass" placeholder="Mot de passe"  /><br />'+					
					'<input type="submit" id="btConnect" class="boutonForm" value="Connexion" />'+					
					'</p>';
		//TEMPLATE.integrerArticle(TEMPLATE.forgerBlock('Connexion', html));
		$('#barreAdmin').html('').removeClass('barreAdminVisible');;
		
		$(formulaire).on("submit", function(event){
			event.preventDefault();
			validerAuth();
		});		
		
		
	}
	
	function validerAuth(){	
		var login = $('#login').val();
		var pass = $('#pass').val();
		plasmide.sequence.auth.connexion(login, pass, function(data){
			
			if(data.statut=='connect' && data.droits == 'maitre'){
				ecranGestionArticle();
				admin.activerBarreAdmin();
			}else if(data.statut=='noPass'){
				ecranDeco('Mauvais couple login-Mot de passe');
			
			}else if(data.droits!='maitre'){
				ecranDeco('Vous n\'avez pas les droits d\'accès');
			}
		});
	}
	
	
	function ecranGestionMembre(){
		$.get(modCtrlPath, {action:'listUser'},function(data){
			if(data.statut == 'ok'){
				
				var listeUser = '';
				
				for(var i=0;i<data.users.length; i++){
					
					listeUser+='<tr><td>'+data.users[i].login+'</td><td>'+droitsLisible(data.users[i].droits)+
					'</td><td><img src="/mods/admin/style/images/crayonEdit.png" class="editUser" title="Editer" alt="Editer" iduser="'+data.users[i].id+
					'" droits="'+data.users[i].droits+'" loginUser="'+data.users[i].login+'"></td><td><img src="/mods/admin/style/images/corbeilleSuppr.png" class="supprUser" title="Supprimer" alt="Supprimer" iduser="'+data.users[i].id+'" ></td></tr>';	
					
				}
				
				
				
				TEMPLATE.integrerArticle(TEMPLATE.forgerArticle('Gestion des utilisateurs',
					'<p class="textAdmin">'+
					'<div id="zoneAjoutUser" class="text-center"></div>'+
					'<table>'+
					'<tr><th>Login</th><th>Droits</th><th></th><th></t</tr>'+
					listeUser+
					'</table>'+
					'</p>'));
				
			
			
			
				var boutonAjout = document.createElement('button');
				boutonAjout.className ='btn btn-default' ;
				boutonAjout.appendChild(document.createTextNode('Ajouter'));
				
				$('#zoneAjoutUser').append(boutonAjout);
				
				$(boutonAjout).click(ecranAjoutMembre);
				
				$('#zoneAjoutUser').append(document.createElement('br'));
				$('#zoneAjoutUser').append(document.createElement('br'));
				
				$('.supprUser').click(function(){supprimerUser($(this).attr('idUser'));});
				$('.editUser').click(function(){ecranEditMembre($(this).attr('idUser'), $(this).attr('loginUser'), $(this).attr('droits'));});
								
			}else if(data.statut == 'deco'){
				ecranDeco("Votre session a expiré. Merci de vous authentifier a nouveau.");
			}else{
				alert(data);
				
			}
		}, 'json');
	}
	
	
	function ecranEditMembre(id, login, droit){
		
		
		
		var maitreSelect = '';
		var modoSelect = '';
		var standardSelect = '';
		var authentifSelect = '';
		if(droit == "maitre"){
			maitreSelect = 'selected="selected"';
			
		}else if(droit == "moderateur"){
			modoSelect = 'selected="selected"';			
		}else if(droit == "standard"){
			standardSelect = 'selected="selected"';
		}else if(droit == "authentif"){
			authentifSelect = 'selected="selected"';
		}
		
		
		TEMPLATE.integrerArticle(
			TEMPLATE.forgerArticle('Editer un utilisateur', 
				'<p style="text-align:center;">'+				
				'<br />Login : <br /><input id="loginUser" type="text" value="'+login+'" />'+
				'<br />Pass : <br /><input id="passUser" type="text" />'+
				'<br />Droits :<br />'+
				'<select id="listeDroits" > '+
				'<option '+standardSelect+' value="standard"> Standard </option>'+
				'<option '+authentifSelect+' value="authentif"> Authentifié </option>'+
				'<option '+modoSelect+' value="moderateur"> Moderateur </option>'+
				'<option '+maitreSelect+' value="maitre"> Administrateur </option>'+
				'</select><br /><br />'+
				'<button id="retourUser" class="btn btn-default">Retour</button> '+
				'<button id="validerEditUser" class="btn btn-default">Valider</button>'+
				'</p>')
		);
		
		
		
		$('#retourUser').click(ecranGestionMembre);
		
		$('#validerEditUser').click(function(){validerEditMembre(id);});
		
	}
	
	function validerEditMembre(id){
		var login = $('#loginUser').val();
		var pass = $('#passUser').val();
		var droits = $('#listeDroits').val();
		
		
		$.get(modCtrlPath, {action:'editUser', login:login, pass:pass, droits:droits, id:id},function(data){		
			
			if(data.statut == 'ok'){
				ecranGestionMembre();
				
			}else{
				alert(data);
				
			}
		}, 'json');
		
	}
	
	
	function ecranAjoutMembre(){
		
		
		TEMPLATE.integrerArticle(
			TEMPLATE.forgerArticle('Ajouter un utilisateur', 
				'<p class="text-center">'+				
				'<br />Login : <br /><input id="loginUser" type="text"/><br /><br />'+
				'Pass : <br /><input id="passUser" type="text"/><br /><br />'+
				'Droits :<br />'+
				'<select id="listeDroits" > '+
					'<option selected="selected" value="standard"> Standard </option>'+
					'<option  value="authentif"> Authentifié </option>'+
					'<option  value="moderateur"> Moderateur </option>'+
					'<option  value="maitre"> Administrateur </option>'+
				'</select>'+
				'<br /><br />'+
				'<button class="btn btn-default" id="retourUser">Retour</button> '+
				'<button class="btn btn-default" id="validerUser">Valider</button>'+
				'</p>')
		);
		
		
		
		$('#retourUser').click(ecranGestionMembre);
		
		$('#validerUser').click(validerAjoutMembre);
		
	}
	
	
	function validerAjoutMembre(){
		var login = $('#loginUser').val();
		var pass = $('#passUser').val();
		var droits = $('#listeDroits').val();
		
		
		$.get(modCtrlPath, {action:'ajoutUser', login:login, pass:pass, droits:droits},function(data){		
			
			if(data.statut == 'ok'){
				ecranGestionMembre();
				
			}else if(data.statut == 'doublon'){
				alert('Ce nom d\'utilisateur est deja utilisé.');
			}else{
				alert(data);
				
			}
		}, 'json');
	}
	
	
	
	function supprimerUser(idUser){
		var suppr = confirm("Supprimer cet utilisateur ?");
		
		if(suppr){
			$.get(modCtrlPath, {action:'supprUser', idUser:idUser,},function(data){		
			
			if(data.statut == 'ok'){
				ecranGestionMembre();
				
			}else if(data.statut == 'deco'){
				ecranDeco('Votre Session a expirée, merci de vous authentifier à nouveau.');
				document.location.reload();
			}else{
				alert(data);
				
			}
		},'json');
			
		}
		
		
	}
	
	function ecranGestionComm(){
		$.get(modCtrlPath, {action:'getComm'},function(data){		
			
			if(data.statut == 'ok'){
				
				var checked = '';
				if(data.commActifs == 'oui'){
					checked = 'checked="checked"';
				}
				
				var html =
				'<p class="textAdmin">'+
					'<table>'+
						'<tr><td>Commentaire</td><td>titre</td><td>Article</td><td>auteur (IP)</td><td>date</td><td></td></tr>';
					
				for(var i=0;i<data.commentaire.length; i++){
					html+='<tr><td>'+data.commentaire[i].texte+'</td><td>'+data.commentaire[i].titre+'</td><td>'+data.article[data.commentaire[i].idArticle].nom+'</td><td>'+data.commentaire[i].pseudo+' ('+data.commentaire[i].ip+')</td><td>'+data.commentaire[i].date+'</td><td><img class="btSupprComm" idComm="'+data.commentaire[i].id+'" src="/mods/admin/style/images/corbeilleSuppr.png"/></td></tr>';
				}
				html+='</table>'+
				'</p>';
				
				TEMPLATE.integrerArticle(TEMPLATE.forgerArticle('Gestion des commentaires', html));
				
				$('.btSupprComm').click(function(){supprimerCommentaire($(this).attr('idComm'));});
				
			}			
		}, 'json');
	}

	function supprimerCommentaire(id){
		
		var valid = confirm("Voulez vous vraiment supprimer ce commentaire ?");
		
		if(valid){
			$.get(modCtrlPath, {action:'supprComm', idComm:id},function(data){
				if(data.statut== 'ok'){
					ecranGestionComm();
				}
			}, 'json');
		}
		
		
	}
	
	
	function ecranGestionConf(){
		$.get(modCtrlPath, {action:'getConfig'},function(data){
			if(data.statut == 'ok'){
								
				TEMPLATE.integrerArticle(TEMPLATE.forgerArticle('Gestion de la configuration',
					'<p class="textAdmin">'+
					'<table>'+
					'<tr><th>Champ</th><th>Valeur</th><th style="width:100px;"></th></tr>'+
					'<tr><td>Nom du site : </td><td><span id="confnom">'+data.nom+
					'</span></td><td><span id="confnomBt"> <img class="editConf" conf="nom" src="/mods/admin/style/images/crayonEdit.png" /></span></td></tr>'+
					'<tr><td>Adresse du site : </td><td><span id="confadresse">'+data.adresse+
					'</span></td><td><span id="confadresseBt"> <img class="editConf" conf="adresse" src="/mods/admin/style/images/crayonEdit.png" /></span></td></tr>'+
					'<tr><td>Adresse de contact : </td><td><span id="confmail"> '+data.mail+
					' </span></td><td><span id="confmailBt"><img class="editConf" conf="mail" src="/mods/admin/style/images/crayonEdit.png" /></span></td></tr>'+
					'<tr><td>Page d\'accueil : </td><td><span id="editAccueil"> '+data.nomHome+
					' </span></td><td><span id="editAccueilBt"><img id="goEditAccueil" src="/mods/admin/style/images/crayonEdit.png" /></span></td></tr>'+
					'<tr><td>Template : </td><td><span id="editTemplate"> '+data.template+
					' </span></td><td><span id="editTemplateBt"><img id="goEditTemplate" src="/mods/admin/style/images/crayonEdit.png" /></span></td></tr>'+
					'</table>'+
					'</p>'
				));
				
				$('.editConf').click(function(){
					var champ =$(this).attr('conf');
					editChampConf(champ, data[champ]);
				});
				
				$('#goEditAccueil').click(function(){editHomePage(data.nomHome, data.idHome);});
				$('#goEditTemplate').click(function(){editTemplate(data.template);});
				
			}else if(data.statut == 'deco'){
				ecranDeco('Votre Session a expirée, merci de vous authentifier à nouveau.');				
			}		
		}, 'json');
		
	}
	
	
	function editChampConf(champ, valeur){
		
		$('#conf'+champ).html('<input id="editConf'+champ+'" type="text" />');			
		
		$('#conf'+champ+'Bt').html(
				'<img id="validEdit'+champ+'" src="/mods/admin/style/images/valid.png" /> <img id="annulEdit'+champ+'" src="/mods/admin/style/images/annul.png" />'
		);
		
		$('#editConf'+champ).val(valeur);
		$('#annulEdit'+champ).click(function(){annulEditConf(champ, valeur);});
		$('#validEdit'+champ).click(function(){validEditConf(champ);});
	}
	
	function annulEditConf(champ, valeur){
		$('#conf'+champ).html(valeur);
		$('#conf'+champ+'Bt').html(' <img class="editConf" conf="login" id="btEditConf'+champ+'" src="/mods/admin/style/images/crayonEdit.png" />');
		$('#btEditConf'+champ).click(function(){editChampConf(champ, valeur);});
	}
	
	function validEditConf(champ){
		var envoiRequete = true;
		var valeur = $('#editConf'+champ).val();
		var param = {action:'validEditConf', champ:champ, valeur:valeur};
		
		
		if(envoiRequete){
			$.get(modCtrlPath, param, function(data){
			
			if(data.statut =='ok'){
				$('#conf'+champ).html(valeur);
				$('#conf'+champ+'Bt').html(' <img class="editConf" conf="login" id="btEditConf'+champ+'" src="/mods/admin/style/images/crayonEdit.png" />');
				$('#btEditConf'+champ).click(function(){editChampConf(champ, valeur);});
			}else if(data.statut =='badPass'){
				$('#infoEditPass').html('Le mot de passe actuel que vous avez mis est incorrect.');
			}
		}, 'json');
		}		
	}
	
	function editHomePage(nomHome, idHome){
		$('#editAccueil').html('<img style="width:220px; height:19px;" src="/mods/admin/style/images/spinner.gif" />');
		$('#editAccueilBt').html('');
		
		$.get(modCtrlPath, {action:'listeMenu'}, function(data){
			
			if(data.statut =='ok'){				
				var listeMenu ='<select id="listeParent" > '+ genererListe(data,idHome , '', 0) +'</select>';
				
				$('#editAccueil').html(listeMenu);
				$('#editAccueilBt').html('<img id="validEditAccueil" src="/mods/admin/style/images/valid.png" /> <img id="annulEditAccueil" src="/mods/admin/style/images/annul.png" />');
				
				$('#validEditAccueil').click(function(){validEditHomePage(data.menu);});
				$('#annulEditAccueil').click(function(){cancelEditHomePage(nomHome, idHome);});
				
			}
		}, 'json');
	}
	function cancelEditHomePage(nomHome, idHome){
		$('#editAccueil').html(nomHome);
		$('#editAccueilBt').html('<img id="goEditAccueil" src="/mods/admin/style/images/crayonEdit.png" />');
		$('#goEditAccueil').click(function(){editHomePage(nomHome, idHome);});
	}
	
	function validEditHomePage(menu){	
		
		var idhome = $('#listeParent').val();
		var nomHome;
		var recherche = true;
		for(var i=0; i<menu.length && recherche; i++){
			if(menu[i].id == idhome){
				recherche = false;
				nomHome = menu[i].nom;
			}
		}
		
		$('#editAccueil').html('<img style="width:220px; height:19px;" src="/mods/admin/style/images/spinner.gif" />');
		$('#editAccueilBt').html('');
	
		$.get(modCtrlPath, {action:'validConfAccueil', idMenu:idhome}, function(data){
			if(data.statut=='ok'){
					
				$('#editAccueil').html(nomHome);
				$('#editAccueilBt').html('<img id="goEditAccueil" src="/mods/admin/style/images/crayonEdit.png" />');
				$('#goEditAccueil').click(function(){editHomePage(nomHome, idhome);});
			}
		
		},'json');
		
	}
	
	function editTemplate(nomTemplate){
		$('#editTemplate').html('<img style="width:220px; height:19px;" src="/mods/admin/style/images/spinner.gif" />');
		$('#editTemplate').html('');
		
		$.get(modCtrlPath, {action:'listeTemplates'}, function(data){
			
			if(data.statut =='ok'){				
				
				var listeMenu ='<select id="listeTemplates" >';
				for(var i=0 ; i<data.templates.length ; i++){
					listeMenu += '<option value="'+data.templates[i]+'"';
					if(data.templates[i]==nomTemplate){
						listeMenu +='selected="selected"'
					}
					listeMenu +='>'+data.templates[i]+'</option>';					
				}				
				listeMenu +='</select>';
				
				$('#editTemplate').html(listeMenu);
				$('#editTemplateBt').html('<img id="validEditTemplate" src="/mods/admin/style/images/valid.png" /> <img id="annulEditTemplate" src="/mods/admin/style/images/annul.png" />');
				
				$('#validEditTemplate').click(function(){validEditTemplate();});
				$('#annulEditTemplate').click(function(){annulEditTemplate(nomTemplate);});
				
			}
		}, 'json');
	}
	
	function annulEditTemplate(nomTemplate){
		$('#editTemplate').html(nomTemplate);
		$('#editTemplateBt').html('<img id="goEditTemplate" src="/mods/admin/style/images/crayonEdit.png" />');
		$('#goEditTemplate').click(function(){editTemplate(nomTemplate);});
	}
	
	function validEditTemplate(){	
		
		var nomTemplate = $('#listeTemplates').val();
				
		$('#editTemplate').html('<img style="width:220px; height:19px;" src="/mods/admin/style/images/spinner.gif" />');
		$('#editTemplateBt').html('');
	
		$.get(modCtrlPath, {action:'validConfTemplate', nomTemplate:nomTemplate}, function(data){
			if(data.statut=='ok'){
					
				$('#editTemplate').html(nomTemplate);
				$('#editTemplateBt').html('<img id="goEditTemplate" src="/mods/admin/style/images/crayonEdit.png" />');
				$('#goEditTemplate').click(function(){editTemplate(nomTemplate);});
			}
		
		},'json');
		
	}
	
	function ecranGestionMenu(){
		
		$.get(modCtrlPath, {action:'listeMenu'}, function(data){
			if(data.statut =='ok'){
				
				admin.menusCourants = data;
				
				var html =
				'<div class="text-center"><button id="ajoutMenu" class="btn btn-default">Nouveau menu</button></div><br />'+
				'<table> <tbody>' +					
				'<tr>'+	
					'<th >Menu</th>'+
					'<th>Menu parent</th>'+
					'<th>Orde</th>'+
					'<th>Type</th>'+
					'<th></th>'+
					'<th></th>'+
				'</tr>';	
				
				var nbMenu = data.menu.length;
				var nomParent;
				for(var i=0 ; i<nbMenu ; i++){
					nomParent = 'Aucun';
					
					for(var j =0;  j<nbMenu & nomParent=="Aucun" ; j++){
						
						if(data.menu[i].idParent == data.menu[j].id){
							nomParent = data.menu[j].nom;							
						}						
					}
					
					html += 
					'<tr>'+		
						'<td>'+ data.menu[i].nom+'</td>'+
						'<td>'+ nomParent+'</td>'+
						'<td> '+data.menu[i].ordre+'</td>'+
						'<td>'+ data.menu[i].mod+'</td>'+
						'<td ><img class="editMenu" idListe="'+i+'" src="/mods/admin/style/images/crayonEdit.png"   title="Editer" alt="Editer" /></td>'+
						'<td ><img class="supprMenu" idMenu="'+data.menu[i].id+'" idParent="'+data.menu[i].idParent+'" src="/mods/admin/style/images/corbeilleSuppr.png" title="Supprimer" alt="Supprimer" /></td>'+
					'</tr>';	
				}				
				
				html += ' </tbody></table>';
				var titre = 'Gestion des Menus';
				TEMPLATE.integrerArticle(TEMPLATE.forgerArticle(titre, html));
				
				
				$('#ajoutMenu').click(function(){ecranModifMenu(false, false);});
				$('.supprMenu').click(supprimerMenu);
				$('.editMenu').click(function(){ecranModifMenu(true, data, $(this).attr('idListe'));});
			}else if(data.statut == 'deco'){
				ecranDeco('Votre Session a expirée, merci de vous authentifier à nouveau.');				
			}	
		},'json');		
	}
	
	function ecranModifMenu(edition, dataMenu, idListe){
		var nom = "";
		var select = "";
		var ordre = "0";
		var metaDesc = "";
		var stylePage = "";
		var selectedMod="";
		var selectedHTML = "";
		
		
		$.get(modCtrlPath, {action:'listeMods'}, function(data){
			if(data.statut == 'ok'){
                
				if(edition){
					
					nom	= dataMenu.menu[idListe].nom;
					select = dataMenu.menu[idListe].idParent;
					ordre= dataMenu.menu[idListe].ordre;
					metaDesc = dataMenu.menu[idListe].metaDesc;
					stylePage = dataMenu.menu[idListe].stylePage;
					var selectedMod =dataMenu.menu[idListe].mod;					
				}
				
				var htmlModList = "";
				for(var i=0; i<data.modsList.length; i++)
                {
					if(data.modsList[i] == selectedMod){
						selectedHTML = 'selected="selected"';
						
					}
					htmlModList+='<option '+selectedHTML+' value="'+data.modsList[i]+'"> '+data.modsList[i]+' </option>';
                    selectedHTML='';
				}
				
				TEMPLATE.integrerArticle(
					TEMPLATE.forgerArticle('Ajouter un menu', 
						'<p class="text-center">'+						
                            '<br />Menu Parent :<br />'+
                            '<select id="listeParent" > '+
                            '<option value=""> Aucun </option>'+
                            genererListe(admin.menusCourants,select, '', 0)+	
                            '</select>'+
                            '<br /><br />'+
                            'Type de page liée :'+
                            '<br />'+
                            '<select id="typeMenus" > '+
                            htmlModList+
                            '</select>'+
                            '<br /><br />'+
                            'Nom du menu : <br />'+
                            '<input id="nomMenu" type="text"/><br /><br />'+
                            'Description de la page : <br />'+
                            '<input id="metaDesc" type="text" value="'+metaDesc+'"/><br /><br />'+
                            'Ordre du menu : <br />'+
                            '<input id="ordreMenu" type="text" value="'+ordre+'"/><br /><br />'+
                            'style de la page : <br />'+
                            '<input id="stylePage" type="text" value="'+stylePage+'"/>'+
                        '</p>'+
                        '<p class="text-center">'+
                            '<button id="retourMenu" class="btn btn-default" >Retour</button> '+
                            '<button id="validerMenu" class="btn btn-default" >Valider</button>'+
						'</p>'
					)
				);
				
				$('#nomMenu').val(nom);
				
				$('#retourMenu').click(ecranGestionMenu);
				if(edition){
					$('#validerMenu').click(function(){validerEditMenu(dataMenu.menu[idListe].id);});
				}else{
					$('#validerMenu').click(validerAjoutMenu);
				}
			}else if(data.statut == 'deco'){
				ecranDeco('Votre Session a expirée, merci de vous authentifier à nouveau.');				
			}	
			
		}, 'json');
		
		
		
		
	}
	
	function validerAjoutMenu(){		
		
		$.post(modCtrlPath, 
		{action:'ajoutMenu', idParent:$('#listeParent').val(), mod:$('#typeMenus').val(),
		nom:$('#nomMenu').val(), ordre:$('#ordreMenu').val(),
		metaDesc:$('#metaDesc').val(), stylePage:$('#stylePage').val()}, 
			function(data){
				if(data.statut =='ok'){
					ecranGestionMenu();
					plasmide.menu.start();
				}			
			},
		'json');		
	}
	
	function supprimerMenu(){
	
		var estSuppr = confirm("Voulez vous vraiment supprimer ce menu ?");
		if(estSuppr){
			var idMenu = $(this).attr('idMenu');
			var idParent = $(this).attr('idParent');
			$.get(modCtrlPath, {action:'supprMenu', idMenu:idMenu, idParent:idParent}, 
			function(data){
				if(data.statut =='ok'){
					ecranGestionMenu();
					plasmide.menu.start();
				}	
			}, 'json');	
		}else{
			$.history.load("type=admin&action=neutre");
		}
		
	}
	
	
	function validerEditMenu(id){
	
		
		$.post(modCtrlPath, 
		{action:'editMenu', idMenu:id, idParent:$('#listeParent').val(),
		mod:$('#typeMenus').val(), nom:$('#nomMenu').val(), ordre:$('#ordreMenu').val(),
		metaDesc:$('#metaDesc').val(), stylePage:$('#stylePage').val()}, 
			function(data){
				if(data.statut =='ok'){
					ecranGestionMenu();
					plasmide.menu.start();
				}		
			}, 
		'json');
	}
	
	function ecranGestionMedia(){
		TEMPLATE.integrerArticle(TEMPLATE.forgerArticle('Gestion des Médias', '<div id="finder">finder</div>'));
		
		var elf = $('#finder').elfinder({
			url : '/tools/elfinder/php/connector.php',  // connector URL (REQUIRED)
			lang : 'fr'
		}).elfinder('instance');
			
			
	}
	
	function ecranGestionArticle(){		
				
		$.get(modCtrlPath, {action:'gestion'}, function(data){
			if(data.statut == 'ok'){			
				
				var nb_art = data.article.length;
				var nb_menu = data.menus.length;
				var liste_menu = '';
				
				var nomTmp ='Aucun';
				
				
				var liste_art = 
				'<table> <tbody>' +					
				'<tr>'+	
					'<th >Article</th>'+
					'<th>Menu parent</th>'+
					'<th>Date</th>'+
					'<th>En News</th>'+
					'<th>Commentaires</th>'+
					'<th>Ordre</th>'+
					'<th></th>'+
					'<th></th>'+
				'</tr>';			
				var nom_menu ='Aucun';		
				
				for(var i =0;  i<nb_art ; i++){					
					
					for(var j =0;  j<nb_menu ; j++){
						
						if(data.article[i].idMenu == data.menus[j].id){
							nom_menu = data.menus[j].nom;							
						}						
					}
					
					
					//indicateur de presence sur les news
					var newsChecked = "";
					var commentChecked = "";
					if(data.article[i].news  =="oui"){
						newsChecked="checked";
					}
					if(data.article[i].comment  =="oui"){
						commentChecked="checked";
					}
					
					
					liste_art += 
					'<tr>'+		
						'<td> <a href="/site/art/'+ data.article[i].id+'" target="_blank">'+ data.article[i].nom+'</a></td>'+
						'<td>'+ nom_menu+'</td>'+
						'<td>'+ data.article[i].date+'</td>'+
						'<td> <input id="boxNews'+i+'" name="'+ data.article[i].id+'" type=checkbox '+newsChecked+' /></td>'+
						'<td> <input id="boxCom'+i+'" name="'+ data.article[i].id+'" type=checkbox '+commentChecked+' /></td>'+
						'<td>'+ data.article[i].ordre+'</td>'+
						'<td > <img src="/mods/admin/style/images/crayonEdit.png" id="edit_'+data.article[i].id+'" title="Editer" alt="Editer" /> </td>'+
						'<td ><img src="/mods/admin/style/images/corbeilleSuppr.png" id="suppr_'+data.article[i].id+'" title="Supprimer" alt="Supprimer" /></td>'+
					'</tr>';	
					
					nom_menu ='Aucun';
								
				}				
				liste_art += ' </tbody></table>';
				var titre = 'Gestion des Articles';
				
				var contenu = 
				'<p class="text-center"><button id="ajout_art" class="btn btn-default">Nouvel Article</button></p>'
				+'<p >Liste des articles du site.</p>'
				+liste_art;
				
				TEMPLATE.integrerArticle(TEMPLATE.forgerArticle(titre, contenu));
				
			
				//'''''''''''''''''''''''''''''''''''''''''''
				//Ajout des evenemets
				for(var i =0;  i<nb_art ; i++){
					
					
					$('#suppr_'+data.article[i].id).attr('idArt', data.article[i].id);
					$('#suppr_'+data.article[i].id).attr('nomArt', data.article[i].nom);
					
					$('#edit_'+data.article[i].id).attr('idArt', data.article[i].id);
					
					$('#suppr_'+data.article[i].id).click(supprArticle);
					$('#edit_'+data.article[i].id).click(pageEditionArticle);
					
					$('#boxNews'+i).click(actionNews);
					$('#boxCom'+i).click(actionComment);
					
				}
			//'''''''''''''''''''''''''''''''''''''''''''
				$('#ajout_art').click(pageCreationArticle);
				
				
			}else if(statut == 'deco'){
				ecranDeco('Votre Session a expirée, merci de vous authentifier à nouveau.');
			}
		}, 'json');
	}
	
	//##############################################
	function actionNews(){
					
		var idArticle = $(this).attr("name");
		
		$.get(modCtrlPath,{action:'inversionNews', id:idArticle}, function(data){
		
			if(data.statut=='ok'){				
				if(data.etatCase== 'coche'){
					$(this).attr("checked", true);					
				}else if(data.etatCase == 'decoche'){					
					$(this).attr("checked", false);
				}				
			}else if(statut == 'deco'){
				ecranDeco('Votre Session a expirée, merci de vous authentifier à nouveau.');
			}			
		},'json');		
	}
	
	function actionComment(){
		
		var idArticle = $(this).attr("name");
		
		$.get(modCtrlPath,{action:'inversionComment', id:idArticle}, function(data){
		
			if(data.statut=='ok'){				
				if(data.etatCase== 'coche'){
					$(this).attr("checked", true);					
				}else if(data.etatCase == 'decoche'){					
					$(this).attr("checked", false);
				}				
			}else if(statut == 'deco'){
				ecranDeco('Votre Session a expirée, merci de vous authentifier à nouveau.');
			}			
		},'json');		
	}
	
	
	function pageAdminArticle (data, edition){
		
		var titre;
		var idMenuSelect = '';
		var titreArtSelect = '';
		var ordreArtSelect = '';
		if(edition){
			titre = 'Editer un article';
			idMenuSelect = data.article.idMenu;
			titreArtSelect =data.article.titre;
			ordreArtSelect =data.article.ordre;
		}else{
			
			titre = 'Créer un article';
		}
		
		
		var contenu =
		'<div class="text-center">'
		+'Menu correspondant :'
		+'<br />'
		+'<select id="listeMenus" > '
		+'<option value="null"> Aucun </option>'
		+genererListe(data,idMenuSelect , '', 0)
		+'</select>'
		+'<br /><br />'
		+'Titre de l\'article :'
		+'<br />'
		+'<input type="text" id="titreArt" value="'+titreArtSelect+'">'
		+'<br />'
		+'Ordre de l\'article :'
		+'<br />'
		+'<input type="text" id="ordreArt" value="'+ordreArtSelect+'">'
		+'<br /><br />'
		+'</div>'
		+'<div id="editeur" name="editeur"></div>'
		+'<div class="text-center">'
		+'<br />'
		+'<button class="btn btn-default" id="retour_art">Retour</button> '
		+'<button class="btn btn-default" id="valider_art">Valider</button> '
		+'</div>';

		TEMPLATE.integrerArticle(TEMPLATE.forgerArticle(titre, contenu));	
				
		delete CKEDITOR.instances['editeur'];
				
		CKEDITOR.replace( 'editeur', ckeConfig);
		
		$('#retour_art').click(ecranGestionArticle);				
		if(edition){
			$('#valider_art').click(validerEdition);
			idArtEdit = data.article.id;
			CKEDITOR.instances['editeur'].setData(data.article.texte);
			
		}else{
			$('#valider_art').click(validerArticle);			
		}
	}
	
	/*
	##############################################
	*/
	function pageEditionArticle(event){
		
		var idArt = $(this).attr('idArt')
		$.get(modCtrlPath, {action:'edition',id:idArt}, function(data){
						
			if(data.statut=='ok'){				
				
				pageAdminArticle(data, true);				
			
			}else if(statut == 'deco'){
				ecranDeco('Votre Session a expirée, merci de vous authentifier à nouveau.');
			}
		}, 'json');
	}
	/*
	##############################################
	*/
	 function pageCreationArticle(){
		
		$.get(modCtrlPath, {action:'creation'}, function(data){
			
			if(data.statut=='ok'){				
				 
         		pageAdminArticle(data, false);
			
			}else if(statut == 'deco'){
				ecranDeco('Votre Session a expirée, merci de vous authentifier à nouveau.');
			}	
		}, 'json');		
	}
	
	/*
	##############################################
	*/
	function supprArticle(){
		
		var nom = $(this).attr('nomArt');
		var idArt = $(this).attr('idArt');
		var choix = confirm('voulez vous vraiment supprimer l\'article : \n" '+nom+' " ?' );
		
		if(choix){
			$.get(modCtrlPath, {action:'suppr', id:idArt}, function(data){
				if(data.statut == 'ok'){
					ecranGestionArticle();	
				}else if(statut == 'deco'){
					ecranDeco('Votre Session a expirée, merci de vous authentifier à nouveau.');
				}		
			}, 'json');				
		}	
	}
	
	/*
	##############################################
	*/
	function validerArticle(){
		var titreArt = $('#titreArt').val();
		titreArt = titreArt.replace(/&/g, '%gronk' );
		titreArt = titreArt.replace(/\+/g, '%grank' );
		var ordreArt = $('#ordreArt').val();
		ordreArt = ordreArt.replace(/&/g, '%gronk' );
		ordreArt = ordreArt.replace(/\+/g, '%grank' );
		var texte = CKEDITOR.instances.editeur.getData();
		texte = texte.replace(/&/g, '%gronk' );
		texte = texte.replace(/\+/g, '%grank' );
		var idMenu = $('#listeMenus').val();
		
		
		var param = 'action=validerCreation&id='+$('listeMenus').value+'&texte='+texte+'&titre='+titreArt;
		
		$.post(modCtrlPath, {action:'validerCreation', id:idMenu, texte:texte, titre:titreArt, ordre:ordreArt}, function(data){
			if(data.statut=='ok'){
				ecranGestionArticle();
			}else if(statut == 'deco'){
				ecranDeco('Votre Session a expirée, merci de vous authentifier à nouveau.');
			}
		}, 'json');		
	}
	
	/*
	##############################################
	*/
	function validerEdition(){
	
		var titreArt = $('#titreArt').val();
		titreArt = titreArt.replace(/&/g, '%gronk' );
		titreArt = titreArt.replace(/\+/g, '%grank' );
		var ordreArt = $('#ordreArt').val();
		ordreArt = ordreArt.replace(/&/g, '%gronk' );
		ordreArt = ordreArt.replace(/\+/g, '%grank' );
		var texte = CKEDITOR.instances.editeur.getData();
		texte = texte.replace(/&/g, '%gronk' );
		texte = texte.replace(/\+/g, '%grank' );
		var idMenu = $('#listeMenus').val();
		
		$.post(modCtrlPath, {action:'validerEdition', idMenu:idMenu, texte:texte, ordre:ordreArt, titre:titreArt, idArt:idArtEdit},
		function(data){
			if(data.statut=='ok'){
				ecranGestionArticle();
			}else if(statut == 'deco'){
				ecranDeco('Votre Session a expirée, merci de vous authentifier à nouveau.');
			}
		}, 'json');		
	}
	
	/*
	##############################################
	*/
	function genererListe(data, select , parent, niveau ){
		var html = '';
		var espace = '&nbsp;';
		for(var i = 0;  i< niveau ; i++)
        {
			espace +=espace+espace;
		}
		
		
		var nb_menu = data.menu.length;
		
		for(var i = 0;  i<nb_menu ; i++ )
        {				
			if(data.menu[i].idParent == parent.toString() ){
				
				html +='<option value="'+data.menu[i].id+'"';
				if(select !='' && select == data.menu[i].id){
					
					html +=' selected="selected"';
					select = data.menu[i].id;
				}				
				html +=' >'+ espace+data.menu[i].nom+'</option>';
				html +=genererListe(data, select, data.menu[i].id, niveau+1);
			}						
		}
		return html;
	}
	

}


//ajout de la sequence dans plasmide.
var admin = new Admin();
plasmide.addSequence(admin, 'admin');


})(window);

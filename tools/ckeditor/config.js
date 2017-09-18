/**
 * @license Copyright (c) 2003-2015, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.md or http://ckeditor.com/license
 */

CKEDITOR.editorConfig = function( config ) {
	// Define changes to default configuration here. For example:
	// config.language = 'fr';
	//config.uiColor = '#AADC00';
	config.extraPlugins = 'video';
	config.ForcePasteAsPlainText = true;
	//config.enterMode = CKEDITOR.ENTER_BR;
	// config.stylesSet = [
		// Block-level styles
		// { name : 'Blue Title', element : 'h2', styles : { 'color' : 'Blue' } },
		// { name : 'Red Title' , element : 'h3', styles : { 'color' : 'Red' } },	 
		// Inline styles		
		// { name : 'Marker: jaune', element : 'span', styles : { 'background-color' : 'Yellow' } }
	// ];
	config.toolbar  = [
			{ name: 'document',    items : [ 'Source','-','Save','NewPage','DocProps','Preview','Print','-','Templates' ] },
			{ name: 'clipboard',   items : [ 'Cut','Copy','Paste','PasteText', 'PasteFromWord','-','Undo','Redo' ] },
			{ name: 'editing',     items : [ 'Find','Replace','-','SelectAll','-','SpellChecker', 'Scayt' ] },
			{ name: 'tools',       items : [ 'Maximize', 'ShowBlocks','-','About' ] },
			'/',
			{ name: 'basicstyles', items : [ 'Bold','Italic','Underline','Strike','Subscript','Superscript','-','RemoveFormat' ] },
			{ name: 'paragraph',   items : [ 'NumberedList','BulletedList','-','Outdent','Indent','-','Blockquote','CreateDiv','-','JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock','-','BidiLtr','BidiRtl' ] },
			'/',
			{ name: 'styles',      items : [ 'Styles','Format','Font','FontSize' ] },
			{ name: 'colors',      items : [ 'TextColor','BGColor' ] },
			{ name: 'insert',      items : [ 'Image', 'Video', 'Flash','Table','HorizontalRule','Smiley','SpecialChar','PageBreak','Iframe','Link','Unlink' ] }
		];
};

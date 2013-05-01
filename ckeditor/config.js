/*
Copyright (c) 2003-2012, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or http://ckeditor.com/license
*/

CKEDITOR.editorConfig = function( config )
{
	// Define changes to default configuration here. For example:
	// config.language = 'fr';
	// config.uiColor = '#AADC6E';
	config.toolbar = 
	[
		['Source', 'Cut', 'Copy', 'Paste', 'PasteFromWord', '-', 'Undo', 'Redo'],
		//['Cut', 'Copy', 'Paste', 'PasteFromWord', '-', 'Undo', 'Redo'],
		['Find', 'Replace', '-', 'SelectAll'],
		['TextColor','BGColor', '-', 'Maximize'],
		['NumberedList','BulletedList', '-', 'Image'],
		['Bold','Italic','Underline','Strike','Subscript','Superscript','-','RemoveFormat'],
	];
	config.resize_enabled = false;
};

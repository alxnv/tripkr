/*
Copyright (c) 2003-2011, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or http://ckeditor.com/license
*/

CKEDITOR.editorConfig = function( config )
{
	// Define changes to default configuration here. For example:
	// config.language = 'fr';
	// config.uiColor = '#AADC6E';
	if (document.location.host == 'localhost') {
		config.filebrowserUploadUrl = '/1-tripkr/a7-ckuploadimage/ckupload';
	} else {
		config.filebrowserUploadUrl = '/a7-ckuploadimage/ckupload';
	};
	config.contentsCss = '/style.css';
};

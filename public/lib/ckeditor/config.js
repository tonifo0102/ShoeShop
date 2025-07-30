/**
 * @license Copyright (c) 2003-2021, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see https://ckeditor.com/legal/ckeditor-oss-license
 */

CKEDITOR.editorConfig = function( config ) {
	config.removeButtons = 'about';
	config.format_tags = 'p;h1;h2;h3;pre';
	config.removeDialogTabs = 'image:advanced;link:advanced';
	config.removePlugins = 'easyimage, cloudservices, about, exportpdf, flash, print, language, save, forms, scayt';
	config.filebrowserUploadUrl = 'group/upload?command=QuickUpload';
};

/**
 * @license Copyright (c) 2003-2019, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see https://ckeditor.com/legal/ckeditor-oss-license
 */

CKEDITOR.editorConfig = function( config ) {
  // Define changes to default configuration here.
  // For complete reference see:
  // https://ckeditor.com/docs/ckeditor4/latest/api/CKEDITOR_config.html

  // The toolbar groups arrangement, optimized for two toolbar rows.
  config.toolbarGroups = [
    { name: 'clipboard',   groups: [ 'clipboard', 'undo' ] },
    { name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ] },
    { name: 'paragraph',   groups: [ 'list', 'indent', 'blocks'] },
    { name: 'editing',     groups: [ 'find', 'selection'] },
    { name: 'links' },
    { name: 'insert'},
    '/',
    { name: 'align' },
    { name: 'styles' },
    { name: 'colors' },
    { name: 'document',    groups: [ 'mode' ] },
  ];

  // Remove some buttons provided by the standard plugins, which are
  // not needed in the Standard(s) toolbar.
  config.removeButtons = 'Underline,Subscript,Superscript';

  config.removePlugins = 'flash,smiley,form,iframe,specialchar,exportpdf,preview,print,save,newpage';

  // Set the most common block elements.
  config.format_tags = 'p;h1;h2;h3;pre';

  // Simplify the dialog windows.
  config.removeDialogTabs = 'image:advanced;link:advanced';
  
  // Enable token plugin
  config.extraPlugins = 'token';

  config.tokenStart = '{{';
  config.tokenEnd = '}}';
};

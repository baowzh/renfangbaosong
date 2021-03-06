﻿/*
Copyright (c) 2003-2012, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or http://ckeditor.com/license
*/

CKEDITOR.editorConfig = function( config )
{
	// Define changes to default configuration here. For example:
    config.language = 'zh-cn';
	// config.uiColor = '#AADC6E';

config.toolbar_Full = [
['Source'],
['Cut','Copy','Paste','PasteText','PasteFromWord',],
['Undo','Redo','-','Find','Replace']
['Bold','Italic','Underline','Strike','-','Subscript','Superscript'],
['NumberedList','BulletedList','-','Outdent','Indent','Blockquote'],
['JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock'],
['Link','Unlink','Anchor'],'/',
['Format','Font','FontSize'],
['TextColor','BGColor'],
['Image','Flash','Link','HorizontalRule','Smiley','SpecialChar','PageBreak']
];

config.font_names='宋体/宋体;黑体/黑体;仿宋/仿宋_GB2312;楷体/楷体_GB2312;隶书/隶书;幼圆/幼圆;微软雅黑/微软雅黑;'+ config.font_names;


};

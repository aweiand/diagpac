
function editorOn(idEditor, w, h){
	var dir = '..';
	if (!w)
		var w = '400px';
	if (!h)
		var h = '200px';		
		
	$("#"+idEditor).ckeditor(function(){ }, { 
		toolbar :
			[
				[ 'Preview', 'Source' ],
				[ 'TextColor','BGColor' ],
				[ 'Cut','Copy','Paste','PasteText','PasteFromWord','-','Undo','Redo' ],
				[ 'Find','Replace','-','SelectAll','-','Scayt' ],
				[ 'Image','Flash','Table','HorizontalRule','SpecialChar','Iframe' ],
				[ 'Bold','Italic','Strike','-','RemoveFormat' ],
				[ 'Styles','Format','Font','FontSize' ],
				[ 'NumberedList','BulletedList','-','Outdent','Indent','-','Blockquote' ],
				[ 'Link','Unlink','Anchor' ] ,
				[ 'Maximize','-','Templates' ],
				[ 'NumberedList','BulletedList','-','Outdent','Indent','-','Blockquote','-','JustifyLeft',
				'JustifyCenter','JustifyRight','JustifyBlock','-','BidiLtr','BidiRtl','-', 'ShowBlocks' ]
			],
			height: h+'px',
			width:  w+'px',
			enterMode : CKEDITOR.ENTER_BR,
			
			filebrowserBrowseUrl 	  : dir+'/tools/mainframe/plugins/ckfinder/ckfinder.html',
			filebrowserImageBrowseUrl : dir+'/tools/mainframe/plugins/ckfinder/ckfinder.html?type=Images',
			filebrowserFlashBrowseUrl : dir+'/tools/mainframe/plugins/ckfinder/ckfinder.html?type=Flash',
			filebrowserUploadUrl 	  : dir+'/tools/mainframe/plugins/ckfinder/core/connector/php/connector.php?command=QuickUpload&amp;type=Files',
			filebrowserImageUploadUrl : dir+'/tools/mainframe/plugins/ckfinder/core/connector/php/connector.php?command=QuickUpload&amp;type=Images',
			filebrowserFlashUploadUrl : dir+'/tools/mainframe/plugins/ckfinder/core/connector/php/connector.php?command=QuickUpload&amp;type=Flash'
		});
}

function editorOnMini(idEditor, w, h){
	var dir = '..';
	if (!w)
		var w = '400px';
	if (!h)
		var h = '200px';		
		
	$("#"+idEditor).ckeditor(function(){ }, { 
		toolbar :
			[
				[ 'Source', 'TextColor','BGColor' ],
				[ 'Image','Table','HorizontalRule' ],
				[ 'Bold','Italic', 'Format','Font','FontSize' ],
				[ 'Link', 'NumberedList','BulletedList','-','Outdent','Indent','-','Blockquote','-','JustifyLeft',
				'JustifyCenter','JustifyRight','JustifyBlock' ]
			],
			height: h+'px',
			width:  w+'px',
			enterMode : CKEDITOR.ENTER_BR,
			
			filebrowserBrowseUrl 	  : dir+'/tools/mainframe/plugins/ckfinder/ckfinder.html',
			filebrowserImageBrowseUrl : dir+'/tools/mainframe/plugins/ckfinder/ckfinder.html?type=Images',
			filebrowserFlashBrowseUrl : dir+'/tools/mainframe/plugins/ckfinder/ckfinder.html?type=Flash',
			filebrowserUploadUrl 	  : dir+'/tools/mainframe/plugins/ckfinder/core/connector/php/connector.php?command=QuickUpload&amp;type=Files',
			filebrowserImageUploadUrl : dir+'/tools/mainframe/plugins/ckfinder/core/connector/php/connector.php?command=QuickUpload&amp;type=Images',
			filebrowserFlashUploadUrl : dir+'/tools/mainframe/plugins/ckfinder/core/connector/php/connector.php?command=QuickUpload&amp;type=Flash'
		});
}

function viraText(idTexto){
/*
  	$(idTexto).each($(idTexto).text(), function(){
		$(idTexto).append(this + '<br />');
	});
*/	
}
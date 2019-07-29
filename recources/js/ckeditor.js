$(document).ready(function ()
{
	CKEDITOR.replace('editor', {
		language: 'nl',
        extraPlugins: 'table,tabletools,tableresize',
		toolbarGroups: [
			{name: 'styles', groups: ['styles']},
			{name: 'clipboard', groups: ['clipboard', 'undo']},
			{name: 'editing', groups: ['find', 'selection', 'spellchecker', 'editing']},
			{name: 'forms', groups: ['forms']},
			{name: 'basicstyles', groups: ['basicstyles', 'cleanup']},
            {name: 'insert', groups: [ 'insert' ] },
			{name: 'table', groups: ['basicstyles', 'cleanup']},
			{name: 'paragraph', groups: ['list', 'indent', 'blocks', 'align', 'bidi', 'paragraph']},
			{name: 'links', groups: ['links']},
			{name: 'insert', groups: ['insert']},
			{name: 'colors', groups: ['colors']},
			{name: 'tools', groups: ['tools']},
			{name: 'others', groups: ['others']},
			{name: 'about', groups: ['about']},
			{name: 'document', groups: ['mode', 'doctools', 'document']}
		],
		removeButtons: 'Save,NewPage,Preview,Print,Templates,SelectAll,Scayt,Form,Checkbox,Radio,TextField,Textarea,Select,Button,ImageButton,HiddenField,Cut,Copy,Paste,Find,Replace,Strike,Subscript,Superscript,CopyFormatting,RemoveFormat,Blockquote,CreateDiv,BidiLtr,BidiRtl,Language,Link,Unlink,Anchor,Image,Flash,HorizontalRule,Smiley,SpecialChar,PageBreak,Iframe,Styles,Font,FontSize,TextColor,BGColor,Maximize,ShowBlocks,About'

	});

	CKEDITOR.config.height = '35rem';
	CKEDITOR.config.height = '35rem';

	//vars toevoegen
	$(document).on('click', '.ckeditor-vars li', function ()
	{

		//aangeklikt element
		$e = $(this);

		//categorie bepalen
        categorie = $e.parent('ul').data('var-categorie');

        //variabele
		val = '{{' + categorie + '.' + $e.data('var') + '}}';

		CKEDITOR.instances.editor.insertHtml(val);
	});
});
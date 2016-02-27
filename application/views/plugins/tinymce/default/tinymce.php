<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

session_start('responsivefilemanager');

$rfm_lang = 'en_EN';

switch ( $this->mcm->filtered_system_params[ $this->mcm->environment . '_language' ] ){
	
	case 'pt-BR':
		$rfm_lang = 'pt_BR';
		break;
	case 'pt-PT':
		$rfm_lang = 'pt_PT';
		break;
	
}

$_SESSION["responsivefilemanager_lang"] = $rfm_lang;

?>

<!--------------------------------------------
TinyMCE
-->

<script type="text/javascript">
	
	$( document ).ready(function(){
		
		tinymce.PluginManager.add('example', function(editor, url) {
			// Add a button that opens a window
			editor.addButton('example', {
				text: 'My button',
				icon: false,
				onclick: function() {
					// Open window
					editor.windowManager.open({
						title: 'Example plugin',
						body: [
							{type: 'textbox', name: 'title', label: 'Title'}
						],
						onsubmit: function(e) {
							// Insert content when the window form is submitted
							editor.insertContent('Title: ' + e.data.title);
						}
					});
				}
			});
			
			// Adds a menu item to the tools menu
			editor.addMenuItem('example', {
				text: 'Example plugin',
				context: 'tools',
				onclick: function() {
					// Open window with a specific url
					editor.windowManager.open({
						title: 'TinyMCE site',
						url: 'http://www.tinymce.com',
						width: 400,
						height: 300,
						buttons: [{
							text: 'Close',
							onclick: 'close'
						}]
					});
				}
			});
			
		});
		
		
		
		tinymce.PluginManager.add('viacms', function(editor, url) {
			
			editor.addButton('viacms', {
				text: '<?= lang( 'readmore' ); ?>',
				icon: false,
				onclick: function() {
					var content = editor.getContent();
					if (content.match(/<hr\s+id=(\"|')vcms-readmore(\"|')\s*\/*>/i)) {
						alert('<?= lang( 'editor_readmore_already_exists' ); ?>');
						return false;
					} else {
						editor.insertContent('<hr id=\"vcms-readmore\" />');
					}
					
				}
			});
		});
		
		
		tinymce.init({
			
			selector: "textarea.js-editor",
			relative_urls : true,
			remove_script_host : true,
			document_base_url : "<?= BASE_URL . '/'; ?>",
			menubar: false,
			entity_encoding : "raw",
			plugins: [
				"viacms advlist autolink link image lists charmap print preview hr anchor pagebreak spellchecker",
				"searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking",
				"save table contextmenu directionality emoticons template paste textcolor responsivefilemanager"
			],
			
			toolbar1: "viacms save newdocument print searchreplace | undo redo | cut copy paste pastetext | table hr | link unlink anchor | image media charmap | preview code visualblocks fullscreen",
			toolbar2: "styleselect removeformat | bold italic underline strikethrough subscript superscript | alignleft aligncenter alignright alignjustify | bullist numlist | outdent indent forecolor backcolor",
			image_advtab: true,
			
			valid_children : "+body[style]",
			
			valid_elements : '*[*]',
// 			extend_valid_elements : "td[title|style|class|id|onclick|ondblclick|onkeydown|onkeypress|onkeyup|onmousedown|onmousemove|onmouseout|onmouseover|onmouseup]"
// 				+"",
			
			//forced_root_block: false, // http://www.tinymce.com/wiki.php/Configuration:forced_root_block
			
			//mode : "exact",
			
			width: '100%',
			
			skin: 'vecms',
			
			save_enablewhendirty: false,
			save_onsavecallback: function() { console.log("Save");$('#submit-apply').click() },
			
			<?php if ( $this->mcm->filtered_system_params[ $this->mcm->environment . '_language' ] != 'english' ) { ?>
			language : '<?= $this->mcm->filtered_system_params[ $this->mcm->environment . '_language' ]; ?>',
			<?php } ?>
			
			external_filemanager_path:"<?= JS_DIR_URL; ?>/responsivefilemanager/filemanager/",
			filemanager_access_key:"<?= md5( $this->config->item( 'encryption_key' ) ); ?>" ,
			filemanager_title: '<?= lang('select_file'); ?>' ,
			external_plugins: { "filemanager" : "<?= JS_DIR_URL; ?>/responsivefilemanager/filemanager/plugin.min.js"},
			content_css : "<?= SITE_THEMES_URL . '/' . $this->mcm->filtered_system_params[ 'site_theme' ] . '/assets/css'; ?>/js-editor.css"
			
		});
			
	});

	$( window ).load(function(){
		
		$('.mce-edit-area iframe').removeAttr('title');
		var editor = tinyMCE.activeEditor;
//editor.setContent('oláaa'); 
	});
	
</script>

<!--
TinyMCE
--------------------------------------------->

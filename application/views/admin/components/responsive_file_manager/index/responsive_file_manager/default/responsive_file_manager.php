		
		<div class="responsive-file-manager-container">
			
			<header class="component-head">
				
				<h1>
					
					<?= lang( $component_name ); ?>
					
				</h1>
				
			</header>
			
			<iframe id="responsive-file-manager" src="<?= JS_DIR_URL; ?>/responsivefilemanager/filemanager/dialog.php?lang=pt_BR&dir=&akey=<?= md5( $this->config->item( 'encryption_key' ) ); ?>"></iframe>
			
		</div>


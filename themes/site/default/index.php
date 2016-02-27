<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="<?= $this->mcm->filtered_system_params[ $this->mcm->environment . '_language' ]; ?>" prefix="og: http://ogp.me/ns#" >
	<head>
		
		<?php
			
			$this->plugins->load( array( 'names' => array( 'jquery', 'jquery_scrolltop' ), 'types' => array( 'js_tooltip' ) ) );
			
			$this->voutput->append_head_stylesheet( 'theme', SITE_THEMES_URL . '/' . site_theme() . '/assets/css/theme.css.php' );
			$this->voutput->append_head_stylesheet( 'google_font', 'http://fonts.googleapis.com/css?family=Droid+Sans+Mono|Roboto:400,100,100italic,300,300italic,400italic,700,700italic|Roboto+Condensed:300italic,400italic,700italic,400,300,700' );
			$this->voutput->append_head_script( 'theme', SITE_THEMES_URL . '/' . site_theme() . '/assets/js/functions.js' );
			
			$google_font_script = "
				
				WebFontConfig = {
					google: { families: [ 'Droid+Sans+Mono::latin', 'Roboto:400,100,100italic,300,300italic,400italic,700,700italic:latin', 'Roboto+Condensed:300italic,400italic,700italic,400,300,700:latin' ] }
				};
				(function() {
					var wf = document.createElement('script');
					wf.src = ('https:' == document.location.protocol ? 'https' : 'http') +
						'://ajax.googleapis.com/ajax/libs/webfont/1/webfont.js';
					wf.type = 'text/javascript';
					wf.async = 'true';
					var s = document.getElementsByTagName('script')[0];
					s.parentNode.insertBefore(wf, s);
				})();
				
			";
			
			$this->voutput->append_head_script_declaration( 'theme', $google_font_script );
			
			echo $this->voutput->get_head();
			
		?>
		
	</head>
	
	<body class="vui <?= $this->ua->is_mobile() ? 'mobile' : 'desktop'; ?> <?= ( @$this->mcm->loaded_modules[ 'top_banner' ] ) ? 'with-top-banner' : ''; ?>">
		
		<?= $this->voutput->get_body_start(); ?>
		
		<div id="site-block">
			
			<div id="top-bar">
				
				<div class="s1">
					
					<div id="top-logo">
						
						<?php if ( @$this->mcm->loaded_modules[ 'logo' ] ){ ?>
						
						<?php foreach ( @$this->mcm->loaded_modules[ 'logo' ] as $key => $module ) { echo @$module; } ?>
						
						<?php } else { ?>
						
						<a id="logo" href="<?= site_url(); ?>"></a>
						
						<?php } ?>
						
					</div>
					
					<?php if ( @$this->mcm->loaded_modules[ 'top-other-info' ] ){ ?>
						
						<div id="top-other-info">
							
							<?php foreach ( @$this->mcm->loaded_modules[ 'top-other-info' ] as $key => $module ) { echo @$module; } ?>
							
						</div>
						
					<?php } ?>
					
					<?php if ( @$this->mcm->loaded_modules[ 'top_menu' ] ){ ?>
					<div id="top-menu">
						
						<?php foreach ( @$this->mcm->loaded_modules[ 'top_menu' ] as $key => $module ) {
							
							echo @$module;
							
						} ?>
						
					</div>
					<?php } ?>
					
				</div>
				
			</div>
			
			<?php if ( @$this->mcm->loaded_modules[ 'top_banner' ] ){ ?>
			
			<div id="top-banner-block">
				
				<div class="s1">
					
					<div class="s2">
						
						<?php foreach ( @$this->mcm->loaded_modules[ 'top_banner' ] as $key => $module ) {
							
							echo @$module;
							
						} ?>
						
					</div>
					
				</div>
				
			</div>
			
			<?php } ?>
			
			<?php if ( @$this->mcm->loaded_modules[ 'after_banner' ] ){ ?>
			
			<div id="after-banner-block">
				
				<div class="s1">
					
					<div id="after-banner-menu" class="s2">
						
						<?php foreach ( @$this->mcm->loaded_modules[ 'after_banner' ] as $key => $module ) {
							
							echo @$module;
							
						} ?>
						
					</div>
					
				</div>
				
				<div class="shadow"></div>
				
			</div>
			
			<?php } ?>
			
			<?php if ( isset( $msg ) AND ! empty( $msg ) AND $msg !== '' ){ ?>
			
			<div id="msg-block">
				
				<div class="s1">
					
					<div class="s2">
						
						<?= $msg; ?>
						
					</div>
					
				</div>
				
				<div class="shadow"></div>
				
			</div>
			
			<?php } ?>
			
			<?php if ( trim( $this->voutput->get_content() ) !== '' ){ ?>
			
			<div id="content-block">
				
				<div class="s1">
					
					<div class="s2">
						
						<?= $this->voutput->get_content(); ?>
						
					</div>
					
				</div>
				
			</div>
			
			<?php } ?>
			
			<?php if ( @$this->mcm->loaded_modules[ 'bottom_content' ] ){ ?>
			
			<div id="bottom-content-block">
				
				<div class="s1">
					
					<div class="s2">
						
						<?php foreach ( @$this->mcm->loaded_modules[ 'bottom_content' ] as $key => $module ) {
							
							echo @$module;
							
						} ?>
						
					</div>
					
				</div>
				
			</div>
			
			<?php } ?>
			
			<?php
				
				$footer_col_count = 0;
				
				if ( @$this->mcm->loaded_modules[ 'footer-left' ] ) $footer_col_count++;
				if ( @$this->mcm->loaded_modules[ 'footer-center' ] ) $footer_col_count++;
				if ( @$this->mcm->loaded_modules[ 'footer-right' ] ) $footer_col_count++;
				
			?>
			
			<footer id="footer-block" class="footer-<?= $footer_col_count; ?>-cols">
				
				<div class="s1">
					
					<?php if ( @$this->mcm->loaded_modules[ 'footer-left' ] ){ ?>
					
					<div id="footer-module-left" class="footer-col">
						
						<div class="s1">
							
							<?php foreach ( @$this->mcm->loaded_modules[ 'footer-left' ] as $key => $module ) { echo @$module; } ?>
							
						</div>
						
					</div><?php
					
					}
					
					if ( @$this->mcm->loaded_modules[ 'footer-center' ] ){
					
					?><div id="footer-module-center" class="footer-col">
						
						<div class="s1">
							
							<?php foreach ( @$this->mcm->loaded_modules[ 'footer-center' ] as $key => $module ) { echo @$module; } ?>
							
						</div>
						
					</div><?php
					
					}
					
					if ( @$this->mcm->loaded_modules[ 'footer-right' ] ){
					
					?><div id="footer-module-right" class="footer-col">
						
						<div class="s1">
							
							<?php foreach ( @$this->mcm->loaded_modules[ 'footer-right' ] as $key => $module ) { echo @$module; } ?>
							
						</div>
						
					</div>
					
					<?php } ?>
					
					<?php /* ?>
					<?= 'environment = <b>' . environment(); ?></b><br/>
					current_component = <b> <?php var_dump($current_component); ?></b><br/>
					<?= 'component_function = <b>' . $component_function; ?></b><br/>
					<?= 'component_function_action = <b>' . $component_function_action; ?></b><br/>
					<?= 'last_url = <b>'.get_last_url(); ?></b>
					<?php */ ?>
					
				</div>
				
			</footer>
			
		</div>
		
		<?= $this->voutput->get_body_end(); ?>
		
	</body>
</html>
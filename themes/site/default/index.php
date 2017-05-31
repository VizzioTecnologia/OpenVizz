<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="<?= $this->mcm->filtered_system_params[ $this->mcm->environment . '_language' ]; ?>" prefix="og: http://ogp.me/ns#" >
	<head itemscope itemtype="http://schema.org/WebSite">
		
		<?php
			
			$this->plugins->load( array( 'names' => array( 'jquery', 'jquery_scrolltop' ), 'types' => array( 'js_tooltip' ) ) );
			
			$get_args[] = 'uab=' . url_title( $this->ua->browser() );
			
			
			
			if ( $this->voutput->get_head_stylesheet( 'nivo_slider' ) ) {
				
				$this->voutput->unset_head_stylesheet( 'nivo_slider' );
				
				$get_args[] = 'ns=1';
				
			}
			
			if ( $this->voutput->get_head_stylesheet( 'fancybox' ) ) {
				
				$this->voutput->unset_head_stylesheet( 'fancybox' );
				
				$get_args[] = 'fb=1';
				
			}
			
			if ( check_var( $this->mcm->loaded_modules[ 'top_menu' ] ) ) {
				
				$get_args[] = 'tmm=1';
				
			}
			
			if ( check_var( $this->current_component[ 'unique_name' ] ) ) {
				
				$get_args[] = 'ct=1';
				
			}
			
			$this->voutput->append_head_stylesheet( 'google_font', 'https://fonts.googleapis.com/css?family=Open+Sans:300,400,700' );
			$this->voutput->append_head_stylesheet( 'theme', SITE_THEMES_URL . '/' . site_theme() . '/assets/css/theme.css.php' . ( ! empty( $get_args ) ? '?' . join( '&', $get_args ) : '' ) );
			$this->voutput->append_head_script( 'theme', SITE_THEMES_URL . '/' . site_theme() . '/assets/js/js.cookie.js' );
			$this->voutput->append_head_script( 'theme', SITE_THEMES_URL . '/' . site_theme() . '/assets/js/functions.js' );
			/*
			$google_font_script = "
				
				WebFontConfig = {
					google: { families: [ 'Open+Sans:300,400,700:latin' ] }
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
			*/
			echo $this->voutput->get_head();
			
			$content_disp = '';
			
			if ( check_var( $this->mcm->loaded_modules[ 'left' ] ) ) $content_disp .= 'l';
			if ( trim( $this->voutput->get_content() ) !== '' ) $content_disp .= 'c';
			if ( check_var( $this->mcm->loaded_modules[ 'right' ] ) ) $content_disp .= 'r';
			
		?>
		
	</head>
	
	<body class="vui content-disp-<?= $content_disp; ?> <?= $this->ua->is_browser() ? 'ua-browser-' . url_title( $this->ua->browser() ) : ''; ?> <?= $this->ua->is_mobile() ? 'mobile' : 'desktop'; ?> <?= ( check_var( $this->mcm->loaded_modules[ 'top_banner' ] ) ) ? 'with-top-banner' : ''; ?>">
		
		<?= $this->voutput->get_body_start(); ?>
		
		<div id="site-block">
			
			<div id="top-bar">
				
				<div class="s1">
					
					<div id="top-logo">
						
						<?php if ( check_var( $this->mcm->loaded_modules[ 'logo' ] ) ){ ?>
						
						<?php foreach ( @$this->mcm->loaded_modules[ 'logo' ] as $key => $module ) { echo @$module; } ?>
						
						<?php } else { ?>
						
						<a id="logo" href="<?= get_url(); ?>"></a>
						
						<?php } ?>
						
					</div>
					
					<?php if ( check_var( $this->mcm->loaded_modules[ 'top_other_info' ] ) ){ ?>
						
						<div id="top-other-info">
							
							<?php foreach ( @$this->mcm->loaded_modules[ 'top_other_info' ] as $key => $module ) { echo @$module; } ?>
							
						</div>
						
					<?php } ?>
					
					<?php if ( check_var( $this->mcm->loaded_modules[ 'top_menu' ] ) ){ ?>
					<div id="top-menu">
						
						<?php foreach ( @$this->mcm->loaded_modules[ 'top_menu' ] as $key => $module ) {
							
							echo @$module;
							
						} ?>
						
					</div>
					<?php } ?>
					
				</div>
				
			</div>
			
			<?php if ( check_var( $this->mcm->loaded_modules[ 'top_banner' ] ) ){ ?>
			
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
			
			<?php if ( check_var( $this->mcm->loaded_modules[ 'after_banner' ] ) ){ ?>
			
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
			
			<div id="content-block">
				
				<div class="s1">
					
					<?php if ( check_var( $this->mcm->loaded_modules[ 'left' ] ) ){ ?>
						
						<div id="left-modules" class="s2 ">
							
							<div id="left-logo" class="module-wrapper">
							
								<a title="<?= strip_tags( $this->mcm->filtered_system_params[ 'site_name' ] ); ?>" class="logo" href="<?= site_url(); ?>"><span class="site-name"><?= $this->mcm->filtered_system_params[ 'site_name' ]; ?></span></a>
								
							</div><?php
							
							foreach ( $this->mcm->loaded_modules[ 'left' ] as $key => $module ) {
								
								echo @$module;
								
							} ?>
							
						</div><?php 
						
					}
					
					if ( check_var( $this->mcm->loaded_modules[ 'right' ] ) ){
						
						?><div id="right-modules" class="s2 ">
							
							<?php foreach ( $this->mcm->loaded_modules[ 'right' ] as $key => $module ) { ?>
								
								<?= @$module; ?>
								
							<?php } ?>
							
						</div><?php
						
					}
					
					?><div id="content" class="s2">
						
						<?php if ( check_var( $this->mcm->loaded_modules[ 'ad_top_content' ] ) ){ ?>
							
							<div id="ad-top-content-modules" class="s2 ">
								
								<?php foreach ( $this->mcm->loaded_modules[ 'ad_top_content' ] as $key => $module ) { ?>
									
									<?= @$module; ?>
									
								<?php } ?>
								
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
						
						<?php if ( check_var( $this->mcm->loaded_modules[ 'top_content' ] ) ){ ?>
							
							<div id="top-content-modules" class="s2 ">
								
								<?php foreach ( $this->mcm->loaded_modules[ 'top_content' ] as $key => $module ) { ?>
									
									<?= @$module; ?>
									
								<?php } ?>
								
							</div>
							
						<?php } ?>
						
						<?= $this->voutput->get_content(); ?>
						
						<?php if ( check_var( $this->mcm->loaded_modules[ 'bottom_content' ] ) ){ ?>
						
						<div id="bottom-content-block">
							
							<div class="s1">
								
								<div class="s2">
									
									<?php foreach ( $this->mcm->loaded_modules[ 'bottom_content' ] as $key => $module ) {
										
										echo @$module;
										
									} ?>
									
								</div>
								
							</div>
							
						</div>
						
						<?php } ?>
						
					</div>
					
				</div>
				
			</div>
			
			<?php
				
				$footer_col_count = 0;
				
				if ( check_var( $this->mcm->loaded_modules[ 'footer_left' ] ) ) $footer_col_count++;
				if ( check_var( $this->mcm->loaded_modules[ 'footer_center' ] ) ) $footer_col_count++;
				if ( check_var( $this->mcm->loaded_modules[ 'footer_right' ] ) ) $footer_col_count++;
				
			?>
			
			<?php if ( check_var( $this->mcm->loaded_modules[ 'footer_top' ] ) ){ ?>
			
			<footer id="footer-top-block">
				
				<div class="s1">
					
					<div id="footer-module-top" class="footer-top">
						
						<div class="s1">
							
							<?php foreach ( $this->mcm->loaded_modules[ 'footer_top' ] as $key => $module ) { echo @$module; } ?>
							
						</div>
						
					</div>
					
				</div>
				
			</footer>
			
			<?php } ?>
			
			<?php if ( check_var( $this->mcm->loaded_modules[ 'footer_left' ] ) OR check_var( $this->mcm->loaded_modules[ 'footer_center' ] ) OR check_var( $this->mcm->loaded_modules[ 'footer_right' ] ) ){ ?>
			
			<footer id="footer-block" class="footer-<?= $footer_col_count; ?>-cols">
				
				<div class="s1">
					
					<?php if ( check_var( $this->mcm->loaded_modules[ 'footer_left' ] ) ){ ?>
					
					<div id="footer-module-left" class="footer-col">
						
						<div class="s1">
							
							<?php foreach ( $this->mcm->loaded_modules[ 'footer_left' ] as $key => $module ) { echo @$module; } ?>
							
						</div>
						
					</div><?php
					
					}
					
					if ( check_var( $this->mcm->loaded_modules[ 'footer_center' ] ) ){

					?><div id="footer-module-center" class="footer-col">

						<div class="s1">

							<?php foreach ( $this->mcm->loaded_modules[ 'footer_center' ] as $key => $module ) { echo @$module; } ?>

						</div>

					</div><?php

					}

					if ( check_var( $this->mcm->loaded_modules[ 'footer_right' ] ) ){
					
					?><div id="footer-module-right" class="footer-col">
						
						<div class="s1">
							
							<?php foreach ( $this->mcm->loaded_modules[ 'footer_right' ] as $key => $module ) { echo @$module; } ?>
							
						</div>
						
					</div>
					
					<?php } ?>
					
				</div>
				
			</footer>
			
			<?php } ?>
			
			<?php if ( check_var( $this->mcm->loaded_modules[ 'footer_bottom' ] ) ){ ?>
			
			<footer id="footer-bottom-block">
				
				<div class="s1">
					
					<div id="footer-module-bottom" class="footer-bottom">
						
						<div class="s1">
							
							<?php foreach ( $this->mcm->loaded_modules[ 'footer_bottom' ] as $key => $module ) { echo @$module; } ?>
							
						</div>
						
					</div>
					
				</div>
				
			</footer>
			
			<?php } ?>
			
		</div>
		
		<?= $this->voutput->get_body_end(); ?>
		
	</body>
</html>

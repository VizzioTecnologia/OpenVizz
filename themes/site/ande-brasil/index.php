<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?><!DOCTYPE html>
<html lang="<?= $this->mcm->filtered_system_params[ $this->mcm->environment . '_language' ]; ?>" prefix="og: http://ogp.me/ns#" >
	<head>

		<?php
			
			$get_args = array();
			
			$this->plugins->load( array( 'names' => array( 'jquery', 'jquery_scrolltop', 'jquery_svg_pan_zoom' ) ) );
			
			if ( $this->voutput->get_head_stylesheet( 'fancybox' ) ) {
				
				$this->voutput->unset_head_stylesheet( 'fancybox' );
				
				$get_args[] = 'fb=1';
				
			}
			
			$this->voutput->append_head_stylesheet( 'theme', SITE_THEMES_URL . '/' . site_theme() . '/assets/css/theme.css.php' . ( ! empty( $get_args ) ? '?' . join( '&', $get_args ) : '' ) );
// 			$this->voutput->append_head_stylesheet( 'google_font', 'https://fonts.googleapis.com/css?family=Open+Sans+Condensed:300,300italic,700|Open+Sans:400,300,300italic,400italic,700,700italic' );
			$this->voutput->append_head_script( 'theme', SITE_THEMES_URL . '/' . site_theme() . '/assets/js/functions.js' );
			/*
			$google_font_script = "
				
				WebFontConfig = {
					google: { families: [ 'Open+Sans+Condensed:300,300italic,700:latin', 'Open+Sans:400,300,300italic,400italic,700,700italic:latin' ] }
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
			
			if ( @$this->mcm->loaded_modules[ 'left' ] ) $content_disp .= 'l';
			if ( trim( $this->voutput->get_content() ) !== '' ) $content_disp .= 'c';
			if ( @$this->mcm->loaded_modules[ 'right' ] ) $content_disp .= 'r';
			/*
			echo '<br/>plataform: ' . $this->ua->platform();
			echo '<br/>browser: ' . $this->ua->browser();
			echo '<br/>mobile: ' . $this->ua->mobile();
			echo '<br/>é mobile: ' . ( $this->ua->is_mobile() ? 'sim' : 'não' );
			echo '<br/>agent: ' . $this->ua->agent_string();
			*/
			
		?>

	</head>

	<body class="vui content-disp-<?= $content_disp; ?> <?= $this->ua->is_mobile() ? 'mobile' : 'desktop'; ?> <?= ( check_var( $this->mcm->loaded_modules[ 'top_banner' ] ) ) ? 'with-top-banner' : ''; ?>">

		<?= $this->voutput->get_body_start(); ?>

		<div id="site-block">
			
			<?php if ( check_var( $this->mcm->loaded_modules[ 'top-other-info' ] ) OR check_var( $this->mcm->loaded_modules[ 'top_menu' ] ) ){ ?>
			<div id="top-bar">

				<div class="s1">
					
					<?php if ( check_var( $this->mcm->loaded_modules[ 'top-other-info' ] ) ){ ?>

						<div id="top-other-info">

							<?php foreach ( $this->mcm->loaded_modules[ 'top-other-info' ] as $key => $module ) { echo @$module; } ?>

						</div>

					<?php } ?>

					<div class="clear">&nbsp;</div>

					<?php if ( check_var( $this->mcm->loaded_modules[ 'top_menu' ] ) ){ ?>
					<div id="top-menu">

						<?php foreach ( $this->mcm->loaded_modules[ 'top_menu' ] as $key => $module ) {

							echo @$module;

						} ?>

					</div>
					<?php } ?>

				</div>
				
			</div>
			<?php } ?>

			<?php if ( check_var( $this->mcm->loaded_modules[ 'top_banner' ] ) ){ ?>

			<div id="top-banner-block">

				<div class="s1">

					<div class="s2">

						<?php foreach ( $this->mcm->loaded_modules[ 'top_banner' ] as $key => $module ) {

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

						<?php foreach ( $this->mcm->loaded_modules[ 'after_banner' ] as $key => $module ) {

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

				if ( @$this->mcm->loaded_modules[ 'footer-left' ] ) $footer_col_count++;
				if ( @$this->mcm->loaded_modules[ 'footer-center' ] ) $footer_col_count++;
				if ( @$this->mcm->loaded_modules[ 'footer-right' ] ) $footer_col_count++;

			?>
			
			<footer id="footer-top-block">
				
				<div class="s1">
					
					<?php if ( check_var( $this->mcm->loaded_modules[ 'footer-top' ] ) ){ ?>
					
					<div id="footer-module-top" class="footer-top">
						
						<div class="s1">
							
							<?php foreach ( $this->mcm->loaded_modules[ 'footer-top' ] as $key => $module ) { echo @$module; } ?>
							
						</div>
						
					</div>
					
					<?php } ?>
					
				</div>
				
			</footer>
			
			<footer id="footer-block" class="footer-<?= $footer_col_count; ?>-cols">
				
				<div class="s1">
					
					<?php if ( check_var( $this->mcm->loaded_modules[ 'footer-left' ] ) ){ ?>
					
					<div id="footer-module-left" class="footer-col">
						
						<div class="s1">
							
							<?php foreach ( $this->mcm->loaded_modules[ 'footer-left' ] as $key => $module ) { echo @$module; } ?>
							
						</div>
						
					</div><?php
					
					}
					
					if ( check_var( $this->mcm->loaded_modules[ 'footer-center' ] ) ){

					?><div id="footer-module-center" class="footer-col">

						<div class="s1">

							<?php foreach ( $this->mcm->loaded_modules[ 'footer-center' ] as $key => $module ) { echo @$module; } ?>

						</div>

					</div><?php

					}

					if ( check_var( $this->mcm->loaded_modules[ 'footer-right' ] ) ){

					?><div id="footer-module-right" class="footer-col">

						<div class="s1">

							<?php foreach ( $this->mcm->loaded_modules[ 'footer-right' ] as $key => $module ) { echo @$module; } ?>

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
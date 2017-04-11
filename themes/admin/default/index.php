<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?><!DOCTYPE html>
<html lang="en" class="vui <?= ( $current_component ) ? $current_component[ 'unique_name' ] :''; ?>" >
	<head>

		<?php

			$this->plugins->load( array( 'names' => array( 'jquery', 'modal_rf_file_picker' ), 'types' => array( 'js_tooltip', 'js_image_preloader', ) ) );
			
			$this->voutput->append_head_script( 'jquery.ba-outside-events', ADMIN_THEMES_URL . '/' . admin_theme() . '/assets/js/jquery.ba-outside-events.min.js' );
			$this->voutput->append_head_script( 'jquery.number', ADMIN_THEMES_URL . '/' . admin_theme() . '/assets/js/jquery.number.min.js' );
			$this->voutput->append_head_script( 'js_numeral', ADMIN_THEMES_URL . '/' . admin_theme() . '/assets/js/numeral-js/numeral.js' );
			$this->voutput->append_head_script( 'jquery.maskMoney', ADMIN_THEMES_URL . '/' . admin_theme() . '/assets/js/jquery.maskMoney.js' );
// 			$this->voutput->append_head_script( 'jquery.caret', ADMIN_THEMES_URL . '/' . admin_theme() . '/assets/js/jquery.caret.js' );
			$this->voutput->append_head_script( 'jquery.timer', ADMIN_THEMES_URL . '/' . admin_theme() . '/assets/js/jquery.timer.js' );
			$this->voutput->append_head_script( 'jquery.switch', ADMIN_THEMES_URL . '/' . admin_theme() . '/assets/js/jquery.switch.min.js' );
			$this->voutput->append_head_script( 'theme', ADMIN_THEMES_URL . '/' . admin_theme() . '/assets/js/functions.js' );
			
			$_get_url = array();
			
			if ( $this->voutput->get_head_stylesheet( 'jquery_ui_all' ) ) {
				
				$this->voutput->unset_head_stylesheet( 'jquery_ui_all' );
				
				$_get_url[] = 'juia=1';
				
			}
			
			//print_r( $this->voutput->get_buffer( 'jquery_ui' ) );
			
			$_get_url = '?' . join( '&', $_get_url );
			
			$this->voutput->append_head_stylesheet( 'theme', ADMIN_THEMES_URL . '/' . admin_theme() . '/assets/css/styles.php' . $_get_url );
			
			//$this->voutput->append_head_stylesheet( 'google_font', 'https://fonts.googleapis.com/css?family=Roboto:100italic,100,400,400italic,700,700italic|Oxygen:400,700,300&subset=latin,latin-ext' );
			/*
			$google_font_script = "
				
				WebFontConfig = {
					google: { families: [ 'Roboto:100italic,100,400,400italic,700,700italic:latin,latin-ext', 'Oxygen:400,700,300:latin,latin-ext' ] }
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
			
			$this->voutput->append_head_script_declaration( 'theme', $google_font_script );*/

			echo $this->voutput->get_head();

		?>

		<script type="text/javascript">

			function mainfunc (func){

				this[func].apply(this, Array.prototype.slice.call(arguments, 1));

			}

			function findCompaniesElements(){

				$('a[data-companyid]').on("click", function (e) {

					$.fancybox.showLoading();

					var jthis = $( this );

					e.preventDefault();

					$.ajax({

						type: "POST",
						cache: false,
						url: '<?= base_url(); ?>admin/companies/ajax/get_company_data/' + jthis.data( 'companyid' ),
						data: {

							company_id: jthis.data('companyid'),
							ajax: true

						},
						success: function ( data ) {

							$.fancybox.open();

							// on success, post (preview) returned data in fancybox
							$.fancybox( data, {

								fitToView: true,
								autoSize: true,
								closeClick: false,
								openEffect: 'none',
								closeEffect: 'none',
								helpers: {

								},
								wrapCSS: 'vui-modal',
								closeBtn: null

							}); // fancybox

						} // success

					}); // ajax

				}); // on

			}

			function createSwitchs(){

				var checkClass = function( ele ){

					if ( ele.find( 'option:selected' ).val() == 0 ){

						ele.addClass( 'switch-off' );
						ele.removeClass( 'switch-middle switch-on' );

					}
					else if ( ele.find( 'option:selected' ).val() == 1 ){

						ele.addClass( 'switch-on' );
						ele.removeClass( 'switch-middle switch-off' );

					}
					else{

						ele.addClass( 'switch-middle' );
						ele.removeClass( 'switch-off switch-on' );

					}

				}
				
				$( document ).on( 'mousedown', 'select.switch:not([multiple])', function( e ){
					
					e.preventDefault();
					
				});
				
				$( document ).on( 'click', 'select.switch:not([multiple])', function( e ){
					
					e.preventDefault();
					
					var ele = $( this );
					
					e.preventDefault();
					
					if ( ! ele.find( 'option:selected' ).length ){
						
						ele.find( 'option:first' ).attr( 'selected', 'selected' );
						ele.val( value ).change();
						
					}
					
					acOp = ele.find( 'option:selected' );
					
					if ( ele.find( 'option:last' ).is( ':selected' ) || ele.find( 'option:last' ).val() == acOp.val() ) {
						
						nextOp = $( this ).find( 'option' ).first();
						
					}
					else{
						
						nextOp = acOp.next();
						
					}
					
					acOp.removeAttr( 'selected' );
					
					nextOp.attr( 'selected', 'selected' );
					ele.val( nextOp.val() ).change();
					
					checkClass( $( this ) );
					
					e.preventDefault();
					
				});
				
				$( 'select:not([multiple])' ).each( function() {
					
					if ( ( $( this ).find( 'option' ).length == 2 || $( this ).find( 'option' ).length == 3 ) && $( this ).find( 'option[value=0]' ).length == 1 ){
						
						var ele = $( this );
						
						ele.addClass( 'switch' );
						
						checkClass( ele );
						
					}
					
				});
			}
			
			$( document ).bind( 'ready', function( event ){

				createSwitchs();

			});

		</script>

	</head>

	<body id="" class="vui <?= ( $current_component ) ? $current_component[ 'unique_name' ] :''; ?> <?= ( ! $this->users->is_logged_in() ) ? 'login' :''; ?> <?= check_var( $this->session->userdata[ 'user_data' ][ $this->mcm->environment ][ 'select_on' ] ) ? 'select-on' : ''; ?> <?= ( ( check_var( $this->session->userdata[ 'user_data' ][ $this->mcm->environment ][ 'profiler' ] ) === TRUE ) ? 'profiler-on' : 'profiler-off' ); ?> <?= ( isset( $toolbar ) AND trim( $toolbar ) !== '' ) ? 'with-toolbar' : ''; ?>">
		
		<?= $this->voutput->get_body_start(); ?>
		<!--
		<script>
			window.fbAsyncInit = function() {
			FB.init({
				appId		: '289346507923979',
				xfbml		: true,
				version	: 'v2.1'
			});
			};

			(function(d, s, id){
			 var js, fjs = d.getElementsByTagName(s)[0];
			 if (d.getElementById(id)) {return;}
			 js = d.createElement(s); js.id = id;
			 js.src = "//connect.facebook.net/en_US/sdk.js";
			 fjs.parentNode.insertBefore(js, fjs);
			 }(document, 'script', 'facebook-jssdk'));
		</script>
		-->
		<div id="site-background" class=""></div>
		
		<div id="fake-top-block"></div>
		
		<?php if ( check_var( $this->mcm->loaded_modules[ 'main_tools' ] ) ) { ?>
			
			<div id="main-tools-modules" class="">
				
				<?php if ( ! check_var( $this->session->userdata[ 'user_data' ][ $this->mcm->environment ][ 'select_on' ] ) ){ ?>
				<?php foreach ( $this->mcm->loaded_modules[ 'main_tools' ] as $key => $module ) { ?>
					
					<?= $module; ?>
					
				<?php } ?>
				<?php } ?>
				
			</div>
			
		<?php } ?>
		
		<?php if ( isset( $toolbar ) AND trim( $toolbar ) !== '' ) { ?>
		<div id="toolbar" class="<?= ( check_var( $this->mcm->loaded_modules[ 'main_tools' ] ) ? 'with-main-tools' : '' ) ?>">
			
			<div id="toolbar-main" class="toolbar-child" >
				
				<?= $toolbar; ?>
				
			</div>
			
		</div>
		<?php } ?>
		
		<div id="site-block" class="">
			
			<?php if ( $this->users->is_logged_in() ){ ?>
			<div id="top-block">
				
			</div>

			<?php } ?>
			
			<?= $msg; ?>
			
			<?php if ( trim( $this->voutput->get_content() ) !== '' ){ ?>

			<div id="content-block">

				<div class="s1">

					<div class="s2">

						<?= $this->voutput->get_content(); ?>

					</div>

				</div>

			</div>

			<?php } ?>
			<!--
			<footer id="footer">
				<p>
					&copy; Copyright by <a href="http://viaeletronica.com.br" target="_blank">Via Eletrônica</a>
				</p>

				<?php if ( check_var( $this->session->userdata[ 'user_data' ][ $this->mcm->environment ][ 'profiler' ] ) ) { ?>

				<?= 'environment = <b>' . environment(); ?></b><br/>
				<?= 'current_component = <b>'; var_dump( $current_component ); ?></b><br/>
				<?= 'component_function = <b>' . $component_function; ?></b><br/>
				<?= 'component_function_action = <b>' . $component_function_action; ?></b><br/>
				<?= 'last_url = <b>'.get_last_url(); ?></b>

				<p>
					
					<?= phpinfo(); ?>
					
				</p>

				<?php } ?>

			</footer>
			-->
		</div>

		<?= $this->voutput->get_body_end(); ?>

	</body>
</html>

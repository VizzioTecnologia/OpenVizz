<?php if ( ! defined( 'BASEPATH' ) ) exit( 'No direct script access allowed' );
	
	$layout_class_name = 'quadro_de_cursos';
	
	$this->voutput->append_head_stylesheet( 'users_submits_module_' . $layout_class_name, MODULES_VIEWS_STYLES_URL . '/users_submits/users_submits.css' );
	
	$unique_module_hash = md5( uniqid( rand(), true ) );
	
	$params = $module_data[ 'params' ];
	
?>

<section id="us-<?= $unique_module_hash; ?>" class="<?= ( ! count( $users_submits ) ? 'no-results ' : '' ); ?>module-wrapper users-submits-module users-submits-module-wrapper <?= $params[ 'module_class' ]; ?> <?= $layout_class_name; ?>">
	
	<?php if ( check_var( $params[ 'show_title' ] ) ) { ?>
	<header class="module-title">
		
		<h3>
			
			<?= $module_data[ 'title' ]; ?>
			
		</h3>
		
		<div class="nav-wrapper">
			
			<div class="nav nav-prev"></div>
			<div class="nav nav-next"></div>
			
		</div>
		
	</header><?php
	
	}
	
	?><div class="module-content users-submits-module-content layout-<?= url_title( $params[ 'users_submits_layout' ] ); ?>">
		
		<div class="users-submits-wrapper results">
			
			<?php if ( check_var( $users_submits ) ) { ?>
				
				<?php
				
				$_i = 1;
				
				foreach ( $users_submits as $key => $user_submit ) {
					
					$_us_wrapper_class = array();
					
					$us_fields = $_titles_fields = $_contents_fields = array();
					
					if ( ! check_var( $params[ 'fields_to_show' ] ) OR in_array( 'id', $params[ 'fields_to_show' ] ) ) {
						
						$us_fields[ 'id' ][ 'label' ] = lang( 'id' );
						$us_fields[ 'id' ][ 'value' ] = $user_submit[ 'id' ];
						
					}
					
					if ( ! check_var( $params[ 'fields_to_show' ] ) OR in_array( 'submit_datetime', $params[ 'fields_to_show' ] ) ) {
						
						$us_fields[ 'submit_datetime' ][ 'label' ] = lang( 'submit_datetime' );
						$us_fields[ 'submit_datetime' ][ 'value' ] = strftime( lang( 'sf_us_dt_ft_pt_ymd_1' ), strtotime( $user_submit[ 'submit_datetime' ] ) );
						
					}
					
					if ( ! check_var( $params[ 'fields_to_show' ] ) OR in_array( 'mod_datetime', $params[ 'fields_to_show' ] ) ) {
						
						$us_fields[ 'mod_datetime' ][ 'label' ] = lang( 'mod_datetime' );
						$us_fields[ 'mod_datetime' ][ 'value' ] = strftime( lang( 'sf_us_dt_ft_pt_ymd_1' ), strtotime( $user_submit[ 'mod_datetime' ] ) );
						
					}
					
					foreach ( $user_submit[ 'data' ] as $key_2 => $field_value ) {
						
						if ( $fields[ $key_2 ][ 'field_type' ] == 'date' ){
							
							$format = '';
							
							$format .= $fields[ $key_2 ][ 'sf_date_field_use_year' ] ? 'y' : '';
							$format .= $fields[ $key_2 ][ 'sf_date_field_use_month' ] ? 'm' : '';
							$format .= $fields[ $key_2 ][ 'sf_date_field_use_day' ] ? 'd' : '';
							
							$format = 'sf_us_dt_ft_pt_' . $format . '_' . $fields[ $key_2 ][ 'sf_date_field_presentation_format' ];
							
							$field_value =  strftime( lang( $format ), strtotime( $field_value ) );
							
						}
						else if ( in_array( $fields[ $key_2 ][ 'field_type' ], array( 'checkbox', 'combo_box', ) ) ){
							
							if ( get_json_array( $field_value ) ) {
								
								$field_value = json_decode( $field_value, TRUE );
								
							}
							
							$_field_value = array();
							
							if ( is_array( $field_value ) ) {
								
								foreach ( $field_value as $k => $value ) {
									
									if ( is_string( $value ) ) {
										
										if ( check_var( $fields[ $key_2 ][ 'options_from_users_submits' ] ) AND ( check_var( $fields[ $key_2 ][ 'options_title_field' ] ) OR check_var( $fields[ $key_2 ][ 'options_title_field_custom' ] ) ) AND is_numeric( $value ) AND $_user_submit = $this->sfcm->get_user_submit( $value ) ) {
											
											$value = isset( $_user_submit[ 'data' ][ $fields[ $key_2 ][ 'options_title_field' ] ] ) ? $_user_submit[ 'data' ][ $fields[ $key_2 ][ 'options_title_field' ] ] : $_user_submit[ 'id' ];
											
											$_field_value[] = $value;
											
										}
										else {
											
											$_field_value[] = $value;
											
										}
										
									}
									
								}
								
								$field_value = join( ', ', $_field_value );
								
							}
							else {
								
								if ( check_var( $fields[ $key_2 ][ 'options_from_users_submits' ] ) AND ( check_var( $fields[ $key_2 ][ 'options_title_field' ] ) OR check_var( $fields[ $key_2 ][ 'options_title_field_custom' ] ) ) AND is_numeric( $field_value ) AND $_user_submit = $this->sfcm->get_user_submit( $field_value ) ) {
									
									$field_value = isset( $_user_submit[ 'data' ][ $fields[ $key_2 ][ 'options_title_field' ] ] ) ? $_user_submit[ 'data' ][ $fields[ $key_2 ][ 'options_title_field' ] ] : $_user_submit[ 'id' ];
									
								}
								
							}
							
						}
						
						// do not use empty field values
						if ( $field_value ) {
							
							$us_fields[ $key_2 ][ 'label' ] = isset( $fields[ $key_2 ][ 'presentation_label' ] ) ? $fields[ $key_2 ][ 'presentation_label' ] : $fields[ $key_2 ][ 'label' ];
							$us_fields[ $key_2 ][ 'value' ] = $field_value;
							
							$_us_wrapper_class[] = $key_2 . '-' . url_title( $field_value, '-', TRUE );
							
						}
						
					}
					
					// defining user submit titles and contents
					foreach ( $us_fields as $key_2 => $us_field ) {
						
						if ( check_var( $params[ 'results_title_field' ] ) AND $key_2 == $params[ 'results_title_field' ] ) {
							
							$_titles_fields[ $key_2 ] = $us_field;
							
						}
						else {
							
							$_contents_fields[ $key_2 ] = $us_field;
							
						}
						
					}
					
					$_us_wrapper_class = join( ' ', $_us_wrapper_class );
					
				?>
					
					<div class="user-submit-wrapper <?= $_i == count( $users_submits ) ? 'user-submit-last last ' : ( $_i == 1 ? 'user-submit-first first ' : '' ); ?><?php foreach ( $user_submit[ 'data' ] as $key_2 => $_title_field ) { echo ' ' . $key_2 . '-' . $_title_field; } ?>">
						
						<?php
						
						$_i++;
						
						foreach ( $_titles_fields as $key_2 => $_title_field ) { ?>
							
							<?php if ( $_title_field AND isset( $fields[ $key_2 ] ) AND in_array( $key_2, $params[ 'fields_to_show' ] ) ) { ?>
								
								<div class="user-submit-title-wrapper user-submit-alias-<?= url_title( $key_2 ); ?> user-submit-title-<?= url_title( $_title_field[ 'label' ] ); ?>">
									
									<h4 class="title"><?= $_title_field[ 'value' ]; ?></h4>
									
								</div>
								
							<?php } ?>
							
						<?php } ?>
						
						<?php foreach ( $_contents_fields as $key_2 => $_content_field ) { ?>
							
							<?php if ( $_content_field AND ( ! check_var( $params[ 'fields_to_show' ] ) ) OR in_array( $key_2, $params[ 'fields_to_show' ] ) ) { ?>
								
								<div class="user-submit-field-wrapper user-submit-alias-<?= url_title( $key_2 ); ?> user-submit-content-<?= url_title( $_content_field[ 'label' ] ); ?>">
									
									<span class="title"><?= lang( $_content_field[ 'label' ] ); ?>: </span>
									<span class="value">
										
										<?= $_content_field[ 'value' ]; ?>
										
									</span>
									
								</div>
								
							<?php } ?>
							
						<?php } ?>
						
					</div>
					
				<?php } ?>
				
				<script type="text/javascript">
					
					<?php
						
						$js_module_id_selector = '#us-' . $unique_module_hash;
						
					?>
					
					$( document ).bind( 'ready', function() {
						
						$( '<?= $js_module_id_selector; ?> .module-content' ).after( '<div class="preload-wrapper"><div class="preload on"></div></div>' );
						
					})
					
					$( window ).load( function() {
						
						$( '<?= $js_module_id_selector; ?> .preload-wrapper' ).remove();
						
						var len = $( '.user-submit-wrapper' ).length;
						
						$( '<?= $js_module_id_selector; ?> .user-submit-wrapper' ).each( function( index, element ) {
							
							$( this ).addClass( 'next' );
							
							if ( index == 0 ) {
								
								$( this ).addClass( 'user-submit-first' );
								$( this ).removeClass( 'prev' );
								$( this ).removeClass( 'next' );
								$( this ).addClass( 'current' );
								
							}
							if ( index == len - 1 ) {
								
								$( this ).addClass( 'user-submit-last' );
								$( this ).removeClass( 'next' );
								$( this ).addClass( 'prev' );
								
							}
							
						});
						
						$( '<?= $js_module_id_selector; ?>.users-submits-module .nav-prev' ).on( 'click', function( e ) {
							
							var usWrapper = $( '<?= $js_module_id_selector; ?> .users-submits-module-content' );
							
							if ( usWrapper.find( '.current' ).hasClass( 'user-submit-first' ) ) {
								
								usWrapper.find( '.user-submit-wrapper' ).removeClass( 'next' );
								usWrapper.find( '.user-submit-wrapper' ).addClass( 'prev' );
								usWrapper.find( '.current' ).removeClass( 'current' );
								usWrapper.find( '.user-submit-wrapper' ).last().removeClass( 'prev' );
								usWrapper.find( '.user-submit-wrapper' ).last().addClass( 'current' );
								
							}
							else {
								
								usWrapper.find( '.current' ).removeClass( 'next' );
								usWrapper.find( '.current' ).addClass( 'next' );
								usWrapper.find( '.current' ).prev().removeClass( 'prev' );
								usWrapper.find( '.current' ).prev().prev().addClass( 'prev' );
								usWrapper.find( '.current' ).removeClass( 'current' ).prev().addClass( 'current' );
								
							}
							
						});
						
						$( '<?= $js_module_id_selector; ?>.users-submits-module .nav-next' ).on( 'click', function( e ) {
							
							var usWrapper = $( '<?= $js_module_id_selector; ?> .users-submits-module-content' );
							
							if ( usWrapper.find( '.current' ).hasClass( 'user-submit-last' ) ) {
								
								usWrapper.find( '.user-submit-wrapper' ).removeClass( 'prev' );
								usWrapper.find( '.user-submit-wrapper' ).addClass( 'next' );
								usWrapper.find( '.current' ).removeClass( 'current' );
								usWrapper.find( '.user-submit-wrapper' ).first().addClass( 'current' );
								usWrapper.find( '.user-submit-wrapper' ).first().removeClass( 'next' );
								
							}
							else {
								
								usWrapper.find( '.current' ).removeClass( 'next' );
								usWrapper.find( '.current' ).addClass( 'prev' );
								usWrapper.find( '.current' ).next().removeClass( 'next' );
								usWrapper.find( '.current' ).next().next().addClass( 'next' );
								usWrapper.find( '.current' ).removeClass( 'current' ).next().addClass( 'current' ).removeClass( 'prev' );
								
							}
							
						});
						
					});
					
				</script>
				
			<?php } else { ?>
				
				<?php if ( check_var( $params[ 'us_module_no_results_message' ] ) ) { ?>
					
					<div class="users-submits-description-no-search-results no-results">
						
						<?= $params[ 'us_module_no_results_message' ]; ?>
						
					</div>
					
				<?php } else { ?>
					
					<h4 class="title"><?= lang( 'users_submits_description_no_search_results' ); ?></h4>
					
				<?php } ?>
				
			<?php } ?>
			
		</div>
		
		<div class="clear">&nbsp;</div>
		
	</div>
	
	<?php if ( check_var( $params[ 'show_readmore_link' ] ) AND check_var( $params[ 'readmore_text' ] ) AND
		( ( ! check_var( $users_submits ) AND ! check_var( $params[ 'hide_on_no_results' ] ) ) OR check_var( $users_submits ) ) ) { ?>
		
		<div class="read-more user-submit-readmore-link-wrapper user-submit-readmore-link">
			
			<div class="s1 inner">
				
				<div class="s2 inner">
					
					<a class="read-more-link user-submit-read-more-link" <?= ( check_var( $params[ 'readmore_link_target' ] ) ? 'target="' . $params[ 'readmore_link_target' ] . '"' : '' ); ?> href="<?= get_url( ( check_var( $params[ 'readmore_url' ] ) ? $params[ 'readmore_url' ] : '' ) ); ?>" title="<?= lang( $params[ 'readmore_text' ] ); ?>" ><?= lang( $params[ 'readmore_text' ] ); ?></a>
					
				</div>
				
			</div>
			
		</div>
		
	<?php } ?>
	
</section>

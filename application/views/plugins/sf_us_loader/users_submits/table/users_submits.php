<?php if ( ! defined( 'BASEPATH' ) ) exit( 'No direct script access allowed' );

$unique_hash = md5( uniqid( rand(), true ) );
$users_submits = $submit_form[ 'users_submits' ];
$users_submits_total_results = count( $users_submits );
$fields = $submit_form[ 'fields' ];

$params = $submit_form[ 'plugin_params' ];

$_path = VIEWS_PATH . SITE_COMPONENTS_VIEWS_PATH . 'submit_forms' . DS . 'index' . DS . 'users_submits' . DS . 'table' . DS;

$columns = array(
	
	array(
		
		'alias' => 'id',
		'title' => check_var( $submit_form[ 'params' ][ 'ud_ds_default_data_id_pres_title' ] ) ? $submit_form[ 'params' ][ 'ud_ds_default_data_id_pres_title' ] : lang( 'id' ),
		'visible' => check_var( $submit_form[ 'params' ][ 'ud_ds_default_data_id_site_list_show' ] ) ? TRUE : FALSE,
		'type' => 'built_in',
		
	),
	array(
		
		'alias' => 'submit_datetime',
		'title' => check_var( $submit_form[ 'params' ][ 'ud_ds_default_data_sdt_pres_title' ] ) ? $submit_form[ 'params' ][ 'ud_ds_default_data_sdt_pres_title' ] : lang( 'submit_datetime' ),
		'visible' => check_var( $submit_form[ 'params' ][ 'ud_ds_default_data_sdt_site_list_show' ] ) ? TRUE : FALSE,
		'type' => 'built_in',
		
	),
	array(
		
		'alias' => 'mod_datetime',
		'title' => check_var( $submit_form[ 'params' ][ 'ud_ds_default_data_mdt_pres_title' ] ) ? $submit_form[ 'params' ][ 'ud_ds_default_data_mdt_pres_title' ] : lang( 'mod_datetime' ),
		'visible' => check_var( $submit_form[ 'params' ][ 'ud_ds_default_data_mdt_site_list_show' ] ) ? TRUE : FALSE,
		'type' => 'built_in',
		
	),
	
);

foreach( $submit_form[ 'fields' ] as $key => $field ){
	
	if ( ! in_array( $field[ 'field_type' ], array( 'html', 'button' ) ) ){
		
		$new_column = & $columns[];
		
		$new_column[ 'alias' ] = $field[ 'alias' ];
		$new_column[ 'title' ] = ( isset( $field[ 'presentation_label' ] ) AND $field[ 'presentation_label' ] ) ? $field[ 'presentation_label' ] : $field[ 'label' ];
		$new_column[ 'visible' ] = check_var( $field[ 'visibility' ][ 'site' ][ 'list' ] );
		$new_column[ 'type' ] = $field[ 'field_type' ];
		
	}
	
}

$fields_to_show = NULL;

foreach( $columns as $k => $column ) {
	
	if ( $column[ 'visible' ] ) {
		
		$fields_to_show[] = $column[ 'alias' ];
		
	}
	
}

?>






<section id="users-submits-<?= $unique_hash; ?>" class="ud-data-schema ud-data-schema-item users-submits <?= $params['wc']; ?> item item-<?= $__index; ?>">
	
	<?php if ( $params['st'] ) { ?>
	<header class="heading">
		
		<h3>
			
			<?php if ( check_var( $submit_form[ 'data_list_site_link' ] ) ) { ?>
				
				<a href="<?= $submit_form[ 'data_list_site_link' ]; ?>">
				
			<?php } ?>
			
			<?php if ( check_var( $params[ 'csft' ] ) AND trim( $params[ 'csft' ] ) != '' ) { ?>
				
				<?= $params[ 'csft' ]; ?>
				
			<?php } else { ?>
				
				<?= $submit_form[ 'title' ]; ?>
				
			<?php } ?>
			
			<?php if ( check_var( $submit_form[ 'data_list_site_link' ] ) ) { ?>
				
				</a>
				
			<?php } ?>
			
		</h3>
		
	</header>
	<?php } ?>
	
	<div class="users-submits-wrapper results">
		
		<div class="s1">
			
			<table class="data-list responsive multi-selection-table">
				
				<tr>
					
					<?php if ( ! check_var( $submit_form_id ) AND ! check_var( $columns ) ) { ?>
						
						<?php $current_column = 'id'; ?>
						
						<th class="col-<?= $current_column; ?>">
							
							<?= lang( $current_column ); ?>
							
						</th>
						
						<?php $current_column = 'submit_datetime'; ?>
						
						<th class="col-<?= $current_column; ?>">
							
							<?= lang( $current_column ); ?>
							
						</th>
						
						<?php $current_column = 'submit_form_title'; ?>
						
						<th class="col-<?= $current_column; ?>">
							
							<?= lang( $current_column ); ?>
							
						</th>
						
						<?php $current_column = 'output'; ?>
						
						<th class="col-<?= $current_column; ?>">
							
							<?= lang( $current_column ); ?>
							
						</th>
						
					<?php } else { ?>
						
						<?php foreach ( $columns as $key => $column ) { ?>
							
							<?php if ( $column[ 'visible' ] ) { ?>
								
								<?php $current_column = $column[ 'alias' ]; ?>
								
								<th class="col-<?= $current_column; ?>">
								
									<?= lang( $column[ 'title' ] ); ?>
									
								</th>
								
							<?php } ?>
							
						<?php } ?>
						
					<?php } ?>
					
				</tr>
				
				<?php foreach( $users_submits as & $ud_data ) {
					
					$ud_data = $this->sfcm->parse_ud_d_data( $ud_data, $fields_to_show, $submit_form );
					
					$_us_status = array();
					$_us_status_classes = '';
					$_us_has_status = FALSE;
					
					if ( check_var( $submit_form[ 'ud_status_props' ] ) ) {
						
						foreach( $submit_form[ 'fields' ] as $status_field ) {
							
							if ( isset( $submit_form[ 'ud_status_props' ][ $status_field[ 'alias' ] ] ) ) {
								
								if ( check_var( $status_field[ 'options_from_users_submits' ] )
								AND ( check_var( $status_field[ 'options_title_field' ] )
								OR check_var( $status_field[ 'options_title_field_custom' ] ) ) ) {
									
									$_current_field_array = array(
										
										'prop_is_ud_status_active',
										'prop_is_ud_status_inactive',
										'prop_is_ud_status_enabled',
										'prop_is_ud_status_disabled',
										'prop_is_ud_status_canceled',
										'prop_is_ud_status_postponed',
										'prop_is_ud_status_archived',
										'prop_is_ud_status_published',
										'prop_is_ud_status_unpublished',
										'prop_is_ud_status_scheduled',
										
									);
									
									foreach( $_current_field_array as $_item ) {
										
										if ( check_var( $status_field[ 'advanced_options' ][ $_item ] ) AND $ud_data[ 'data' ][ $status_field[ 'alias' ] ] == $status_field[ 'advanced_options' ][ $_item ] ) {
											
											if ( $_item == 'prop_is_ud_status_active' ) {
												
												$_us_status[ 'active' ] = 'status-active';
												
											}
											else if ( $_item == 'prop_is_ud_status_inactive' ) {
												
												$_us_status[ 'inactive' ] = 'status-inactive';
												
											}
											else if ( $_item == 'prop_is_ud_status_enabled' ) {
												
												$_us_status[ 'enabled' ] = 'status-enabled';
												
											}
											else if ( $_item == 'prop_is_ud_status_disabled' ) {
												
												$_us_status[ 'disabled' ] = 'status-disabled';
												
											}
											else if ( $_item == 'prop_is_ud_status_canceled' ) {
												
												$_us_status[ 'disabled' ] = 'status-disabled';
												
											}
											else if ( $_item == 'prop_is_ud_status_postponed' ) {
												
												$_us_status[ 'postponed' ] = 'status-postponed';
												
											}
											else if ( $_item == 'prop_is_ud_status_archived' ) {
												
												$_us_status[ 'archived' ] = 'status-archived';
												
											}
											else if ( $_item == 'prop_is_ud_status_published' ) {
												
												$_us_status[ 'published' ] = 'status-published';
												
											}
											else if ( $_item == 'prop_is_ud_status_unpublished' ) {
												
												$_us_status[ 'unpublished' ] = 'status-unpublished';
												
											}
											else if ( $_item == 'prop_is_ud_status_scheduled' ) {
												
												$_us_status[ 'scheduled' ] = 'status-scheduled';
												
											}
											
											$_us_has_status = TRUE;
											
										}
										
									};
									
								}
								
							}
							
						}
						
					}
					
					if ( $_us_has_status ) {
						
						$_us_status_classes = join( $_us_status, ' ' );
						
					}
					
				?>
					
					<tr class="<?= $_us_has_status ? $_us_status_classes : ''; ?>">
						
						<?php foreach ( $columns as $key => $column ) {
							
							if ( $column[ 'visible' ] ) {
								
								$_prop_is_ud_status = FALSE;
								
								$advanced_options = check_var( $fields[ $column[ 'alias' ] ][ 'advanced_options' ] ) ? $fields[ $column[ 'alias' ] ][ 'advanced_options' ] : FALSE;
								
								?>
								
								<td
									
									class="
										
										col-<?= $column[ 'alias' ]; ?>
										col-<?= $column[ 'visible' ] ? 'visible' : 'hidden'; ?>
										<?= check_var( $advanced_options[ 'prop_is_ud_image' ] ) ? ' field-is-image' : ''; ?>
										<?= check_var( $advanced_options[ 'prop_is_ud_title' ] ) ? ' field-is-presentation-title' : ''; ?>
										<?= check_var( $advanced_options[ 'prop_is_ud_content' ] ) ? ' field-is-presentation-content' : ''; ?>
										<?= check_var( $advanced_options[ 'prop_is_ud_other_info' ] ) ? ' field-is-presentation-other-info' : ''; ?>
										<?= check_var( $advanced_options[ 'prop_is_ud_email' ] ) ? ' field-is-email' : ''; ?>
										<?= check_var( $advanced_options[ 'prop_is_ud_url' ] ) ? ' field-is-url' : ''; ?>
										<?= check_var( $advanced_options[ 'prop_is_ud_status' ] ) ? ' field-is-status' : ''; ?>
										sf-field-type-<?= check_var( $fields[ $column[ 'alias' ] ][ 'field_type' ] ) ? $fields[ $column[ 'alias' ] ][ 'field_type' ] : 'default'; ?>-wrapper
										
									"
									
								>
									
									<?php foreach( $ud_data[ 'parsed_data' ][ 'full' ] as $alias => $pd ) { ?>
										
										<?php if ( $column[ 'alias' ] == $alias ) { ?>
											
											<?php
												
												if ( $alias == 'submit_datetime' OR $alias == 'mod_datetime' ) {
													
													$pd[ 'value' ] = strtotime( $pd[ 'value' ] );
													
													switch ( $alias ) {
														
														case 'submit_datetime':
															
															$_mask = check_var( $submit_form[ 'params' ][ 'ud_ds_default_data_sdt_dt_format' ] ) ? $submit_form[ 'params' ][ 'ud_ds_default_data_sdt_dt_format' ] : lang( 'ud_data_datetime' );
															
															break;
															
														case 'mod_datetime':
															$_mask = check_var( $submit_form[ 'params' ][ 'ud_ds_default_data_mdt_dt_format' ] ) ? $submit_form[ 'params' ][ 'ud_ds_default_data_mdt_dt_format' ] : lang( 'ud_data_datetime' );
															
															break;
															
													}
													
													
													
													$pd[ 'value' ] = strftime( $_mask, $pd[ 'value' ] );
													
												}
												
												if ( check_var( $advanced_options[ 'prop_is_ud_image' ] ) AND check_var( $pd[ 'value' ] ) ) {
													
													$thumb_params = array(
														
														'wrapper_class' => 'us-image-wrapper',
														'src' => url_is_absolute( $pd[ 'value' ] ) ? $pd[ 'value' ] : get_url( 'thumbs/' . $pd[ 'value' ] ),
														'href' => get_url( $pd[ 'value' ] ),
														'rel' => 'us-thumb',
														'title' => $pd[ 'value' ],
														'modal' => TRUE,
														'prevent_cache' => check_var( $advanced_options[ 'prop_is_ud_image_thumb_prevent_cache_admin' ] ) ? TRUE : FALSE,
														
													);
													
													echo vui_el_thumb( $thumb_params );
													
												}
												else if ( check_var( $advanced_options[ 'prop_is_ud_url' ] ) AND check_var( $pd[ 'value' ] ) ) {
													
													echo '<a target="_blank" href="' . get_url( $pd[ 'value' ] ) . '">' . $pd[ 'value' ] . '</a>';
													
												}
												else if ( check_var( $advanced_options[ 'prop_is_ud_title' ] ) AND check_var( $pd[ 'value' ] ) ) {
													
													echo '<a href="' . $ud_data[ 'edit_link' ] . '">' . $pd[ 'value' ] . '</a>';
													
												}
												else if ( check_var( $advanced_options[ 'prop_is_ud_email' ] ) AND check_var( $pd[ 'value' ] ) ) {
													
													echo '<a href="mailto:' . $pd[ 'value' ] . '">' . $pd[ 'value' ] . '</a>';
													
												}
												else if ( isset( $pd[ 'value' ] ) ) {
													
													if ( $fields[ $alias ][ 'field_type' ] == 'textarea' AND ! is_array( $ud_data[ 'data' ][ $alias ] ) ) {
														
														echo word_limiter( htmlspecialchars_decode( $pd[ 'value' ] ) );
														
													}
													else {
														
														echo word_limiter( $pd[ 'value' ] );
														
													}
													
												}
												
											?>
											
										<?php } ?>
										
									<?php } ?>
									
								</td>
								
							<?php } ?>
							
						<?php } ?>
						
					</tr>
					
				<?php
				
					$ud_data = NULL;
					unset( $user_submit );
					
				} ?>
				
			</table>
			
			<?php if ( check_var( $users_submits ) ) { ?>
			
				<div class="clear"></div>
				
				<?php
					
					/* ---------------------------------------------------------------------------
					* ---------------------------------------------------------------------------
					* Read more
					* ---------------------------------------------------------------------------
					*/
					
					if ( check_var( $params[ 'ud_data_list_ds_readmore_link' ] ) AND file_exists( $_path . 'readmore.php' ) ) {
						
						require( $_path . 'readmore.php' );
						
					}
					
				?>
			
			<?php } ?>
			
		</div>
		
		<div class="clear"></div>
		
	</div>
	
</section>

<?php if ( ! defined( 'BASEPATH' ) ) exit( 'No direct script access allowed' );

//echo print_r( $fields, true ) . '<br/><br/><br/>-----------<br/><br/><br/>';

$filter_fields_input_name = 'users_submits_search[dinamic_filter_fields]';

$pre_text_pos = 'before_search_fields';

if ( check_var( $submit_form[ 'params' ][ 'us_pre_text_position' ] ) ) {
	
	$pre_text_pos = $submit_form[ 'params' ][ 'us_pre_text_position' ];
	
}

?>

<section class="submit-form users-submits <?= @$params['page_class']; ?> cursos-externos">
	
	<?php if ( @$params['show_page_content_title'] ) { ?>
	<header class="component-heading">
		
		<h1>
			
			<?= $this->mcm->html_data['content']['title']; ?>
			
		</h1>
		
	</header>
	<?php } ?>
	
	<div id="component-content">
		
		<?php if ( check_var( $params[ 'use_search' ] ) ) { ?>
		<div id="users-submits-search-wrapper" class="users-submits-search-wrapper">
			
			<?php
				
				if ( check_var( $submit_form[ 'params' ][ 'us_search_pre_text' ] ) AND $pre_text_pos == 'before_search_fields' ) {
					
					if ( check_var( $search_mode ) AND check_var( $submit_form[ 'params' ][ 'us_show_search_pre_text_on_search' ] ) ) {
						
						echo $submit_form[ 'params' ][ 'us_search_pre_text' ];
						
					}
					else if ( ! check_var( $search_mode ) AND check_var( $submit_form[ 'params' ][ 'us_show_search_pre_text_on_normal' ] ) ) {
						
						echo $submit_form[ 'params' ][ 'us_search_pre_text' ];
						
					}
					
				}
				
			?>
			
			<?= form_open_multipart( get_url( $this->uri->ruri_string() ) . '#users-submits-search-results-wrapper', array( 'id' => 'contact-form', ) );
				
				$combo_box_fields_to_search = array();
				
				?>
				
				<div class="submit-form-field-wrapper submit-form-field-wrapper-terms submit-form-field-wrapper-terms ">
					
					<?php
						
						if ( check_var( $params[ 'search_terms_string' ] ) ) {
							
							echo form_label( lang( $params[ 'search_terms_string' ] ) );
							
						}
						else {
							
							echo form_label( lang( 'search_terms' ) );
							
						}
						
					?>
					
					<div class="submit-form-field-control">
						
						<?= form_input( array( 'id' => 'submit-form-terms', 'name' => 'users_submits_search[terms]', 'class' => 'form-element submit-form submit-form-terms' ), $terms ); ?>
						
					</div>
					
				</div><?php
					
					foreach ( $fields as $key_2 => $field ) {
					
					$field_name = url_title( $field[ 'label' ], '-', TRUE );
					$formatted_field_name = 'form[' . $field_name . ']';
					$field_value = ( isset( $post[ 'users_submits_search' ][ 'dinamic_filter_fields' ][ $field_name ] ) ) ? $post[ 'users_submits_search' ][ 'dinamic_filter_fields' ][ $field_name ] : '';
					
					if ( $field[ 'field_type' ] == 'combo_box' ) {
						
						$options = array(
							
							'' => lang( 'combobox_select' ),
							
						);
							
						$combo_box_fields_to_search[ $field[ 'alias' ] ] = $field;
						
						if ( check_var( $field[ 'options_from_users_submits' ] ) AND ( check_var( $field[ 'options_title_field' ] ) OR check_var( $field[ 'options_title_field_custom' ] ) ) ) {
							
							$search_config = array(
								
								'plugins' => 'sf_us_search',
								'allow_empty_terms' => TRUE,
								'ipp' => 0,
								'cp' => NULL,
								'plugins_params' => array(
									
									'sf_us_search' => array(
										
										'sf_id' => $field[ 'options_from_users_submits' ],
										'order_by' => 'id',
										'filters' => NULL,
										
									),
									
								),
								
							);
							
							$this->load->library( 'search' );
							$this->search->config( $search_config );
							
							$_users_submits = $this->search->get_full_results( 'sf_us_search', TRUE );
							
							foreach( $_users_submits as & $_user_submit ) {
								
								$_user_submit[ 'data' ] = get_params( $_user_submit[ 'data' ] );
								
								if ( $field[ 'options_title_field' ] )
								
								foreach( $_user_submit[ 'data' ] as $_dk => $_data ) {
									
									if ( $_dk == $field[ 'options_title_field' ] )
									
									$options[ $_user_submit[ 'id' ] ] = $_data;
									
								};
								
							};
							
						}
						else {
							
							$options_temp = explode( "\n" , $field[ 'options' ] );
							
							foreach( $options_temp as $option ) {
								
								$options[ $option ] = $option;
								
							};
							
						}
						
					?><div class="submit-form-field-wrapper submit-form-field-wrapper-<?= $field[ 'alias' ]; ?> submit-form-field-wrapper-<?= $field[ 'field_type' ]; ?> ">
						
						<?= form_label( lang( $field[ 'label' ] ) ); ?>
						
						<div class="submit-form-field-control">
							
							<?= form_dropdown( $filter_fields_input_name . '[' . $field[ 'alias' ] . ']', $options, $field_value, 'id="submit-form-' . $field[ 'alias' ] . '"' . ' class="form-element submit-form submit-form-' . $field[ 'alias' ] . '"' ); ?>
							
						</div>
						
					</div><?php
					
					}
					
				} ?>
				
				<div class="submit-form-field-wrapper submit-form-field-wrapper-submit_search submit-form-field-wrapper-button">
					
					<div class="submit-form-field-control">
						
						<?= form_submit( array( 'id' => 'submit-form-submit_search', 'class' => 'button form-element submit-form submit-form-submit_search', 'name' => 'users_submits_search[submit_search]' ), lang( 'submit_search' ) ); ?>
						
					</div>
					
				</div>
				
			<?= form_close(); ?>
			
			<?php
				
				if ( check_var( $submit_form[ 'params' ][ 'us_search_pre_text' ] ) AND $pre_text_pos == 'after_search_fields' ) {
					
					if ( check_var( $search_mode ) AND check_var( $submit_form[ 'params' ][ 'us_show_search_pre_text_on_search' ] ) ) {
						
						echo $submit_form[ 'params' ][ 'us_search_pre_text' ];
						
					}
					else if ( ! check_var( $search_mode ) AND check_var( $submit_form[ 'params' ][ 'us_show_search_pre_text_on_normal' ] ) ) {
						
						echo $submit_form[ 'params' ][ 'us_search_pre_text' ];
						
					}
					
				}
				
				$combo_box_fields_to_search = array();
				
			?>
			
		</div><?php
		}
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
		
		/* ---------------------------------------------------------------------------
		* ---------------------------------------------------------------------------
		* Users submits
		* ---------------------------------------------------------------------------
		*/
		
		?>
		
		<?php if ( $pagination ){ ?>
		<div class="pagination">
			<?= $pagination; ?>
		</div>
		<?php } ?>
		
		<div id="users-submits-search-results-wrapper" class="users-submits-wrapper results">
			
			<?php if ( check_var( $users_submits ) ) { ?>
				
				<div class="users-submits-search-results-title-wrapper">
					
					<h3 class="users-submits-search-results-title">
						
						<?php
							
							if ( $users_submits_total_results > 1 ) {
								
								if ( check_var( $params[ 'users_submits_search_results_string' ] ) ) {
									
									echo sprintf( $params[ 'users_submits_search_results_string' ], '<span class="users-submits-search-count">' . $users_submits_total_results . '</span>' );
									
								}
								else {
									
									echo sprintf( lang( 'users_submits_search_results_string' ), '<span class="users-submits-search-count">' . $users_submits_total_results . '</span>' );
									
								}
								
							}
							else {
								
								if ( check_var( $params[ 'users_submits_search_single_result_string' ] ) ) {
									
									echo sprintf( $params[ 'users_submits_search_single_result_string' ], '<span class="users-submits-search-count">' . $users_submits_total_results . '</span>' );
									
								}
								else {
									
									echo sprintf( lang( 'users_submits_search_single_result_string' ), '<span class="users-submits-search-count">' . $users_submits_total_results . '</span>' );
									
								}
								
							}
							
						?>
						
					</h3>
					
				</div>
				
				<?php
				
				foreach ( $users_submits as $key => $user_submit ) {
					
					$_us_wrapper_class = array();
					
					$_tipo_de_centro = 'default';
					
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
					
					$data_curso = '';
					$imagem = '';
					
					foreach ( $user_submit[ 'data' ] as $key_2 => $field_value ) {
						
						if ( $key_2 == 'capa' ) {
							
							$imagem = $field_value;
							
						}
						
						if ( $key_2 == 'data-do-curso' ) {
							
							$data_curso = $field_value;
							
						}
						
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
					
					
					
					?><div class="user-submit-wrapper <?= $_us_wrapper_class ?>" >
						
						<?php foreach ( $_titles_fields as $key_2 => $_title_field ) { ?>
							
							<?php if ( $_title_field AND isset( $fields[ $key_2 ] ) AND in_array( $key_2, $params[ 'fields_to_show' ] ) ) { ?>
								
								<div class="user-submit-title-wrapper user-submit-alias-<?= url_title( $key_2 ); ?> user-submit-title-<?= url_title( $_title_field[ 'label' ] ); ?>">
									
									<div class="tipo-centro tipo-centro-<?= $_tipo_de_centro; ?>" title="<?= ( $_tipo_de_centro == 'centro-filiado' ? 'Centro Filiado' : ( $_tipo_de_centro == 'centro-agregado' ? 'Centro Agregado' : ( $_tipo_de_centro == 'nao-renovado' ? 'Centro não Filiado' : '' ) ) ); ?>"></div>
									
									<h4 class="title"><?= $_title_field[ 'value' ]; ?></h4>
									
								</div>
								
							<?php } ?>
							
						<?php } ?>
						
						<?php
							
							if ( $imagem ) {
								
								$thumb_params = array(
									
									'wrapper_class' => 'article-image-wrapper',
									'src' => $imagem,
									'href' => $imagem,
									'rel' => FALSE,
									
								);
								
								echo vui_el_thumb( $thumb_params );
								
							}
							
						?>
						
						<?php if ( $data_curso ) { ?>
							
							<div class="user-submit-title-wrapper user-submit-alias-data-do-curso user-submit-title-data-do-curso; ?>">
								
								<h6 class="title"><?= $data_curso; ?></h6>
								
							</div>
							
						<?php } ?>
						
						<?php foreach ( $_contents_fields as $key_2 => $_content_field ) { ?>
							
							<?php if ( $_content_field AND ( ! check_var( $params[ 'fields_to_show' ] ) ) OR in_array( $key_2, $params[ 'fields_to_show' ] ) ) { ?>
								
								<div class="user-submit-field-wrapper user-submit-alias-<?= url_title( $key_2 ); ?> user-submit-content-<?= url_title( $_content_field[ 'label' ] ); ?>">
									
									<?php if ( ! in_array( $key_2, array( 'capa', 'data-do-curso', ) ) ) { ?>
										
										<span class="title"><?= lang( $_content_field[ 'label' ] ); ?>: </span>
										<span class="value">
											
											<?php if ( $key_2 == 'site' ) { ?>
												
												<a target="_blank" href="<?= 'http://' . $_content_field[ 'value' ]; ?>"><?= $_content_field[ 'value' ]; ?></a>
												
											<?php } else if ( $key_2 == 'e-mail' ) { ?>
												
												<a href="<?= 'mailto:' . $_content_field[ 'value' ]; ?>"><?= $_content_field[ 'value' ]; ?></a>
												
											<?php } else { ?>
												
												<?= $_content_field[ 'value' ]; ?>
												
											<?php } ?>
											
										</span>
										
									<?php } ?>
									
								</div>
								
							<?php } ?>
							
						<?php } ?>
						
					</div><?php
					
				}
				
			} else { ?>
				
				<?php if ( $this->input->post( NULL, TRUE ) OR ! check_var( $users_submits ) ) { ?>
					
					<h4 class="title"><?= lang( 'no_results' ); ?></h4>
					
				<?php } else { ?>
					
					<div class="users-submits-description-no-search-results">
						
						<?= lang( 'users_submits_description_no_search_results' ); ?>
						
					</div>
					
				<?php } ?>
				
			<?php } ?>
			
		</div>
		
		<?php if ( $pagination ){ ?>
		<div class="pagination">
			<?= $pagination; ?>
		</div>
		<?php } ?>
		
		<div class="clear">&nbsp;</div>
		
	</div>

</section>



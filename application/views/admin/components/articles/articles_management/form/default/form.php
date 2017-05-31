<?php
	
	$this->plugins->load( array( 'names' => array( 'image_cropper', 'fancybox', 'modal_rf_file_picker', 'yetii' ), 'types' => array( 'js_text_editor', 'js_time_picker', ) ) );
	
	$created_date_time = ( check_var( $article[ 'created_date' ] ) ) ? strtotime( $article[ 'created_date' ] ) : gmt_to_local( now(), $this->mcm->filtered_system_params[ 'time_zone' ], $this->mcm->filtered_system_params[ 'dst' ] );
	$created_date = $this->input->post( 'created_date' ) ? $this->input->post( 'created_date' ) : ov_strftime( '%Y-%m-%d', $created_date_time );
	$created_time = $this->input->post( 'created_time' ) ? $this->input->post( 'created_time' ) : ov_strftime( '%T', $created_date_time );
	
	$modified_date_time = ( check_var( $article[ 'modified_date' ] ) ) ? strtotime( $article[ 'modified_date' ] ) : gmt_to_local( now(), $this->mcm->filtered_system_params[ 'time_zone' ], $this->mcm->filtered_system_params[ 'dst' ] );
	$modified_date = ov_strftime( '%Y-%m-%d', $modified_date_time );
	$modified_time = ov_strftime( '%T', $modified_date_time );
	
?>



<div id="article-form-wrapper" class="form-wrapper tabs-wrapper">
	
	<div class="form-wrapper-sub tabs-children">
		
		<?= form_open( get_url( 'admin' . $this->uri->ruri_string() ), array( 'id' => 'article-form', 'class' => ( ( $f_action == 'e' ) ? 'ajax' : '' ) ) ); ?>
			
			<div class="form-actions to-toolbar to-main-toolbar">
				
				<?= vui_el_button( array( 'text' => lang( 'action_save' ), 'icon' => 'save', 'button_type' => 'button', 'type' => 'submit', 'name' => 'submit', 'id' => 'submit', 'only_icon' => TRUE, 'form' => 'article-form', ) ); ?>
				
				<?= vui_el_button( array( 'text' => lang( 'action_apply' ), 'icon' => 'apply', 'button_type' => 'button', 'type' => 'submit', 'name' => 'submit_apply', 'id' => 'submit-apply', 'only_icon' => TRUE, 'form' => 'article-form', ) ); ?>
				
				<?= vui_el_button( array( 'text' => lang( 'action_cancel' ), 'icon' => 'cancel', 'button_type' => 'button', 'type' => 'submit', 'name' => 'submit_cancel', 'id' => 'submit-cancel', 'only_icon' => TRUE, 'form' => 'article-form', ) ); ?>
				
			</div>
			
			<header class="form-header tabs-header">
				
				<?php if ( $f_action == 'a' ) { ?>
				
				<h1><?= lang( 'new_article' ); ?></h1>
				
				<?php } else if ( $f_action == 'e' ) { ?>
				
				<h1><?= lang( 'edit_article' ); ?></h1>
				
				<?php } ?>
				
			</header>
			
			<div class="form-items tabs-items">
				
				<div class="form-item">
					
					<fieldset class="for-tab">
						
						<legend>
							
							<?= vui_el_button( array( 'text' => lang( 'basic_details' ), 'icon' => 'basic-details',  ) ); ?>
							
						</legend>
						
						<?php if ( $f_action == 'a' ) { ?>
						
						
						
						<?php } else if ( $f_action == 'e' ) { ?>
						
						<div class="article-info tabs-info">
							
							<div class="article-info-item article-info-title">
								
								<?= $article[ 'title' ]; ?>
								
							</div>
							
							<div class="article-info-item article-info-modified-by">
								
								<?= sprintf( lang( 'articles_created_by_name_date_time' ), '<span class="created-by-name">' . $article[ 'created_by_name' ] . '</span>', '<span class="created-date-time">' . ov_strftime( lang( 'articles_created_datetime_format' ), $created_date_time ) . '</span>' ); ?>
								
							</div>
							
							<?php if ( check_var( $article[ 'modified_by_name' ] ) ){ ?>
							
							<div class="article-info-item article-info-last-modification">
								
								<?= sprintf( lang( 'articles_modified_by_name_date_time' ), '<span class="modified-by-name">' . $article[ 'modified_by_name' ] . '</span>', '<span class="modified-date-time">' . ov_strftime( lang( 'articles_modified_datetime_format' ), $modified_date_time ) . '</span>' ); ?>
								
							</div>
							
							<?php } else {  ?>
							
							<div class="article-info-item article-info-last-modification">
								
								<?= ov_strftime( lang( 'articles_last_modification_in_datetime_format' ), $modified_date_time ); ?>
								
							</div>
							
							<?php } ?>
							
						</div>
						
						<?php } ?>
						
						<div id="title" class="vui-field-wrapper-inline">
							
							<?php
								
								$field_name = 'title';
								$field_error = form_error( $field_name, '<div class="msg-inline-error">', '</div>' );
								
								echo form_label( lang( 'set_article_' . $field_name ) );
								
								echo vui_el_input_text(
										
									array(
										
										'text' => $field_error ? element_title( $field_error ) : lang( 'tip_set_article_' . $field_name ),
										'title' => $field_error ? element_title( $field_error ) : lang( 'tip_set_article_' . $field_name ),
										'name' => $field_name,
										'value' => set_value( $field_name, @$article[ $field_name ] ),
										'class' => $field_name . ' ' . ( $field_error ? 'field-error' : '' ),
										'id' => 'article-' . $field_name,
										'autofocus' => TRUE,
										
									)
									
								);
								
								unset( $field_error );
								
							?>
							
						</div>
						
						<div id="alias" class="vui-field-wrapper-inline">
							
							<?php
								
								$field_name = 'alias';
								$field_error = form_error( $field_name, '<div class="msg-inline-error">', '</div>' );
								
								echo form_label( lang( 'set_article_' . $field_name ) );
								
								echo vui_el_input_text(
										
									array(
										
										'text' => $field_error ? element_title( $field_error ) : lang( 'tip_set_article_' . $field_name ),
										'title' => $field_error ? element_title( $field_error ) : lang( 'tip_set_article_' . $field_name ),
										'name' => $field_name,
										'value' => set_value( $field_name, @$article[ $field_name ] ),
										'class' => $field_name . ' ' . ( $field_error ? 'field-error' : '' ),
										'id' => 'article-' . $field_name,
										'autofocus' => $field_error ? TRUE : FALSE,
										
									)
									
								);
								
								unset( $field_error );
								
							?>
							
						</div>
						
						<div id="created-date" class="vui-field-wrapper-inline">
							
							<?php
								
								$field_name = 'created_date';
								$field_error = form_error( $field_name, '<div class="msg-inline-error">', '</div>' );
								$field_attr = ( $field_error ? 'autofocus' : '' ) . ' id="article-' . $field_name . '" name="' . $field_name . '" class="date ' . $field_name . ' ' . ( $field_error ? 'field-error' : '' ) . '"' . ( $field_error ? element_title( $field_error ) : '' );
								
							?>
							
							<?= form_label( lang( $field_name ) ); ?>
							<?= form_input( $field_name, set_value( $field_name, $created_date ), $field_attr ); ?>
							
							<?php
								
								unset( $field_error );
								unset( $field_attr );
								
							?>
							
						</div>
						
						<div id="created-date" class="vui-field-wrapper-inline">
							
							<?php
								
								$field_name = 'created_time';
								$field_error = form_error( $field_name, '<div class="msg-inline-error">', '</div>' );
								$field_attr = ( $field_error ? 'autofocus' : '' ) . ' id="article-' . $field_name . '" name="' . $field_name . '" class="time ' . $field_name . ' ' . ( $field_error ? 'field-error' : '' ) . '"' . ( $field_error ? element_title( $field_error ) : '' );
								
							?>
							
							<?= form_label( lang( $field_name ) ); ?>
							<?= form_input( $field_name, set_value( $field_name, $created_time ), $field_attr ); ?>
							
							<?php
								
								unset( $field_error );
								unset( $field_attr );
								
							?>
							
						</div>
						
						<div class="divisor-h"></div>
						
						<div id="created-by" class="vui-field-wrapper-inline">
							
							<?php
								
								$field_name = 'created_by_id';
								$field_error = form_error( $field_name, '<div class="msg-inline-error">', '</div>' );
								$field_attr = ( $field_error ? 'autofocus' : '' ) . ' id="article-' . $field_name . '" name="' . $field_name . '" class="' . $field_name . ' ' . ( $field_error ? 'field-error' : '' ) . '"' . ( $field_error ? element_title( $field_error ) : '' );
								
								foreach( $users as $user ):
									$field_options[ $user[ 'id' ] ] = $user[ 'name' ];
								endforeach;
								
							?>
							
							<?= form_label( lang( 'user' ) ); ?>
							<?= form_dropdown( $field_name, $field_options, set_value( $field_name, @$article[ $field_name ] ), $field_attr ); ?>
							
							<?php
								
								unset( $field_error );
								unset( $field_attr );
								unset( $field_options );
								
							?>
							
						</div>
						
						<div id="status" class="vui-field-wrapper-inline">
							
							<?php
								
								$field_name = 'status';
								$field_error = form_error( $field_name, '<div class="msg-inline-error">', '</div>' );
								$field_attr = ( $field_error ? 'autofocus' : '' ) . ' id="article-' . $field_name . '" name="' . $field_name . '" class="' . $field_name . ' ' . ( $field_error ? 'field-error' : '' ) . '"' . ( $field_error ? element_title( $field_error ) : '' );
								
								$field_options = array(
									
									-1 => lang('archived'),
									0 => lang('unpublished'),
									1 => lang('published'),
									
								);
								
							?>
							
							<?= form_label( lang( $field_name ) ); ?>
							<?= form_dropdown( $field_name, $field_options, set_value( $field_name, isset( $article[ $field_name ] ) ? $article[ $field_name ] : 1 ), $field_attr ); ?>
							
							<?php
								
								unset( $field_error );
								unset( $field_attr );
								unset( $field_options );
								
							?>
							
						</div>
						
						<div id="category" class="vui-field-wrapper-inline">
							
							<?php
								
								$field_name = 'category_id';
								$field_error = form_error( $field_name, '<div class="msg-inline-error">', '</div>' );
								$field_attr = ( $field_error ? 'autofocus' : '' ) . ' id="article-' . $field_name . '" name="' . $field_name . '" class="' . $field_name . ' ' . ( $field_error ? 'field-error' : '' ) . '"' . ( $field_error ? element_title( $field_error ) : '' );
								
								$field_options = array(
									
									0 => lang('uncategorized'),
									
								);
								
								if ( $categories ){
									
									foreach( $categories as $category ):
										$field_options[ $category[ 'id' ] ] = $category[ 'indented_title' ];
									endforeach;
									
								}
								
							?>
							
							<?= form_label( lang( 'category' ) ); ?>
							<?= form_dropdown( $field_name, $field_options, set_value( $field_name, @$article[ $field_name ] ?@$article[ $field_name ] : ( $this->users->get_user_preference( 'articles_filter_by_category' ) ? $this->users->get_user_preference( 'articles_filter_by_category' ) : 0 ) ), $field_attr ); ?>
							
							<?php
								
								unset( $field_error );
								unset( $field_attr );
								unset( $field_options );
								
							?>
							
						</div>
						
						<div id="image" class="vui-field-wrapper-inline">
							
							<?php
								
								$field_name = 'image';
								$field_error = form_error( $field_name, '<div class="msg-inline-error">', '</div>' );
								$field_attr = 'id="article-' . $field_name . '" name="' . $field_name . '" class="' . $field_name . ' ' . ( $field_error ? 'field-error' : '' ) . '"' . ( $field_error ? element_title( $field_error ) : '' );
								
							?>
							
							<?= form_label( lang( $field_name ) ); ?>
							<?= form_input( $field_name, set_value( $field_name, @$article[ $field_name ] ), $field_attr ); ?>
							
							<?php
								
								unset( $field_error );
								unset( $field_attr );
								
							?>
							
						</div>
						
						<?php if ( $f_action == 'edit_article' ) { ?>
						
						<?= form_hidden('article_id',$article->id); ?>
						
						<?php } ?>
						
					</fieldset>
					
				</div>
				
				<div class="form-item">
					
					<fieldset class="for-tab">
						
						<legend>
							
							<?= vui_el_button( array( 'text' => lang( 'content' ), 'icon' => 'content',  ) ); ?>
							
						</legend>
						
						<div id="introtext-fulltext" class="vui-field-wrapper">
							
							<?php
								
								$field_name = 'introtext';
								$field_error = form_error( $field_name, '<div class="msg-inline-error">', '</div>' );
								$field_attr = ( $field_error ? 'autofocus' : '' ) . ' id="' . $field_name . '" name="' . $field_name . '" class="' . $field_name . ' ' . ( $field_error ? 'field-error' : '' ) . '"' . ( $field_error ? element_title( $field_error ) : '' );
								
							?>
							
							<?= form_label( lang( $field_name ) ); ?>
							<textarea <?= $field_attr; ?>><?= ( $this->input->post( $field_name ) ? $this->input->post( $field_name, FALSE ) : ( check_var( $article[ $field_name ] ) ? $article[ $field_name ] : '' ) ); ?></textarea>
							
							<?php
								
								unset( $field_error );
								unset( $field_attr );
								
							?>
							
							<div class="divisor-h"></div>
							
							<?php
								
								$field_name = 'fulltext';
								$field_error = form_error( $field_name, '<div class="msg-inline-error">', '</div>' );
								$field_attr = ( $field_error ? 'autofocus' : '' ) . ' id="' . $field_name . '" name="' . $field_name . '" class="' . $field_name . ' ' . ( $field_error ? 'field-error' : '' ) . '"' . ( $field_error ? element_title( $field_error ) : '' );
								
							?>
							
							<?= form_label( lang( $field_name ) ); ?>
							<textarea <?= $field_attr; ?>><?= ( $this->input->post( $field_name ) ? $this->input->post( $field_name, FALSE ) : ( check_var( $article[ $field_name ] ) ? $article[ $field_name ] : '' ) ); ?></textarea>
							
							<?php
								
								unset( $field_error );
								unset( $field_attr );
								
							?>
							
						</div>
						
						<div id="full-content" class="hidden vui-field-wrapper">
							
							<legend>
								
								<?= vui_el_button( array( 'text' => lang( 'content' ), 'icon' => 'content',  ) ); ?>
								
							</legend>
							
							<?php
								
								$field_name = 'fullcontent';
								$field_error = form_error( $field_name, '<div class="msg-inline-error">', '</div>' );
								$field_attr = ( $field_error ? 'autofocus' : '' ) . ' id="' . $field_name . '" name="' . $field_name . '" class="js-editor ' . $field_name . ' ' . ( $field_error ? 'field-error' : '' ) . '"' . ( $field_error ? element_title( $field_error ) : '' );
								
							?>
							
							<?= form_label( lang( $field_name ) ); ?>
							
							<?php if ( $this->plugins->load( NULL, 'js_text_editor' ) ){ ?>
							
							<script type="text/javascript" >
								
								$( document ).bind( 'ready', function(){
									
									$( '#introtext-fulltext' ).remove();
									$( '#full-content' ).removeClass( 'hidden' );
									$( '#full-content' ).append( '<textarea <?= $field_attr; ?>><?= str_replace( array( "'", "\n", "\r" ), array( "\'", "", "" ), ( $this->input->post( $field_name ) ? $this->input->post( $field_name, FALSE ) : ( check_var( $article[ $field_name ] ) ? $article[ $field_name ] : '' ) ) ); ?></textarea>' );
									
								});
								
								
								
							</script>
							
							<?php } ?>
							
							<?php
								
								unset( $field_error );
								unset( $field_attr );
								
							?>
							
						</div>
						
					</fieldset>
					
				</div>
				
				<div class="form-item">
					
					<fieldset class="for-tab">
						
						<legend>
							
							<?= vui_el_button( array( 'text' => lang( 'access' ), 'icon' => 'security', 'title' => lang( 'tip_articles_management_access_level' ),  ) ); ?>
							
						</legend>
						
						<div class="field-wrapper">
							
							<label>
								
								<?php
									
									$options = array(
										
										'name' => 'access_type',
										'value' => 'public',
										'checked' => ( $this->input->post( 'access_type' ) == 'public' ) ? TRUE: ( ( ! $this->input->post( 'access_type' ) ) ? TRUE : FALSE ),
										'text' => lang( 'public' ),
										
									);
									
									if ( $f_action == 'edit_article' ) {
										
										$options[ 'checked' ] = ( $this->input->post( 'access_type' ) == 'public' ) ? TRUE : ( ( ! $this->input->post( 'access_type' ) AND $article->access_type == 'public' ) ? TRUE : FALSE );
										
									}
									
									echo vui_el_radiobox( $options );
									
								?>
								
							</label>
							
						</div>
						
						<div class="field-wrapper">
							
							<?php
								
								$access_type_users_field_name = 'access_user_id[]';
								$access_type_users_field_error = form_error( $access_type_users_field_name, '<div class="msg-inline-error">', '</div>' );
								$access_type_users_label_attr = 'class="' . ( $access_type_users_field_error ? 'field-error' : '' ) . '"' . ( $access_type_users_field_error ? element_title( $access_type_users_field_error ) : '' );
								
							?>
							
							<label <?= $access_type_users_label_attr; ?>>
								
								<span class="fake-label">
									
									<?php
										
										$options = array(
											
											'name' => 'access_type',
											'value' => 'users',
											'checked' => ( $this->input->post('access_type') == 'users') ? TRUE: FALSE,
											'text' => lang( 'specific_users' ),
											
										);
										
										if ( $f_action == 'edit_article' ) {
											
											$options[ 'checked' ] = ( $this->input->post('access_type') == 'users' ) ? TRUE : ( ( ! $this->input->post( 'access_type' ) AND $article->access_type == 'users' ) ? TRUE : FALSE );
											
										}
										
										echo vui_el_radiobox( $options );
										
									?>
									
								</span>
								
								<?php
								
								if ( $this->input->post( 'access_user_id' ) ) {
									
									$post_access_user_id = $this->input->post('access_user_id');
									
								}
								else {
									
									$post_access_user_id = FALSE;
									
								}
								
								?>
								
								<?php foreach( $users as $user ){
									
									$options = array(
										
										'wrapper-class' => 'checkbox-sub-item',
										'name' => $access_type_users_field_name,
										'id' => $user[ 'id' ],
										'value' => '>' . $user[ 'id' ] . '<',
										'checked' => ( $this->input->post( 'access_type' ) === 'users' AND $post_access_user_id AND in_array( html_escape( '>' . $user['id'] . '<' ), $post_access_user_id ) ) ? 'checked' : '',
										'text' => $user[ 'name' ] . ' (' . $user[ 'username' ] . ')',
										
									);
									
									if ( $f_action == 'edit_article' ) {
										
										$options[ 'checked' ] = ( $this->input->post( 'access_type' ) === 'users' AND $post_access_user_id AND in_array( html_escape( '>' . $user['id'] . '<' ), $post_access_user_id ) ) ? TRUE : ( ( ! $this->input->post('access_type') AND $article->access_type == 'users' AND in_array( '>'.$user['id'].'<', $article->access_user_id ) ) ? TRUE : FALSE );
										
									}
									
									echo vui_el_checkbox( $options );
									
								};
								
								?>
								
							</label>
							
						</div>
						
						<div class="field-wrapper">
							
							<label>
								
								<?php
									
									$access_type_users_groups_field_name = 'access_user_group_id[]';
									$access_type_users_groups_field_error = form_error( $access_type_users_groups_field_name, '<div class="msg-inline-error">', '</div>' );
									$access_type_users_groups_label_attr = 'class="' . ( $access_type_users_groups_field_error ? 'field-error' : '' ) . '"' . ( $access_type_users_groups_field_error ? element_title( $access_type_users_groups_field_error ) : '' );
									
								?>
								
								<label <?= $access_type_users_groups_label_attr; ?>>
									
									<?php
										
										$options = array(
											
											'name' => 'access_type',
											'value' => 'users_groups',
											'checked' => ( $this->input->post( 'access_type' ) == 'users_groups') ? TRUE: FALSE,
											'text' => lang( 'specific_users_groups' ),
											
										);
										
										if ( $f_action == 'edit_article' ) {
											
											$options[ 'checked' ] = ( $this->input->post( 'access_type' ) == 'users_groups' ) ? TRUE: ( ( ! $this->input->post( 'access_type' ) AND $article->access_type == 'users_groups' ) ? TRUE : FALSE );
											
										}
										
										echo vui_el_radiobox( $options );
										
									?>
									
									<?php
									
									if ( $this->input->post( 'access_user_group_id' ) ) {
										
										$post_access_user_group_id = $this->input->post('access_user_group_id');
										
									}
									else {
										
										$post_access_user_group_id = FALSE;
										
									}
									
									?>
									
									<?php foreach( $users_groups as $user_group ){
										
										$options = array(
											
											'wrapper-class' => 'checkbox-sub-item',
											'name' => $access_type_users_groups_field_name,
											'id' => 'user-group-' . $user_group[ 'id' ],
											'value' => '>' . $user_group[ 'id' ] . '<',
											'checked' => ( $this->input->post( 'access_type' ) === 'users_groups' AND $post_access_user_group_id AND in_array( html_escape( '>' . $user_group['id'] . '<' ), $post_access_user_group_id ) ) ? TRUE : FALSE,
											'text' => check_var( $user_group[ 'indented_title' ] ) ? $user_group[ 'indented_title' ] : '',
											
										);
										
										if ( $f_action == 'edit_article' ) {
											
											$options[ 'checked' ] = ( $this->input->post( 'access_type' ) === 'users_groups' AND $post_access_user_group_id AND in_array( html_escape( '>' . $user_group['id'] . '<' ), $post_access_user_group_id ) ) ? TRUE : ( ( ! $this->input->post('access_type') AND $article->access_type == 'users_groups' AND in_array( '>'.$user_group['id'].'<', $article->access_user_group_id ) ) ? TRUE : FALSE );
											
										}
										
										echo vui_el_checkbox( $options );
										
									};
									
									?>
									
								</label>
								
						</div>
						
					</fieldset>
					
				</div>
				
				<div class="form-item">
					
					<?php
					
					/* gerando o html dos parâmetros, ele deve ser chamado na view, não no controller,
					 * pois os erros de validação dos elementos dos parâmetros devem ser expostos
					 * após a chamada da função $this->form_validation->run()
					 */
					
					echo params_to_html( $params_spec, $final_params_values );
					
					?>
					
				</div>
				
			</div>
			
		<?= form_close(); ?>
		
	</div>
	
</div>

<?php if ( $this->plugins->performed( 'yetii' ) ){ ?>

<script type="text/javascript" >
	
	$( document ).bind( 'keydown', function( e ){
		
		if ( pressed_key == '83 Ctrl' ){
			e.preventDefault();
			$( '#submit-apply' ).trigger( 'click' );
		}
		
	});
	
	$( document ).bind( 'ready', function(){
		
		/*************************************************/
		/**************** Criando as tabs ****************/
		
		callbackFunctionMakeTabs = function(){
			
			$( '.article-info-title' ).before( '<div class="article-info-item article-info-image"><?= ( isset( $article->image ) AND $article->image != '' ) ? '<a class="article-image-thumb-container" href="' . $article->image . '" target="_blank"><img class="article-image-thumb" src="' . $article->image . '" /></a>' : ''; ?></div>' );
			
		}
		
		makeTabs( $( '.tabs-wrapper' ), '.form-item fieldset.for-tab, .params-set-wrapper', 'legend, .params-set-title', null, callbackFunctionMakeTabs );
		
		/**************** Criando as tabs ****************/
		/*************************************************/
		
	});
	
</script>

<?php } ?>

<script type="text/javascript">
	
	var updateArticleTitle = function(){
		
		$( '.tab-item .article-info-title' ).html( '<span class="article-title-edited">' + window.articleTitle + '</span>' );
		
	}
	
	$( '#article-title' ).bind( 'keyup', function() {
		
		window.articleTitle = $( this ).val();
		updateArticleTitle();
		
	});
	
	<?php if ( $this->plugins->performed( 'modal_rf_file_picker' ) ) { ?>
	
	window.updateImage = function(){
		
		var url = $( '#article-image' ).val(),
			thumb_image = $( '.article-image-thumb' );
			
		var image_src = url + '?' + Math.floor( ( Math.random() * 100 ) + 1 );
		var thumb_image_src = 'thumbs/' + image_src;
		
		$( '.article-info-image' ).empty();
		
		if ( url != '' ){
			
			$( '.article-info-image' ).append( '<span class="article-image-thumb-container" href="' + url + '" target="_blank"><img class="article-image-thumb" src="' + image_src + '" /></span>' );
			
			$( '.article-image-thumb' ).attr( 'src', thumb_image_src );
			
		}
		
		$.fancybox.close();
		
	}
	window.onFileChooseFunction = function(){
		
		var url = '<?= BASE_URL; ?>/' + $( '#article-image' ).val();
		
		
		
		if ( url != '' ){
			
			$.imageCrop.open({
				
				imgSrc: url,
				callback: updateImage
				
			});
			
		}
		
	}
	
	$( document ).bind( 'ready', function(){
		
		window.updateImage();
		
		$( '#article-image' ).after( '<?=
			
			str_replace(
				
				"'", "\'", vui_el_button(
					
					array(
						
						'url' => '#',
						'text' => lang( 'select_image' ),
						'get_url' => FALSE,
						'id' => 'image-picker',
						'icon' => 'more',
						'only_icon' => TRUE,
						'class' => 'modal-file-picker',
						'attr' => array(
							
							'data-rffieldid' => 'article-image',
							'data-rftype' => 'image',
							
						)
							
					)
					
				)
				
			);
			
		?>' );
		
		$('.article-image-thumb-container').fancybox();
		
	});
	
	<?php } ?>
	
</script>

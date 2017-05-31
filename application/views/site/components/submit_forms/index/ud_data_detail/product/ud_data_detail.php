<?php if ( ! defined( 'BASEPATH' ) ) exit( 'No direct script access allowed' );
	
	$_path = VIEWS_PATH . SITE_COMPONENTS_VIEWS_PATH . 'submit_forms' . DS . 'index' . DS . 'ud_data_detail' . DS . 'default' . DS;
	$_list_path = VIEWS_PATH . SITE_COMPONENTS_VIEWS_PATH . 'submit_forms' . DS . 'index' . DS . 'users_submits' . DS . 'default' . DS;
	
	$filter_fields_input_name = 'users_submits_search[dinamic_filter_fields]';
	
	$unique_hash = md5( uniqid( rand(), true ) );
	
	$_is_presentation = FALSE;
	
	$wrapper_class = array(
		
		'ud-data-detail-wrapper',
		
	);
	
	$_ud_data_wrapper_class = array();
	
	$property_presentation_types = array(
		
		'image',
		'title',
		'event_datetime',
		'content',
		'other_info',
		'status',
		
	);
	
	$__main_image = FALSE;
	
	reset( $property_presentation_types );
	
	while ( list( $k, $v ) = each( $property_presentation_types ) ) {
		
		${ '_ud_' . $v . '_props' } = array();
		
		if ( isset( $params[ 'ud_' . $v . '_prop' ] ) ) {
			
			$_is_presentation = TRUE;
			
		}
		
		if ( check_var( $params[ 'ud_' . $v . '_prop' ] ) ) {
			
			$__class = FALSE;
			
			reset( $params[ 'ud_' . $v . '_prop' ] );
			
			while ( list( $_alias, $_field ) = each( $params[ 'ud_' . $v . '_prop' ] ) ) {
				
				if ( $v == 'image' ) {
					
					if ( ! $__main_image AND check_var( $props_to_show[ $_alias ] ) ) {
						
						$__main_image = get_url( $ud_data[ 'data' ][ $_alias ] );
						
					}
					
				}
				
				if ( check_var( $props_to_show[ $_alias ] ) ) {
					
					$wrapper_class[ $_alias ] = 'has-ud-' . str_replace( '_', '-', url_title( $v, '-', TRUE ) ) . '-prop';
					
				}
				
				if ( check_var( $props_to_show[ $_alias ] ) AND check_var( $ud_data[ 'parsed_data' ][ 'full' ][ $_alias ][ 'value' ], TRUE ) ) {
					
					if ( ! $__class ) $__class = 'has-ud-' . $v;
					
					${ '_ud_' . $v . '_props' }[ $_alias ][ 'label' ] = $ud_data[ 'parsed_data' ][ 'full' ][ $_alias ][ 'label' ];
					${ '_ud_' . $v . '_props' }[ $_alias ][ 'value' ] = $ud_data[ 'parsed_data' ][ 'full' ][ $_alias ][ 'value' ];
					
				}
				
			}
			
			if ( $__class ) $_ud_data_wrapper_class[ $__class ] = $__class;
			
		}
		
	}
	
	if ( ! $__main_image AND check_var( $params[ 'ud_image_prop' ] ) ) {
		
		reset( $params[ 'ud_image_prop' ] );
		
		while ( list( $_alias, $_field ) = each( $params[ 'ud_image_prop' ] ) ) {
			
			if ( $_field AND isset( $props[ $_alias ] ) ) {
				
				if ( ! $__main_image ) {
					
					$__main_image = get_url( $ud_data[ 'data' ][ $_alias ], NULL, FALSE );
					
					break;
					
				}
				
			}
			
		}
		
	}
	
	$wrapper_class = join( ' ', $wrapper_class );
	
	$show_page_content_title = check_var( $params['show_page_content_title'] );
	
	if ( check_var( $params[ 'ud_d_detail_show_page_content_title' ], TRUE ) ) {
		
		if ( $params[ 'ud_d_detail_show_page_content_title' ] != 'global' ) {
			
			$show_page_content_title = check_var( $params[ 'ud_d_detail_show_page_content_title' ] );
			
		}
		
	}
	
	?>
	
	<article id="submit-form-user-submit-detail-<?= $unique_hash; ?>" class="<?= ( $__main_image AND check_var( $params[ 'ud_d_detail_main_image_on_title' ] ) ) ? 'ud-image-on-title ' : ''; ?>unid ud-d-detail-layout-<?= $params[ 'ud_d_detail_layout_site' ]; ?> ud-d-detail-wrapper submit-form user-submit <?= @$params['page_class']; ?>">
	
		<?php if ( $show_page_content_title ) { ?>
		<header class="page-title">
			
			<h1>
				
				<?= $this->mcm->html_data[ 'content' ][ 'title' ]; ?>
				
			</h1>
			
		</header>
		<?php } ?>
		
		<?php
		
		?>
		
		<div class="component-content">
			
			<?php
				
				/* ---------------------------------------------------------------------------
				* ---------------------------------------------------------------------------
				* User submit
				* ---------------------------------------------------------------------------
				*/
				
				if ( file_exists( $_path . 'sub_layouts' . DS . 'default' . DS . 'ud_data_detail.php' ) ) {
					
					require( $_path . 'sub_layouts' . DS . 'default' . DS . 'ud_data_detail.php' );
					
				}
				
			?>
			
		</div>
		
		<div class="after-content-plugins-wrapper">
			
			<?php
				
				$this->plugins->load( NULL, 'after_content' );
				echo print_r( $this->plugins->get_output( NULL, 'after_content' ), TRUE );
				
			?>
			
		</div>
		
	</article>
	

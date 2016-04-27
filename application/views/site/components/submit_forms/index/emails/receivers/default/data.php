<?php if ( ! defined( 'BASEPATH' ) ) exit( 'No direct script access allowed' );
	
	if ( ! check_var( $vars_loaded ) ) {
		
		require( 'vars.php' );
		
	}
	
?>

<table width="80%" style="font-size: <?= number_format( DEFAULT_SPACING / 1.1, 2, '.', '' ); ?>em; background: <?= $content_bg_color; ?>; color: <?= $content_fg_color; ?>; margin: 0 auto; text-align: left; border-collapse: collapse; border: none; width:80%" cellpadding="0" cellspacing="0">
	
	<?php
	
	$existing_fields = FALSE;
	
	$fields = $submit_form[ 'fields' ];
	
	foreach ( $fields as $field_key => $field ) {
		
		if ( ! in_array( $field[ 'field_type' ], array( 'html', 'button' ) ) ) {
			
			$existing_fields[ $field[ 'alias' ] ] = $field;
			
		}
		
	}
	
	$total_rows = 0;
	
	$current_row_index = 1;
	
	foreach( $ud_data[ 'parsed_data' ][ 'full' ] as $alias => $pd ) {
		
		if ( $alias != 'mod_datetime' AND ( in_array( $alias, array( 'id', 'submit_datetime' ) ) OR ( ( $pd[ 'value' ] == '' AND check_var( $params[ 'submit_form_sending_email_show_empty_fields' ] ) ) OR $pd[ 'value' ] != '' ) ) ) {
			
			$total_rows++;
			
		}
		
	}
	
	foreach( $ud_data[ 'parsed_data' ][ 'full' ] as $alias => $pd ) {
		
		if ( $alias != 'mod_datetime' AND ( in_array( $alias, array( 'id', 'submit_datetime' ) ) OR ( ( $pd[ 'value' ] == '' AND check_var( $params[ 'submit_form_sending_email_show_empty_fields' ] ) ) OR $pd[ 'value' ] != '' ) ) ) {
			
			$current_row_index++;
			
			if ( ! isset( $fields[ $alias ][ 'field_type' ] ) OR ! in_array( $fields[ $alias ][ 'field_type' ], array( 'button' ) ) ) {
				
				if ( in_array( $alias, array( 'id', 'submit_datetime', 'mod_datetime' ) ) ) {
					
					if ( ! ( $alias == 'mod_datetime' AND ( isset( $ud_data[ 'parsed_data' ][ 'full' ][ 'submit_datetime' ][ 'value' ] ) AND $ud_data[ 'parsed_data' ][ 'full' ][ 'submit_datetime' ][ 'value' ] == $pd[ 'value' ] ) ) ) {
						
						echo '<tr>';
						
						echo '<td style="padding: ' . ( number_format( DEFAULT_SPACING / 2, 2, '.', '' ) ) . 'em ' . ( number_format( DEFAULT_SPACING, 2, '.', '' ) ) . 'em; width: 20%; text-align: right; border-bottom: thin solid ' . $content_border_color . '; border-right: thin solid ' . $content_border_color . '; border-left: ' . ( number_format( DEFAULT_SPACING * 2, 2, '.', '' ) ) . 'em solid ' . $content_bg_color . ';">';
						
						echo '<span>';
						
						echo lang( $alias );
						
						echo '</span>';
						
						echo '</td>';
						
						echo '<td style="padding: ' . ( number_format( DEFAULT_SPACING / 2, 2, '.', '' ) ) . 'em ' . ( number_format( DEFAULT_SPACING, 2, '.', '' ) ) . 'em; border-bottom: thin solid ' . $content_border_color . '; border-right: ' . ( number_format( DEFAULT_SPACING * 2, 2, '.', '' ) ) . 'em solid ' . $content_bg_color . ';">';
						
						echo '<span>';
						
						if ( $alias == 'submit_datetime' OR $alias == 'mod_datetime' ) {
							
							$pd[ 'value' ] = strtotime( $pd[ 'value' ] );
							$pd[ 'value' ] = strftime( lang( 'ud_data_datetime' ), $pd[ 'value' ] );
							
						}
						
						echo $pd[ 'value' ];
						
						echo '</span>';
						
						echo '</td>';
						
						echo '</tr>';
						
					}
					
					continue;
					
				}
				
				echo '<tr>';
				
				echo '<td style="padding: ' . ( number_format( DEFAULT_SPACING / 2, 2, '.', '' ) ) . 'em ' . ( number_format( DEFAULT_SPACING, 2, '.', '' ) ) . 'em; width: 20%; text-align: right; ' . ( ( $current_row_index <= $total_rows ) ? 'border-bottom: thin solid ' . $content_border_color . '; ' : '' ) . 'border-right: thin solid ' . $content_border_color . '; border-left: ' . ( number_format( DEFAULT_SPACING * 2, 2, '.', '' ) ) . 'em solid ' . $content_bg_color . ';">';
				
				echo '<span>';
				
				echo lang( isset( $existing_fields[ $alias ][ 'presentation_label' ] ) ? $existing_fields[ $alias ][ 'presentation_label' ] : ( isset( $existing_fields[ $alias ][ 'label' ] ) ? $existing_fields[ $alias ][ 'label' ] : '<span class="error" title="' . lang( 'submit_forms_error_no_field_on_submit_form' ) . '">[' . $alias . ']</span>' ) );
				
				echo '</span>';
				
				echo '</td>';
				
				echo '<td style="padding: ' . ( number_format( DEFAULT_SPACING / 2, 2, '.', '' ) ) . 'em ' . ( number_format( DEFAULT_SPACING, 2, '.', '' ) ) . 'em; ' . ( ( $current_row_index <= $total_rows ) ? 'border-bottom: thin solid ' . $content_border_color . '; ' : '' ) . 'border-right: ' . ( number_format( DEFAULT_SPACING * 2, 2, '.', '' ) ) . 'em solid ' . $content_bg_color . ';">';
				
				echo '<span class="filter-me">';
				
				$advanced_options = check_var( $existing_fields[ $alias ][ 'advanced_options' ] ) ? $existing_fields[ $alias ][ 'advanced_options' ] : FALSE;
				
				if ( check_var( $advanced_options[ 'prop_is_ud_image' ] ) AND check_var( $pd[ 'value' ] ) ) {
					
					$thumb_params = array(
						
						'wrapper_class' => 'us-image-wrapper',
						'src' => url_is_absolute( $pd[ 'value' ] ) ? $pd[ 'value' ] : get_url( 'thumbs/' . $pd[ 'value' ] ),
						'href' => get_url( $pd[ 'value' ] ),
						'rel' => 'gus-thumb',
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
					
					echo $pd[ 'value' ];
					
				}
				else if ( check_var( $advanced_options[ 'prop_is_ud_email' ] ) AND check_var( $pd[ 'value' ] ) ) {
					
					echo '<a href="mailto:' . $pd[ 'value' ] . '">' . $pd[ 'value' ] . '</a>';
					
				}
				else if ( isset( $pd[ 'value' ] ) ) {
					
					echo htmlspecialchars_decode( $pd[ 'value' ] );
					
				}
				
				echo '</span>';
				
				echo '</td>';
				
				echo '</tr>';
				
			}
			
		}
		
	} ?>
	
</table>

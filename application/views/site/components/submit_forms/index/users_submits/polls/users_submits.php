<?php if ( ! defined( 'BASEPATH' ) ) exit( 'No direct script access allowed' );

//echo print_r( $fields, true ) . '<br/><br/><br/>-----------<br/><br/><br/>';

$_default_path = VIEWS_PATH . SITE_COMPONENTS_VIEWS_PATH . 'submit_forms' . DS . 'index' . DS . 'users_submits' . DS . 'default' . DS;

$_path = VIEWS_PATH . SITE_COMPONENTS_VIEWS_PATH . 'submit_forms' . DS . 'index' . DS . 'users_submits' . DS . 'testimonials' . DS;

$filter_fields_input_name = 'users_submits_search[dinamic_filter_fields]';

$pre_text_pos = 'before_search_fields';

$unique_hash = md5( uniqid( rand(), true ) );

if ( check_var( $submit_form[ 'params' ][ 'us_pre_text_position' ] ) ) {
	
	$pre_text_pos = $submit_form[ 'params' ][ 'us_pre_text_position' ];
	
}

?>

<?php
	
	if ( check_var( $users_submits ) AND check_var( $params[ 'poll_data_field' ] ) ) {
		
		$_results = array();
		$_total_votes = 0;
		$_major = 0;
		
		foreach ( $users_submits as $key => $user_submit ) {
			
			if ( check_var( $user_submit[ 'data' ][ $params[ 'poll_data_field' ] ] ) ){
				
				$_total_votes++;
				
				if ( isset( $_results[ $user_submit[ $params[ 'poll_data_field' ] ] ][ 'count' ] ) ) {
					
					$_results[ $user_submit[ $params[ 'poll_data_field' ] ] ][ 'count' ]++;
					
					if ( $_results[ $user_submit[ $params[ 'poll_data_field' ] ] ][ 'count' ] > $_major ){
						
						$_major = $_results[ $user_submit[ $params[ 'poll_data_field' ] ] ][ 'count' ];
						
					}
					
				}
				else {
					
					$_results[ $user_submit[ $params[ 'poll_data_field' ] ] ][ 'count' ] = 1;
					
				}
				
				$_results[ $user_submit[ $params[ 'poll_data_field' ] ] ][ 'value' ] = $user_submit[ $params[ 'poll_data_field' ] ];
				
			}
			
		}
		
		$_params = $params;
		
		$result = '';
		usort( $_results, function( $a, $b ) use ( & $params )  {
			
			return $a[ 'count' ] - $b[ 'count' ];
			
		});
		
		foreach ( $_results as $key => & $_result ) {
			
			$_result[ 'pct' ] = ( 100 * $_result[ 'count' ] ) / $_total_votes;
			
		}
		
		$_major_pct = ( 100 * $_major ) / $_total_votes;
		$_major_pct_dif = 100 - ( 100 * $_major ) / $_total_votes;
		
		if ( $_major_pct > 0 ) {
			
			$_factor = 100 / $_major_pct;
			
		}
		else {
			
			$_factor = 1;
			
		}
		
		$_results = array_reverse( $_results );
		
	}
	
?>
	
	
	
<section id="submit-form-users-submits-<?= $unique_hash; ?>" class="submit-form users-submits <?= @$params['page_class']; ?> polls">
	
	<?php if ( @$params[ 'poll_data_field' ] ) { ?>
	<header class="component-heading">
		
		<h1>
			
			<?= $this->mcm->html_data['content']['title']; ?>
			
		</h1>
		
	</header>
	<?php } ?>
	
	<div id="component-content">
		
		<div id="ud-d-search-results-wrapper" class="users-submits-wrapper results"><?php
			
			if ( check_var( $users_submits ) AND check_var( $params[ 'poll_data_field' ] ) ) {
				
				$i = 1;
				
				foreach ( $_results as $key => & $_result ) {
					
					?><div class="user-submit-wrapper <?= $_result[ 'pct' ] == $_major_pct ? 'major' : ''; ?> value-<?= $i; ?> user-submit-<?= url_title( $_result[ 'value' ] ); ?>">
						<div class="item user-submit-value-wrapper">
							
							<div class="value">
								
								<?= sprintf( lang( 'polls_result_value' ), $_result[ 'value' ] ); ?>
								
							</div>
							
						</div><?php
						
						?><div class="item user-submit-bar-wrapper">
							
							<div class="bar" style="width:<?= str_replace( ',', '.', $_factor * $_result[ 'pct' ] ); ?>%"></div>
							
						</div><?php
						
						?><div class="item user-submit-pct-wrapper">
							
							<div class="pct">
								
								<?= sprintf( lang( 'polls_result_pct' ), round( $_result[ 'pct' ], 2 ) ); ?>
								
							</div>
							
						</div><?php
						
						?><div class="item user-submit-votes-wrapper">
							
							<div class="votes">
								
								<?php if ( $_result[ 'count' ] <= 0 ) { ?>
									
									<?= lang( 'polls_result_votes_none' ); ?>
									
								<?php } else if ( $_result[ 'count' ] == 1 ) { ?>
									
									<?= sprintf( lang( 'polls_result_votes_one' ), $_result[ 'count' ] ); ?>
									
								<?php } else if ( $_result[ 'count' ] > 1 ) { ?>
									
									<?= sprintf( lang( 'polls_result_votes_more' ), $_result[ 'count' ] ); ?>
									
								<?php } ?>
								
							</div>
							
						</div>
						
					</div><?php
					
					$i++;
					
				}
				
			}
			else {
			
				?><div class="user-submit-polls-no-results">
					
					<?= lang( 'user_submit_polls_no_results' ); ?>
					
				</div><?php
			
			}
			
		?></div>
		
		<div class="clear"></div>
		
		<?php
			
			/* ---------------------------------------------------------------------------
			* ---------------------------------------------------------------------------
			* Read more
			* ---------------------------------------------------------------------------
			*/
			
			if ( file_exists( $_default_path . 'readmore.php' ) ) {
				
				require( $_default_path . 'readmore.php' );
				
			}
			
		?>
		
	</div>

</section>


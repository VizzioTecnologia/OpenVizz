	
	<?php
	
	$adjacents = 2;
	$page = $cp;
	$prev = $page - 1;
	$next = $page + 1;
	$lpm1 = $tp - 1;
	$targetpage = 'uri';
	$pagination = '';
	
	
	
	$prev_url = str_replace( array( '%p%', '%ipp%' ), array( $cp-1, $ipp ), $uri );
	$prev_button = '';
	
	if ( $cp > 1 ) {
		
		$prev_button = vui_el_button(
		
			array(
				
				'url' => $prev_url . $uri_sfx,
				'text' => lang( 'previous_page' ),
				'wrapper_class' => 'prev',
				
			)
			
		);
		
	}
	else {
		
		$prev_button = vui_el_button(
		
			array(
				
				'text' => lang( 'previous_page' ),
				'wrapper_class' => 'prev current inactive',
				
			)
			
		);
		
	}
	
	
	$total_mid_buttons = 0;
	$mid_buttons = '';
	
	if ( $tp < 7 + ( $adjacents * 2 ) ){
		
		for ( $i = 1; $i <= $tp; $i++ ) {
			
			if ( $i != $cp ) {
				
				$mid_buttons .= vui_el_button(
					
					array(
						
						'url' => str_replace( array( '%p%', '%ipp%' ), array( $i, $ipp ), $uri ) . $uri_sfx,
						'text' => $i,
						'wrapper_class' => 'page-num',
						
					)
					
				);
				
				$total_mid_buttons++;
				
			}
			else {
				
				$mid_buttons .= vui_el_button(
					
					array(
						
						'text' => $i,
						'wrapper_class' => 'page-num current inactive',
						
					)
					
				);
				
				$total_mid_buttons++;
				
			}
			
		}
		
	}
	else if ( $tp > 5 + ( $adjacents * 2 ) ) {
		
		if ( $page <= 1 + ( $adjacents * 2 ) ) {
			
			for ($i = 1; $i < 4 + ($adjacents * 2); $i++){
				
				if ( $i != $cp ) {
					
					$mid_buttons .= vui_el_button(
						
						array(
							
							'url' => str_replace( array( '%p%', '%ipp%' ), array( $i, $ipp ), $uri ) . $uri_sfx,
							'text' => $i,
							'wrapper_class' => 'page-num',
							
						)
						
					);
					
					$total_mid_buttons++;
					
				}
				else {
					
					$mid_buttons .= vui_el_button(
						
						array(
							
							'text' => $i,
							'wrapper_class' => 'page-num current inactive',
							
						)
						
					);
					
					$total_mid_buttons++;
					
				}
				
			}
			
			$mid_buttons .= vui_el_button(
				
				array(
					
					'text' => '...',
					'wrapper_class' => 'page-more inactive',
					
				)
				
			);
			$total_mid_buttons++;
			
			$mid_buttons .= vui_el_button(
				
				array(
					
					'url' => str_replace( array( '%p%', '%ipp%' ), array( $tp - 1, $ipp ), $uri ),
					'text' => ( $tp - 1 ),
					'wrapper_class' => 'page-num',
					
				)
				
			);
			$total_mid_buttons++;
			
			$mid_buttons .= vui_el_button(
				
				array(
					
					'url' => str_replace( array( '%p%', '%ipp%' ), array( $tp, $ipp ), $uri ),
					'text' => $tp,
					'wrapper_class' => 'page-num',
					
				)
				
			);
			$total_mid_buttons++;
			
		}
		else if ( $tp - ( $adjacents * 2 ) > $page && $page > ( $adjacents * 2 ) ) {
			
			$mid_buttons .= vui_el_button(
				
				array(
					
					'url' => str_replace( array( '%p%', '%ipp%' ), array( 1, $ipp ), $uri ),
					'text' => 1,
					'wrapper_class' => 'page-num',
					
				)
				
			);
			$total_mid_buttons++;
			
			$mid_buttons .= vui_el_button(
				
				array(
					
					'url' => str_replace( array( '%p%', '%ipp%' ), array( 2, $ipp ), $uri ),
					'text' => 2,
					'wrapper_class' => 'page-num',
					
				)
				
			);
			$total_mid_buttons++;
			
			$mid_buttons .= vui_el_button(
				
				array(
					
					'text' => '...',
					'wrapper_class' => 'page-more inactive',
					
				)
				
			);
			$total_mid_buttons++;
			
			for ($i = $cp - $adjacents; $i <= $cp + $adjacents; $i++) {
				
				if ( $i != $cp ) {
					
					$mid_buttons .= vui_el_button(
						
						array(
							
							'url' => str_replace( array( '%p%', '%ipp%' ), array( $i, $ipp ), $uri ),
							'text' => $i,
							'wrapper_class' => 'page-num',
							
						)
						
					);
					
					$total_mid_buttons++;
					
				}
				else {
					
					$mid_buttons .= vui_el_button(
						
						array(
							
							'text' => $i,
							'wrapper_class' => 'page-num current inactive',
							
						)
						
					);
					
					$total_mid_buttons++;
					
				}
				
			}
			
			$mid_buttons .= vui_el_button(
				
				array(
					
					'text' => '...',
					'wrapper_class' => 'page-more inactive',
					
				)
				
			);
			$total_mid_buttons++;
			
			$mid_buttons .= vui_el_button(
				
				array(
					
					'url' => str_replace( array( '%p%', '%ipp%' ), array( $tp - 1, $ipp ), $uri ),
					'text' => $tp - 1,
					'wrapper_class' => 'page-num',
					
				)
				
			);
			$total_mid_buttons++;
			
			$mid_buttons .= vui_el_button(
				
				array(
					
					'url' => str_replace( array( '%p%', '%ipp%' ), array( $tp, $ipp ), $uri ),
					'text' => $tp,
					'wrapper_class' => 'page-num',
					
				)
				
			);
			$total_mid_buttons++;
			
		} else {
			
			$mid_buttons .= vui_el_button(
				
				array(
					
					'url' => str_replace( array( '%p%', '%ipp%' ), array( 1, $ipp ), $uri ),
					'text' => 1,
					'wrapper_class' => 'page-num',
					
				)
				
			);
			$total_mid_buttons++;
			
			$mid_buttons .= vui_el_button(
				
				array(
					
					'url' => str_replace( array( '%p%', '%ipp%' ), array( 2, $ipp ), $uri ),
					'text' => 2,
					'wrapper_class' => 'page-num',
					
				)
				
			);
			$total_mid_buttons++;
			
			$mid_buttons .= vui_el_button(
				
				array(
					
					'text' => '...',
					'wrapper_class' => 'page-more inactive',
					
				)
				
			);
			$total_mid_buttons++;
			
			for ( $i = $tp - ( 2 + ( $adjacents * 2 ) ); $i <= $tp; $i++ ) {
				
				if ( $i != $cp ) {
					
					$mid_buttons .= vui_el_button(
						
						array(
							
							'url' => str_replace( array( '%p%', '%ipp%' ), array( $i, $ipp ), $uri ),
							'text' => $i,
							'wrapper_class' => 'page-num',
							
						)
						
					);
					
					$total_mid_buttons++;
					
				}
				else {
					
					$mid_buttons .= vui_el_button(
						
						array(
							
							'text' => $i,
							'wrapper_class' => 'page-num current inactive',
							
						)
						
					);
					
					$total_mid_buttons++;
					
				}
				
			}
			
		}
		
	}
	
	$next_url = str_replace( array( '%p%', '%ipp%' ), array( $cp+1, $ipp ), $uri );
	$next_button = '';
	
	if ( $cp < $tp ) {
		
		$next_button = vui_el_button(
			
			array(
				
				'url' => $next_url,
				'text' => lang( 'next_page' ),
				'wrapper_class' => 'next',
				
			)
			
		);
		
	}
	else {
		
		$next_button = vui_el_button(
		
			array(
				
				'text' => lang( 'next_page' ),
				'wrapper_class' => 'next current inactive',
				
			)
			
		);
		
	}
	
	?>
	
	<div class="pagination total-<?= ( $total_mid_buttons % 2 === 0 ? 'even' : 'odd' ); ?> total-<?= $total_mid_buttons; ?>"><?php
		
		echo $prev_button;
		
		?><span class="mid-buttons-wrapper"><?php
			
			echo $mid_buttons;
			
		?></span><?php
		
		echo $next_button;
		
	?></div>
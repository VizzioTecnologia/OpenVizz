<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>

	<script type="text/javascript">
		
		var beforeChange_<?= $unique_module_hash; ?> = function(){
			
			/*
			$( '#ns-<?= $unique_module_hash; ?> .nivo-caption' ).removeClass( 'visible' );
			$( '#ns-<?= $unique_module_hash; ?> .nivo-caption' ).addClass( 'hidden' );
			*/
			
		};
		
		var afterChange_<?= $unique_module_hash; ?> = function(){
			
			/*
			$( '#ns-<?= $unique_module_hash; ?> .nivo-caption' ).removeClass( 'visible' );
			$( '#ns-<?= $unique_module_hash; ?> .nivo-caption' ).addClass( 'hidden' );
			
			var curCaption = $( '#ns-<?= $unique_module_hash; ?> .nivo-caption' ).text();
			
			if ( curCaption ) {
				
				$( '#ns-<?= $unique_module_hash; ?> .nivo-caption' ).removeClass( 'hidden' );
				$( '#ns-<?= $unique_module_hash; ?> .nivo-caption' ).addClass( 'visible' );
				
			}
			*/
			
		};
		
		var afterLoad_<?= $unique_module_hash; ?> = function(){
			
		};
		
		$( document ).bind( 'ready', function() {
			
			$( '#ns-<?= $unique_module_hash; ?>' ).parent().append( '<div class="preload-wrapper"><div class="preload on"></div></div>' );
			
		});
		
		$( window ).load(function() {
			
			$( '#ns-<?= $unique_module_hash; ?>' ).nivoSlider({
				
				effect: '<?= $module_data[ 'params' ][ 'effect' ]; ?>',			   // Specify sets like: 'fold,fade,sliceDown'
				slices: <?= $module_data[ 'params' ][ 'slices' ]; ?>,					 // For slice animations
				boxCols: <?= $module_data[ 'params' ][ 'box_cols' ]; ?>,					 // For box animations
				boxRows: <?= $module_data[ 'params' ][ 'box_rows' ]; ?>,					 // For box animations
				animSpeed: <?= $module_data[ 'params' ][ 'anim_speed' ]; ?>,				 // Slide transition speed
				pauseTime: <?= $module_data[ 'params' ][ 'pause_time' ]; ?>,				// How long each slide will show
				startSlide: <?= $module_data[ 'params' ][ 'start_slide' ]; ?>,				  // Set starting Slide (0 index)
				directionNav: <?= $module_data[ 'params' ][ 'direction_nav' ]; ?>,			 // Next & Prev navigation
				controlNav: <?= $module_data[ 'params' ][ 'control_nav' ]; ?>,			   // 1,2,3... navigation
				controlNavThumbs: <?= $module_data[ 'params' ][ 'control_nav_thumbs' ]; ?>,		// Use thumbnails for Control Nav
				pauseOnHover: <?= $module_data[ 'params' ][ 'pause_on_hover' ]; ?>,			 // Stop animation while hovering
				manualAdvance: <?= $module_data[ 'params' ][ 'manual_advance' ]; ?>,		   // Force manual transitions
				prevText: '<?= $module_data[ 'params' ][ 'prev_text' ]; ?>',			   // Prev directionNav text
				nextText: '<?= $module_data[ 'params' ][ 'next_text' ]; ?>',			   // Next directionNav text
				randomStart: <?= $module_data[ 'params' ][ 'random_start' ]; ?>,			 // Start on a random slide
				beforeChange: function(){
					
					beforeChange_<?= $unique_module_hash; ?>();
					
				},	 // Triggers before a slide transition
				afterChange: function(){
					
					afterChange_<?= $unique_module_hash; ?>();
					
				},	  // Triggers after a slide transition
				slideshowEnd: function(){},	 // Triggers after all slides have been shown
				lastSlide: function(){},		// Triggers when last slide is shown
				afterLoad: function(){
					
					$( '#ns-<?= $unique_module_hash; ?>' ).parent().find( '.preload-wrapper' ).remove();
					
					beforeChange_<?= $unique_module_hash; ?>();
					afterChange_<?= $unique_module_hash; ?>();
					afterLoad_<?= $unique_module_hash; ?>();
					
				}		 // Triggers when slider has loaded
				
			});
			
		});
		
	</script>
	

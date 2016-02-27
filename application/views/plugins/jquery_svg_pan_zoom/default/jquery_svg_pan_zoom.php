<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

?>

<!--------------------------------------------
jQuery svg pan zoom plugin
-->

<script type="text/javascript">
	
	$( window ).load(function(){
		
		var options = {
			
			events: {
				mouseWheel: true, // enables mouse wheel zooming events
				doubleClick: true, // enables double-click to zoom-in events
				drag: true, // enables drag and drop to move the SVG events
				dragCursor: "move" // cursor to use while dragging the SVG
			},
			animationTime: 300, // time in milliseconds to use as default for animations. Set 0 to remove the animation
			zoomFactor: 0.25, // how much to zoom-in or zoom-out
			maxZoom: 3, //maximum zoom in, must be a number bigger than 1
			panFactor: 100, // how much to move the viewBox when calling .panDirection() methods
			initialViewBox: { // the initial viewBox, if null or undefined will try to use the viewBox set in the svg tag. Also accepts string in the format "X Y Width Height"
				x: 0, // the top-left corner X coordinate
				y: 0, // the top-left corner Y coordinate
				width: 1000, // the width of the viewBox
				height: 1000, // the height of the viewBox
			},
			limits: { // the limits in which the image can be moved. If null or undefined will use the initialViewBox plus 15% in each direction
				x: -150,
				y: -150,
				x2: 1150,
				y2: 1150,
			}
			
		}
		
		var svgPanZoom = $( 'svg.pan-zoom' ).svgPanZoom( options )
		/*
		$( document ).on( 'click', '#para', function(){
			
			alert( 'oi' );
			
		});
		*/
	});
	
</script>

<!--
jQuery svg pan zoom plugin
--------------------------------------------->

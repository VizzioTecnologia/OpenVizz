<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
	
	if ( ! $this->voutput->get_head_stylesheet( 'thumbs' ) AND file_exists( HELPERS_STYLES_PATH . DS . 'vui_elements'. DS . 'layouts'. DS . 'default'. DS . 'thumbs.css.php' ) ) {
		
		$this->voutput->append_head_stylesheet( 'thumbs', HELPERS_STYLES_URL . '/vui_elements/layouts/default/thumbs.css.php' );
		
	}
?>

<<?= $figure ? 'figure' : $wrappers_el_type; ?> class="thumb thumb-wrapper <?= $wrapper_class; ?>">
	
	<<?= $wrappers_el_type; ?> class="s1 inner">
		
		<<?= $wrappers_el_type; ?> class="s2">
			
			<?php if ( check_var( $href ) ){ ?>
			<a
				class="s3 <?= $modal ? 'thumb-modal' : ''; ?>"
				href="<?= $href; ?>"
				data-fancybox-href="<?= $href; ?>"
				data-fancybox-group="<?= $rel ? $rel : 'thumb-modal'; ?>"
				<?= element_title( $title ); ?>
				<?= $target ? 'target="' . $target . '"' : ''; ?>
				<?= $rel ? 'rel="' . $rel . '"' : ''; ?>>
				
			<?php }
			
				if ( $src AND $prevent_cache ) {
					
					$url_parts = parse_url( $src );
					
					if ( isset( $url_parts[ 'query' ] ) ) {
						
						parse_str( $url_parts[ 'query' ], $__get );
						
					}
					else {
						
						$__get = array();
						
					}
					
					$__get[ 'thumb_pc' ] = md5( uniqid( rand(), true ) );
					
					// Note that this will url_encode all values
					$url_parts[ 'query' ] = http_build_query( $__get );
					
					$src = $url_parts['scheme'] . '://' . $url_parts['host'] . $url_parts['path'] . '?' . $url_parts['query'];
					
				}
				
			?>
				
				<img 
					
					<?= $id ? 'id="' . $id . '"' : ''; ?>
					src="<?= strip_tags( $src ); ?>"
					alt="<?= strip_tags( $title ); ?>"
					class="<?= strip_tags( $class ); ?>"
					onload=""
					<?= $attr; ?>
					
				/>
				
			<?php if ( check_var( $href ) ){ ?>
			</a>
			<?php } ?>
			
		</<?= $wrappers_el_type; ?>>
		
	</<?= $wrappers_el_type; ?>>
	
	<?php if ( $text ){ ?>
		
		<<?= $figure ? 'figcaption' : 'div class="caption"'; ?>><?= $text; ?></<?= $figure ? 'figcaption' : 'div'; ?>>
		
	<?php } ?>
	
</<?= $figure ? 'figure' : $wrappers_el_type; ?>>

<?php if ( $modal ) { ?>
	
	<?php if ( $this->plugins->load( 'fancybox' ) AND ! defined( 'MODAL_THUMBS_ON' ) ){ ?>
	
	<?php define( 'MODAL_THUMBS_ON', TRUE ); ?>
	
	<script type="text/javascript" >
		
		$( document ).on( 'ready', function( e ){
			
			$( ".thumb-modal" ).fancybox({
				
			});
			
		});
		
	</script>
	
	<?php } ?>
	
<?php } ?>

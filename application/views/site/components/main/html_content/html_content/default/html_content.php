<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>

<section class="html-content <?= check_var( $params['page_class'] ) ? $params['page_class'] : ''; ?>">
	
	<?php if ( check_var( $params[ 'show_page_content_title' ] ) ) { ?>
	<header class="component-heading">
		<h1>
			<?= $this->mcm->html_data['content']['title']; ?>
		</h1>
	</header>
	<?php } ?>
	
	<div id="component-content">
		
		<?= $params[ 'html_content' ]; ?>
		
	</div>
	
</section>

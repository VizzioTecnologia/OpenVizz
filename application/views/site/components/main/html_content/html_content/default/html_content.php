<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>

<section class="html-content <?= @$params['page_class']; ?>">
	
	<?php if ( $params[ 'show_page_content_title' ] ) { ?>
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

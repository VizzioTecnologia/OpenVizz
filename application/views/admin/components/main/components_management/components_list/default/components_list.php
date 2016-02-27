		
		<header>
			<h1><?= lang('components'); ?></h1>
		</header>
		
		<div class="">
			<div class="dashboard-items-container">
				
				<?php foreach($components as $component) { ?>
				
				<?php if ( $component->title != 'Main' ) { ?>
					
				<a href="<?= get_url($component->admin_url); ?>" class="dashboard-item" <?= element_title( lang( $component->title ) . ( $component->description ? '\n' . lang( $component->description ) : '' ) ); ?> >
					
					<span class="dashboard-item-icon icon icon-<?= $component->unique_name; ?>"></span>
					
					<span class="dashboard-title"><?= lang( $component->title ); ?></span>
					
				</a>
				
				<?php }; ?>
				
				<?php }; ?>
				
			</div>
		</div>
		
		<header>
			<h1><?php echo lang('dashboard'); ?></h1>
		</header>
		
		<div class="">
			<div class="dashboard-items-container">
				
				<a href="<?= get_url( 'admin/responsive_file_manager/index/a/rfm' ); ?>" class="dashboard-item" <?= element_title( lang( 'file_manager' ) ); ?> >
					
					<span class="dashboard-item-icon icon icon-browse"></span>
					
					<span class="dashboard-title"><?= lang( 'file_manager' ); ?></span>
					
				</a>
				
				<a href="<?= get_url( 'admin/users/users_management/users_list' ); ?>" class="dashboard-item" <?= element_title( lang( 'users' ) ); ?> >
					
					<span class="dashboard-item-icon icon icon-users"></span>
					
					<span class="dashboard-title"><?= lang( 'users' ); ?></span>
					
				</a>
				
				<a href="<?= get_url( 'admin/menus/menu_types_management/menu_types_list' ); ?>" class="dashboard-item" <?= element_title( lang( 'menus' ) ); ?> >
					
					<span class="dashboard-item-icon icon icon-menus"></span>
					
					<span class="dashboard-title"><?= lang( 'menus' ); ?></span>
					
				</a>
				
				<a href="<?= get_url( 'admin/contacts/contacts_management/contacts_list' ); ?>" class="dashboard-item" <?= element_title( lang( 'contacts' ) ); ?> >
					
					<span class="dashboard-item-icon icon icon-contacts"></span>
					
					<span class="dashboard-title"><?= lang( 'contacts' ); ?></span>
					
				</a>
				
				<a href="<?= get_url( 'admin/articles/articles_management/articles_list' ); ?>" class="dashboard-item" <?= element_title( lang( 'articles' ) ); ?> >
					
					<span class="dashboard-item-icon icon icon-articles"></span>
					
					<span class="dashboard-title"><?= lang( 'articles' ); ?></span>
					
				</a>
				
				<a href="<?= get_url( 'admin/main/config_management/global_config' ); ?>" class="dashboard-item" <?= element_title( lang( 'global_config' ) ); ?> >
					
					<span class="dashboard-item-icon icon icon-config"></span>
					
					<span class="dashboard-title"><?= lang( 'global_config' ); ?></span>
					
				</a>
				
				<a href="<?= get_url( 'admin/main/index/logout' ); ?>" class="dashboard-item" <?= element_title( lang( 'logout' ) ); ?> >
					
					<span class="dashboard-item-icon icon icon-logout"></span>
					
					<span class="dashboard-title"><?= lang( 'logout' ); ?></span>
					
				</a>
				
			</div>
		</div>
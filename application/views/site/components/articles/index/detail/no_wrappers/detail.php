<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>

<?php if ( @$params['show_page_content_title'] ) { ?>

<h1>
	
	<?php if ( @$params['show_title_as_link_on_detail_view'] AND $article['title'] == @$this->mcm->html_data['content']['title'] ) { ?>
	<a href="<?= $article['url']; ?>"><?= html_entity_decode( @$this->mcm->html_data['content']['title'] ); ?></a>
	<?php } else if ( @$this->mcm->html_data['content']['title'] ) { ?>
	<?= html_entity_decode( @$this->mcm->html_data['content']['title'] ); ?>
	<?php } else { ?>
	<?= html_entity_decode( $article[ 'title' ] ); ?>
	<?php } ?>
	
</h1>

<?php } ?>

<?php if ( check_var( $article['params']['show_image_on_detail_view'] ) AND check_var( $article[ 'image' ] ) ) { ?>

<?php
	
	$thumb_params = array(
		
		'wrapper_class' => 'article-image-wrapper',
		'src' => get_url( $article[ 'image' ] ),
		'href' => ! check_var( $params[ 'show_image_as_link_on_detail_view' ] ) ? FALSE : ( ( check_var( $params[ 'detail_view_image_link_mode' ] ) AND $params[ 'detail_view_image_link_mode' ] == 'link_to_image' ) ? get_url( $article[ 'image' ] ) : $article[ 'url' ] ),
		'title' => $article[ 'title' ],
		
	);
	
	echo vui_el_thumb( $thumb_params );
	
?>

<?php } ?>

<?php if ( @$params['show_title_on_detail_view'] AND ( $article['title'] != @$this->mcm->html_data['content']['title'] ) ) { ?>
<h2>
	<?php if ( @$params['show_title_as_link_on_detail_view'] ) { ?>
	<a href="<?= $article['url']; ?>"><?= html_entity_decode( $article[ 'title' ] ); ?></a>
	<?php } else { ?>
	<?= html_entity_decode( $article[ 'title' ] ); ?>
	<?php } ?>
</h2>
<?php }; ?>

<?php if ( ( @$article['params']['show_article_category_on_detail_view'] AND @$article['category_title'] ) OR ( @$article['params']['show_created_by_on_list_view'] ) OR ( @$article['params']['show_created_date_on_detail_view'] ) ) { ?>

<?php if ( @$article['params']['show_article_category_on_detail_view'] AND @$article['category_title'] ) { ?>
<div class="content-info-item article-category">
	<?php if ( $article['params']['show_article_category_as_link_on_detail_view'] AND $article['category_url'] ) { ?>
	<a href="<?= $article['category_url']; ?>"><?= $article['category_title']; ?></a>
	<?php } else { ?>
	<?= $article['category_title']; ?>
	<?php } ?>
</div>
<?php } ?>

<?php if ( @$article['params']['show_created_by_on_detail_view'] ) { ?>
<div class="content-info-item article-info-created-by-wrapper">
	<div class="article-info-created-by">
		<?= $article['user_name']; ?>
	</div>
</div>
<?php } ?>

<?php if ( @$article['params']['show_created_date_on_detail_view'] ) { ?>
<div class="content-info-item article-info-created-date-wrapper">
	<div class="article-info-created-date">
		<?= created_date( $article['created_date'], lang( 'publish_time_format_1' ) ); ?>
	</div>
</div>
<?php } ?>

<?php } ?>

<?php if ( ( @$article['params']['show_introtext_on_detail_view'] AND @$article['introtext'] ) OR ( @$article['fulltext'] ) ) { ?>
	
		<?php if ( @$article['params']['show_introtext_on_detail_view'] AND @$article['introtext'] ) { ?>
			
			<?= html_entity_decode( $article[ 'introtext' ] ); ?>
			
		<?php } ?>
		
		<?php if ( @$article['fulltext'] ) { ?>
		
			<?= html_entity_decode( $article[ 'fulltext' ] ); ?>
			
		<?php }; ?>
		
<?php }; ?>

<?= $this->plugins->get_output( NULL, 'after_content' ); ?>

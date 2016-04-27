
<?php if ( $_title_field AND isset( $fields[ $key_2 ] ) ) { ?>
	
	<div class="user-submit-title-wrapper user-submit-alias-<?= url_title( $key_2 ); ?> user-submit-title-<?= url_title( $_title_field[ 'label' ] ); ?>" title="<?= ( $_tipo_de_centro == 'centro-filiado' ? 'Centro Filiado' : ( $_tipo_de_centro == 'centro-agregado' ? 'Centro Agregado' : ( $_tipo_de_centro == 'nao-renovado' ? 'Centro não Filiado' : '' ) ) ); ?>">
		
		<div class="tipo-centro tipo-centro-<?= $_tipo_de_centro; ?>" title="<?= ( $_tipo_de_centro == 'centro-filiado' ? 'Centro Filiado' : ( $_tipo_de_centro == 'centro-agregado' ? 'Centro Agregado' : ( $_tipo_de_centro == 'nao-renovado' ? 'Centro não Filiado' : '' ) ) ); ?>"></div>
		
		<h4 class="title"><?= $_title_field[ 'value' ]; ?></h4>
		
	</div>
	
<?php } ?>

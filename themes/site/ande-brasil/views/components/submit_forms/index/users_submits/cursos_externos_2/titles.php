
<?php if ( $_title_field AND isset( $fields[ $key_2 ] ) ) { ?>
	
	<div class="user-submit-title-wrapper user-submit-alias-<?= url_title( $key_2 ); ?> user-submit-title-<?= url_title( $_title_field[ 'label' ] ); ?>" title="<?= ( $_tipo_de_centro == 'centro-filiado' ? 'Centro Filiado' : ( $_tipo_de_centro == 'centro-agregado' ? 'Centro Agregado' : ( $_tipo_de_centro == 'nao-renovado' ? 'Centro não Filiado' : '' ) ) ); ?>">
		
		<div class="tipo-centro tipo-centro-<?= $_tipo_de_centro; ?>" title="<?= ( $_tipo_de_centro == 'centro-filiado' ? 'Centro Filiado' : ( $_tipo_de_centro == 'centro-agregado' ? 'Centro Agregado' : ( $_tipo_de_centro == 'nao-renovado' ? 'Centro não Filiado' : '' ) ) ); ?>"></div>
		
		<h4 class="title">
		<?php if ( $key_2 == 'site' ) { ?>
			
			<a target="_blank" href="<?= 'http://' . $_title_field[ 'value' ]; ?>"><?= $_title_field[ 'value' ]; ?></a>
			
		<?php } else if ( $key_2 == 'e-mail' ) { ?>
			
			<a href="<?= 'mailto:' . $_title_field[ 'value' ]; ?>"><?= $_title_field[ 'value' ]; ?></a>
			
		<?php } else { ?>
			
			<?= $_title_field[ 'value' ]; ?>
			
		<?php } ?>
		</h4>
		
	</div>
	
	<?php if ( $data_curso ) { ?>
		
		<div class="user-submit-title-wrapper user-submit-alias-data-do-curso user-submit-title-data-do-curso; ?>">
			
			<h6 class="title"><?= $data_curso; ?></h6>
			
		</div>
		
	<?php } ?>
	
<?php } ?>

<?php

header('Content-Type: application/pdf');
header('Content-Disposition: inline; '.filename);

$cs = $tender['params']['currency_symbol'];

$pmt = 185;
$pmr = 10;
$pmb = 10;
$pml = 10;

$fl = $pml;
$fr = $pmr;
$fb = $pmb;
$fh = 12; // footer height

$pmb = $pmb + $fh;

// adicionando as unidades
$pmt .= 'px';
$pmr .= 'mm';
$pmb .= 'mm';
$pml .= 'mm';

$fl .= 'mm';
$fr .= 'mm';
$fb .= 'mm';
$fh .= 'mm';

?>
<html>
	<head>
		<style>
		@page {
			size: auto;
			margin: <?= $pmt; ?> <?= $pmr; ?> <?= $pmb; ?> <?= $pml; ?>;
			margin-header:35px;
			margin-footer:<?= $fh; ?>;
			odd-header-name: html_myHeader1;
			even-header-name: html_myHeader1;
			odd-footer-name: html_myFooter1;
			even-footer-name: html_myFooter1;
		}
		@page chapter2 {
			odd-header-name: html_myHeader2;
			even-header-name: html_myHeader2;
			odd-footer-name: html_myFooter2;
			even-footer-name: html_myFooter2;
		}
		
		
		div.chapter2 {
			page-break-before: right;
			page: chapter2;
		}
		div.noheader {
			page-break-before: right;
			page: noheader;
		}
		
		body{
			font-family:gudea,"Arial",serif;
			color:#000;
		}
		table{
			width:100%;
			border-collapse:collapse;
			font-size:1px; /* Tem que ter este valor para equilibrar as centralizações verticais das células */
		}
		th{
			
		}
		tr{
			font-size:1px;
			border-collapse:collapse;
		}
		td{
			font-size:1px;
			border-collapse:collapse;
		}
		
		
		p{
			position:relative;
			display:block;
			margin: 0;
			padding: 0 0 4px 0;
		}
		/*
		#footer-content{
			font-size:10px;
			position:absolute;
			left:<?= $fl; ?>;
			right:<?= (int)$fr + 15; ?>mm;
			bottom:<?= $fb; ?>;
		}
		#footer-pagination{
			font-size:11px;
			position:absolute;
			right:<?= $fr; ?>;
			bottom:<?= $fb; ?>;
			background: #999;
			white-space: nowrap;
			width:auto;
			height:17px;
			padding: 5px 10px 0;
			text-align:center;
		}
		*/
		
		
		#footer-content{
			font-size:10px;
		}
		#footer-pagination{
			font-size:11px;
			white-space: nowrap;
			width:auto;
			text-align:center;
			vertical-align: bottom;
		}
		
		
		.header{
			text-align: right;
			font-size: 10pt;
			color:#000;
			padding-bottom:10px;
			border-bottom:0.3mm dotted #000;
		}
		
		td.header-logo{
			padding:0 15px 0 0;
			width:100px;
			vertical-align: top;
		}
		
		td.header-title{
			text-align:right;
			color:#000;
			padding:0 15px;
		}
		.header-title-main-title{
			position:relative;
			display:block;
			font-family:oswald, arial;
			font-size:25px;
			padding:100px;
		}
		.header-title-proposal-number{
			font-family:robotoslab, arial;
			font-size:20px;
		}
		.header-title-proposal-seller,
		.header-title-proposal-dates{
			font-family:robotocondensed,"Arial",serif;
			font-size:12px;
		}
		
		td.header-company-info{
			font-family:robotocondensed,"Arial",serif;
			text-align:left;
			color:#000;
			padding:0 15px;
		}
		.header-company-info-company-name,
		.header-company-info-address,
		.header-company-info-cnpj,
		.header-company-info-state-registration{
			font-size:12px;
		}
		
		td.header-company-contact{
			font-family:robotocondensed,"Arial",serif;
			text-align:right;
			color:#000;
			padding:0 0 0 15px;
			vertical-align:top;
		}
		.header-company-contact-phone,
		.header-company-contact-fax{
			font-size:18px;
			font-weight:bold;
		}
		.header-company-contact-email{
			font-size:12px;
		}
		.header-company-contact-website{
			font-size:12px;
		}
		
		
		
		.header-customer{
			text-align: right;
			font-size: 10pt;
			color:#000;
			padding:5px 5px 15px;
		}
		.header-customer table,
		.header-customer table tr,
		.header-customer table th,
		.header-customer table td{
			font-size: 10px;
			vertical-align: middle;
		}
		.header-customer td.customer-col-1{
			padding-right:10px;
			border-right:0.3mm dotted #000;
			width: auto;
			white-space: nowrap;
		}
		.header-customer td.customer-col-2,
		.header-customer td.customer-col-3,
		.header-customer td.customer-col-4{
			padding-left:10px;
			padding-right:10px;
			border-right:0.3mm dotted #000;
			width: auto;
			white-space: nowrap;
		}
		.header-customer td.customer-col-5{
			padding-left:10px;
			white-space: nowrap;
		}
		
		
		
		
		
		
		#conteudo-principal{
			width:100%;
			height:100%;
		}
		
		#conteudo-principal table{
			
		}
		#conteudo-principal th,
		#conteudo-principal tr,
		#conteudo-principal td{
			font-size:10px;
		}
		.content-table-header th,
		#conteudo-principal th{
			font-size:10px;
			background:#bbb;
			color:#000;
			padding:4px;
		}
		#conteudo-principal tr.odd{
			background:#ddd;
		}
		#conteudo-principal th,
		#conteudo-principal td{
			padding:4px;
		}
		#conteudo-principal td{
			border-top:0.3mm dotted #000;
			border-bottom:0.3mm dotted #000;
			border-right:0.3mm dotted #000;
		}
		#conteudo-principal td.total{
			border-right:none;
		}
		.number,
		.code,
		.unit,
		.warranty,
		.delivery-time,
		.delivery-time,
		.provider,
		.quantity,
		.total{
			text-align:center;
		}
		td.unit-price,
		td.total{
			text-align:left;
			font-weight:bold;
		}
		.number{
			white-space: nowrap;
			width:3%;
		}
		.code{
			white-space: nowrap;
			width:12%;
		}
		.description{
			
		}
		.unit{
			white-space: nowrap;
			width:4%;
		}
		.warranty{
			white-space: nowrap;
			width:7%;
		}
		.delivery-time{
			white-space: nowrap;
			width:5%;
		}
		.provider{
			white-space: nowrap;
			width:6%;
		}
		.unit-price{
			white-space: nowrap;
			width:9%;
		}
		.quantity{
			white-space: nowrap;
			width:4%;
		}
		.total{
			white-space: nowrap;
			width:9%;
		}
		
		
		tr.totals .col-1{
			text-align: right;
		}
		tr.totals .product-selling-price-total{
			text-align: left;
			font-weight: bold;
		}
		
		table#prov-conds{
			page-break-inside:avoid;
		}
		table#prov-conds td{
			text-align: center;
		}
		table#prov-conds td.table-header{
			text-align: left;
		}
		
		
		#conteudo-principal .last-col{
			border-right: none;
		}
		#conteudo-principal table td.table-header{
			font-size: 12px;
			font-weight: bold;
			border-right: none;
			padding:5px 0;
			color:#a73b00;
			border-top:none;
		}
		
		
		
		
		#tender-notes{
			padding:15px;
			font-size:11px;
		}
		.product-notes{
			font-size:9px;
		}
		
		
		.ta-center{
			text-align: center !important;
		}
		.no-border,
		#conteudo-principal table.no-border,
		#conteudo-principal tr.no-border,
		#conteudo-principal th.no-border,
		#conteudo-principal td.no-border,
		#conteudo-principal table tr.no-border,
		#conteudo-principal table th.no-border,
		#conteudo-principal table td.no-border{
			border: none !important;
			border-top: none !important;
			border-right: none !important;
			border-bottom: none !important;
			border-left: none !important;
		}
		.margin{
			font-size:4px;
		}
		.divisor{
			width: 100%;
			height:1px;
			border-bottom:0.3mm dotted #000;
		}
		.gray{
			color:#aaa;
		}
		.highlight{
			color:#a73b00;
		}
		.bold{
			font-weight:bold;
		}
		.spacer{
			height: 15px;
		}
		
		</style>
	</head>
	<body>
		
		<htmlpageheader name="myHeader1" style="display:none">
		<div class="header">
			<table>
				<tr>
					
					<td class="header-logo">
						<img style="height:65px;" src="<?= base_url(); ?>assets/images/components/vesm/tenders_management/tender_pdf/default/logo-pdf.svg"/>
					</td>
					
					<td class="header-title">
						<div class="header-title-main-title">
							Proposta de Orçamento
						</div>
						<div class="margin">&nbsp;</div>
						<div class="header-title-proposal-number highlight">
							N° <?= $tender['tender_code']; ?>
						</div>
						<div class="header-title-proposal-seller">
							Consultor: <span class="highlight bold">Fabiano Barcelos</span>
						</div>
						<div class="header-title-proposal-dates">
							
							<?= lang('date_tender_order'); ?>: <span class="highlight bold"><?= date( $tender['params'][ 'tender_date_format' ], strtotime( $tender[ 'date_tender_order' ] ) ); ?></span> - <?= lang('date_issue'); ?>: <span class="highlight bold"><?= date( $tender['params'][ 'tender_date_format' ], strtotime( $tender[ 'date_issue' ] ) ); ?></span>
							
						</div>
					</td>
					
					<td class="header-company-info">
						<div class="header-company-info-company-name">
							Via Serviços de Informática e Comunicação Visual LTDA ME
						</div>
						<div class="margin">&nbsp;</div>
						<div class="header-company-info-address">
							SMT, Conjunto 10, Lote 14, Casa 06, Sala 01 - Taguatinga Sul<br/>
							Brasília - DF - CEP 72023-450
						</div>
						<div class="margin">&nbsp;</div>
						<div class="header-company-info-cnpj">
							CNPJ - 10.286.315/0001-09
						</div>
						<div class="header-company-info-state-registration">
							Inscrição Estadual - 07.508.539/001-33
						</div>
					</td>
					
					<td class="header-company-contact">
						<div class="header-company-contact-phone">
							<span class="gray">61.</span>3536-6881
						</div>
						<div class="header-company-contact-fax">
							<span class="gray">(fax) 61.</span>3264-2626
						</div>
						<div class="margin">&nbsp;</div>
						<div class="header-company-contact-email">
							<span class="highlight">vendas@viaeletronica.com.br</span>
						</div>
						<div class="header-company-contact-website">
							<span class="highlight">www.viaeletronica.com.br</span>
						</div>
					</td>
					
				</tr>
			</table>
		</div>
		
		<div class="header-customer">
			<table>
				<tr>
					
					<td class="customer-col-1">
						<?= lang('customer'); ?>: <b><?= $tender['customer']['title']; ?></b><br/>
						<b><?= $tender['customer']['company_name']; ?></b>
					</td>
					
					<td class="customer-col-2">
						<?= lang('corporate_tax_register'); ?>: <b><?= $tender['customer']['corporate_tax_register']; ?></b><br/>
						<?= lang('ab_state_registration'); ?>: <b><?= $tender['customer']['state_registration']; ?></b>
					</td>
					
					<td class="customer-col-3">
						<?= $tender['address']['public_area_title'] ? $tender['address']['public_area_title'].', ' : ''; ?>
						<?= $tender['address']['complement'] ? $tender['address']['complement'].', ' : ''; ?>
						<?= $tender['address']['number'] ? lang('ab_number').' '.$tender['address']['number'].', ' : ''; ?>
						<?= $tender['address']['neighborhood_title'] ? $tender['address']['neighborhood_title'].' ' : ''; ?><br/>
						<?= $tender['address']['city_title'] ? $tender['address']['city_title'].' ' : ''; ?>
						<?= $tender['address']['state_acronym'] ? '- '.$tender['address']['state_acronym'].' ' : ''; ?>
						<?= $tender['address']['postal_code'] ? '- '.$tender['address']['postal_code'] : ''; ?>
					</td>
					
					<td class="customer-col-4">
						<?= lang('contact'); ?>: <b><?= $tender['contact']['name']; ?></b><br/>
						<?= lang('email'); ?>: <b><?= $tender['email']['email']; ?></b>
					</td>
					
					<td class="customer-col-5">
						<?= lang('phone'); ?>: <b><?= ($tender['phone']['area_code'] ? '('.$tender['phone']['area_code'].') ' : '') . $tender['phone']['number']; ?></b>
					</td>
					
				</tr>
			</table>
		</div>
		<!--
		<div class="content-table-header">
			
			<table width="100%">
				
				<tr>
					<th class="number">
						N°
					</th>
					
					<th class="code">
						Cód.
					</th>
					
					<th class="description">
						Descrição
					</th>
					
					<th class="unit">
						Unid.
					</th>
					
					<th class="warranty">
						Garantia
					</th>
					
					<th class="delivery-time">
						Entrega
					</th>
					
					<th class="provider">
						Fornecedor
					</th>
					
					<th class="unit-price">
						Valor unit.
					</th>
					
					<th class="quantity">
						Qtd.
					</th>
					
					<th class="total">
						Total
					</th>
					
				</tr>
				
			</table>
			
		</div>
		-->
		</htmlpageheader>
		
		<htmlpageheader name="myHeader2" style="display:none">
		<div class="header">
			<table width="100%">
				<tr>
					
					<td class="header-logo">
						<img style="height:65px;" src="<?= base_url(); ?>assets/images/components/vesm/tenders_management/tender_pdf/default/logo-pdf.svg"/>
					</td>
					
				</tr>
			</table>
		</div>
		</htmlpageheader>
		
		<htmlpagefooter name="myFooter1" style="display:none">
			
			<table>
				
				<tr>
					
					<td id="footer-content">
						<?= $tender['params']['tender_footer_content']; ?>
					</td>
					
					<td id="footer-pagination">
						
						{PAGENO}/{nbpg}
						
					</td>
					
				</tr>
				
			</table>
			
		</htmlpagefooter>
		
		<div id="conteudo-principal">
			
			<table id="products">
				
				<tr>
					<th class="number">
						<?= lang('ab_number_2'); ?>
					</th>
					
					<th class="code">
						<?= lang('ab_partnumber'); ?>
					</th>
					
					<th class="description">
						<?= lang('description'); ?>
					</th>
					
					<th class="unit">
						<?= lang('ab_product_unit'); ?>
					</th>
					
					<th class="warranty">
						<?= lang('warranty'); ?>
					</th>
					
					<th class="delivery-time">
						<?= lang('product_delivery_time'); ?>
					</th>
					
					<th class="provider">
						<?= lang('provider'); ?>
					</th>
					
					<th class="unit-price">
						<?= lang('short_product_selling_price_per_unit_2'); ?>
					</th>
					
					<th class="quantity">
						<?= lang('quantity'); ?>
					</th>
					
					<th class="total">
						<?= lang('total'); ?>
					</th>
					
				</tr>
				
				<?php
					
					$total_selling_price = 0;
					$row_class = '';
					
				?>
				
				<?php foreach ($tender['products'] as $key => $product) { ?>
				
				
				<?php
					
					$product['code'] = isset($product['code']) ? $product['code']:'';
					$product['title'] = isset($product['title']) ? $product['title']:'';
					$product['warranty'] = isset($product['warranty']) ? $product['warranty']: $tender['params']['default_products_warranty'];
					$product['quantity'] = isset($product['quantity']) ? $product['quantity']:1;
					$product['cost_price'] = isset($product['cost_price']) ? $product['cost_price']:0;
					$product['profit_factor'] = isset($product['profit_factor']) ? $product['profit_factor']: $tender['params']['default_products_warranty'];
					$product['ncm'] = isset($product['ncm']) ? $product['ncm']:'';
					$product['product_provider_tax'] = isset($product['product_provider_tax']) ? $product['product_provider_tax']:0;
					$product['tax_other'] = isset($product['tax_other']) ? $product['tax_other']:0;
					$product['company_tax'] = isset($product['company_tax']) ? $product['company_tax']:$tender['params']['company_tax'];
					
					$cost_price = $product['cost_price'];
					$profit_factor = $product['profit_factor'];
					$quantity = $product['quantity'];
					
					// lucro bruto 1
					$profit_1 = $profit_factor / 100 * $cost_price;
					
					// preço de venda
					$selling_price_per_unit = $cost_price + $profit_1;
					
					
					// incrementando os valores totais
					$total_selling_price += $selling_price_per_unit * $quantity;
					
				?>
				
				
				<tr class="<?= $row_class; ?>" width="100%">
					
					<td class="number">
						<?= $product['key']; ?>
					</td>
					
					<td class="code">
						<?= $product['code']; ?>
					</td>
					
					<td class="description">
						<?= nl2br($product['title']); ?>
						<?php if ( $product['notes'] ){ ?>
							<div class="product-notes">
								<b><?= lang('ab_notes'); ?>:</b> <?= $product['notes']; ?>
							</div>
							
						<?php } ?>
					</td>
					
					<td class="unit">
						<?= $product['unit']; ?>
					</td>
					
					<td class="warranty">
						<?= $product['warranty']; ?>
					</td>
					
					<td class="delivery-time">
						<?= $product['delivery_time']; ?>
					</td>
					
					<td class="provider">
						<?php
							foreach ($providers as $key => $provider) {
								if ( $provider['id'] == $product['provider_id'] )
									echo $provider['title'];
							}
							
						?>
					</td>
					
					<td class="unit-price">
						<?= $cs; ?> <?= number_format($selling_price_per_unit, 2, ',', '.'); ?>
					</td>
					
					<td class="quantity">
						<?= $product['quantity']; ?>
					</td>
					
					<td class="total">
						<?= $cs; ?> <?= number_format($selling_price_per_unit * $quantity, 2, ',', '.'); ?>
					</td>
					
				</tr>
				
				<?php
					
					$row_class = $row_class == '' ? 'odd' : '';
					
				?>
				
				<?php } ?>
				
				<tr class="totals <?= $row_class; ?>">
					<td class="col-1" colspan="9">
						<?= lang('total'); ?>:
					</td>
					<td class="product-selling-price-total last-col">
						 <?= $cs; ?> <?= number_format($total_selling_price, 2, ',', '.'); ?>
					</td>
				</tr>
				
			</table>
			
			<div class="spacer"></div>
			
			<table id="prov-conds" width="100%" autosize="1">
				
				<tr class="table-header">
					
					<td class="table-header" colspan="6">
						
						<?= lang('providers_and_conditions'); ?>
						
					</td>
					
				</tr>
				
				<tr class="">
					
					<th class="prov-cond-title" title="<?= lang('provider'); ?>">
						<?= lang('provider'); ?>
					</th>
					
					<th class="corporate-tax-register" title="<?= lang('corporate_tax_register'); ?>">
						<?= lang('corporate_tax_register'); ?>
					</th>
					
					<th class="prov-cond-freight-type" title="<?= lang('freight_type'); ?>">
							<?= lang('freight_type'); ?>
					</th>
					
					<th class="prov-conds-payment-conditions" title="<?= lang('payment_conditions'); ?>">
							<?= lang('ab_payment_conditions'); ?>
					</th>
					
					<th class="prov-conds-tender-validity" title="<?= lang('tender_validity'); ?>">
							<?= lang('validity'); ?>
					</th>
					
					<th class="prov-conds-notes" title="<?= lang('tender_validity'); ?>">
							<?= lang('notes'); ?>
					</th>
					
				</tr>
				
				<?php
					
					$row_class = 'even';
					
				?>
				
				<?php foreach ($tender['prov_conds'] as $key => $prov_cond) { ?>
				
				<?php
					
					$prov_cond['id'] = isset($prov_cond['id']) ? $prov_cond['id']:'';
					$prov_cond['title'] = isset($prov_cond['title']) ? $prov_cond['title']:'';
					$prov_cond['corporate_tax_register'] = isset($prov_cond['corporate_tax_register']) ? $prov_cond['corporate_tax_register']:'';
					$prov_cond['freight_type'] = isset($prov_cond['freight_type']) ? $prov_cond['freight_type']:'';
					$prov_cond['payment_conditions'] = isset($prov_cond['payment_conditions']) ? $prov_cond['payment_conditions']:'';
					$prov_cond['tender_validity'] = isset($prov_cond['tender_validity']) ? $prov_cond['tender_validity']:'';
					$prov_cond['notes'] = isset($prov_cond['notes']) ? $prov_cond['notes']:'';
					
				?>
				
				<tr class="<?= $row_class; ?>">
					
					<td class="prov-cond-title">
						
						<?= $prov_cond['title']; ?>
						
					</td>
					
					<td class="corporate-tax-register">
						
						<?= $prov_cond['corporate_tax_register']; ?>
						
					</td>
					
					<td class="prov-cond-freight-type">
						
						<?= $prov_cond['freight_type']; ?>
						
					</td>
					
					<td class="prov-conds-payment-conditions">
						
						<?= $prov_cond['payment_conditions']; ?>
						
					</td>
					
					<td class="prov-conds-tender-validity">
						
						<?= $prov_cond['tender_validity']; ?>
						
					</td>
					
					<td class="prov-conds-notes last-col">
						
						<?= $prov_cond['notes']; ?>
						
					</td>
					
				</tr>
				
				<?php
					
					$row_class = $row_class == 'even' ? 'odd' : 'even';
					
				?>
				
				<?php } ?>
				
			</table>
			
			<div class="spacer"></div>
			
			<table id="tender-notes">
				
				<tr>
					
					<td class="table-header last-col">
						
						<?= lang('notes'); ?>
						
					</td>
					
				</tr>
				
			</table>
			
			<div id="tender-notes">
				
				<?= $tender['params']['tender_notes_content']; ?>
				
			</div>
			
		</div>
		
		<!--
		<div class="chapter2">
			texto do capítulo 2
		</div>
		-->
		
	</body>
</html>
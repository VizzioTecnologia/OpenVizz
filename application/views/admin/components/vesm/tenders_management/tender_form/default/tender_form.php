
<?php
	
	$this->plugins->load( NULL, array( 'js_text_editor', 'js_time_picker' ) );
	$this->plugins->load( array( 'fancybox', 'modal_contacts' ) );
	
	$date_issue_time = ( @$tender[ 'date_issue' ] ) ? strtotime( $tender[ 'date_issue' ] ) : gmt_to_local( now(), $this->mcm->filtered_system_params[ 'time_zone' ], $this->mcm->filtered_system_params[ 'dst' ] );
	$date_issue = $this->input->post( 'date_issue' ) ? $this->input->post( 'date_issue' ) : strftime( '%Y-%m-%d', $date_issue_time );
	
	$date_tender_order_time = ( @$tender[ 'date_tender_order' ] ) ? strtotime( $tender[ 'date_tender_order' ] ) : gmt_to_local( now(), $this->mcm->filtered_system_params[ 'time_zone' ], $this->mcm->filtered_system_params[ 'dst' ] );
	$date_tender_order = $this->input->post( 'date_tender_order' ) ? $this->input->post( 'date_tender_order' ) : strftime( '%Y-%m-%d', $date_tender_order_time );
	
	$cs = $tender['params']['currency_symbol'];
	
?>

<div id="global-config-form-wrapper" class="form-wrapper tabs-wrapper">
	
	<div class="form-wrapper-sub tabs-children">
		
		<?= form_open( get_url( 'admin'.$this->uri->ruri_string() ), array( 'id' => 'tender-form', 'class' => 'ajax' ) ); ?>
			
			<div class="form-actions to-toolbar">
				
				<?= vui_el_button( array( 'text' => lang( 'action_save' ), 'icon' => 'save', 'button_type' => 'button', 'type' => 'submit', 'name' => 'submit', 'id' => 'submit', 'only_icon' => TRUE, 'form' => 'tender-form', ) ); ?>
				
				<?= vui_el_button( array( 'text' => lang( 'action_apply' ), 'icon' => 'apply', 'button_type' => 'button', 'type' => 'submit', 'name' => 'submit_apply', 'id' => 'submit-apply', 'only_icon' => TRUE, 'form' => 'tender-form', ) ); ?>
				
				<?= vui_el_button( array( 'text' => lang( 'preview_tender_pdf' ), 'icon' => 'pdf', 'button_type' => 'button', 'type' => 'submit', 'name' => 'submit_preview_tender_pdf', 'id' => 'submit-preview-tender-pdf', 'only_icon' => TRUE, 'form' => 'tender-form', ) ); ?>
				
				<?= vui_el_button( array( 'text' => lang( 'action_cancel' ), 'icon' => 'cancel', 'button_type' => 'button', 'type' => 'submit', 'name' => 'submit_cancel', 'id' => 'submit-cancel', 'only_icon' => TRUE, 'form' => 'tender-form', ) ); ?>
				
			</div>
			
			<header class="form-header tabs-header">
				
				<h1><?= $tender['title']; ?></h1>
				
				<div class="tender-information">
					
					<div class="tender-information-code">
						
						<?= lang('ab_number'); ?>
						<span class="tender-information-code-code">
							<?= $tender['tender_code']; ?>
						</span>
						
					</div>
					
					<div class="tender-information-creation-datetime">
						
						<?= lang('created_in'); ?>
						<span class="tender-information-creation-date">
							<?= date( $tender['params'][ 'tender_date_format' ], strtotime($tender['date_creation'] ) ); ?>
						</span>
						
						<?= lang('time_at'); ?>
						<span class="tender-information-creation-time">
							<?= date("H:i:s", strtotime($tender['date_creation'])); ?>
						</span>
						
					</div>
					
				</div>
				
			</header>
			
			<div class="form-items tabs-items">
				
				<div class="form-item">
					
					<fieldset>
						
						<legend>
							
							<?= vui_el_button( array( 'text' => lang( 'basic_details' ), 'icon' => 'basic-details',  ) ); ?>
							
						</legend>
						
						<?= form_error('tender_status', '<div class="msg-inline-error">', '</div>'); ?>
						<?= form_label(lang('tender_status')); ?>
						
						<?php if ($tenders_status){ ?>
						
						<?php
							foreach($tenders_status as $row):
								$options[$row['id']] = $row['title'];
							endforeach;
							asort($options);
						?>
						
						<?= form_dropdown('status_id', $options, isset($tender['status_id']) ? $tender['status_id'] : '','id="tender-status" class="tender-status"'); ?>
						
						<?php $options = array(); ?>
						
						<?php } ?>
						
						
						<?= form_error('date_tender_order', '<div class="msg-inline-error">', '</div>'); ?>
						<?= form_label(lang('date_tender_order')); ?>
						<?= form_input(array('id'=>'date-tender-order','name'=>'date_tender_order', 'class' => 'date-tender-order date'), isset( $date_tender_order ) ? $date_tender_order : '' ); ?>
						
						<?= form_label(lang('date_issue')); ?>
						<?= form_input(array('id'=>'date-issue', 'name'=>'date_issue', 'class' => 'date-issue date'), isset( $date_issue ) ? $date_issue : ''); ?>
						
						<?= form_error('title', '<div class="msg-inline-error">', '</div>'); ?>
						<?= form_label(lang('title')); ?>
						<?= form_input(array('id'=>'title','name'=>'title'),isset($tender['title'])?$tender['title']:'','autofocus'); ?>
						
					</fieldset>
					
				</div>
				
				<div class="form-item">
					
					<fieldset>
						
						<legend>
							
							<?= vui_el_button( array( 'text' => lang( 'customer' ), 'icon' => 'customers',  ) ); ?>
							
						</legend>
						
						<div id="customers-categories-wrapper" class="vui-field-wrapper-inline">
							
							<div class="customers-fields">
								
								<?= form_error('customers_category_id', '<div class="msg-inline-error">', '</div>'); ?>
								<?= form_label(lang('customers_category')); ?>
								<?php
								
								foreach($customers_categories as $row):
									$options[$row['id']] = $row['indented_title'];
								endforeach;
								asort($options);
								?>
								
								<?php if ($customers_categories){ ?>
								<?= form_dropdown('customers_category_id', $options, isset($tender['customers_category_id']) ? $tender['customers_category_id'] : 0,'id="customers-category" class="customers-category"'); ?>
								<?php $options = array(); ?>
								
								<?php } ?>
								
							</div>
							
						</div>
						
						<?php
							
							// definimos aqui os valores do campo de clientes, pois usaremos também mais a frente no javascript
							$customer_field_id = 'customer';
							$customer_field_class = 'customer';
							$customer_field_name = 'customer_id';
							
						?>
						
						<div id="customers-wrapper" class="vui-field-wrapper-inline">
							
							<div id="customers-fields">
								
								<?php if ( isset($customers) AND $customers ){ ?>
								
								<?= form_error('customer_id', '<div class="msg-inline-error">', '</div>'); ?>
								<?= form_label(lang('customer')); ?>
								
								<?php
									foreach($customers as $row):
										$options[$row['id']] = $row['title'];
									endforeach;
									asort($options);
								?>
								<?= form_dropdown($customer_field_name, $options, isset($tender['customer_id']) ? $tender['customer_id'] : '','id="' . $customer_field_id . '" class="' . $customer_field_class . '"'); ?>
								<?php $options = array(); ?>
								
								<?php } ?>
								
							</div>
							
						</div>
						
						<div class="clear"></div>
						
						<div id="customer-information-wrapper" class="vui-field-wrapper-inline">
							
							<?php if ( isset($tender['customer']) AND $tender['customer'] AND is_array($tender['customer']) ){ ?>
							
							<span class="vesm-tender-information-item">
								<b><?= lang('trading_name'); ?>:</b>
								<span id="trading-name"><?= $tender['customer']['trading_name'] ? $tender['customer']['trading_name'] : lang('not_informed'); ?></span>
							</span>
							
							<span class="vesm-tender-information-item">
								<b><?= lang('company_name'); ?>:</b>
								<span id="company-name"><?= $tender['customer']['company_name'] ? $tender['customer']['company_name'] : lang('not_informed'); ?></span>
							</span>
							
							<span class="vesm-tender-information-item">
								<b><?= lang('state_registration'); ?>:</b>
								<span id="state-registration"><?= $tender['customer']['state_registration'] ? $tender['customer']['state_registration'] : lang('not_informed'); ?></span>
							</span>
							
							<span class="vesm-tender-information-item">
								<b><?= lang('corporate_tax_register'); ?>:</b>
								<span id="corporate-tax-register"><?= $tender['customer']['corporate_tax_register'] ? $tender['customer']['corporate_tax_register'] : lang('not_informed'); ?></span>
							</span>
							
							<span class="vesm-tender-information-item">
								<b><?= lang('sic'); ?>:</b>
								<span id="sic"><?= $tender['customer']['sic'] ? $tender['customer']['sic'] : lang('not_informed'); ?></span>
							</span>
							
							<?php } ?>
							
						</div>
						
						
						<div id="customer-fields-ajax" class="vui-field-wrapper-inline info-wrapper">
							
							
							
						</div>
						
						<?php if ( isset($tender['customer']['contacts']) AND $tender['customer']['contacts'] ){ ?>
						
						<div id="contacts-wrapper" class="vui-field-wrapper-inline">
							
							<div id="contacts-elements">
								
								<?= form_error('contact_id', '<div class="msg-inline-error">', '</div>'); ?>
								<?= form_label(lang('contact')); ?>
								<div id="contact-photo-thumb">
									<div id="contact-photo-preloader">
										
									</div>
									<div class="photo-thumb">
										<?php
											foreach($tender['customer']['contacts'] as $row):
												$options[$row['id']] = $row['name'];
											endforeach;
											asort($options);
											
										?>
										<?php if ( isset($tender['contact']) AND $tender['contact']['thumb_local'] ) { ?>
										<img src="<?= $tender['contact']['thumb_local']; ?>" />
										<?php } ?>
									</div>
								</div>
								<?= form_dropdown('contact_id', $options, isset($tender['contact_id']) ? $tender['contact_id'] : '','id="contacts" class="contacts"'); ?>
								<?php $options = array(); ?>
								
							</div>
							
							<?php if ( isset($tender['contact']) AND $tender['contact'] AND is_array($tender['contact']) ){ ?>
							<p id="contact-information">
								
								<span class="vesm-tender-information-item">
									<b><?= lang('name'); ?>:</b>
									<span id="name"><?= $tender['contact']['name'] ? $tender['contact']['name'] : lang('not_informed'); ?></span>
								</span>
								
								<span class="vesm-tender-information-item">
									<b><?= lang('birthday_date'); ?>:</b>
									<span id="birthday-date"><?= $tender['contact']['birthday_date'] ? $tender['contact']['birthday_date'] : lang('not_informed'); ?></span>
								</span>
								
							</p>
							<?php } ?>
							
						</div>
						
						<?php } ?>
						
						<?php if ( isset($tender['phones_options']) AND $tender['phones_options'] AND is_array($tender['phones_options']) ){ ?>
						<div id="phones-wrapper" class="vui-field-wrapper-inline">
							
							<div id="phones-preloader"></div>
							
							<div id="phones-elements">
								
								<?= form_error('phone_key', '<div class="msg-inline-error">', '</div>'); ?>
								<?= form_label(lang('phone')); ?>
								<?= form_dropdown('phone_key', $tender['phones_options'], isset($tender['phone_key']) ? $tender['phone_key'] : '','id="phones" class="phones-combobox"'); ?>
								<?php $options = array(); ?>
								
							</div>
							
						</div>
						<?php } ?>
						
						<?php if ( isset($tender['emails_options']) AND $tender['emails_options'] AND is_array($tender['emails_options']) ){ ?>
						<div id="emails-wrapper" class="vui-field-wrapper-inline">
							
							<div id="emails-preloader"></div>
							
							<div id="emails-elements">
								
								<?= form_error('email_key', '<div class="msg-inline-error">', '</div>'); ?>
								<?= form_label(lang('email')); ?>
								<?= form_dropdown('email_key', $tender['emails_options'], isset($tender['email_key']) ? $tender['email_key'] : '','id="emails" class="emails-combobox"'); ?>
								<?php $options = array(); ?>
								
							</div>
							
						</div>
						<?php } ?>
						
						<?php if ( isset($tender['addresses_options']) AND $tender['addresses_options'] AND is_array($tender['addresses_options']) ){ ?>
						<div id="addresses-wrapper" class="vui-field-wrapper-inline">
							
							<div id="addresses-preloader"></div>
							
							<div id="addresses-elements">
								
								<?= form_error('address_key', '<div class="msg-inline-error">', '</div>'); ?>
								<?= form_label(lang('address')); ?>
								<?= form_dropdown('address_key', $tender['addresses_options'], isset($tender['address_key']) ? $tender['address_key'] : '','id="addresses" class="addresses-combobox"'); ?>
								<?php $options = array(); ?>
								
							</div>
							
							<?php if ( isset($tender['address']) AND $tender['address'] AND is_array($tender['address']) ){ ?>
							<p id="address-information">
								
								<span class="vesm-tender-information-item">
									<b><?= lang('public_area'); ?>:</b>
									<span id="address-public-area"><?= $tender['address']['public_area_title'] ? $tender['address']['public_area_title'] : lang('not_informed'); ?></span>
								</span>
								
								<span class="vesm-tender-information-item">
									<b><?= lang('complement'); ?>:</b>
									<span id="address-complement"><?= $tender['address']['complement'] ? $tender['address']['complement'] : lang('not_informed'); ?></span>
								</span>
								
								<span class="vesm-tender-information-item">
									<b><?= lang('number'); ?>:</b>
									<span id="address-number"><?= $tender['address']['number'] ? $tender['address']['number'] : lang('not_informed'); ?></span>
								</span>
								
								<span class="vesm-tender-information-item">
									<b><?= lang('neighborhood'); ?>:</b>
									<span id="address-neighborhood"><?= $tender['address']['neighborhood_title'] ? $tender['address']['neighborhood_title'] : lang('not_informed'); ?></span>
								</span>
								
								<span class="vesm-tender-information-item">
									<b><?= lang('postal_code'); ?>:</b>
									<span id="address-postal-code"><?= $tender['address']['postal_code'] ? $tender['address']['postal_code'] : lang('not_informed'); ?></span>
								</span>
								
								<span class="vesm-tender-information-item">
									<b><?= lang('city'); ?>:</b>
									<span id="address-city"><?= $tender['address']['city_title'] ? $tender['address']['city_title'] : lang('not_informed'); ?></span>
								</span>
								
								<span class="vesm-tender-information-item">
									<b><?= lang('state'); ?>:</b>
									<span id="address-state-acronym"><?= $tender['address']['state_acronym'] ? $tender['address']['state_acronym'] : lang('not_informed'); ?></span>
								</span>
								
							</p>
							<?php } ?>
							
						</div>
						<?php } ?>
						
					</fieldset>
					
				</div>
				
				<div class="form-item">
					
					<fieldset>
						
						<legend>
							
							<?= vui_el_button( array( 'text' => lang( 'parameters' ), 'icon' => 'config',  ) ); ?>
							
						</legend>
						
						<?php
						
						/* gerando o html dos parâmetros, ele deve ser chamado na view, não no controller,
						 * pois os erros de validação dos elementos dos parâmetros devem ser expostos
						 * após a chamada da função $this->form_validation->run()
						 */
						
						echo params_to_html( $params_spec, $final_params_values );
						
						?>
						
					</fieldset>
					
				</div>
				
				<div class="form-item">
					
					<fieldset>
						
						<legend>
							
							<?= vui_el_button( array( 'text' => lang( 'products' ), 'icon' => 'products',  ) ); ?>
							
						</legend>
						
						<input type="text" id="filter" placeholder="<?= lang('live_filter'); ?>" class="live-filter" data-live-filter-for="table#products tr.product-row" ></input>
						
						<?php if ( $tender['products'] ){ ?>
						<table id="products" class="products">
							
							
							<tr class="product-row-headers">
								
								<th class="product-key" title="<?= lang('order'); ?>">
									<?= lang('ab_order'); ?>
								</th>
								
								<th class="product-code" title="<?= lang('product_code'); ?>">
									<?= lang('ab_product_code'); ?>
								</th>
								
								<th class="product-title" title="<?= lang('product_title'); ?>">
									<?= lang('product_title'); ?>
								</th>
								
								<th class="product-warranty" title="<?= lang('product_warranty'); ?>">
									<?= lang('ab_product_warranty'); ?>
								</th>
								
								<th class="product-provider" title="<?= lang('product_provider'); ?>">
									<?= lang('ab_product_provider'); ?>
								</th>
								
								<th class="product-quantity" title="<?= lang('product_quantity'); ?>">
									<?= lang('ab_product_quantity'); ?>
								</th>
								
								<th class="product-selling-price-per-unit" title="<?= lang('product_selling_price_per_unit'); ?>">
									<?= lang('ab_product_selling_price_per_unit'); ?>
								</th>
								
								<th class="product-total" title="<?= lang('product_total'); ?>">
									<?= lang('ab_product_total'); ?>
								</th>
								
								<th class="operations" title="<?= lang('operations'); ?>">
									<?= lang('ab_operations'); ?>
								</th>
								
							</tr>
							
							
							<?php
								
								$total_cost_price = 0;
								$total_selling_price = 0;
								$total_profit_4 = 0;
								$row_class = 'even';
								
							?>
							
							<?php foreach ($tender['products'] as $key => $product) { ?>
							
							<?php
								
								$product['code'] = isset($product['code']) ? $product['code']:'';
								$product['code_on_provider'] = isset($product['code_on_provider']) ? $product['code_on_provider']:'';
								$product['delivery_time'] = isset($product['delivery_time']) ? $product['delivery_time']: $tender['params']['default_delivery_time'];
								$product['title'] = isset($product['title']) ? $product['title']:'';
								$product['warranty'] = isset($product['warranty']) ? $product['warranty']:  $tender['params']['default_products_warranty'];
								$product['quantity'] = isset($product['quantity']) ? $product['quantity']:1;
								$product['cost_price'] = isset($product['cost_price']) ? $product['cost_price']:0;
								$product['profit_factor'] = isset($product['profit_factor']) ? $product['profit_factor']: $tender['params']['product_profit_factor'];
								$product['unit'] = isset($product['unit']) ? $product['unit']: $tender['params']['default_product_unit'];
								$product['mcn'] = isset($product['mcn']) ? $product['mcn']:'';
								$product['product_provider_tax'] = isset($product['product_provider_tax']) ? $product['product_provider_tax']:0;
								$product['tax_other'] = isset($product['tax_other']) ? $product['tax_other']:0;
								$product['company_tax'] = isset($product['company_tax']) ? $product['company_tax']:$tender['params']['company_tax'];
								$product['external_url'] = isset($product['external_url']) ? $product['external_url']:'';
								$product['notes'] = isset($product['notes']) ? $product['notes']:'';
								$product['internal_notes'] = isset($product['internal_notes']) ? $product['internal_notes']:'';
								
								$cost_price = $product['cost_price'];
								$profit_factor = $product['profit_factor'];
								$tax_other = $product['tax_other'];
								$provider_tax = $product['product_provider_tax'];
								$company_tax = $product['company_tax'];
								$quantity = $product['quantity'];
								
								// lucro bruto 1
								$profit_1 = $profit_factor / 100 * $cost_price;
								
								// preço de venda
								$selling_price_per_unit = $cost_price + $profit_1;
								
								// protocolo 21
								$tax_other_value = $selling_price_per_unit * $tax_other / 100;
								
								// lucro bruto 2
								$profit_2 = $selling_price_per_unit - $cost_price - $tax_other_value;
								
								// imposto da empresa
								$provider_tax_value = $profit_2 * $provider_tax / 100;
								
								// lucro bruto 3
								$profit_3 = $profit_2 - $provider_tax_value;
								
								// imposto da empresa
								$company_tax_value = $profit_3 * $company_tax / 100;
								
								// lucro líquido
								$profit_4 = $profit_3 - $company_tax_value;
								
								
								// incrementando os valores totais
								$total_cost_price += $cost_price * $quantity;
								$total_selling_price += $selling_price_per_unit * $quantity;
								$total_profit_4 += $profit_4 * $quantity;
								
							?>
							
							<tr class="product-row <?= $row_class; ?>" data-key="<?= $key; ?>">
								
								<td class="product-key">
									
									<div class="field-wrapper">
										<input type="hidden" name="<?= 'products['.$key.'][key]'; ?>" value="<?= $key; ?>" class="key" />
										<span class="content"><?= $key; ?></span>
									</div>
									
								</td>
								
								<td class="product-code">
									
									<div class="field-wrapper">
										<?= form_input(array('id'=>'product-code-'.$key,'name'=>'products['.$key.'][code]','class'=>'product-code filter-me', 'title'=>lang('tip_product_code')), $product['code']); ?>
									</div>
									
								</td>
								
								<td class="product-title">
									
									<div class="field-wrapper">
										<textarea name="<?= 'products['.$key.'][title]'; ?>" id="<?= 'product-title-'.$key; ?>" class="product-title filter-me" title="<?= lang('tip_product_title'); ?>"><?= $product['title']; ?></textarea>
									</div>
									
								</td>
								
								<td class="product-warranty">
									
									<div class="field-wrapper">
										
										<?php if ($warranties){ ?>
										
										<?php
											foreach($warranties as $row):
												$options[$row['title']] = $row['title'];
											endforeach;
											natsort($options);
										?>
										<?= form_dropdown('products['.$key.'][warranty]', $options, $product['warranty'] ? $product['warranty'] : $tender['params']['default_products_warranty'],'id="product-warranty-'.$key.'" class="product-warranty filter-me"'); ?>
										
										<?php $options = array(); ?>
										
										<?php } ?>
										
									</div>
									
								</td>
								
								<td class="product-provider">
									
									<div class="field-wrapper">
										
										<?php if ($providers){ ?>
										
										<?php
											foreach($providers as $row):
												$options[$row['id']] = $row['title'];
											endforeach;
											asort($options);
										?>
										<?= form_dropdown('products['.$key.'][provider_id]', $options, isset($product['provider_id']) ? $product['provider_id'] : '','id="product-provider-id-'.$key.'" class="product-provider-id filter-me"'); ?>
										
										<?php $options = array(); ?>
										
										<?php } ?>
										
									</div>
									
								</td>
								
								<td class="product-quantity">
									
									<div class="field-wrapper">
										<?= form_input_number(array('id'=>'product-quantity-'.$key,'name'=>'products['.$key.'][quantity]','class'=>'product-quantity filter-me', 'title'=>lang('tip_product_quantity')), $product['quantity']); ?>
									</div>
									
								</td>
								
								<td class="product-selling-price">
									
									<div class="field-wrapper">
										<span class="product-selling-price-per-unit value filter-me" data-value="<?= $selling_price_per_unit; ?>"><?= $cs; ?> <?= number_format($selling_price_per_unit, 2, ',', '.'); ?></span>
									</div>
									
								</td>
								
								<td class="product-total">
									
									<div class="field-wrapper">
										<span class="product-selling-price-total value filter-me" data-value="<?= $selling_price_per_unit * $quantity; ?>"><?= $cs; ?> <?= number_format($selling_price_per_unit * $quantity, 2, ',', '.'); ?></span>
									</div>
									
								</td>
								
								<td class="product-operations">
									
									<div class="field-wrapper">
										
										<?= vui_el_button( array( 'text' => lang( 'remove_product' ), 'icon' => 'remove', 'only_icon' => TRUE, 'button_type' => 'button', 'type' => 'submit', 'name' => 'submit_remove_product[' . $key . ']', 'class' => 'btn-delete-product', 'id' => 'submit-remove-product-' . $key, ) ); ?>
										
									</div>
									
								</td>
								
							</tr>
							
							<tr class="secondary-fields <?= $row_class; ?>" data-key="<?= $key; ?>">
								
								<td colspan="9">
									
									<div class="vui-field-wrapper-inline">
										
										<?= vui_el_checkbox( array( 'title' => 'tip_update_product', 'text' => 'update_product', 'value' => 'update_product', 'name' => 'products[' . $key . '][update_product]', 'class' => 'multi-selection-action', 'checked' => isset( $product[ 'update_product' ] ) ? TRUE : FALSE ) ); ?>
										
										<?= vui_el_checkbox( array( 'title' => 'tip_dont_update_blank_fields', 'text' => 'dont_update_blank_fields', 'value' => 'dont_update_blank_fields', 'name' => 'products[' . $key . '][dont_update_blank_fields]', 'class' => 'multi-selection-action', 'checked' => isset( $product[ 'dont_update_blank_fields' ] ) ? TRUE : FALSE ) ); ?>
										
									</div>
									
									<div class="vui-field-wrapper-inline">
										
										<?= form_error('products['.$key.'][origin_state_id]', '<div class="msg-inline-error">', '</div>'); ?>
										<?= form_label(lang('product_origin_state')); ?>
										
										<?php if ($states){ ?>
										
										<?php
											foreach($states as $row):
												$options[$row['id']] = $row['acronym'].' - '.$row['title'];
											endforeach;
											asort($options);
										?>
										
										<?= form_dropdown('products['.$key.'][origin_state_id]', $options, isset($product['origin_state_id']) ? $product['origin_state_id'] : '','id="product-origin-state-'.$key.'" class="product-origin-state"'); ?>
										
										<?php $options = array(); ?>
										
										<?php } ?>
										
									</div>
									
									<div class="vui-field-wrapper-inline">
										
										<?= form_label(lang('product_cost_price_per_unit')); ?>
										<span class="input-near-complement"><?= $cs; ?></span>
										<?= form_input(array('id'=>'product-cost-price-'.$key,'name'=>'products['.$key.'][cost_price]','class'=>'product-cost-price input-float-filter', 'title'=>lang('tip_product_cost_price')), $product['cost_price']); ?>
										
									</div>
									
									<div class="vui-field-wrapper-inline">
										
										<?= form_label(lang('product_profit_factor')); ?>
										<?= form_input(array('id'=>'product-profit-factor-'.$key,'name'=>'products['.$key.'][profit_factor]','class'=>'product-profit-factor input-float-filter', 'title'=>lang('tip_product_profit_factor')), $product['profit_factor']); ?>
										
									</div>
									
									<div class="vui-field-wrapper-inline">
										<?= form_label(lang('product_unit')); ?>
										<?= form_input(array('id'=>'product-unit-'.$key,'name'=>'products['.$key.'][unit]','class'=>'product-unit', 'title'=>lang('tip_product_unit')), $product['unit']); ?>
									</div>
									
									<div class="vui-field-wrapper-inline">
										<?= form_label(lang('product_mcn')); ?>
										<?= form_input(array('id'=>'product-mcn-'.$key,'name'=>'products['.$key.'][mcn]','class'=>'product-mcn', 'title'=>lang('tip_product_mcn')), $product['mcn']); ?>
									</div>
									
									<div class="vui-field-wrapper-inline">
										
										<?= form_label(lang('product_provider_tax')); ?>
										<?= form_input(array('id'=>'product-provider-tax-'.$key,'name'=>'products['.$key.'][product_provider_tax]','class'=>'product-provider-tax input-float-filter', 'title'=>lang('tip_product_provider_tax')), $product['product_provider_tax']); ?>
										
									</div>
									
									<div class="vui-field-wrapper-inline">
										
										<?= form_label(lang('company_tax')); ?>
										<?= form_input(array('id'=>'company-tax-'.$key,'name'=>'products['.$key.'][company_tax]','class'=>'company-tax input-float-filter', 'title'=>lang('tip_company_tax')), $product['company_tax']); ?>
										
									</div>
									
									<div class="vui-field-wrapper-inline">
										
										<?= form_label(lang('product_tax_other')); ?>
										<?= form_input(array('id'=>'product-tax-other-'.$key,'name'=>'products['.$key.'][tax_other]','class'=>'product-tax-other input-float-filter', 'title'=>lang('tip_product_tax_other')), $product['tax_other']); ?>
										
									</div>
									
									<div class="vui-field-wrapper-inline">
										
										<?= form_label(lang('code_on_provider')); ?>
										<?= form_input(array('id'=>'product-code-on-provider-'.$key,'name'=>'products['.$key.'][code_on_provider]','class'=>'product-code-on-provider', 'title'=>lang('tip_tender_product_code_on_provider')), $product['code_on_provider']); ?>
										
									</div>
									
									<div class="vui-field-wrapper-inline">
										
										<?= form_label(lang('delivery_time')); ?>
										<?= form_input(array('id'=>'product-delivery-time-'.$key,'name'=>'products['.$key.'][delivery_time]','class'=>'product-delivery-time', 'title'=>lang('tip_provider_delivery_time')), $product['delivery_time']); ?>
										
									</div>
									
									<div class="vui-field-wrapper-inline">
										
										<?= form_label(lang('external_url')); ?>
										<?= form_input(array('id'=>'product-external-url-'.$key,'name'=>'products['.$key.'][external_url]','class'=>'product-external-url', 'title'=>lang('tip_tender_product_external_url')), $product['external_url']); ?>
										
									</div>
									
									<div class="vui-field-wrapper-inline">
										
										<?= form_label(lang('tender_product_notes')); ?>
										<?= form_input(array('id'=>'product-notes-'.$key,'name'=>'products['.$key.'][notes]','class'=>'product-notes', 'title'=>lang('tip_tender_product_notes')), $product['notes']); ?>
										
									</div>
									
									<div class="vui-field-wrapper-inline">
										
										<?= form_label(lang('tender_product_internal_notes')); ?>
										<?= form_input(array('id'=>'product-internal-notes-'.$key,'name'=>'products['.$key.'][internal_notes]','class'=>'product-internal-notes', 'title'=>lang('tip_tender_product_internal_notes')), $product['internal_notes']); ?>
										
									</div>
									
									<div class="vui-field-wrapper-inline">
										
										<div class="vui-field-wrapper-inline">
											
											<div class="product-cost-price-per-unit">
												<?= lang('product_cost_price_per_unit'); ?>: <span class="value" data-value="<?= $cost_price; ?>"><?= $cs; ?> <?= number_format($cost_price, 2, ',', '.'); ?></span>
											</div>
											
											<div class="product-cost-total">
												<?= lang('product_cost_total'); ?>: <span class="value" data-value="<?= $cost_price * $quantity; ?>"><?= $cs; ?> <?= number_format($cost_price * $quantity, 2, ',', '.'); ?></span>
											</div>
											
										</div>
										
										<div class="vui-field-wrapper-inline">
											
											<div class="product-profit-without-tax-per-unit">
												<?= lang('product_profit_without_tax_per_unit'); ?>: <span class="value" data-value="<?= $profit_3; ?>"><?= $cs; ?> <?= number_format($profit_3, 2, ',', '.'); ?></span>
											</div>
											
											<div class="product-profit-without-tax-total">
												<?= lang('product_profit_without_tax_total'); ?>: <span class="value" data-value="<?= $profit_3 * $quantity; ?>"><?= $cs; ?> <?= number_format($profit_3 * $quantity, 2, ',', '.'); ?></span>
											</div>
											
										</div>
										
										<div class="vui-field-wrapper-inline">
											
											<div class="product-profit-with-tax-per-unit">
												<?= lang('product_profit_with_tax_per_unit'); ?>: <span class="value" data-value="<?= $profit_4; ?>"><?= $cs; ?> <?= number_format($profit_4, 2, ',', '.'); ?></span>
											</div>
											
											<div class="product-profit-with-tax-total">
												<?= lang('product_profit_with_tax_total'); ?>: <span class="value" data-value="<?= $profit_4 * $quantity; ?>"><?= $cs; ?> <?= number_format($profit_4 * $quantity, 2, ',', '.'); ?></span>
											</div>
											
										</div>
										
									</div>
									
								</td>
								
							</tr>
							
							<?php
								
								$row_class = $row_class == 'even' ? 'odd' : 'even';
								
							?>
							
							<?php } ?>
							
						</table>
						
						<table class="products-totals">
							<tr>
								<td>
									
								</td>
								<td class="product-cost-price-total">
									<?= lang('product_cost_total'); ?>: <span class="value"><?= $cs; ?> <?= number_format($total_cost_price, 2, ',', '.'); ?></span>
								</td>
								<td class="product-selling-price-total">
									<?= lang('selling_price_total'); ?>: <span class="value"><?= $cs; ?> <?= number_format($total_selling_price, 2, ',', '.'); ?></span>
								</td>
								<td class="product-profit-with-tax-total">
									<?= lang('product_profit_with_tax_total'); ?>: <span class="value"><?= $cs; ?> <?= number_format($total_profit_4, 2, ',', '.'); ?></span>
								</td>
							</tr>
						</table>
						
						<?php } else { ?>
						<p>
							<?= lang('no_products_on_tender'); ?>
						</p>
						<?php } ?>
						
						<div class="field-wrapper ta-right">
							<?= form_submit(array('id'=>'submit-add-product', 'class'=>'button','name'=>'submit_add_product'),lang('add_product')); ?>
						</div>
						
					</fieldset>
					
				</div>
				
				<div class="form-item">
					
					<fieldset>
						
						<legend>
							
							<?= vui_el_button( array( 'text' => lang( 'providers_and_conditions' ), 'icon' => 'providers',  ) ); ?>
							
						</legend>
						
						<div id="prov-conds-wrapper">
							
							<?php if ( $tender['prov_conds'] ){ ?>
							<table id="prov-conds" class="prov-conds">
								
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
									<!--
									<th class="prov-conds-delivery-time" title="<?= lang('delivery_time'); ?>">
											<?= lang('delivery_time'); ?>
									</th>
									-->
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
									$prov_cond['payment_conditions'] = isset($prov_cond['payment_conditions']) ? $prov_cond['payment_conditions']: $tender['params']['default_prov_cond_payment_conditions'];
									$prov_cond['delivery_time'] = isset($prov_cond['delivery_time']) ? $prov_cond['delivery_time']:'';
									$prov_cond['tender_validity'] = isset($prov_cond['tender_validity']) ? $prov_cond['tender_validity']: $tender['params']['default_prov_cond_tender_validity'];
									$prov_cond['notes'] = isset($prov_cond['notes']) ? $prov_cond['notes']:'';
									
								?>
								
								<tr class="<?= $row_class; ?>">
									
									<td class="prov-cond-title">
										
										<div class="vui-field-wrapper-inline">
											<?= form_hidden('prov_conds['.$prov_cond['id'].'][id]',$prov_cond['id']); ?>
											<?= form_hidden('prov_conds['.$prov_cond['id'].'][title]',$prov_cond['title']); ?>
											<?= $prov_cond['title']; ?>
										</div>
										
									</td>
									
									<td class="corporate-tax-register">
										
										<div class="vui-field-wrapper-inline">
											<?= form_hidden('prov_conds['.$prov_cond['id'].'][corporate_tax_register]',$prov_cond['corporate_tax_register']); ?>
											<?= $prov_cond['corporate_tax_register']; ?>
										</div>
										
									</td>
									
									<td class="prov-cond-freight-type">
										
										<div class="vui-field-wrapper-inline">
											
											<?php
												$options = array(
													lang('freight_type_cif') => lang('freight_type_cif'),
													lang('freight_type_fob') => lang('freight_type_fob'),
												);
											?>
											
											<?= form_dropdown('prov_conds['.$prov_cond['id'].'][freight_type]', $options, $prov_cond['freight_type'],'id="prov-cond-freight-type-'.$prov_cond['id'].'" class="prov-cond-freight-type"'); ?>
											
											<?php $options = array(); ?>
											
										</div>
										
									</td>
									
									<td class="prov-conds-payment-conditions">
										
										<div class="vui-field-wrapper-inline">
											<?= form_input(array('id'=>'prov-conds-payment-conditions-'.$prov_cond['id'],'name'=>'prov_conds['.$prov_cond['id'].'][payment_conditions]','class'=>'prov-conds-payment-conditions', 'title'=>lang('tip_provider_payment_conditions')), $prov_cond['payment_conditions']); ?>
										</div>
										
									</td>
									<!--
									<td class="prov-conds-delivery-time">
										
										<div class="vui-field-wrapper-inline">
											<?= form_input(array('id'=>'prov-conds-delivery-time-'.$prov_cond['id'],'name'=>'prov_conds['.$prov_cond['id'].'][delivery_time]','class'=>'prov-conds-delivery-time', 'title'=>lang('tip_provider_delivery_time')), $prov_cond['delivery_time']); ?>
										</div>
										
									</td>
									-->
									<td class="prov-conds-tender-validity">
										
										<div class="vui-field-wrapper-inline">
											<?= form_input(array('id'=>'prov-conds-tender-validity-'.$prov_cond['id'],'name'=>'prov_conds['.$prov_cond['id'].'][tender_validity]','class'=>'prov-conds-tender-validity'), $prov_cond['tender_validity']); ?>
										</div>
										
									</td>
									
									<td class="prov-conds-notes">
										
										<div class="vui-field-wrapper-inline">
											<?= form_textarea(array('id'=>'prov-conds-notes-'.$prov_cond['id'],'name'=>'prov_conds['.$prov_cond['id'].'][notes]','class'=>'prov-conds-notes'), $prov_cond['notes']); ?>
										</div>
										
									</td>
									
								</tr>
								
								<?php
									
									$row_class = $row_class == 'even' ? 'odd' : 'even';
									
								?>
								
								<?php } ?>
								
							</table>
							
							<?php } else { ?>
							<p>
								<?= lang('no_products_on_tender'); ?>
							</p>
							<?php } ?>
							
						</div>
						
					</fieldset>
					
				</div>
				
			</div>
			
		<?= form_close(); ?>
		
	</div>
	
</div>

<script type="text/javascript" >
	
	/*************************************************/
	/**** Função de obtenção da lista de clientes ****/
	
	function getCustomers( callBackFunction ){
		
		var id = $("#customers-category").val();
		var customers_field_options = '';
		var customer_id = $("#customer").val(); // detectando o cliente atual
		
		$('#customers-fields').empty();
		$('#customer-information-wrapper').empty();
		customers_field.empty();
		//alert(customer_id )
		jQuery.ajax({
			type: "POST",
			data: {
				category_id: id
			},
			url: "<?php echo base_url(); ?>admin/vesm/json/get_customers", //URL de destino
			dataType: "json", //Tipo de Retorno
			success: function(json){ //Se ocorrer tudo certo
				
				if ( json.hasOwnProperty('customers') ){
					
					var current_option = '';
					var customer_selected = false;
					
					$.each(json.customers, function(key, customer){
						
						if ( customer_id == customer.id){
							current_option = 'selected="selected"';
							customer_selected = true;
						}
						
						customers_field_options += '<option value="' + customer.id + '" ' + current_option + '>' + customer.title + '</option>';
						
						current_option = '';
						
					});
					
					customers_field.append(customers_field_options);
					
					// selecionando a primeira opção do select de clientes, se não for encontrado o cliente atual
					if ( ! customer_selected ) {
						customers_field.find('option:first-child').attr('selected', 'selected');
					}
					
					
					$('#customers-fields').append('<?= form_label(lang('customer')); ?>');
					$('#customers-fields').append(customers_field);
					setCustomerFieldEvents();
					getCustomer();
					
					if(typeof callBackFunction == 'function'){
						callBackFunction();
					}
					
				}
				else createGrowl( '<?php echo lang('msg_error_loading_customers'); ?>', null, null, 'msg-type-error' );
				
			},
			error: function(xhr, textStatus, errorThrown){
				
				if ( xhr.responseText == 'no_data' ){
					
					createGrowl( '<?php echo lang('msg_customers_category_empty'); ?>', null, null, 'msg-type-error' );
					
				}
				for(i in xhr){
					if(i!="channel")
					console.log(i + '>> ' + xhr[i]);
				};
				
			}
		});
		
		customers_field_options = '';
		
	}
	
	/**** Função de obtenção da lista de clientes ****/
	/*************************************************/
	
	/*************************************************/
	/**** Função de obtenção dos dados do cliente ****/
	
	function getCustomer( callBackFunction ){
		
		var container = $( '#customer-information-wrapper' ),
		customer_id = $( "#customer" ).val(); // detectando o cliente atual
		$( container_customer_fields_id ).empty();
		//alert(customer_id )
		$.ajax({
			url: '<?php echo base_url(); ?>admin/customers/ajax/get_customer_data/' + customer_id,
			success: function(data){
				
				container.html(data);
				var contacts_fields = $( '<div />' );
				var no_option_selected = true;
				
				container.find( '.customer-contacts' ).appendTo( contacts_fields ); // removendo os contatos do container com os dados da empresa e levando-os para a div abstrata dos contatos
				console.log( contacts_fields.html() );
				contacts_fields.find('a[data-contact-id]').each(function(i){
					
					var contact_element = $(this);
					var selected = '';
					var contact_wrapper = contact_element.closest( 'label' );
					var radio = contact_wrapper.find( 'input:radio' );
					
					contact_element.removeClass( 'modal-contact' );
					
					if ( contact_id == $(this).data('contact-id') ){
						selected = 'checked';
						no_option_selected = false;
					}
					
					if ( selected ){
						
						radio.prop( 'checked', true );
						radio.attr( 'checked', 'checked' );
						radio.change();
						contact_element.parent().addClass( 'selected' );
						
					}
					
				});
				
				if ( typeof( contact_click_event_binded ) == 'undefined' ){
					
					contact_click_event_binded = true;
					
					$( document ).on( 'click', '.customer-contacts a[data-contact-id]', function( e ){
						
						e.preventDefault();
						
						var jthis = $(this);
						var contact_wrapper = jthis.closest( 'label' );
						var radio = contact_wrapper.find( 'input:radio' );
						var contacts_wrapper = jthis.closest( '#contacts-wrapper' );
						
						contacts_wrapper.find( 'input:checked' ).prop( 'checked', false );
						contacts_wrapper.find( 'input[checked]' ).removeAttr( 'checked' );
						contacts_wrapper.find( '.selected' ).removeClass( 'selected' );
						radio.prop( 'checked', true );
						radio.attr( 'checked', 'checked' );
						radio.change();
						jthis.parent().addClass( 'selected' );
						
					});
					
				}
				
				// selecionando a primeira opção do elemento de contatos
				if ( no_option_selected ){
					contacts_fields.find('input[type=radio]').first().attr('checked','');
				}
				contacts_fields.find('.info-item-title').removeClass('info-item-title').addClass('info-title');
				
				$(container_customer_fields_id).append('<div id="contacts-wrapper" class="info-items">' + contacts_fields.html() + '</div>');
				
				container.find('.customer-phones, .customer-emails, .customer-addresses').remove();
				contacts_fields.remove();
				
				setContactsFieldEvents();
				
				getPhonesEmailsAddresses();
				
				if(typeof callBackFunction == 'function'){
					callBackFunction();
				}
				
			}, // success
			
			error: function(xhr, textStatus, errorThrown){
				
				console.log('content.text', status + ': ' + error);
				
				if ( xhr.responseText == 'no_data' ){
					alert('<?php echo lang('customer_no_data'); ?>');
					$('#contacts-preloader').stop().animate({
						'opacity': 0
					});
				}
				for(i in xhr){
					if(i!="channel")
					console.log(i + '>> ' + xhr[i]);
				};
				
			}
		});
		
	}
	
	/**** Função de obtenção dos dados do cliente ****/
	/*************************************************/
	
	/*************************************************/
	/*** Função de obtenção dos tel, emails e end. ***/
	
	function getPhonesEmailsAddresses(){
		
		var customer_id = $("#customer").val(); // detectando o cliente atual
		var contact_id = $("input[name=contact_id]:checked").val(); // detectando o contato atual
		
		$('#phones-wrapper, #emails-wrapper, #addresses-wrapper').remove();
		console.log( 'chamado' )
		$.ajax({
			url: '<?php echo base_url(); ?>admin/vesm/ajax/get_tender_customer_fields_merged/' + customer_id + '/' + contact_id,
			success: function(data){
				
				var no_option_selected = true;
				
				$(container_customer_fields_id).append(data);
				
				$(container_customer_fields_id).find('input[name=phone_key]').each(function(i){
					
					var el = $(this);
					var selected = '';
					
					if ( phone_key == $(this).attr('value') ){
						el.attr('checked', 'checked');
						no_option_selected = false;
					}
					
				});
				// selecionando a primeira opção dos telefones
				if ( no_option_selected ){
					$(container_customer_fields_id).find('input[name=phone_key]').first().attr('checked','checked');
				}
				
				no_option_selected = true;
				$(container_customer_fields_id).find('input[name=email_key]').each(function(i){
					
					var el = $(this);
					var selected = '';
					
					if ( email_key == $(this).attr('value') ){
						el.attr('checked', 'checked');
						no_option_selected = false;
					}
					
				});
				// selecionando a primeira opção dos emails
				if ( no_option_selected ){
					$(container_customer_fields_id).find('input[name=email_key]').first().attr('checked','checked');
				}
				
				no_option_selected = true;
				$(container_customer_fields_id).find('input[name=address_key]').each(function(i){
					
					var el = $(this);
					var selected = '';
					
					if ( address_key == $(this).attr('value') ){
						el.attr('checked', 'checked');
						no_option_selected = false;
					}
					
				});
				// selecionando a primeira opção dos endereços
				if ( no_option_selected ){
					$(container_customer_fields_id).find('input[name=address_key]').first().attr('checked','checked');
				}
				
			},
			error: function(xhr, textStatus, errorThrown){
				
				console.log('content.text', status + ': ' + error);
				
				if ( xhr.responseText == 'no_data' ){
					alert('<?php echo lang('customer_no_data'); ?>');
					$('#contacts-preloader').stop().animate({
						'opacity': 0
					});
				}
				for(i in xhr){
					if(i!="channel")
					console.log(i + '>> ' + xhr[i]);
				};
				
			}
		});
		
	}
	
	/*** Função de obtenção dos tel, emails e end. ***/
	/*************************************************/
	
	/*************************************************/
	/**** Definindo funções de geração de eventos ****/
	
	function setCustomersCategoryFieldEvents(){
		
		if ( typeof( customer_category_change_event_binded ) == 'undefined' ){
			
			$( document ).on( 'change', '#customers-category', function( e ){
				
				getCustomers( true );
				
			});
			
		}
		
	}
	
	function setCustomerFieldEvents(){
		
		if ( typeof( customer_change_event_binded ) == 'undefined' ){
			
			$( document ).on( 'change', '#<?= $customer_field_id; ?>', function( e ){
				
				getCustomer( true );
				
			});
			
		}
		
	}
	
	function setContactsFieldEvents(){
		
		if ( typeof( contact_change_event_binded ) == 'undefined' ){
			
			contact_change_event_binded = true;
			
			$( document ).on( 'change', 'input[name=contact_id]', function( e ){
				
				getPhonesEmailsAddresses( true );
				
			});
			
		}
		
	}
	
	/**** Definindo funções de geração de eventos ****/
	/*************************************************/
	
	/*************************************************/
	/**** Função de limpeza dos campos de clientes ***/
	
	function clearCustomerFields(){
		
		
		
	}
	
	/**** Função de limpeza dos campos de clientes ***/
	/*************************************************/
	
	/*************************************************/
	/******** Função de ordenação dos produtos *******/
	
	function changePosition(direction, productRow){
		
		if ( direction == 'up' ){
			
			var prevRow = productRow.prev().prev('.product-row');
			var secondaryRow = productRow.next('.secondary-fields');
			
			if ( prevRow.length > 0 ){
				
				productRow.insertBefore(prevRow)
				secondaryRow.insertAfter(productRow)
				
			}
			
		}
		else if ( direction == 'down' ){
			
			var nextRow = productRow.next().next('.product-row').next('.secondary-fields');
			var secondaryRow = productRow.next('.secondary-fields');
			
			if ( nextRow.length > 0 ){
				
				productRow.insertAfter(nextRow)
				secondaryRow.insertAfter(productRow)
				
			}
			
		}
		else if ( direction == 'down' ){
			
			var nextRow = productRow.next().next('.product-row').next('.secondary-fields');
			var secondaryRow = productRow.next('.secondary-fields');
			
			if ( nextRow.length > 0 ){
				
				productRow.insertAfter(nextRow)
				secondaryRow.insertAfter(productRow)
				
			}
			
		}
		
		
		reorderProductsRows();
		
	}
	
	/******** Função de ordenação dos produtos *******/
	/*************************************************/
	
	/*************************************************/
	/****** Reordenando os índices dos produtos ******/
	
	reorderProductsRows = function(){
		
		var productTable = $('table#products');
		var fields = '';
		var rowClass = 'even';
		
		productTable.find('.product-row').each(function(index) {
			
			var productRow = $(this);
			productRow.data('key', productRow.attr('data-key'));
			var oldKey = productRow.data('key');
			
			var newKey = index + 1;
			var secondaryFields = productRow.next('.secondary-fields');
			
			
			productRow.removeClass('even odd');
			secondaryFields.removeClass('even odd');
			productRow.addClass(rowClass);
			secondaryFields.addClass(rowClass);
			
			productRow.find('.product-key .content').text(newKey);
			
			productRow.attr('data-key', newKey);
			secondaryFields.attr('data-key', newKey);
			
			productRow.find('[name^="products"], .editable-text').each(function(input_index, e) {
				
				$.each(this.attributes, function() {
					if(this.specified) {
						if ( this.name == 'name' || this.name == 'id' || this.name == 'for' ){
							
							if ( $(e).attr('name') == 'products[' + oldKey + '][key]' ){
								$(e).attr('value', newKey);
							}
							
							var oldAttrValue = this.value;
							var newAttrValue = this.value.replace(new RegExp(oldKey, 'g'), newKey);
							$(e).attr(this.name, newAttrValue);
							
						}
						console.log(this.name, this.value);
					}
				});
				
			});
			
			secondaryFields.find('[name^="products"], .editable-text').each(function(input_index, e) {
				
				$.each(this.attributes, function() {
					if(this.specified) {
						if ( this.name == 'name' || this.name == 'id' || this.name == 'for' ){
							var oldAttrValue = this.value;
							var newAttrValue = this.value.replace(new RegExp(oldKey, 'g'), newKey);
							$(e).attr(this.name, newAttrValue);
						}
						console.log(this.name, this.value);
					}
				});
				
			});
			
			rowClass = rowClass == 'even' ? 'odd' : 'even';
			
		});
		
	}
	
	/****** Reordenando os índices dos produtos ******/
	/*************************************************/
	
	/*************************************************/
	/********** Desfazendo linhas expandidas *********/
	
	collapseProductsRows = function(){
		
		var productTable = $('table#products');
		
		productTable.find('.not-focused-row').removeClass('not-focused-row');
		
	}
	
	/********** Desfazendo linhas expandidas *********/
	/*************************************************/
	
	/*************************************************/
	/************ Salvamento da proposta *************/
	
	saveTender = function( callBackFunction ){
		
		var form = $('#tender-form');
		
		if ( ! form.attr('enctype') || ! form.attr('enctype') == 'multipart/form-data' ){
			
			var formData = form.serializeArray();
			formData.push({ name: 'submit_apply', value: 'submit_apply' });
			
			//createGrowl( '<?= lang('saving_tender_please_wait'); ?>', null, null, 'msg-type-info' );
			
			$.ajax( {
				type: "POST",
				url: form.attr( 'action' ) + '?ajax=submit_apply',
				data: formData,
				success: function( data, textStatus, jqXHR ) {
					for(i in jqXHR){
						if(i!="channel")
						console.log(i + '>> ' + jqXHR[i]);
					};
					console.log( data );
					
					var object = $('<div/>').html(data).contents();
					
					data = object.html();
					createGrowl( data, null, null, 'msg-type-success' );
					
					if(typeof callBackFunction == 'function'){
						callBackFunction();
					}
					
				},
				error: function(xhr, textStatus, errorThrown){
					
					for( i in request ){
						
						if( i!="channel" )
						console.log( i + '>> ' + request[i] );
						
					};
					
					msg = '<div class="msg-item msg-type-error">';
					msg += '<div class="error"><?= lang( 'error_trying_save_tender' ); ?>: <strong>' + request.status + ' ' + request.statusText + '</strong></div>';
					msg += '</div>';
					
					createGrowl( msg, null, null, 'msg-type-error' );
					
				}
			} );
			
		}
		
	}
	
	/************ Salvamento da proposta *************/
	/*************************************************/
	
	/*************************************************/
	/****** Carregando fornecedores e condições ******/
	
	loadProvCond = function(){
		
		$( "#prov-conds-wrapper" ).empty();
		$( "#prov-conds-wrapper" ).load( "<?= get_url('admin'.$this->uri->ruri_string()); ?> #prov-conds" );
		
		//createGrowl( '<?php echo lang( 'tender_prov_cond_updated' ); ?>', null, null, 'msg-type-success' );
		
	}
	
	/****** Carregando fornecedores e condições ******/
	/*************************************************/
	
	/*************************************************/
	/************* Cálculos dos produtos *************/
	
	updateProductFields = function(productRow){
		
		var sf = productRow.next('.secondary-fields'); // secondary fields
		cs = '<?= $cs; ?>';
		
		quantity_input = productRow.find('input.product-quantity');
		if ( parseInt(quantity_input.val()) <= 0 || ! parseInt(quantity_input.val()) || parseInt(quantity_input.val()) == '' ) quantity_input.val(1);
		quantity = parseInt(quantity_input.val());
		
		cost_price_input = sf.find('input.product-cost-price');
		cost_price = parseFloat(cost_price_input.val());
		profit_factor_input = sf.find('input.product-profit-factor');
		profit_factor = parseFloat(profit_factor_input.val());
		provider_tax_input = sf.find('input.product-provider-tax');
		provider_tax = parseFloat(provider_tax_input.val());
		tax_other_input = sf.find('input.product-tax-other');
		tax_other = parseFloat(tax_other_input.val());
		
		company_tax_input = sf.find('input.company-tax');
		company_tax = parseFloat(company_tax_input.val());
		
		// lucro bruto 1
		profit_1 = profit_factor / 100 * cost_price;
		
		// preço de venda
		selling_price_per_unit = cost_price + profit_1;
		
		// protocolo 21
		tax_other_value = selling_price_per_unit * tax_other / 100;
		
		// lucro bruto 2
		profit_2 = selling_price_per_unit - cost_price - tax_other_value;
		
		// imposto da empresa
		provider_tax_value = profit_2 * provider_tax / 100;
		
		// lucro bruto 3
		profit_3 = profit_2 - provider_tax_value;
		
		// imposto da empresa
		company_tax_value = profit_3 * company_tax / 100;
		
		// lucro líquido
		profit_4 = profit_3 - company_tax_value;
		
		
		
		productRow.find('.product-selling-price-per-unit').attr('data-value', selling_price_per_unit).text( cs + ' ' + $.number(selling_price_per_unit, 2, ',', '.') );
		productRow.find('.product-selling-price-total').attr('data-value', selling_price_per_unit * quantity).text( cs + ' ' + $.number(selling_price_per_unit * quantity, 2, ',', '.') );
		
		sf.find('.product-cost-price-per-unit .value').attr('data-value', cost_price).text( cs + ' ' + $.number(cost_price, 2, ',', '.') );
		sf.find('.product-cost-total .value').attr('data-value', cost_price * quantity).text( cs + ' ' + $.number(cost_price * quantity, 2, ',', '.') );
		
		sf.find('.product-profit-without-tax-per-unit .value').attr('data-value', profit_3).text( cs + ' ' + $.number(profit_3, 2, ',', '.') );
		sf.find('.product-profit-without-tax-total .value').attr('data-value', profit_3 * quantity).text( cs + ' ' + $.number(profit_3 * quantity, 2, ',', '.') );
		
		sf.find('.product-profit-with-tax-per-unit .value').attr('data-value', profit_4).text( cs + ' ' + $.number(profit_4, 2, ',', '.') );
		sf.find('.product-profit-with-tax-total .value').attr('data-value', profit_4 * quantity).text( cs + ' ' + $.number(profit_4 * quantity, 2, ',', '.') );
		
		updateTotals();
		
	}
	
	updateTotals = function(){
		
		var total_cost_price = 0;
		var total_selling_price = 0;
		var total_profit_4 = 0;
		var cs = '<?= $cs; ?>';
		
		$('table.products tr.product-row').each(function(index) {
			
			var productRow = $(this);
			var sf = productRow.next('.secondary-fields'); // secondary fields
			
			var cost_price_total = parseFloat(sf.find('.product-cost-total .value').attr('data-value'));
			var selling_price_total = parseFloat(productRow.find('.product-total .value').attr('data-value'));
			var profit_4_total = parseFloat(sf.find('.product-profit-with-tax-total .value').attr('data-value'));
			
			total_cost_price += cost_price_total;
			total_selling_price += selling_price_total;
			total_profit_4 += profit_4_total;
			
		});
		
		$('table.products-totals .product-cost-price-total .value').text( cs + ' ' + $.number(total_cost_price, 2, ',', '.') );
		$('table.products-totals .product-selling-price-total .value').text( cs + ' ' + $.number(total_selling_price, 2, ',', '.') );
		$('table.products-totals .product-profit-with-tax-total .value').text( cs + ' ' + $.number(total_profit_4, 2, ',', '.') );
		
	}
	
	setProductsInputsFieldsEvents = function(){
		
		$('table.products tr.product-row').each(function(index) {
			
			productRow = $(this);
			sf = productRow.next('.secondary-fields'); // secondary fields
			
			productRow.find('input, textarea, select').each(function(index, element){
				
				$(element).data('productRow', productRow);
				
				$(element).delegate(productRow, 'change', function(){
					
					updateProductFields($(this).data('productRow'));
					
				});
				
				$(element).delegate(productRow, 'keyup', function(event){
					
					updateProductFields($(this).data('productRow'));
					
				});
			});
			
			sf.find('input, textarea, select').each(function(index, element){
				
				$(element).data('productRow', productRow);
				
				$(element).delegate(productRow, 'change', function(event){
					
					updateProductFields($(this).data('productRow'));
					
				});
				$(element).delegate(productRow, 'keyup', function(event){
					
					$(element).trigger('change');
					
				});
				
				$(element).delegate(productRow, 'keydown', function(event){
					
					// prevenindo que o formulário seja submetido
					if ( event.keyCode == 13 ) {
						
						$(element).trigger('blur');
						event.preventDefault();
						
					}
					
				});
			});
			
		});
		
	}
	
	/************* Cálculos dos produtos *************/
	/*************************************************/
	
	
	
	/*************************************************/
	/********** Ajustando origem do produto **********/
	
	setProductStateOrigin = function( productRow ){
		
		var providerIdField = productRow.find('.product-provider-id');
		var secondaryFields = productRow.next('.secondary-fields');
		var eleStateOrigin = secondaryFields.find('.product-origin-state');
		var id = providerIdField.val();
		
		jQuery.ajax({
			type: "POST",
			data: {
				provider_id: id
			},
			url: "<?php echo base_url(); ?>admin/vesm/json/get_provider_data", //URL de destino
			dataType: "json", //Tipo de Retorno
			success: function(json){ //Se ocorrer tudo certo
				
				if ( json.hasOwnProperty('provider') ){
					
					eleStateOrigin.val(json.provider.state_origin_id);
					//createGrowl('<?php echo lang('msg_product_state_origin_changed_to'); ?> <b>' + eleStateOrigin.find('option:selected').text() + '</b>');
					
				}
				else createGrowl( '<?php echo lang( 'msg_error_loading_provider' ); ?>', null, null, 'msg-type-error' );
				
			},
			error: function( request, status, error ){
				
				for( i in request ){
					
					if( i!="channel" )
					console.log( i + '>> ' + request[i] );
					
				};
				
				msg = '<div class="msg-item msg-type-error">';
				msg += '<div class="error"><?= lang( 'msg_error_no_provider' ); ?>: <strong>' + request.status + ' ' + request.statusText + '</strong></div>';
				msg += '</div>';
				
				createGrowl( msg, null, null, 'msg-type-error' );
				
			}
		});
		
	}
	
	/********** Ajustando origem do produto **********/
	/*************************************************/
	
	
	
	
	
	$( document ).bind( 'ready', function(){
		
		<?php if ( $this->plugins->load( 'yetii' ) ){ ?>
		
		/*************************************************/
		/**************** Criando as tabs ****************/
		
		makeTabs( $( '.tabs-wrapper' ), '.form-item', 'legend' );
		
		/**************** Criando as tabs ****************/
		/*************************************************/
		
		<?php } ?>
		
		/*************************************************/
		/*** Criando botões de ordenação dos produtos ****/
		
		$('table.products td.product-key .content').each(function(){
			
			var jthis = $(this);
			
			jthis.before('<a href="#" class="product-order-btn product-up" title="<?= lang('up_position'); ?>">&uarr;</a>');
			jthis.prev('.product-up').delegate($('table.products'), 'click', function(event) {
				var productRow = $(this).closest('.product-row');
				event.preventDefault();
				changePosition('up', productRow);
			});
			
			jthis.after('<a href="#" class="product-order-btn product-down" title="<?= lang('down_position'); ?>">&darr;</a>');
			jthis.next('.product-down').delegate(jthis, 'click', function(event) {
				var productRow = $(this).closest('.product-row');
				event.preventDefault();
				changePosition('down', productRow);
			});
		});
		
		/*** Criando botões de ordenação dos produtos ****/
		/*************************************************/
		
		var productSelected = false;
		
		$( document ).bind( 'keydown', function( e ){
			
			if ( pressed_key == '83 Ctrl' ){
				e.preventDefault();
				$( '#submit-apply' ).trigger( 'click' );
			}
			
		});
		
		/*************************************************/
		/********* Expansão / retração das linhas ********/
		
		$('table.products th.product-key').before('<th class="row-expander">+</th>');
		$('table.products td.product-key').before('<td class="row-expander">+</td>');
		$('table.products tr.secondary-fields').addClass('row-collapsed');
		$('table.products tr.secondary-fields td').attr('colspan', 10);
		$('table.products tr.secondary-fields').stop().hide();
		
		// expansor de todas as linhas
		$('table.products th.row-expander').bind('click', function(){
			
			var table = $(this).closest('table.products');
			
			table.find('.not-focused-row').removeClass('not-focused-row');
			table.find('.selected-row').removeClass('selected-row');
			
			table.addClass('row-expanded');
			
			if ( ! $(this).parent().hasClass('row-expanded') ){
				
				$(this).parent().addClass('row-expanded');
				$(this).parent().parent().find('.secondary-fields').stop().show();
				$(this).parent().parent().find('td.row-expander').stop().hide();
				$(this).parent().parent().find('td.product-key').attr('colspan',2);
				
			}
			else{
				
				table.removeClass('row-expanded');
				$(this).parent().removeClass('row-expanded');
				$(this).parent().parent().find('.secondary-fields').stop().hide();
				$(this).parent().parent().find('td.row-expander').stop().show();
				$(this).parent().parent().find('td.product-key').attr('colspan', 1);
				
			}
			
		});
		// expansor de uma linha específica
		$('table.products td.row-expander').bind('click', function(){
			
			var row = $(this).parent();
			var secondaryRow = $(this).parent().next();
			
			if ( ! row.hasClass('selected-row') ){
				
				row.parent().find('tr:not(:first-child)').addClass('not-focused-row');
				row.removeClass('not-focused-row');
				secondaryRow.removeClass('not-focused-row');
				
				$(this).parent().parent().find('.row-expanded').stop().hide();
				
				row.parent().find('.selected-row').removeClass('selected-row');
				secondaryRow.removeClass('row-collapsed selected-row');
				secondaryRow.addClass('row-expanded selected-row');
				row.addClass('selected-row');
				
				secondaryRow.stop().show();
			}
			else{
				
				row.parent().find('.not-focused-row').removeClass('not-focused-row');
				$(this).parent().parent().find('.row-expanded').stop().hide();
				
				row.parent().find('.selected-row').removeClass('selected-row');
				secondaryRow.removeClass('row-collapsed selected-row');
				
			}
			
		});
		
		/********* Expansão / retração das linhas ********/
		/*************************************************/
		
		/*************************************************/
		/**************** Textos editáveis ***************/
		
		$('table.products tr:not(tr.secondary-fields) td input[type=text]:not(.product-quantity), table.products tr:not(tr.secondary-fields) td select, table.products tr:not(tr.secondary-fields) td textarea').each(function(){
			
			var jthis=$(this);
			var tagName, val;
			
			jthis.hide();
			
			jthis.addClass('editable-text-input');
			
			tagName=jthis.get(0).tagName;
			
			if ( tagName == 'SELECT' &&(!(jthis.attr('multiple') || (jthis.attr('size') && jthis.attr('size')!='1')) || ('SELECT'&&!jthis.attr('size')) || ('SELECT'&&jthis.attr('size')==0)) ){
				val = jthis.children('option:selected').html();
			}
			else if ( tagName == 'TEXTAREA' ){
				val = jthis.val();
				val = val.replace(/\r?\n/g, "<br/>");
			}
			else{
				val = jthis.val();
			}
			
			jthis.after('<a href="#" class="editable-text" for="' + jthis.attr('id') + '">' + val + '</a>');
			
		});
		
		/**************** Textos editáveis ***************/
		/*************************************************/
		
		/*************************************************/
		/************** Botões da quantidade *************/
		
		$('table.products input[type=text].product-quantity').each(function(){
			
			var jthis=$(this);
			var tagName, val;
			tagName=jthis.get(0).tagName;
			
			jthis.before('<a class="btn btn-decrement" href="#" title="<?= lang('decrease'); ?>"></a>');
			jthis.after('<a class="btn btn-increment" href="#" title="<?= lang('increase'); ?>"></a>');
			
			var btnDec = jthis.prev('.btn-decrement');
			var btnInc = jthis.next('.btn-increment');
			
			btnDec.delegate(jthis, 'click', function(event){
				
				var step = 1;
				var val = parseInt(jthis.val());
				
				// se shift está pressionado, o passo é 10
				if ( shifted ){
					step = 10;
				}
				
				val -= step;
				
				if ( val <= 0 ) val = 1;
				
				jthis.val(val);
				
				updateProductFields($(this).parent().parent().parent());
				event.preventDefault();
				
			});
			btnInc.delegate(jthis, 'click', function(event){
				
				var step = 1;
				var val = parseInt(jthis.val());
				
				// se shift está pressionado, o passo é 10
				if ( shifted ){
					step = 10;
				}
				
				val += step;
				
				if ( val <= 0 ) val = 1;
				
				jthis.val(val);
				
				updateProductFields($(this).parent().parent().parent());
				event.preventDefault();
				
			});
			jthis.trigger('keydown');
			
		});
		
		/************** Botões da quantidade *************/
		/*************************************************/
		
		$('.editable-text-input').bind('change', function(event){
			
			var jthis=$(this);
			var tagName, val;
			
			tagName=jthis.get(0).tagName;
			
			if ( tagName == 'SELECT' &&(!(jthis.attr('multiple') || (jthis.attr('size') && jthis.attr('size')!='1')) || ('SELECT'&&!jthis.attr('size')) || ('SELECT'&&jthis.attr('size')==0)) ){
				val = jthis.children('option:selected').html();
			}
			else if ( tagName == 'TEXTAREA' ){
				val = jthis.val();
				val = val.replace(/\r?\n/g, "<br/>");
			}
			else{
				val = jthis.val();
			}
			
			jthis.next().html(val);
			jthis.stop().fadeOut(300, function(){
				jthis.next().stop().show();
				jthis.unbind('clickoutside, keydown, blur');
			});
			
		});
		
		
		$('.editable-text').bind('focus', function(event){
			
			$(this).trigger('click');
			
		});
		
		$('.editable-text').bind('click', function(event){
			
			event.preventDefault();
			
			var jthis = $(this);
			var jprev = jthis.prev();
			
			jthis.stop().fadeOut(100, function(){
				
				jprev.stop().fadeIn(100, function(){
					
					jprev.focus().val(jprev.val());
					
					jprev.bind('keydown', function(event){
						
						if ( event.keyCode == 13 ) {
							
							tagName=jprev.get(0).tagName;
							
							if ( tagName != 'TEXTAREA' ){
								
								jprev.trigger('change');
								
								event.preventDefault();
								return false;
								
							}
							
						}
						
					});
					
					jprev.bind('clickoutside, blur', function(event){
						
						$(this).trigger('change');
						$(this).unbind('clickoutside');
						
					});
				});
				
			});
			
		});
		
		/*************************************************/
		/************** Remoção dos produtos *************/
		
		$('.btn-delete-product').bind('click', function(event){
			
			event.preventDefault();
			var jthis = $(this);
			
			jthis.parent().parent().parent().next().remove();
			jthis.parent().parent().parent().remove();
			saveTender(loadProvCond);
			reorderProductsRows();
			updateTotals();
			collapseProductsRows();
			return false;
			
		});
		
		/************** Remoção dos produtos *************/
		/*************************************************/
		
		$('.product-provider-id').bind('change', function(event){
			
			var productRow = $(this).parent().parent().parent();
			setProductStateOrigin( productRow );
			saveTender(loadProvCond);
			
		});
		
		/*************************************************/
		/************ Gerando preview do PDF *************/
		
		$('#submit-preview-tender-pdf').bind('click', function(event) {
			
			var form = form = $( '#' + $(this).attr( 'form' ) );
			
			if ( ! form.attr('enctype') || ! form.attr('enctype') == 'multipart/form-data' ){
				
				var formData = form.serializeArray();
				formData.push({ name: this.name, value: this.value });
				
				createGrowl( '<?= lang('msg_loading'); ?>', null, null, 'msg-type-info' );
				
				console.log(formData)
				
				$.ajax( {
					
					type: "POST",
					url: form.attr( 'action' ) + '?ajax=submit_preview_tender_pdf',
					data: formData,
					success: function( data ) {
						
						console.log( data );
						
						var object = $('<div/>').html( data ).contents();
						
						data = object.html();
						createGrowl( data, true, null, 'msg-type-mix' );
						
					}
					
				} );
				event.preventDefault();
				
			}
			
		});
		
		/************ Gerando preview do PDF *************/
		/*************************************************/
		
		/*************************************************/
		/********* Ajustando origens dos produtos ********/
		
		$('table.products tr.product-row').each(function(){
			
			setProductStateOrigin( $(this) );
			
		});
		
		/********* Ajustando origens dos produtos ********/
		/*************************************************/
		
		customer_id = $("#customer").val(); // detectando o cliente atual
		customers_field_html = '<select name="<?= $customer_field_name; ?>" id="<?= $customer_field_id; ?>" class="<?= $customer_field_class; ?>"></select>';
		customers_field = $(customers_field_html);
		
		contact_id = $('#contacts').val(); // detectando o contato atual
		phone_key = $('#phones').val(); // detectando o contato atual
		email_key = $('#emails').val(); // detectando o email atual
		
		address_key = $('#addresses').val(); // detectando o endereço atual
		// definindo o id do container dos campos do cliente selecionado
		container_customer_fields_id = '#customer-fields-ajax';
		
		// removendo os campos que serão usados com jquery
		$('#contacts-wrapper').remove(); // removendo o wrapper dos contatos, não precisaremos dele com o jquery
		$('#phones-wrapper').remove(); // removendo o wrapper dos telefones, não precisaremos dele com o jquery
		$('#emails-wrapper').remove(); // removendo o wrapper dos emails, não precisaremos dele com o jquery
		$('#addresses-wrapper').remove(); // removendo o wrapper dos endereços, não precisaremos dele com o jquery
		
		getCustomers(function(){
			
			setCustomersCategoryFieldEvents();
			
			setProductsInputsFieldsEvents();
			checkInputFloats();
			
			//setCustomerFieldEvents();
			//getCustomer();
			
		});
		
		var timer = $.timer(function() {
			saveTender();
		});
		timer.set({ time : 1000 * 60 * 3, autostart : true }); // 3 = minutos
		
	});
	
	$(window).load(function(){
		
	});
	
</script>

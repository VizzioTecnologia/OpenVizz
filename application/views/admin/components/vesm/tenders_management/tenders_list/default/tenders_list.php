

		<div id="tenders-list">

			<header class="component-head">

				<h1>

					<?= lang( 'tenders' ); ?>

				</h1>

			</header>

			<script type="text/javascript">
			document . write( '<div class="filter"><input type="text" id="filter" placeholder="<?= lang( 'live_filter' ); ?>" class="live-filter" data-live-filter-for="table#tenders-list tr" ></input></div>' );
			</script>

			<?php if ( $pagination ){ ?>
			<div class="pagination">
				<?= $pagination; ?>
			</div>
			<?php } ?>

			<table id="tenders-list" class="data-list responsive">
				<tr>
					<th class="tender-code order-by <?= ( $order_by == 'tender_code' ) ? 'order-by-column ' . 'order-by-' . $order_by_direction : '' ?>">

						<?= anchor( get_url( 'admin' . '/' . $component_name . '/' . $component_function . '/' . 'change_order_by' . '/tender_code' ) , lang( 'tender_code_column' ), 'class="" title="' .  ( ( $order_by == 'tender_code' ) ? lang( 'ordering_by_this_column_' . $order_by_direction . '_click_to_' . ( $order_by_direction == 'ASC' ? 'DESC' : 'ASC' ) ) :  ( ( $order_by == 'tender_code' ) ? lang( 'ordering_by_this_column_' . $order_by_direction . '_click_to_' . ( $order_by_direction == 'ASC' ? 'DESC' : 'ASC' ) ) : lang( 'click_to_order_by_this_column' ) )  )  . '"' ); ?>

					</th>
					<th class="title order-by <?= ( $order_by == 'title' ) ? 'order-by-column ' . 'order-by-' . $order_by_direction : '' ?>">

						<?= anchor( get_url( 'admin' . '/' . $component_name . '/' . $component_function . '/' . 'change_order_by' . '/title' ) , lang( 'title' ), 'class="" title="' .  ( ( $order_by == 'title' ) ? lang( 'ordering_by_this_column_' . $order_by_direction . '_click_to_' . ( $order_by_direction == 'ASC' ? 'DESC' : 'ASC' ) ) : lang( 'click_to_order_by_this_column' ) )  . '"' ); ?>

					</th>
					<th class="data-creation order-by <?= ( $order_by == 'date_creation' ) ? 'order-by-column ' . 'order-by-' . $order_by_direction : '' ?>">

						<?= anchor( get_url( 'admin' . '/' . $component_name . '/' . $component_function . '/' . 'change_order_by' . '/date_creation' ) , lang( 'date_creation_th' ), 'class="" title="' .  ( ( $order_by == 'date_creation' ) ? lang( 'ordering_by_this_column_' . $order_by_direction . '_click_to_' . ( $order_by_direction == 'ASC' ? 'DESC' : 'ASC' ) ) : lang( 'click_to_order_by_this_column' ) )  . '"' ); ?>

					</th>
					<th class="date-issue order-by <?= ( $order_by == 'date_tender_order' ) ? 'order-by-column ' . 'order-by-' . $order_by_direction : '' ?>">

						<?= anchor( get_url( 'admin' . '/' . $component_name . '/' . $component_function . '/' . 'change_order_by' . '/date_tender_order' ) , lang( 'date_tender_order_th' ), 'class="" title="' .  ( ( $order_by == 'date_tender_order' ) ? lang( 'ordering_by_this_column_' . $order_by_direction . '_click_to_' . ( $order_by_direction == 'ASC' ? 'DESC' : 'ASC' ) ) : lang( 'click_to_order_by_this_column' ) )  . '"' ); ?>

					</th>
					<th class="date-issue order-by <?= ( $order_by == 'date_issue' ) ? 'order-by-column ' . 'order-by-' . $order_by_direction : '' ?>">

						<?= anchor( get_url( 'admin' . '/' . $component_name . '/' . $component_function . '/' . 'change_order_by' . '/date_issue' ) , lang( 'date_issue_th' ), 'class="" title="' .  ( ( $order_by == 'date_issue' ) ? lang( 'ordering_by_this_column_' . $order_by_direction . '_click_to_' . ( $order_by_direction == 'ASC' ? 'DESC' : 'ASC' ) ) : lang( 'click_to_order_by_this_column' ) )  . '"' ); ?>

					</th>
					<th class="providers">
						<?= lang( 'providers' ); ?>
					</th>
					<th class="customer order-by <?= ( $order_by == 'customer' ) ? 'order-by-column ' . 'order-by-' . $order_by_direction : '' ?>">

						<?= anchor( get_url( 'admin' . '/' . $component_name . '/' . $component_function . '/' . 'change_order_by' . '/customer' ) , lang( 'customer' ), 'class="" title="' .  ( ( $order_by == 'customer' ) ? lang( 'ordering_by_this_column_' . $order_by_direction . '_click_to_' . ( $order_by_direction == 'ASC' ? 'DESC' : 'ASC' ) ) : lang( 'click_to_order_by_this_column' ) )  . '"' ); ?>

					</th>
					<th class="contact order-by <?= ( $order_by == 'contact' ) ? 'order-by-column ' . 'order-by-' . $order_by_direction : '' ?>">

						<?= anchor( get_url( 'admin' . '/' . $component_name . '/' . $component_function . '/' . 'change_order_by' . '/contact' ) , lang( 'contact' ), 'class="" title="' .  ( ( $order_by == 'contact' ) ? lang( 'ordering_by_this_column_' . $order_by_direction . '_click_to_' . ( $order_by_direction == 'ASC' ? 'DESC' : 'ASC' ) ) : lang( 'click_to_order_by_this_column' ) )  . '"' ); ?>

					</th>
					<th class="seller">
						<?= lang( 'seller' ); ?>
					</th>
					<th class="status order-by <?= ( $order_by == 'status' ) ? 'order-by-column ' . 'order-by-' . $order_by_direction : '' ?>">

						<?= anchor( get_url( 'admin' . '/' . $component_name . '/' . $component_function . '/' . 'change_order_by' . '/status' ) , lang( 'status' ), 'class="" title="' .  ( ( $order_by == 'status' ) ? lang( 'ordering_by_this_column_' . $order_by_direction . '_click_to_' . ( $order_by_direction == 'ASC' ? 'DESC' : 'ASC' ) ) : lang( 'click_to_order_by_this_column' ) )  . '"' ); ?>

					</th>
					<th class="op-column">
						<?= lang( 'operations' ); ?>
					</th>
				</tr>

				<?php foreach( $tenders as $tender ): ?>

				<?php /*print_r( $tender );*/ ?>

				<tr class="tender-status-<?= ( isset( $tender['status_id'] ) ? $tender['status_id'] : '' ); ?>">

					<td class="tender-code ta-center <?= ( $order_by == 'tender_code' ) ? 'order-by-column ' . 'order-by-' . $order_by_direction : '' ?>">

						<span class=" filter-me">

							<?php
								$year = substr( strip_tags( $tender['tender_code'] ) , 0,4 );
								$month = substr( strip_tags( $tender['tender_code'] ) , 4,2 );
								$day = substr( strip_tags( $tender['tender_code'] ) , 6,2 );

								$pos = strpos( $tender['tender_code'], $month, 4 );
								if ( $pos !== FALSE ){
									$tender['tender_code'] = substr_replace( $tender['tender_code'], '<b>', $pos, 0 );
									$pos = strpos( $tender['tender_code'], $day, $pos );
									$tender['tender_code'] = substr_replace( $tender['tender_code'], '</b>', $pos + 2, 0 );
								}

							?>
							<?= $tender['tender_code']; ?>

						</span>

					</td>

					<td class="title ta-center <?= ( $order_by == 'title' ) ? 'order-by-column ' . 'order-by-' . $order_by_direction : '' ?>">

						<span class=" filter-me">

							<?= anchor( 'admin/' . $component_name . '/' . $component_function . '/edit_tender/' . $tender['id'], $tender['title'],'class="list-link-cover-me"' ); ?>

						</span>

					</td>

					<td class="date-creation ta-center <?= ( $order_by == 'date_creation' ) ? 'order-by-column ' . 'order-by-' . $order_by_direction : '' ?>">

						<span class=" filter-me">

							<?= date( $tender['params'][ 'tender_date_format' ], strtotime( strip_tags( $tender[ 'date_creation' ] ) ) ); ?>

						</span>

					</td>

					<td class="date-tender-order ta-center <?= ( $order_by == 'date_tender_order' ) ? 'order-by-column ' . 'order-by-' . $order_by_direction : '' ?>">

						<span class=" filter-me">

							<?= date( $tender['params'][ 'tender_date_format' ], strtotime( strip_tags( $tender[ 'date_tender_order' ] ) ) ); ?>

						</span>

					</td>

					<td class="date-issue ta-center <?= ( $order_by == 'date_issue' ) ? 'order-by-column ' . 'order-by-' . $order_by_direction : '' ?>">

						<span class=" filter-me">

							<?= date( $tender['params'][ 'tender_date_format' ], strtotime( strip_tags( $tender[ 'date_issue' ] ) ) ); ?>

						</span>

					</td>

					<td class="providers ta-center">

						<?php if ( isset( $tender['providers'] ) AND $tender['providers'] ){

							$i = 0;
							$len = count( $tender['providers'] );

						?>
						<?php foreach( $tender[ 'providers' ] as $key => $provider ){ ?>

						<a href="<?= get_url( 'admin/companies/companies_management/edit_company/' . $provider['company_id'] ); ?>" class="list-info-wrapper filter-me" data-companyid="<?= $provider['company_id']; ?>" target="_blank" >

							<span class="list-info-thumb-wrapper">
								<?php if ( $provider['logo_thumb'] ){ ?>

								<?= img( array( 'src' => $provider['logo_thumb'], 'width' => 24 ) ); ?>

								<?php } ?>
							</span>

							<?= $provider['title']; ?>

						</a>


						<?php if ( $i < $len - 1 ) { ?>
						/
						<?php } ?>
						<?php $i++; ?>
						<?php } ?>
						<?php } ?>

					</td>
					<td class="customer ta-center <?= ( $order_by == 'customer' ) ? 'order-by-column ' . 'order-by-' . $order_by_direction : '' ?>">

						<span class=" filter-me">

							<a href="<?= get_url( 'admin/companies/companies_management/edit_company/' . $tender['company_id'] ); ?>" class="list-info-wrapper" data-companyid="<?= $tender['company_id']; ?>" target="_blank" >

								<span class="list-info-thumb-wrapper">
									<?php if ( $tender['logo_thumb'] ){ ?>

									<?= img( array( 'src' => $tender['logo_thumb'], 'width' => 24 ) ); ?>

									<?php } ?>
								</span>

								<?= $tender['customer_title']; ?>

							</a>

						</span>

					</td>
					<td class="contact ta-center <?= ( $order_by == 'contact' ) ? 'order-by-column ' . 'order-by-' . $order_by_direction : '' ?>">

						<span class=" filter-me">

							<a rel="tender-contacts-<?= $tender[ 'id' ]; ?>" href="<?= get_url( 'admin/contacts/contacts_management/edit_contact/' . $tender['contact_id'] ); ?>" data-mc-last-modal-group="<?= ( $this->input->get( 'last-modal-group' ) ? $this->input->get( 'last-modal-group' ) : 'tender-contacts-' . $tender[ 'id' ] ); ?>" data-mc-action="get" data-contact-id="<?= $tender[ 'contact_id' ]; ?>" class="modal-contact list-info-wrapper" target="_blank" title="<?= strip_tags( $tender[ 'contact_name' ] ); ?>">

								<span class="list-info-thumb-wrapper">
									<?php if ( $tender['thumb_local'] ){ ?>

									<?= img( array( 'src' => $tender['thumb_local'], 'width' => 24 ) ); ?>

									<?php } ?>
								</span>

								<?= $tender[ 'contact_name' ]; ?>

							</a>

						</span>

					</td>
					<td class="seller ta-center <?= ( $order_by == 'seller' ) ? 'order-by-column ' . 'order-by-' . $order_by_direction : '' ?>">

						<span class=" filter-me">

							<?php //echo $tender['seller_name']; ?>

						</span>

					</td>
					<td class="status ta-center <?= ( $order_by == 'status' ) ? 'order-by-column ' . 'order-by-' . $order_by_direction : '' ?>">

						<?= form_open( get_url( 'admin' . '/' . $component_name . '/' . $component_function . '/' . 'change_status' ), 'id="status-change-form-' . $tender['id'] . '"' ); ?>

						<?= form_hidden( 'tender_id', $tender['id'] ); ?>

						<?= form_error( 'tender_status', '<div class="msg-inline-error">', '</div>' ); ?>
						<?php if ( $tenders_status ){ ?>

						<?php
							foreach( $tenders_status as $row ):
								$options[$row['id']] = $row['title'];
							endforeach;
							asort( $options );
						?>

						<?= form_dropdown( 'status_id', $options, isset( $tender['status_id'] ) ? $tender['status_id'] : '','id="tender-status" class="tender-status filter-me"' ); ?>

						<?php $options = array(); ?>

						<?php } ?>

						<?= vui_el_button( array( 'text' => lang( 'action_save' ), 'icon' => 'ok', 'only_icon' => TRUE, 'button_type' => 'button', 'type' => 'submit', 'name' => 'submit_change_status', 'id' => 'submit-change-status', ) ); ?>

						<?= form_close(); ?>

					</td>

					<td class="operations">

						<?php if ( isset( $tender['pdf_file'] ) AND $tender['pdf_file'] ){ ?>

							<?= vui_el_button( array( 'url' => $tender['pdf_file'], 'text' => lang( 'action_view_pdf' ), 'get_url' => FALSE, 'target' => '_blank', 'icon' => 'pdf', 'only_icon' => TRUE, ) ); ?>

						<?php } ?>

						<?= vui_el_button( array( 'url' => 'admin/' . $component_name . '/' . $component_function . '/copy_tender/' . $tender['id'], 'text' => lang( 'action_copy' ), 'icon' => 'copy', 'only_icon' => TRUE, ) ); ?>

						<?= vui_el_button( array( 'url' => 'admin/' . $component_name . '/' . $component_function . '/edit_tender/' . $tender['id'],'text' => lang( 'action_edit' ), 'icon' => 'edit', 'only_icon' => TRUE, ) ); ?>

						<?php //echo anchor( 'admin/' . $component_name . '/' . $component_function . '/remove_tender/' . $tender['id'],lang( 'action_delete' ),'class="btn btn-delete" title="' . lang( 'action_delete' ) . '"' ); ?>
					</td>

				</tr>
				<?php endforeach; ?>
			</table>

			<?php if ( $pagination ){ ?>
			<div class="pagination">
				<?= $pagination; ?>
			</div>
			<?php } ?>

			<script type="text/javascript" >

				$( document ).ready( function(){

					<?php

						$this->plugins->load( array( 'fancybox', 'modal_contacts' ) );

					?>

					findCompaniesElements();

					// removendo o botão de submit de cada form, já que será usado ajax
					$( '.tender-status' ).parent().find( '[name=submit_change_status]' ).remove();

					$( '.tender-status' ).on( 'change', function(){

						var form = $( this ).closest( 'form' );

						var tr = $( this ).closest( 'tr' );
						tr.removeClass( tr.attr( 'class' ) ).addClass( 'tender-status-' + $( this ).val() );

						if ( ! form.attr( 'enctype' ) || ! form.attr( 'enctype' ) == 'multipart/form-data' ){

							var formData = form.serializeArray();

							formData.push( { name: 'submit_apply', value: 'submit_apply' } );

							$.ajax( {
								type: "POST",
								url: form.attr( 'action' ) + '?ajax=submit_apply',
								data: formData,
								success: function( data, textStatus, jqXHR ) {
									for( i in jqXHR ){
										if( i!="channel" )
										console.log( i + '>> ' + jqXHR[i] );
									};
									console.log( data );
									var object = $( '<div/>' ).html( data ).contents();

									data = object.html();

									createGrowl( data, null, null, 'msg-type-success' );

								},
								error: function( request, status, error ){

									for( i in request ){

										if( i!="channel" )
										console.log( i + '>> ' + request[i] );

									};

									msg = '<div class="msg-item msg-type-error">';
									msg += '<div class="error"><?= lang( 'error_trying_change_tender_status' ); ?>: <strong>' + request.status + ' ' + request.statusText + '</strong></div>';
									msg += '</div>';

									createGrowl( msg, null, null, 'msg-type-error' );

								}
							} );

						}

					} );

				} );

			</script>

		</div>
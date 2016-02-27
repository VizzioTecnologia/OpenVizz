<?php if ( ! defined( 'BASEPATH' ) ) exit( 'No direct script access allowed' ); ?>



<?php if ( @$post[ 'name' ] ) { ?>

<p><?php printf( lang( 'sending_email_intro_sent_by' ), $post[ 'name' ], BASE_URL ); ?></p>

<?php } else { ?>

<p><?php printf( lang( 'sending_email_intro' ), BASE_URL ); ?></p>

<?php } ?>



<?php if ( @$post[ 'subject' ] ) { ?>

<p><b><?= lang( 'sending_email_subject_title' ); ?></b> <?= $post[ 'subject' ]; ?></p>

<?php } ?>



<?php if ( @$post[ 'message' ] ) { ?>

<p><b><?= lang( 'sending_email_message_title' ); ?></b></p>

<p><?= $post[ 'message' ]; ?></p>

<?php } ?>

<hr />

<p><b><?= lang( 'sending_email_user_info' ); ?></b></p>

<?php if ( @$post[ 'email' ] ) { ?>

<p><b><?= lang( 'sending_email_email_title' ); ?></b> <?= $post[ 'email' ]; ?></p>

<?php } ?>

<?php if ( @$post[ 'phone_1' ] ) { ?>

<p><b><?= lang( 'sending_email_phone_1_title' ); ?></b> <?= $post[ 'phone_1' ]; ?></p>

<?php } ?>

<?php if ( @$post[ 'phone_2' ] ) { ?>

<p><b><?= lang( 'sending_email_phone_2_title' ); ?></b> <?= $post[ 'phone_2' ]; ?></p>

<?php } ?>

<?php if ( @$post[ 'company' ] ) { ?>

<p><b><?= lang( 'sending_email_company_title' ); ?></b> <?= $post[ 'company' ]; ?></p>

<?php } ?>

<?php if ( ( @$params[ 'contact_form_show_field_addresses' ] ) AND ( @$post[ 'country' ] OR @$post[ 'state' ] OR @$post[ 'city' ] OR @$post[ 'neighborhood' ] OR @$post[ 'public_area' ] OR @$post[ 'number' ] OR @$post[ 'complement' ] OR @$post[ 'postal_code' ] ) ) { ?>

<p><b><?= lang( 'sending_email_address_title' ); ?></b> <?= @$post[ 'public_area' ]; ?> <?= @$post[ 'number' ]; ?> <?= @$post[ 'neighborhood' ]; ?> <?= @$post[ 'complement' ]; ?> <?= @$post[ 'city' ]; ?> <?= @$post[ 'state' ]; ?> <?= @$post[ 'country' ]; ?> <?= @$post[ 'postal_code' ]; ?></p>

<?php } ?>

<?php if ( @$params[ 'contact_form_title_field_extra_combobox_1' ] AND @$post[ 'extra_combobox_1' ] ) { ?>

<p><b><?= $params[ 'contact_form_title_field_extra_combobox_1' ]; ?>:</b> <?= $post[ 'extra_combobox_1' ]; ?></p>

<?php } ?>

<?php if ( @$params[ 'contact_form_title_field_extra_combobox_2' ] AND @$post[ 'extra_combobox_2' ] ) { ?>

<p><b><?= $params[ 'contact_form_title_field_extra_combobox_2' ]; ?>:</b> <?= $post[ 'extra_combobox_2' ]; ?></p>

<?php } ?>

<?php if ( @$params[ 'contact_form_title_field_extra_combobox_3' ] AND @$post[ 'extra_combobox_3' ] ) { ?>

<p><b><?= $params[ 'contact_form_title_field_extra_combobox_3' ]; ?>:</b> <?= $post[ 'extra_combobox_3' ]; ?></p>

<?php } ?>

<?php
/* tradução: franksouza.com.br */

$lang[ 'menu_item_ud_data_config' ] = 															"Configurações da página de detalhes de dado";
$lang[ 'ud_data_id' ] = 																		"ID do dado";
$lang[ 'tip_ud_data_id' ] = 																	"Informe ID do dado";

$lang[ 'ud_field_conditions' ] = 																"Condições";
$lang[ 'tip_ud_field_is_required' ] = 															"Se marcado, o campo deverá ser preenchido";
$lang[ 'ud_validation_rule_mask' ] = 															"Máscara";
$lang[ 'ud_validation_rule_custom_mask' ] = 													"Máscara personalizada";
$lang[ 'ud_validation_rule_parameter_mask_type_custom_mask' ] = 								"Máscara personalizada";
$lang[ 'validation_rule_parameter_custom_mask' ] = 												"Máscara";
$lang[ 'ud_validation_rule_parameter_mask_custom_mask' ] = 										"Máscara";
$lang[ 'test_validation_rule_parameter_custom_mask' ] = 										"Teste a máscara selecionada";
$lang[ 'ud_validation_rule_parameter_mask_test' ] = 											"Teste a máscara selecionada";
$lang[ 'validation_rule_parameter_mask_phone_BR' ] = 											"Máscara de telefone (Brasil)";
$lang[ 'tip_validation_rule_parameter_mask_phone_BR' ] = 										"Máscara de telefone compatível apenas com o formato brasileiro de 8 e 9 dígitos";
$lang[ 'ud_validation_rule_parameter_mask_type_zip_brazil' ] = 									"Máscara de CEP (somente Brasil)";

$lang[ 'ud_data_created_by_user' ] = 															"Criado por %s";
$lang[ 'ud_data_modified_by_user' ] = 															"Criado por um usuário desconhecido, modificado por %s";
$lang[ 'ud_data_created_by_unknow_user' ] = 													"Criado por um usuário desconhecido";
$lang[ 'ud_data_created_and_modified_by_user' ] = 												"Criado e modificado por %s";
$lang[ 'ud_data_datetime' ] =																	"%d/%m/%Y às %T";

$lang[ 'tip_ud_validation_rule_matches' ] = 													"Se marcado, o valor do campo de entrada desta propriedade deverá ser igual a de outro campo, de outra propriedade. Isto é útil, por exemplo, em campos de senha + confirmação de senha, ou e-mail + confirmação.";

$lang[ 'ud_prop_type' ] = 																		"Tipo da propriedade";

// email sending
$lang[ 'ud_ds_notify_unid_data_via_email' ] = 													"Ativar notificações de dados via e-mail";
$lang[ 'tip_ud_ds_notify_unid_data_via_email' ] = 												"Se ativo, além do salvamento no banco de dados, os novos dados (envios) serão também enviados por e-mail para receptores específicos.";
$lang['sending'] = 																				"Envio";
$lang['submit_form_param_send_email_to'] = 														"E-mails de destino";
$lang['tip_submit_form_param_send_email_to'] = 													"<p>Defina para onde as mensagens devem ser enviadas.</p><ul><li><strong>E-mails do contato:</strong> Se selecionado, as mensagens serão enviadas para todos os e-mails do contato selecionado no campo <strong><i>Contato de recebimento</i></strong>. <span class=\"warning\"><strong>Importante: </strong>Tenha certeza que os e-mails do contato selecionado estão <strong>marcados para recebimento de mensagens</strong>. Você pode definir isto nas configurações do contato.</span></li><li><strong>E-mails personalizados:</strong> Se selecionado, as mensagens serão enviadas para os e-mails definidos no campo <strong><i>E-mails de recebimento personalizados</i></strong>.</li></ul>";
$lang['submit_form_param_send_email_to_contact'] =												"Contato de recebimento";
$lang['tip_submit_form_param_send_email_to_contact'] =											"<p>Se você selecionou a opção <strong><i>E-mails do contato</i></strong> no campo <strong><i>E-mails de destino</i></strong>, defina aqui o contato que recebrá as mensagens.</p><p><span class=\"warning\"><br /><strong>Importante: </strong>Tenha certeza que os e-mails do contato selecionado estão <strong>marcados para recebimento de mensagens</strong>. Você pode definir isto nas configurações do contato.</span></p>";
$lang['submit_form_param_send_email_to_custom_emails'] =										"E-mails de recebimento personalizados";
$lang['tip_submit_form_param_send_email_to_custom_emails'] =									"<p>Se você selecionou a opção <strong><i>E-mails personalizados</i></strong> no campo <strong><i>E-mails de destino</i></strong>, defina aqui os endereços de e-mail para os quais serão enviadas as mensagens.</p><p>Se desejar mais de um endereço de e-mail, informe um por linha.</p>";

$lang[ 'ud_ds_emails_for_receivers' ] = 														"Receptores dos e-mails";
$lang[ 'ud_ds_receivers_layout_specific_params_label' ] = 										"Parâmetros específicos do layout";

$lang[ 'ud_ds_receivers_layout_default_title' ] = 												"Notificação de novo registro";
$lang[ 'ud_ds_receivers_layout_default_pre_text' ] = 											'<p>Um novo registro foi criado através do formulário <a target="_blank" href="%1$s">%2$s</a> em <strong>%3$s</strong>. Seguem abaixo os dados enviados:</p>';
$lang[ 'ud_ds_receivers_layout_default_footer_text' ] = 										'<p>Este é um e-mail de notificação de registros do site <a target="_blank" href="%1$s">%2$s</a>, e o seu endereço de e-mail está na lista de e-mails receptores. Se não quiser mais receber esses e-mails de notificação, remova-o nas configurações do formulário relacionado, no painel de controle, ou solicite ao administrador do sistema a remoção do mesmo.</p>';

$lang[ 'ud_ds_emails_for_submitters' ] = 														"E-mails para submissores";
$lang[ 'ud_ds_submitters_layout_specific_params_label' ] = 										"Parâmetros específicos do layout";

$lang[ 'ud_ds_submitters_layout_default_title' ] = 												"Registro recebido com sucesso!";
$lang[ 'ud_ds_submitters_layout_default_pre_text' ] = 											'<p>A sua mensagem foi recebida com sucesso em <strong>%3$s</strong>! Seguem abaixo os dados que você nos informou:</p>';
$lang[ 'ud_ds_submitters_layout_default_footer_text' ] = 										'<p>Este é um e-mail contendo os dados que você informou através do site <a target="_blank" href="%1$s">%2$s</a>.</p>';

$lang['sfsmr_send_copy_to_submitter'] = 														"Enviar para os submissores";
$lang['sfsmr_email_field'] = 																	"Campo com o endereço de e-mail";
$lang['sfsmr_from'] = 																			"Remetente";
$lang['sfsmr_from_name'] = 																		"Em nome de";
$lang['sfsmr_reply_to'] = 																		"Responder para";
$lang['sfsmr_subject'] = 																		"Assunto";
$lang['sfsmr_layout_source'] = 																	"Origem do layout";
$lang['layouts_list'] = 																		"Lista de layouts";
$lang['custom_layout'] = 																		"Layout personalizado";
$lang['sfsmr_layout_custom'] = 																	"Layout personalizado";
$lang['sfsmr_layout_view'] = 																	"Layout";
$lang['sfsmr_message_prefix_custom'] = 															"Pré-texto personalizado";
$lang['sfsmr_message_suffix_custom'] = 															"Pós-texto personalizado";
$lang['sfsmr_show_empty_fields'] = 																"Exibir campos com valores vazios";

// ----------------------------
// Export

$lang[ 'ud_data_export_layout_specific_params_label' ] = 										"Parâmetros específicos do layout";
$lang[ 'ud_data_export_xls_worksheet_default_label' ] = 										"Planilha exportada do OpenVizz"; // WARNING: This string can not contain any special characters!!!

// -----------
// PDF

$lang[ 'ud_data_export_pdf_layout_specific_params_label' ] = 									"Exportação .PDF";
$lang[ 'ud_data_export_pdf_data_footer' ] = 													'Registro pertencente ao formulário <strong>%2$s (#%1$s)</strong>';

// -----------
// CSV

$lang[ 'ud_data_export_csv_delimiter_tab' ] = 													"Tabulação";
$lang[ 'ud_data_export_csv_delimiter_comma' ] = 												"Vírgula";
$lang[ 'ud_data_export_csv_delimiter_semicolon' ] = 											"Ponto de vírgula (;)";
$lang[ 'ud_data_export_csv_delimiter_colon' ] = 												"Dois pontos (:)";
$lang[ 'ud_data_export_csv_delimiter_space' ] = 												"Espaço";
$lang[ 'ud_data_export_csv_delimiter_pipe' ] = 													"Pipe (|)";



$lang[ 'ud_advanced_options_prop_is_ud_image' ] = 												"Imagem";
$lang[ 'ud_advanced_options_prop_is_ud_image_thumb_prevent_cache_admin' ] = 					"Prevenir cache de miniaturas (admin)";
$lang[ 'ud_advanced_options_prop_is_ud_image_thumb_prevent_cache_site' ] = 						"Prevenir cache de miniaturas (site)";
$lang[ 'ud_advanced_options_prop_is_ud_file' ] = 												"Arquivo";
$lang[ 'ud_advanced_options_prop_is_ud_file_upload_dir' ] = 									"Diretório de upload";
$lang[ 'ud_advanced_options_prop_is_ud_file_email_send_attachment' ] = 							"Enviar como anexo nos e-mails";
$lang[ 'ud_advanced_options_prop_is_ud_file_overwrite' ] = 										"Substituir arquivos existentes";
$lang[ 'ud_advanced_options_prop_is_ud_file_max_size' ] = 										"Tamanho máximo (em kilobytes)";
$lang[ 'ud_data_prop_ud_file_max_size_desc' ] = 												"Tamanho máximo permitido: <strong>%s</strong>";
$lang[ 'ud_advanced_options_prop_is_ud_file_allowed_types' ] = 									"Tipos permitidos";
$lang[ 'tip_ud_advanced_options_prop_is_ud_file_allowed_types' ] = 								"Especifique os tipos de arquivos permitidos, separados por <strong>|</strong>. <p>Ex.: jpg|png|doc|pdf</p><p>Se nenhum tipo for definido, qualquer tipo de arquivo será permitido.</p>";
$lang[ 'ud_data_prop_ud_file_allowed_types_desc' ] = 											"Tipos de arquivo permitidos: <strong>%s</strong>";
$lang[ 'ud_data_prop_file_error_title' ] = 														"Erro no envio do arquivo";
$lang[ 'ud_data_prop_file_required_error' ] = 													'Um arquivo deve ser enviado no campo <strong>%1$s</strong>.';
$lang[ 'ud_data_prop_file_size_exceeds_limit_error' ] = 										'O arquivo enviado no campo <strong>%1$s</strong> possui <strong>%3$s</strong>, excedendo o limite de <strong>%2$s</strong>.';
$lang[ 'ud_data_prop_file_allowed_types_error' ] = 												'O tipo do arquivo enviado no campo <strong>%1$s</strong> não é permitido.';
$lang[ 'ud_data_prop_file_upload_path_error_no_path' ] = 										'Não foi definido um diretório de upload no campo <strong>%1$s</strong>.';
$lang[ 'ud_data_prop_file_upload_path_error_not_a_valid_path' ] = 								'O diretório de upload definido no campo <strong>%1$s</strong> não é um caminho válido.';
$lang[ 'ud_data_prop_file_upload_path_error_not_writable' ] = 									'O diretório de upload definido no campo <strong>%1$s</strong> não é gravável.';
$lang[ 'ud_data_prop_file_moving_error' ] = 													'Não foi possível mover o arquivo <strong>%1$s</strong> para <strong>%1$s</strong>.';
$lang[ 'ud_data_prop_file_upload_library_error' ] = 											'<p>A biblioteca de envio retornou os seguintes errors:</p>%1$s';
$lang[ 'ud_data_prop_file_create_thumb_path_error' ] = 											'Não foi possível criar o diretório de miniatura <strong>%1$s</strong>.';
$lang[ 'ud_data_prop_file_copy_thumb_error' ] = 												'Não foi possível copiar o arquivo fonte da miniatura <strong>%1$s</strong> para <strong>%2$s</strong>.';
$lang[ 'ud_data_prop_file_thumb_path_not_writable_error' ] = 									'O diretório destino da miniatura <strong>%1$s</strong> não é gravável.';

$lang[ 'ud_data_prop_file_tmp_path_error_not_valid' ] = 										'O diretório temporário não é válido.';
$lang[ 'ud_data_prop_file_tmp_path_error_not_writable' ] = 										'O diretório temporário não é gravável.';
$lang[ 'ud_data_prop_file_tmp_upload_path_error_cant_create_dir' ] = 							'Não foi possível criar o diretório de temporário upload.';
$lang[ 'ud_data_prop_file_tmp_upload_path_error_not_valid' ] = 									'O diretório temporário de upload não é válido.';
$lang[ 'ud_data_prop_file_tmp_upload_path_error_not_writable' ] = 								'O diretório temporário de upload não é gravável.';

$lang[ 'ud_advanced_options_prop_is_ud_title' ] = 												"Título";
$lang[ 'ud_advanced_options_prop_is_ud_content' ] = 											"Conteúdo";
$lang[ 'ud_advanced_options_prop_is_ud_other_info' ] = 											"Outras informações";
$lang[ 'ud_advanced_options_prop_is_ud_email' ] = 												"E-mail";
$lang[ 'ud_advanced_options_prop_is_ud_url' ] = 												"Link (url)";
$lang[ 'ud_advanced_options_prop_is_ud_status' ] = 												"Situação (status)";

$lang[ 'tip_ud_advanced_options_prop_is_tip_ud_image' ] = 										"Esta propriedade é do tipo <strong>imagem</strong>";
$lang[ 'tip_ud_advanced_options_prop_is_tip_ud_title' ] = 										"Esta propriedade é do tipo <strong>título</strong>";
$lang[ 'tip_ud_advanced_options_prop_is_tip_ud_content' ] = 									"Esta propriedade é do tipo <strong>conteúdo</strong>";
$lang[ 'tip_ud_advanced_options_prop_is_tip_ud_other_info' ] = 									"Esta propriedade é do tipo <strong>outras informações</strong>";
$lang[ 'tip_ud_advanced_options_prop_is_tip_ud_email' ] = 										"Esta propriedade é do tipo <strong>e-mail</strong>";
$lang[ 'tip_ud_advanced_options_prop_is_tip_ud_url' ] = 										"Esta propriedade é do tipo <strong>link (url)</strong>";
$lang[ 'tip_ud_advanced_options_prop_is_tip_ud_status' ] = 										"Esta propriedade é do tipo <strong>situação (status)</strong>";

$lang[ 'privilege_ud_unified_data_management' ] = 												"Gerenciamento de Dados unificados";
$lang[ 'privilege_access_denied_ud_unified_data_management' ] = 								"Você não possui privilégios suficientes para gerenciar Dados unificados";

// messages

$lang[ 'ud_inserting_ud_data_for_get_id_fail' ] = 												"Falha ao tentar criar dado para obter seu ID";
$lang[ 'ud_data_update_fail_referenced_data' ] = 												"Não foi possível atualizar o valor referenciado no dado de id <strong><i>%s</i></strong>";

/* End of file unified_data_lang.php */
/* Location: ./application/language/pt-BR/admin/unified_data_lang.php */
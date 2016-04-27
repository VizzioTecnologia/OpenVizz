<?php
/* tradução: franksouza.com.br */

$lang['you_are_already_logged'] = 																	"Você está logado!";
$lang['login'] = 																					"Login";
$lang['users_login'] = 																				"Entrar";
$lang['action_login'] = 																			"Entrar";
$lang['action_logout'] = 																			"Sair";
$lang['logout'] = 																					"Sair";
$lang['users_logout'] = 																			"Sair";
$lang['insufficient_information'] = 																"Informações insuficientes";
$lang['login_i_have_no_account'] = 																	"Não tem conta? Cadastre-se!";
$lang['login_get_cplink'] = 																		"Esqueci minha senha";
$lang['login_recover_username'] = 																	"Recuperar nome de login";
$lang['login_resend_activation_code'] = 															"Renviar código de ativação";

$lang['c_users_get_cplink_page_title'] = 															"Alterar senha de acesso";
$lang['c_users_change_pass_page_title'] = 															$lang['c_users_get_cplink_page_title'];
$lang['c_users_get_cplink_page_description'] = 														"
	
	<p>Se você esqueceu, perdeu ou deseja alterar sua senha, informe o endereço de e-mail principal da sua conta.
	Um e-mail será enviado para este endereço contendo um link para redefinir sua senha.
	Não é possível recuperar a senha perdida.<p>
	
	<p>Links antigos de redefinição de senha serão invalidados.</p>
	
";
$lang['c_users_submit_get_cplink_field_label'] = 													"Enviar";
$lang['notif_c_users_get_cplink_invalid_user_error'] = 												"Não existe nenhum usuário cadastrado utilizando o endereço de e-mail informado, verifique se digitou corretamente.";
$lang['c_users_send_cplink_error'] = 																"Erro ao processar os dados";
$lang['notif_c_users_send_cplink_account_disabled_desc'] = 													'
	
	<p>Antes de alterar sua senha, <a href="%6$s">ative sua conta</a>.<p>
	
';
$lang['email_c_users_your_cplink_subject_string'] = 													'%1$s, aqui está o link para redefinir sua senha';
$lang['email_c_users_your_cplink_body_string'] = 													'
	
	<p>
		
		Olá, <strong>%1$s!</strong>
		
	</p>
	
	<p>
		
		Você solicitou a redefinição da sua senha em <strong><a href="%11$s">%10$s</a></strong>, para alterá-la, clique <a href="%5$s">aqui</a> ou cole o seguinte link no seu navegador:
		%5$s
		
	</p>
	
	<p>
		
		O link vencerá em breve, portanto, utilize-o imediatamente.
		
	</p>
	
';
$lang['c_users_new_password_field_label'] = 														"Nova senha";
$lang['c_users_submit_submit_change_pass_field_label'] = 											"Alterar";
$lang['notif_c_users_change_pass_error'] = 															"Erro ao tentar modificar sua senha";
$lang['c_users_form_validation_new_password_required_error'] = 										"Por favor, informe sua <strong><i>nova senha</i></strong>";
$lang['notif_c_users_invalid_cpcode_error'] = 														"Código de redefinição de senha inválido! Tente reenviar um novo link de redefinição";
$lang['notif_c_users_cplink_sent_success'] = 														"
	
	<p>Um link para redefinição de senha foi enviado para o e-mail informado, verifique sua caixa de entrada dentro de instantes.
	Caso não tenha recebido nenhum e-mail, verifique sua <spam>caixa de spam (lixo eletrônico)</strong></p>
	
";
$lang['notif_c_users_pass_changed_success'] = 														"Sua senha foi alterada com sucesso! Tente fazer login.";
$lang['notif_c_users_send_cplink_invalid_user_error'] = 											"Usuário inexistente";
$lang['notif_c_users_cplink_sent_error'] = 															"Erro ao tentar enviar e-mail de recuperação de senha";

// ------------------------

$lang['c_users_recover_username_page_title'] = 														"Recuperar nome de login";
$lang['c_users_recover_username_page_description'] = 												"
	
	<p>Informe o endereço de e-mail principal da sua conta.
	Um e-mail contendo seu nome de login será enviado para o endereço informado.
	
";
$lang['c_users_submit_recover_username_field_label'] = 												"Enviar";
$lang['notif_c_users_recover_username_invalid_user_error'] = 										"Não existe nenhum usuário cadastrado utilizando o endereço de e-mail informado, verifique se digitou corretamente.";
$lang['c_users_recover_username_error'] = 															"Erro ao processar os dados";
$lang['notif_c_users_recover_username_account_disabled_desc'] = 											'
	
	<p>Antes de alterar sua senha, <a href="%6$s">ative sua conta</a>.<p>
	
';
$lang['email_c_users_recover_username_subject_string'] = 											'%1$s, aqui está seu nome de login';
$lang['email_c_users_recover_username_body_string'] = 												'
	
	<p>
		
		Olá, <strong>%1$s!</strong>
		
	</p>
	
	<p>
		
		Seu nome de login é <strong>%2$s</strong>
		
	</p>
	
	<p>
		
		Acesse <a href="%4$s">%4$s</a> e faça seu login.
		
	</p>
	
';
$lang['c_users_new_password_field_label'] = 														"Nova senha";
$lang['c_users_submit_submit_change_pass_field_label'] = 											"Alterar";
$lang['notif_c_users_change_pass_error'] = 															"Erro ao tentar modificar sua senha";
$lang['notif_c_users_recover_username_sent_success'] = 														"
	
	<p>O seu nome de login foi enviado para o e-mail informado, verifique sua caixa de entrada dentro de instantes.
	Caso não tenha recebido nenhum e-mail, verifique sua <spam>caixa de spam (lixo eletrônico)</strong></p>
	
";
$lang['notif_c_users_pass_changed_success'] = 														"Sua senha foi alterada com sucesso! Tente fazer login.";
$lang['notif_c_users_recover_username_invalid_user_error'] = 										"Usuário inexistente";
$lang['notif_c_users_recover_username_sent_error'] = 												"Erro ao tentar enviar e-mail de recuperação de nome de login";
$lang['c_users_recover_username_invalid_data_error'] = 												"Erro ao processar os dados";

// ------------------------

$lang['c_users_register_page_title'] = 																"Criar conta";
$lang['c_users_user_register_success'] = 															"Sua conta foi criada com sucesso";
$lang['notif_c_users_account_created_success_no_active_site'] = 									"Sua conta foi criada com sucesso, mas está desativada. Um e-mail contendo o link de ativação foi enviado para o e-mail que você informou, verifique sua caixa de entrada.";

// ------------------------

$lang['c_users_activate_account_page_title'] = 														"Ativar conta";
$lang['c_users_acode_field_label'] = 																"Código de ativação";
$lang['c_users_submit_acode_field_label'] = 														"Ativar";
$lang['username'] = 																				"Nome de login";
$lang['user_username'] = 																			"Nome de login";
$lang['complete_name'] = 																			"Nome completo";
$lang['user_name'] = 																				"Nome completo";
$lang['user_email'] = 																				"E-mail";
$lang['password'] = 																				"Senha";
$lang['user_password'] = 																			"Senha";
$lang['c_users_password_field_label'] = 															"Senha";
$lang['confirm_password'] = 																		"Repita a senha";
$lang['c_users_confirm_password_field_label'] = 													"Repita a senha";
$lang['action_register'] = 																			"Registrar";
$lang['c_users_submit_submit_register_field_label'] = 												"Registrar";
$lang['c_users_resend_acode_page_title'] = 															"Reenviar código de ativação";
$lang['c_users_resend_acode_error'] = 																"Erro ao tentar reenviar código de ativação!";
$lang['c_users_resend_acode_page_description'] = 													"
	
	<p>Se não recebeu o e-mail contendo o código de ativação, informe o e-mail que usou para criar sua conta.
	Um novo e-mail será enviado para este endereço contendo um novo link de ativação.<p>
	
	<p>Códigos de ativação antigos serão invalidados.</p>
	
";
$lang['c_users_captcha_field_label'] = 																"Digite o texto da imagem";
$lang['c_users_form_validation_captcha_required_error'] = 											"Por favor, resolva o captcha.";
$lang['c_users_form_validation_captcha_captcha_error'] = 											"O texto captcha é inválido.";
$lang['c_users_submit_resend_acode_field_label'] = 													"Enviar código";
$lang['c_users_name_field_label'] = 																"Nome";
$lang['c_users_complete_name_field_label'] = 														"Nome completo";
$lang['c_users_username_field_label'] = 															"Nome de login";
$lang['tip_c_users_username_field_label'] = 														"Informe um nome de login que será usado para fazer login no site";
$lang['c_users_email_field_label'] = 																"E-mail";
$lang['notif_c_users_send_acode_error'] = 															"Não foi possível enviar código de ativação!";
$lang['notif_c_users_resend_acode_success'] = 														"Um novo código de ativação foi enviado para seu e-mail, verifique sua caixa de entrada dentro de instantes. Se não recebeu nenhum e-mail em sua caixa de entrada, verifique sua <spam>caixa de spam (lixo eletrônico)</strong>";
$lang['notif_c_users_already_have_account_disabled'] = 												"Esta conta está <strong>desativada!</strong>";
$lang['notif_c_users_already_have_account_disabled_desc'] = 										"Antes de efetuar login, é necessário ativar sua conta.";
$lang['notif_c_users_already_have_account_disabled_desc'] = 													'
	
	<p>Para ativar sua conta, <a href="%6$s">clique aqui</a>.<p>
	
';
$lang['notif_c_users_account_already_active_error'] = 												"Esta conta já está ativa.";
$lang['notif_c_users_activation_account_success'] = 												"Sua conta foi ativada com sucesso! Você pode efetuar login.";
$lang['notif_c_users_invalid_acode_error'] = 														"Código de ativação inválido.";
$lang['notif_c_users_acode_sent_success'] = 														"Código de ativação enviado para seu e-mail, verifique sua caixa de entrada dentro de instantes. Se não recebeu nenhum e-mail em sua caixa de entrada, verifique sua <spam>caixa de spam (lixo eletrônico)</strong>";
$lang['notif_c_users_send_acode_invalid_user_error'] = 												"Usuário inexistente!";
$lang['notif_c_users_send_pass_invalid_user_error'] = 												"Usuário inexistente!";
$lang['notif_c_users_activate_account_error'] = 													"Erro ao tentar ativar conta de usuário";
$lang['notif_c_users_login_success'] = 																"Você está logado!";
$lang['notif_c_users_acode_sent_error'] = 															"O código de ativação não pode ser enviado.";

$lang['email_c_users_your_acode_subject_string'] = 													'%1$s, aqui está seu código de ativação de conta';
$lang['email_c_users_your_acode_body_string'] = 													'
	
	<p>
		
		Olá, <strong>%1$s!</strong>
		
	</p>
	
	<p>
		
		Este e-mail é referente a ativação da sua conta em <strong><a target="_blank" href="%13$s">%12$s</a></strong>.
		Clique <strong><a target="_blank" href="%11$s">aqui</a></strong> para ativar sua conta ou cole o código <strong>%14$s</strong> em <strong><a target="_blank" href="%10$s">%10$s</a></strong>.
		
	</p>
	
	<p>
		
		O código vencerá em breve, portanto, utilize-o imediatamente.
		
	</p>
	
';

$lang['users_login_success'] = 																		"Login efetuado com sucesso!";
$lang['users_logout_success'] = 																	"Logout efetuado com sucesso!";

$lang['access_denied'] = 																			"Acesso negado";
$lang['login_fail'] = 																				"Erro de login";
$lang['the_user_does_not_exist'] = 																	"Usuário inexistente!";
$lang['incorrect_password'] = 																		"Senha incorreta!";
$lang['authentication_failure'] = 																	"Falha de autenticação!";
$lang['you_must_be_logged_in'] = 																	"Você não está logado";
$lang['c_users_error_new_registers_need_group_id'] = 												"Grupo de usuários para novos cadastros não especificado!";

$lang['c_users_form_validation_is_unique'] = 														"%s já existente em nossa base de dados";
$lang['c_users_form_validation_matches'] = 															"Os campos %s e %s não conferem";
$lang['c_users_form_validation_name_required_error'] = 												"Por favor, informe seu <strong><i>nome completo</i></strong>";
$lang['c_users_form_validation_name_min_length_error'] = 											'O <strong><i>nome</i></strong> deve conter pelo menos <strong><i>%2$s</i></strong> caractéres';
$lang['c_users_form_validation_username_required_error'] = 											"Por favor, informe um <strong><i>nome de login</i></strong>";
$lang['c_users_form_validation_username_min_length_error'] = 										'O <strong><i>nome de login</i></strong> deve conter pelo menos <strong><i>%2$s</i></strong> caractéres';
$lang['c_users_form_validation_username_max_length_error'] = 										'O <strong><i>nome de login</i></strong> não pode conter mais que <strong><i>%2$s</i></strong> caractéres';
$lang['c_users_form_validation_username_is_unique_error'] = 										'Já existe uma conta utilizando o nome de login <strong><i>%1$s</i></strong>. Se o nome de login está correto, você pode <a href="%2$s">fazer login</a> usando-o juntamente com sua senha.<p>Esqueceu sua senha? Acesse a <a href="%3$s">página de recuperação de senha</a>.<br/>Esqueceu seu e-mail? Acesse a <a href="%4$s">página de recuperação de e-mail</a>.</p>';
$lang['c_users_form_validation_username_alpha_dash_error'] = 										'O <strong><i>nome de login</i></strong> pode conter apenas letras, números, traços e sublinhados.';
$lang['c_users_form_validation_email_is_unique_error'] = 											'Já existe uma conta utilizando o e-mail <strong><i>%1$s</i></strong>. Se o e-mail está correto, você pode <a href="%2$s">fazer login</a> usando seu nome de login e senha.<p>Esqueceu seu nome de login? Acesse a <a href="%5$s">página de recuperação de nome de login</a>.<br/>Esqueceu sua senha? Acesse a <a href="%3$s">página de recuperação de senha</a>.</p>';
$lang['c_users_form_validation_email_required_error'] = 											"Por favor, informe seu <strong><i>e-mail</i></strong>";
$lang['c_users_form_validation_email_valid_email_error'] = 											"Por favor, informe um endereço de <strong><i>e-mail</i></strong> válido";
$lang['c_users_form_validation_password_required_error'] = 											"Por favor, informe sua <strong><i>senha</i></strong>";
$lang['c_users_form_validation_password_min_length_error'] = 										'O <strong><i>nome de login</i></strong> deve conter pelo menos <strong><i>%2$s</i></strong> caractéres';
$lang['c_users_form_validation_password_max_length_error'] = 										'O <strong><i>nome de login</i></strong> não pode conter mais que <strong><i>%2$s</i></strong> caractéres';
$lang['c_users_form_validation_confirm_password_required_error'] = 									"Por favor, confirme a senha";
$lang['c_users_form_validation_confirm_password_matche_error'] = 									"As senhas não conferem, tente novamente";

$lang['user'] = 																					"Usuário";
$lang['users'] = 																					"Usuários";
$lang['add_user'] = 																				"Adicionar usuário";
$lang['new_user'] = 																				"Novo usuário";
$lang['keep_me_logged_in'] = 																		"Mantenha-me conectado";

$lang['users_group'] = 																				"Grupo de usuários";
$lang['users_groups'] = 																			"Grupos de usuários";
$lang['add_users_group'] = 																			"Adicionar grupo de usuários";
$lang['new_users_group'] = 																			"Novo grupo de usuários";

$lang['access'] = 																					"Nível de acesso";
$lang['public'] = 																					"Público";
$lang['access_user'] = 																				"Usuário";
$lang['specific_users'] = 																			"Usuários específicos";
$lang['access_group'] = 																			"Grupo de usuários";
$lang['specific_users_groups'] = 																	"Grupos de usuários específicos";

/* End of file general_lang.php */
/* Location: ./system/language/english/users_lang.php */
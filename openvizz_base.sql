-- phpMyAdmin SQL Dump
-- version 4.0.10deb1
-- http://www.phpmyadmin.net
--
-- Servidor: localhost
-- Tempo de Geração: 28/02/2016 às 22:19
-- Versão do servidor: 5.5.43-0ubuntu0.14.04.1
-- Versão do PHP: 5.5.9-1ubuntu4.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Banco de dados: `openvizz_dev`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `tb_articles`
--

CREATE TABLE IF NOT EXISTS `tb_articles` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `alias` varchar(255) DEFAULT NULL,
  `category_id` int(10) unsigned DEFAULT '0',
  `ordering` int(10) unsigned NOT NULL,
  `title` longtext NOT NULL,
  `introtext` longtext NOT NULL,
  `fulltext` mediumtext NOT NULL,
  `status` tinyint(3) unsigned NOT NULL DEFAULT '2' COMMENT '-1 = archived; 0 = unpublished; 1 = published',
  `access_type` varchar(100) NOT NULL,
  `access_id` mediumtext NOT NULL,
  `created_by_id` int(11) unsigned DEFAULT NULL,
  `created_date` datetime DEFAULT '0000-00-00 00:00:00',
  `modified_by_id` int(11) DEFAULT NULL,
  `modified_date` datetime DEFAULT '0000-00-00 00:00:00',
  `publish_user_id` int(11) NOT NULL,
  `publish_datetime` datetime NOT NULL,
  `params` mediumtext,
  `created_by_alias` varchar(255) DEFAULT NULL,
  `image` mediumtext,
  PRIMARY KEY (`id`),
  KEY `created_by_id` (`created_by_id`),
  KEY `modified_by_id` (`modified_by_id`),
  KEY `category_id` (`category_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estrutura para tabela `tb_articles_categories`
--

CREATE TABLE IF NOT EXISTS `tb_articles_categories` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `alias` varchar(255) NOT NULL,
  `parent` int(10) unsigned DEFAULT '0',
  `title` varchar(45) NOT NULL,
  `description` mediumtext NOT NULL,
  `ordering` int(10) unsigned NOT NULL,
  `status` int(10) unsigned NOT NULL DEFAULT '1',
  `image` mediumtext,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estrutura para tabela `tb_captcha`
--

CREATE TABLE IF NOT EXISTS `tb_captcha` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `time` varchar(100) NOT NULL,
  `ip_address` varchar(16) CHARACTER SET latin1 NOT NULL DEFAULT '0',
  `word` varchar(30) CHARACTER SET latin1 NOT NULL,
  PRIMARY KEY (`id`),
  KEY `word` (`word`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estrutura para tabela `tb_cities`
--

CREATE TABLE IF NOT EXISTS `tb_cities` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `state_id` int(10) unsigned NOT NULL,
  `title` varchar(100) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `state_id` (`state_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estrutura para tabela `tb_companies`
--

CREATE TABLE IF NOT EXISTS `tb_companies` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `trading_name` varchar(100) NOT NULL,
  `company_name` varchar(100) DEFAULT NULL,
  `contacts` mediumtext NOT NULL,
  `phones` mediumtext NOT NULL,
  `emails` mediumtext NOT NULL,
  `addresses` mediumtext NOT NULL,
  `state_registration` varchar(30) DEFAULT NULL,
  `sic` varchar(30) DEFAULT NULL COMMENT 'Standard Industrial Classification, Brazilian equivalent = CNAE',
  `corporate_tax_register` varchar(30) DEFAULT NULL COMMENT 'Brazilian equivalent = CNPJ',
  `foundation_date` varchar(30) DEFAULT NULL,
  `favicon` varchar(255) NOT NULL,
  `logo_thumb` varchar(255) NOT NULL,
  `logo` varchar(255) NOT NULL,
  `websites` varchar(300) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estrutura para tabela `tb_components`
--

CREATE TABLE IF NOT EXISTS `tb_components` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `unique_name` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `status` int(10) unsigned NOT NULL,
  `type` varchar(30) NOT NULL,
  `version` float NOT NULL,
  `depends` mediumtext NOT NULL,
  `only_admin` int(10) unsigned NOT NULL,
  `admin_url` varchar(255) NOT NULL,
  `short_description` longtext,
  `description` mediumtext,
  `core` int(10) unsigned NOT NULL,
  `params` mediumtext,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_name` (`unique_name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=18 ;

--
-- Fazendo dump de dados para tabela `tb_components`
--

INSERT INTO `tb_components` (`id`, `unique_name`, `title`, `status`, `type`, `version`, `depends`, `only_admin`, `admin_url`, `short_description`, `description`, `core`, `params`) VALUES
(1, 'main', 'Main', 1, 'core', 0, '', 1, 'admin/main', NULL, NULL, 1, '{"time_reference":"GMT","time_zone":"UM3","dst":"1","js_text_editor":"tinymce","minify_output":"1","cache_path":"application\\/cache\\/","log_threshold":"0","log_path":"","log_date_format":"Y-m-d H:i:s","sess_expiration":"60","sess_expire_on_close":"1","sess_encrypt_cookie":"1","sess_use_database":"0","sess_table_name":"tb_sessions","sess_match_ip":"0","sess_match_useragent":"1","sess_time_to_update":"300","global_xss_filtering":"0","email_config_enabled":"1","email_config_protocol":"smtp","email_config_smtp_crypto":"tls","email_config_smtp_host":"ssl:\\/\\/smtp.googlemail.com","email_config_smtp_port":"465","email_config_smtp_user":"gmail_email@gmail.com","email_config_smtp_pass":"","email_config_mailtype":"html","email_config_charset":"utf-8","email_config_wordwrap":"1","email_config_newline":"\\\\r\\\\n","email_config_useragent":"OpenVizz","admin_name":"OpenVizz - Administra\\u00e7\\u00e3o","admin_theme":"default","admin_language":"pt-BR","admin_items_per_page":"10","admin_custom_items_per_page":"2","admin_hide_pagination_when_only_one_page":"1","site_name":"OpenVizz","author_name":"OpenVizz","site_copyright":"\\u00a9 Vizzio Tecnologia","site_theme":"default","site_language":"pt-BR","site_items_per_page":"30","site_custom_items_per_page":"6","site_hide_pagination_when_only_one_page":"1","friendly_urls":"0","seo_title_prefix":"","seo_title_suffix":"","seo_title_separator":" | ","meta_keywords":"","meta_description":"","meta_custom":"","meta_scripts_declaration_custom":"","google_site_verification":"","google_base_app_name":"","google_client_id":"","google_developer_key":"","google_client_secret":"","google_email_address":"","google_service_client_id":"","google_service_email_address":"","show_google_plus_comments":"0","show_facebook_comments":"0","show_disqus_comments":"0","disqus_shortname":""}'),
(2, 'users', 'users', 1, 'users', 0, '[{"unique_name":"contacts"}]', 0, 'admin/users', NULL, NULL, 1, ''),
(3, 'menus', 'Menus', 1, 'menus', 0, '[{"unique_name":"users"}]', 1, 'admin/menus', NULL, NULL, 1, NULL),
(4, 'articles', 'Articles', 1, 'content', 0, '[{"unique_name":"users"}]', 0, 'admin/articles', NULL, NULL, 0, ''),
(5, 'submit_forms', 'submit_forms', 1, 'submit_forms', 0, '', 0, 'admin/submit_forms', NULL, NULL, 1, NULL),
(6, 'contacts', 'Contacts', 1, 'contacts', 0, '[{"unique_name":"users"}]', 0, 'admin/contacts', NULL, NULL, 1, ''),
(7, 'companies', 'Companies', 1, 'companies', 0, '[{"unique_name":"contacts"}]', 0, 'admin/companies', NULL, NULL, 0, NULL),
(8, 'places', 'places', 1, 'places', 0, '', 0, 'admin/places', NULL, NULL, 0, NULL),
(13, 'modules', 'Modules', 1, 'modules_management', 0, '{"1":{"unique_name":"menus"},"2":{"unique_name":"users"}}', 1, 'admin/modules', NULL, NULL, 1, NULL),
(14, 'responsive_file_manager', 'responsive_file_manager', 1, 'file_manager', 0, '', 0, 'admin/responsive_file_manager', NULL, NULL, 0, NULL),
(15, 'urls', 'urls', 1, 'urls_management', 0, '', 1, 'admin/urls', NULL, NULL, 1, NULL),
(16, 'plugins', 'plugins', 1, 'plugins_management', 1, '', 0, 'admin/plugins', NULL, NULL, 0, NULL),
(17, 'unid', 'unid', 0, 'unid_management', 1, '', 0, 'admin/unified_data', NULL, NULL, 0, NULL);

-- --------------------------------------------------------

--
-- Estrutura para tabela `tb_component_items`
--

CREATE TABLE IF NOT EXISTS `tb_component_items` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `component_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `alias` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;

--
-- Fazendo dump de dados para tabela `tb_component_items`
--

INSERT INTO `tb_component_items` (`id`, `component_id`, `title`, `alias`) VALUES
(1, 4, 'Articles list', 'articles_list'),
(2, 4, 'Article detail', 'article_detail'),
(3, 4, 'Send article', 'send_article'),
(4, 6, 'Contact details', 'contact_details'),
(5, 2, 'Login page', 'login_page'),
(6, 5, 'submit_form', 'submit_form');

-- --------------------------------------------------------

--
-- Estrutura para tabela `tb_contacts`
--

CREATE TABLE IF NOT EXISTS `tb_contacts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `user_id_assoc` int(10) unsigned NOT NULL,
  `name` varchar(100) NOT NULL,
  `given_name` varchar(30) NOT NULL,
  `additional_name` varchar(30) NOT NULL,
  `family_name` varchar(30) NOT NULL,
  `name_prefix` varchar(30) NOT NULL,
  `name_sufix` varchar(30) NOT NULL,
  `initials` varchar(30) NOT NULL,
  `nickname` varchar(30) NOT NULL,
  `thumb_local` mediumtext NOT NULL,
  `photo_local` mediumtext NOT NULL,
  `thumb_url` mediumtext NOT NULL,
  `photo_url` mediumtext NOT NULL,
  `group_membership` int(11) NOT NULL,
  `emails` mediumtext NOT NULL,
  `birthday_date` varchar(10) NOT NULL,
  `company_id` varchar(50) NOT NULL,
  `phones` mediumtext NOT NULL,
  `addresses` mediumtext NOT NULL,
  `websites` mediumtext NOT NULL,
  `params` mediumtext NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estrutura para tabela `tb_countries`
--

CREATE TABLE IF NOT EXISTS `tb_countries` (
  `id` int(2) unsigned NOT NULL AUTO_INCREMENT,
  `alias` varchar(100) NOT NULL,
  `title` varchar(100) NOT NULL,
  `status` tinyint(2) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estrutura para tabela `tb_customers`
--

CREATE TABLE IF NOT EXISTS `tb_customers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `title` varchar(100) DEFAULT NULL,
  `category_id` int(11) NOT NULL,
  `type` varchar(30) NOT NULL,
  `company_id` int(11) NOT NULL,
  `contact_id` int(11) NOT NULL,
  `customer_since` varchar(30) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `company_id` (`company_id`),
  KEY `contact_id` (`contact_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estrutura para tabela `tb_customers_categories`
--

CREATE TABLE IF NOT EXISTS `tb_customers_categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `alias` varchar(255) NOT NULL,
  `title` varchar(100) NOT NULL,
  `description` mediumtext,
  `status` int(5) unsigned NOT NULL,
  `ordering` int(10) unsigned NOT NULL,
  `parent` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `parent` (`parent`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estrutura para tabela `tb_menus`
--

CREATE TABLE IF NOT EXISTS `tb_menus` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `alias` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` mediumtext NOT NULL,
  `ordering` int(10) unsigned NOT NULL,
  `link` mediumtext NOT NULL,
  `type` varchar(50) NOT NULL,
  `status` int(10) unsigned NOT NULL DEFAULT '1',
  `access_type` varchar(100) NOT NULL,
  `access_ids` mediumtext NOT NULL,
  `parent` int(10) unsigned NOT NULL DEFAULT '0',
  `component_id` int(10) unsigned NOT NULL,
  `component_item` varchar(100) NOT NULL,
  `menu_type_id` int(10) unsigned NOT NULL,
  `params` mediumtext NOT NULL,
  `home` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `menu_type_id` (`menu_type_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Fazendo dump de dados para tabela `tb_menus`
--

INSERT INTO `tb_menus` (`id`, `alias`, `title`, `description`, `ordering`, `link`, `type`, `status`, `access_type`, `access_ids`, `parent`, `component_id`, `component_item`, `menu_type_id`, `params`, `home`) VALUES
(1, 'home', 'Home', '', 0, 'main/bc/0/1', 'blank_content', 1, 'public', '0', 0, 0, '0', 1, '{"show_page_content_title":"1","custom_page_content_title":"","custom_page_title":"","page_class":"","meta_keywords":"","meta_description":""}', 1);

-- --------------------------------------------------------

--
-- Estrutura para tabela `tb_menu_items_types`
--

CREATE TABLE IF NOT EXISTS `tb_menu_items_types` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `type` varchar(100) NOT NULL,
  `component_id` int(11) NOT NULL,
  `component_item` varchar(100) NOT NULL,
  `title` varchar(100) NOT NULL,
  `parent` int(11) NOT NULL,
  `description` mediumtext NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=19 ;

--
-- Fazendo dump de dados para tabela `tb_menu_items_types`
--

INSERT INTO `tb_menu_items_types` (`id`, `type`, `component_id`, `component_item`, `title`, `parent`, `description`) VALUES
(1, '', 0, '', 'articles', 0, ''),
(2, '', 0, '', 'articles', 1, ''),
(3, 'component', 4, 'article_detail', 'article_detail', 2, ''),
(4, 'external_link', 0, '', 'external_link', 0, ''),
(5, 'separator', 0, '', 'separator', 0, ''),
(6, '', 0, '', 'articles_list', 1, ''),
(7, 'component', 4, 'articles_list', 'articles_list', 6, ''),
(8, 'blank_content', 0, '', 'blank_content', 0, ''),
(9, '', 0, '', 'Contacts', 0, ''),
(10, 'component', 6, 'contact_details', 'Contact details', 9, ''),
(11, '', 0, '', 'submit_forms', 0, ''),
(14, 'component', 5, 'submit_form', 'submit_form', 11, ''),
(15, 'html_content', 0, '', 'html_content', 0, ''),
(16, 'component', 5, 'users_submits', 'users_submits', 11, ''),
(17, '', 0, '', 'users', 0, ''),
(18, 'component', 2, 'login_page', 'login_page', 17, '');

-- --------------------------------------------------------

--
-- Estrutura para tabela `tb_menu_types`
--

CREATE TABLE IF NOT EXISTS `tb_menu_types` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `alias` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` longtext,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Fazendo dump de dados para tabela `tb_menu_types`
--

INSERT INTO `tb_menu_types` (`id`, `alias`, `title`, `description`) VALUES
(1, 'main-menu', 'Main menu', '');

-- --------------------------------------------------------

--
-- Estrutura para tabela `tb_modules`
--

CREATE TABLE IF NOT EXISTS `tb_modules` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(255) NOT NULL,
  `status` int(11) NOT NULL,
  `mi_cond` mediumtext NOT NULL COMMENT 'Menu items condition',
  `position` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `alias` varchar(255) NOT NULL,
  `ordering` int(11) NOT NULL,
  `access_type` varchar(100) NOT NULL,
  `access_ids` mediumtext NOT NULL,
  `params` mediumtext NOT NULL,
  `environment` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=28 ;

--
-- Fazendo dump de dados para tabela `tb_modules`
--

INSERT INTO `tb_modules` (`id`, `type`, `status`, `mi_cond`, `position`, `title`, `alias`, `ordering`, `access_type`, `access_ids`, `params`, `environment`) VALUES
(26, 'admin_menu', 1, '{"all":[]}', 'main_tools', 'Menu do sistema', 'menu-do-sistema', 0, 'users_groups', '>1<', '{"admin_menu_layout":"default","admin_menu_direction":"v","show_title":"1","module_class":""}', 'admin');

-- --------------------------------------------------------

--
-- Estrutura para tabela `tb_modules_menu`
--

CREATE TABLE IF NOT EXISTS `tb_modules_menu` (
  `module_id` int(11) NOT NULL DEFAULT '0',
  `menu_item_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`module_id`,`menu_item_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `tb_neighborhoods`
--

CREATE TABLE IF NOT EXISTS `tb_neighborhoods` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `city_id` int(11) DEFAULT NULL,
  `title` varchar(100) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `city_id` (`city_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estrutura para tabela `tb_plugins`
--

CREATE TABLE IF NOT EXISTS `tb_plugins` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(255) CHARACTER SET latin1 NOT NULL,
  `status` int(11) NOT NULL,
  `name` varchar(100) CHARACTER SET latin1 NOT NULL,
  `version` float NOT NULL,
  `depends` text CHARACTER SET latin1 NOT NULL,
  `title` varchar(255) CHARACTER SET latin1 NOT NULL,
  `ordering` int(11) NOT NULL,
  `access` text CHARACTER SET latin1 NOT NULL,
  `params` text CHARACTER SET latin1 NOT NULL,
  `environment` varchar(255) CHARACTER SET latin1 NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=44 ;

--
-- Fazendo dump de dados para tabela `tb_plugins`
--

INSERT INTO `tb_plugins` (`id`, `type`, `status`, `name`, `version`, `depends`, `title`, `ordering`, `access`, `params`, `environment`) VALUES
(35, 'search', 1, 'articles_categories_search', 1, '', 'articles_categories_search', 30, '', '', 'both'),
(34, 'search', 1, 'companies_search', 1, '', 'companies_search', 29, '', '', 'both'),
(33, 'search', 1, 'articles_search', 1, '', 'articles_search', 28, '', '', 'both'),
(32, 'js_jquery_scrolltop', 1, 'jquery_scrolltop', 1, '[{"name":"jquery"}]', 'jquery_scrolltop', 27, '', '', 'both'),
(30, 'image_cropper', 1, 'image_cropper', 1, '[{"name":"fancybox"}]', 'image_cropper', 26, '', '', 'both'),
(29, 'js_jquery_checkboxes', 1, 'jquery_checkboxes', 1, '[{"name":"jquery"}]', 'jquery_checkboxes', 25, '', '', 'both'),
(28, 'js_inline_edit', 1, 'jquery_inline_edit', 1, '[{"name":"jquery_ui"}]', 'jquery_inline_edit', 24, '', '', 'both'),
(27, 'js_modal_users_submits', 1, 'modal_users_submits', 1, '[{"name":"fancybox"}]', 'modal_users_submits', 23, '', '', 'both'),
(25, 'js_modal_file_picker', 1, 'modal_rf_file_picker', 1, '[{"name":"fancybox"}]', 'modal_rf_file_picker', 21, '', '', 'both'),
(26, 'js_modal_contacts', 1, 'modal_contacts', 1, '[{"name":"fancybox"},{"name":"jquery_ui"},{"name":"jquery_inline_edit"},{"name":"modal_rf_file_picker"},{"name":"image_cropper"}]', 'modal_contacts', 22, '', '', 'both'),
(24, 'js_modal_articles_picker', 1, 'modal_articles_picker', 1, '[{"name":"fancybox"}]', 'modal_articles_picker', 20, '', '', 'both'),
(23, 'js_modal', 1, 'fancybox', 0.1, '[{"name":"jquery"}]', 'fancybox', 19, '', '', 'both'),
(21, 'after_content', 1, 'google_plus_comments', 1, '', 'google_plus_comments', 18, '', '', 'both'),
(20, 'after_content', 1, 'disqus', 1, '', 'disqus', 17, '', '', 'both'),
(19, 'after_content', 1, 'facebook_comments', 1, '', 'facebook_comments', 16, '', '', 'both'),
(18, 'js_live_search', 1, 'viacms_live_search', 2, '[{"name":"qtip2","version":"2"}]', 'viacms_live_search', 15, '', '', 'both'),
(17, 'js_tab_generator', 1, 'yetii', 1.8, '[{"name":"jquery"}]', 'yetii', 14, '', '', 'both'),
(16, 'content', 1, 'article_loader', 1, '', 'article_loader', 13, '', '', 'both'),
(15, 'js_time_picker', 1, 'jquery_ui_timepicker', 0, '[{"name":"jquery_ui"}]', 'jquery_ui_timepicker', 12, '', '', 'both'),
(14, 'js_tooltip', 1, 'qtip2', 221, '[{"name":"jquery"}]', 'qtip2', 11, '', '', 'both'),
(13, 'js_jquery_library', 1, 'jquery_ui', 0, '[{"name":"jquery"}]', 'jquery_ui', 2, '', '', 'both'),
(1, 'js_framework', 1, 'jquery', 1.111, '', 'jquery', 1, '', '', 'both'),
(2, 'js_text_editor', 1, 'tinymce', 0, '[{"name":"jquery"}]', 'tinymce', 3, '', '', 'both'),
(36, 'search', 1, 'menu_items_search', 1, '', 'menu_items_search', 31, '', '', 'both'),
(37, 'content', 1, 'articles_category_loader', 1, '', 'articles_category_loader', 32, '', '', 'both'),
(38, 'content', 1, 'href_parser', 1, '', 'href_parser', 33, '', '', 'both'),
(39, 'search', 1, 'sf_us_search', 1, '', 'sf_us_search', 34, '', '', 'both'),
(40, 'js_image_preloader', 1, 'jquery_lazy', 0.61, '[{"name":"jquery"}]', 'jquery_lazy', 35, '', '', 'both'),
(41, 'content', 1, 'sf_us_loader', 1, '', 'sf_us_loader', 36, '', '', 'both'),
(42, 'jquery_svg_pan_zoom', 1, 'jquery_svg_pan_zoom', 1.03, '[{"name":"jquery"}]', 'jquery_svg_pan_zoom', 37, '', '', 'both'),
(43, 'js_input_mask', 1, 'vanilla_masker', 1.1, '', 'vanilla_masker', 38, '', '', 'both');

-- --------------------------------------------------------

--
-- Estrutura para tabela `tb_providers`
--

CREATE TABLE IF NOT EXISTS `tb_providers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `title` varchar(30) NOT NULL,
  `company_id` int(11) DEFAULT NULL,
  `default_warranty` varchar(255) NOT NULL,
  `default_provider_tax` float NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estrutura para tabela `tb_public_areas`
--

CREATE TABLE IF NOT EXISTS `tb_public_areas` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `neighborhood_id` int(10) unsigned NOT NULL,
  `type` int(10) unsigned NOT NULL,
  `title` varchar(255) NOT NULL,
  `postal_code` varchar(11) NOT NULL,
  `coordinates` varchar(200) NOT NULL,
  `map_url` mediumtext NOT NULL,
  PRIMARY KEY (`id`),
  KEY `neighborhood_id` (`neighborhood_id`),
  KEY `postal_code` (`postal_code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estrutura para tabela `tb_sessions`
--

CREATE TABLE IF NOT EXISTS `tb_sessions` (
  `session_id` varchar(40) NOT NULL DEFAULT '0',
  `ip_address` varchar(16) NOT NULL DEFAULT '0',
  `user_agent` varchar(255) NOT NULL,
  `last_activity` int(10) unsigned NOT NULL DEFAULT '0',
  `user_data` text NOT NULL,
  PRIMARY KEY (`session_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `tb_states`
--

CREATE TABLE IF NOT EXISTS `tb_states` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(100) NOT NULL,
  `acronym` varchar(5) NOT NULL,
  `country_id` int(10) unsigned NOT NULL,
  `status` tinyint(3) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `country_id` (`country_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estrutura para tabela `tb_submit_forms`
--

CREATE TABLE IF NOT EXISTS `tb_submit_forms` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `alias` varchar(100) NOT NULL,
  `title` varchar(100) NOT NULL,
  `create_datetime` datetime NOT NULL,
  `mod_datetime` datetime NOT NULL,
  `ordering` int(10) NOT NULL,
  `status` int(11) NOT NULL,
  `fields` mediumtext,
  `params` mediumtext NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estrutura para tabela `tb_submit_forms_us`
--

CREATE TABLE IF NOT EXISTS `tb_submit_forms_us` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `submit_form_id` int(11) NOT NULL,
  `submit_datetime` datetime NOT NULL,
  `mod_datetime` datetime NOT NULL,
  `output` text,
  `output_submitter` text,
  `data` text,
  `params` text,
  `xml_data` longtext,
  PRIMARY KEY (`id`),
  KEY `submit_form_id` (`submit_form_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estrutura para tabela `tb_tmp_data`
--

CREATE TABLE IF NOT EXISTS `tb_tmp_data` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `reference` varchar(255) NOT NULL,
  `data` mediumtext NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estrutura para tabela `tb_unid_data`
--

CREATE TABLE IF NOT EXISTS `tb_unid_data` (
  `id` int(11) NOT NULL DEFAULT '0',
  `data_schema_id` int(11) NOT NULL,
  `create_datetime` datetime NOT NULL,
  `mod_datetime` datetime NOT NULL,
  `output` text,
  `output_submitter` text,
  `data` text,
  `params` text,
  `xml_data` longtext,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `tb_unid_data_schemas`
--

CREATE TABLE IF NOT EXISTS `tb_unid_data_schemas` (
  `id` int(11) NOT NULL DEFAULT '0',
  `alias` varchar(100) NOT NULL,
  `title` varchar(100) NOT NULL,
  `create_datetime` datetime NOT NULL,
  `mod_datetime` datetime NOT NULL,
  `ordering` int(10) NOT NULL,
  `status` int(11) NOT NULL,
  `properties` mediumtext,
  `params` mediumtext NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura para tabela `tb_urls`
--

CREATE TABLE IF NOT EXISTS `tb_urls` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sef_url` mediumtext NOT NULL,
  `target` mediumtext NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Fazendo dump de dados para tabela `tb_urls`
--

INSERT INTO `tb_urls` (`id`, `sef_url`, `target`) VALUES
(1, 'default_controller', 'main/bc/0/1');

-- --------------------------------------------------------

--
-- Estrutura para tabela `tb_users`
--

CREATE TABLE IF NOT EXISTS `tb_users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `created_datetime` varchar(19) NOT NULL,
  `username` varchar(45) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `group_id` int(10) unsigned NOT NULL,
  `status` int(10) unsigned NOT NULL,
  `params` mediumtext NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Fazendo dump de dados para tabela `tb_users`
--

INSERT INTO `tb_users` (`id`, `created_datetime`, `username`, `password`, `name`, `email`, `group_id`, `status`, `params`) VALUES
(1, '', 'admin', 'ODhmOGM3YTcxMjA0ODQ1MjE1MzkzNTk4NDlmN2I4NmY=', 'Administrator', 'youremail@domain.com', 1, 1, '{"admin_user_session":{"login":true,"login_mode":"persistent","user":{"id":"1","username":"admin","name":"Administrator","email":"youremail@domain.com"},"access_hash":{"user_hash":"f50be7efdf62e3b4aedd624f52065368","client_hash":"72eaee3cf3d9d3f8e4d10f85e752de8c"},"last_url":"admin\\/menus\\/mim\\/mtid\\/1\\/cp\\/1\\/ipp\\/10\\/a\\/l","last_session_db_save":"2016-02-28 22:14:12","msg":[{"msg":"component_preferences_updated","type":"success"}]},"admin_user_hashes":{"user_hashf50be7efdf62e3b4aedd624f52065368":"f50be7efdf62e3b4aedd624f52065368"},"admin_client_hashes":{"client_hash72eaee3cf3d9d3f8e4d10f85e752de8c":"72eaee3cf3d9d3f8e4d10f85e752de8c"}}');

-- --------------------------------------------------------

--
-- Estrutura para tabela `tb_users_acodes`
--

CREATE TABLE IF NOT EXISTS `tb_users_acodes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(255) NOT NULL,
  `user_id` int(11) NOT NULL,
  `created_datetime` datetime NOT NULL,
  `validity_datetime` datetime DEFAULT NULL,
  `data` mediumtext,
  PRIMARY KEY (`id`),
  UNIQUE KEY `code` (`code`),
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estrutura para tabela `tb_users_groups`
--

CREATE TABLE IF NOT EXISTS `tb_users_groups` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `alias` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` longtext NOT NULL,
  `parent` int(10) unsigned NOT NULL,
  `privileges` mediumtext NOT NULL,
  `params` mediumtext NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=12 ;

--
-- Fazendo dump de dados para tabela `tb_users_groups`
--

INSERT INTO `tb_users_groups` (`id`, `alias`, `title`, `description`, `parent`, `privileges`, `params`) VALUES
(1, 'super-administrador', 'Super administrador', '', 0, '{"privileges":["vesm_management_vesm_management","admin_access","content_management","addons_management","media_management","admin_config_management","users_management_users_management","users_management_users_groups_management","users_management_can_add_user","users_management_view_info","users_management_view_personal_info","urls_management_urls_management","submit_forms_management_submit_forms_management","responsive_file_manager_management","providers_management_providers_management","plugins_management","places_management_places_management","modules_management_modules_management","menus_management_menus_management","customers_management_customers_management","contacts_management_contacts_management","companies_management_companies_management","articles_management_articles_management","articles_management_can_add_articles","articles_management_can_view_archived_articles","articles_management_can_view_unpublished_articles"],"privileges_users_management_view_group_level":"users_management_can_see_all_group_levels","privileges_users_management_edit":"users_management_can_edit_only_same_and_low_group_level","privilege_ud_unified_data_management":"privilege_ud_unified_data_management","viewing_menus_items":"menus_items_can_view_all","articles_management_edit":"articles_management_can_edit_all_articles","articles_management_view":"articles_management_can_view_all_articles"}', '0'),
(2, 'gerente', 'Gerente', '', 5, '', 'privileges[]=admin_access|content_management|addons_management|media_management|users_management_users_management|users_management_can_add_user|users_management_view_info|users_management_view_personal_info|articles_management_articles_management|articles_management_can_add_articles\nprivileges_users_management_view_group_level=users_management_can_see_only_low_group_level\nprivileges_users_management_edit=users_management_can_edit_only_same_group_and_below\narticles_management_edit=articles_management_can_edit_all_articles\narticles_management_view=articles_management_can_view_all_articles\n'),
(3, 'editor', 'Editor', '', 5, '{"privileges":"admin_access"}', '0'),
(4, 'registrado', 'Registrado', '', 3, '{"privileges":["0","0","0","0","0","0","0","0","0","0","0","0","0","0","0","0","0","0"],"privileges_users_management_view_group_level":"users_management_cant_see_others_users","privileges_users_management_edit":"users_management_can_edit_only_your_own_user","viewing_menus_items":"menus_items_can_view_only_accessible","articles_management_edit":"articles_management_can_edit_only_your_own_user","articles_management_view":"articles_management_can_view_only_your_own"}', 'privileges[]=admin_access|users_management_users_management|articles_management_articles_management|articles_management_can_add_articles\nprivileges_users_management_view_group_level=users_management_cant_see_others_users\nprivileges_users_management_edit=users_management_can_edit_only_your_own_user\narticles_management_edit=articles_management_can_edit_only_your_own_user\narticles_management_view=articles_management_can_view_only_your_own_user\n'),
(5, 'administrador', 'Administrador', '', 1, '{"privileges":"articles_management_can_view_unpublished_articles","privileges_users_management_view_group_level":"users_management_can_see_only_same_and_low_group_level","privileges_users_management_edit":"users_management_can_edit_only_same_and_low_group_level","viewing_menus_items":"menus_items_can_view_only_same_and_low_group_level","articles_management_edit":"articles_management_can_edit_all_articles","articles_management_view":"articles_management_can_view_all_articles"}', '0');

-- --------------------------------------------------------

--
-- Estrutura para tabela `tb_users_rpcodes`
--

CREATE TABLE IF NOT EXISTS `tb_users_rpcodes` (
  `id` int(11) NOT NULL DEFAULT '0',
  `code` varchar(255) NOT NULL,
  `user_id` int(11) NOT NULL,
  `created_datetime` datetime NOT NULL,
  `validity_datetime` datetime DEFAULT NULL,
  `data` mediumtext
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

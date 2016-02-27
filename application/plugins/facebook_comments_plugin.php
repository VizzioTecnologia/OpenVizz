<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Facebook_comments_plugin extends Plugins_mdl{
	
	function __construct(){
		
		parent::__construct();
		
	}
	
	public function run( &$data, $params = NULL ){
		
		$ok = check_var( $this->mcm->filtered_system_params[ 'show_facebook_comments' ] );
		
		if ( $ok ){
			
			$ok = FALSE;
			
			if( $this->current_component[ 'unique_name' ] === 'articles' ){
				
				if ( NULL !== $this->component_function ){
					
					if ( $this->component_function === 'index' ){
						
						if ( $this->component_function_action === 'articles_list' ){
							
							$ok = check_var( $this->mcm->filtered_system_params[ 'show_facebook_comments_on_articles_list' ] );
							
						}
						else if ( $this->component_function_action === 'article_detail' ){
							
							$ok = check_var( $this->mcm->filtered_system_params[ 'show_facebook_comments_on_article_detail' ] );
							
						}
						
					}
					
				}
				
			}
			
			if ( $ok ){
				
				log_message( 'debug', '[Plugins] Facebook comments plugin initialized' );
				
				$this->voutput->append_body_start_script_declaration( 'facebook_comments_js_sdk', $this->load->view( 'admin/plugins/facebook_comments/default/facebook_comments', NULL, TRUE ), NULL, NULL );
				
				$this->_set_plugin_output( 'facebook_comments', '<fb:comments href="' . current_url() . '" width="100%" numposts="5" colorscheme="light"' . ( $this->ua->is_mobile() ? ' mobile="true"' : '' ) . '></fb:comments>' );
				
			}
			
		}
		
		parent::_set_performed( 'facebook_comments' );
		
		return $ok;
		
	}
	
	public function get_params_spec(){
		
		$current_component = $this->current_component;
		
		$component_name = $current_component[ 'unique_name' ];
		
		$params = array();
		
		if( $component_name === 'main' ){
			
			if ( NULL !== $this->component_function ){
				
				if ( $this->component_function === 'config_management' ){
					
					$params = array_merge_recursive( $params, get_params_spec_from_xml( PLUGINS_PATH . 'facebook_comments/after_content_global_params.xml' ) );
					
				}
				
			}
			
		}
		else if( $component_name === 'articles' ){
			
			if ( NULL !== $this->component_function ){
				
				if ( $this->component_function === 'component_config' ){
					
					$params = array_merge_recursive( $params, get_params_spec_from_xml( PLUGINS_PATH . 'facebook_comments/articles/articles_global_params.xml' ) );
					
				}
				else if ( $this->component_function === 'am' AND ( $this->component_function_action === 'a' OR $this->component_function_action === 'e' ) ){
					
					
					$params = array_merge_recursive( $params, get_params_spec_from_xml( PLUGINS_PATH . 'facebook_comments/articles/articles_list.xml' ) );
					$params = array_merge_recursive( $params, get_params_spec_from_xml( PLUGINS_PATH . 'facebook_comments/articles/article_detail.xml' ) );
					
				}
				
			}
			
		}
		else if( $component_name === 'menus' ){
			
			if ( NULL !== $this->articles_model->menu_item_component_item ){
				
				if ( $this->articles_model->menu_item_component_item === 'menu_item_article_detail' ){
					
					$params = array_merge_recursive( $params, get_params_spec_from_xml( PLUGINS_PATH . 'facebook_comments/articles/article_detail.xml' ) );
					
				}
				else if ( $this->articles_model->menu_item_component_item === 'menu_item_articles_list' ){
					
					$params = array_merge_recursive( $params, get_params_spec_from_xml( PLUGINS_PATH . 'facebook_comments/articles/articles_list.xml' ) );
					$params = array_merge_recursive( $params, get_params_spec_from_xml( PLUGINS_PATH . 'facebook_comments/articles/article_detail.xml' ) );
					
				}
				
			}
			
		}
		
		return $params;
		
	}
	
}

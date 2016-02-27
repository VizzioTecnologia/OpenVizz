<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Companies_search_plugin extends Plugins_mdl{
	
	public function run( &$data, $params = NULL ){
		
		$return = FALSE;
		
		if ( is_object( $this->search ) ) {
			
			log_message( 'debug', '[Plugins] Companies search plugin initialized' );
			
			// db search params
			$dsp = array(
				
				'plugin_name' => 'companies_search',
				'table_name' => 'tb_companies t1',
				'select_columns' => '
					
					t1.*,
					
					t2.name as user_name,
					t2.username,
					
				',
				'tables_to_join' => array(
					
					array( 'tb_users t2', 't1.user_id = t2.id', 'left' ),
					
				),
				'search_columns' => '
					
					t1.trading_name,
					t1.company_name,
					t1.contacts,
					t1.phones,
					t1.emails,
					t1.addresses,
					t1.websites,
					t1.state_registration,
					t1.sic,
					t1.corporate_tax_register,
					t1.foundation_date,
					
				',
				
			);
			
			$full_search_results = $this->search->db_search( $dsp );
			$full_search_results = $full_search_results ? $full_search_results->result_array() : $full_search_results;
			
			foreach ( $full_search_results as $key => & $search_result ) {
				
				$search_result[ 'contacts' ] = json_decode( $search_result[ 'contacts' ], TRUE);
				$search_result[ 'phones' ] = json_decode( $search_result[ 'phones' ], TRUE);
				$search_result[ 'addresses' ] = json_decode( $search_result[ 'addresses' ], TRUE);
				$search_result[ 'emails' ] = json_decode( $search_result[ 'emails' ], TRUE);
				$search_result[ 'websites' ] = json_decode( $search_result[ 'websites' ], TRUE);
				
				if ( check_var( $search_result[ 'contacts' ] ) AND is_array( $search_result[ 'contacts' ] ) ) {
					
					foreach ( $search_result[ 'contacts' ] as $c_key => & $contact ) {
						
						$contact = array_merge( $contact, $this->contacts_model->get_contacts( array( 't1.id' => $contact[ 'id' ] ) )->row_array() );
						
					}
					
				}
				
			}
			
			// apply the string highlight
			if ( $this->search->config( 'terms' ) ) {
				
				$keys = array(
					
					'title',
					'trading_name',
					'company_name',
					'name',
					'email',
					'area_code',
					'number',
					'extension_number',
					'url',
					
				);
				$full_search_results = $this->search->array_highlight( $full_search_results, $keys );
				
			}
			
			if ( $full_search_results ) {
				
				$default_search_results = array();
				
				foreach ( $full_search_results as $key => $search_result ) {
					
					$line = & $default_search_results[];
					
					$line[ 'title' ] = $search_result[ 'trading_name' ];
					$line[ 'image' ] = $search_result[ 'logo' ];
					$line[ 'content' ] = $search_result[ 'company_name' ] ? word_limiter( strip_tags( html_entity_decode( $search_result[ 'company_name' ] ) ), 20, '...' ) : '';
					
				}
				
				$result = array(
					
					'results' => $default_search_results,
					'full_results' => $full_search_results,
					
				);
				
				$this->search->append_results( 'companies_search', $result );
				
				$return = TRUE;
				
			}
			
		}
		else {
			
			log_message( 'debug', '[Plugins] Companies search plugin could not be executed! Search library object not initialized!' );
			
			$return = FALSE;
			
		}
		
		parent::_set_performed( 'companies_search' );
		
		return $return;
		
	}
	
	public function get_params_spec(){
		
	}
	
}

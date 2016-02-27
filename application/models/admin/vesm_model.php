<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

	class Vesm_model extends CI_Model{
		
		/**************************************************/
		/********************* Tenders ********************/
		
		public function get_tenders($condition = NULL, $limit = NULL, $offset = NULL, $return_type = 'get', $order_by = 't1.tender_code asc, t1.title asc, t1.id asc'){
			
			$this->db->select('
				
				t1.*,
				
				t2.title as customer_title,
				t2.company_id,
				
				t3.trading_name,
				t3.logo,
				t3.logo_thumb,
				
				t4.name as contact_name,
				t4.thumb_local,
				t4.photo_local,
				
				t5.title as status_title,
				
			');
			$this->db->from('tb_vesm_tenders t1');
			$this->db->join('tb_customers t2', 't1.customer_id = t2.id', 'left');
			$this->db->join('tb_companies t3', 't2.company_id = t3.id', 'left');
			$this->db->join('tb_contacts t4', 't1.contact_id = t4.id', 'left');
			$this->db->join('tb_vesm_tenders_status t5', 't1.status_id = t5.id', 'left');
			$this->db->order_by($order_by);
			
			if ($condition){
				$this->db->where($condition);
			}
			if ($limit){
				$this->db->limit($limit, $offset?$offset:NULL);
			}
			if ( $return_type === 'count_all_results' ){
				return $this->db->count_all_results();
			}
			
			return $this->db->get();
		}
		
		public function copy_tender( $tender_id = NULL ){
			
			if ( $tender_id != NULL ){
				
				$tender = $this->get_tenders( array( 't1.id'=> $tender_id ), 1 )->row_array();
				
				$db_data = elements( array(
					
					'tender_code',
					'title',
					'date_tender_order',
					'date_creation',
					'date_issue',
					'products',
					'prov_conds',
					'customers_category_id',
					'customer_id',
					'address_key',
					'phone_key',
					'email_key',
					'contact_id',
					'seller_id',
					'status_id',
					'params',
					
				), $tender );
				
				$db_data[ 'tender_code' ] = $this->get_new_tender_code();
				
				if ( $db_data != NULL ){
					
					if ( $this->db->insert( 'tb_vesm_tenders', $db_data ) ){
						
						// confirm the insertion for controller
						return $this->db->insert_id();
						
					}
					
				}
				
			}
			
			return FALSE;
			
		}
		
		public function get_new_tender_code(){
			
			/************************************************/
			/************ Código da nova proposta ***********/
			
			// O objetivo é obter as propostas do dia corrente
			// e usar a data + o [número resultante +1] como número da proposta
			
			// definindo as variáveis para montagem da data e captura das propostas
			$time = human_to_unix(now());
			$day = mdate("%d", $time);
			$month = lang(mdate("%m", $time));
			$year = mdate("%Y", $time);
			$hour = mdate("%H", $time);
			$minute = lang(mdate("%i", $time));
			$second = mdate("%s", $time);
			
			$current_date = $year.$month.$day;
			
			// atribuindo o valor inicial para o número da proposta
			$next_record = 0;
			
			// 1° passo: selecionar a última proposta do dia
			$last_tender = $this->get_tenders( "t1.tender_code LIKE '%$current_date%'", 1, NULL, NULL, 't1.tender_code desc, t1.id desc' )->row_array();
			
			// 2° passo: obtem a sub string que contém o número da última proposta, se esta existir
			if ( $last_tender ){
				$next_record = substr($last_tender['tender_code'], 8);
			}
			
			// incrementa o número do último registro
			$next_record++;
			
			// 3° passo: adiciona os zeros a esquerda
			$next_record = str_pad( $next_record, 3, '0', STR_PAD_LEFT );
			
			return $year.$month.$day.$next_record;
			
			/************ Código da nova proposta ***********/
			/************************************************/
			
		}
		public function insert_tender( $data = NULL ){
			if ($data != NULL){
				if ($this->db->insert('tb_vesm_tenders',$data)){
					// confirm the insertion for controller
					return $this->db->insert_id();
				}
				else {
					// case the insertion fails, return false
					return FALSE;
				}
			}
			else {
				redirect('admin');
			}
		}
		public function update_tender( $data = NULL, $condition = NULL ){
			
			if ( $data != NULL && $condition != NULL ){
				
				if ( $this->db->update( 'tb_vesm_tenders', $data, $condition ) ){
					
					// confirm update for controller
					return TRUE;
					
				}
				else {
					
					// case update fails, return false
					return FALSE;
					
				}
				
			}
			
			redirect('admin');
			
		}
		public function delete_tender($condition = NULL){
			if ($condition != null){
				if ($this->db->delete('tb_vesm_tenders',$condition)){
					// confirm delete for controller
					return TRUE;
				}
				else {
					// case delete fails, return false
					return FALSE;
				}
			}
			redirect();
		}
		
		public function get_tender_params(){
			
			$params = get_params_spec_from_xml(APPPATH.'controllers/admin/com_vesm/tender_params.xml');
			
			// obtendo lista de garantias
			$warranties_list = $this->get_warranties()->result_array();
			$warranties_options = array();
			foreach ($warranties_list as $key => $warranty) {
				$warranties_options[$warranty['title']] = $warranty['title'];
			}
			
			foreach ( $params['params_spec']['tenders'] as $key => $element ) {
				
				if ( $element['name'] == 'default_products_warranty' ){
					
					$params['params_spec']['tenders'][$key]['options'] = $warranties_options;
					
				}
				
			}
			
			return $params;
			
		}
		
		public function generate_tender_pdf( $filename = NULL, $data = NULL, $destination = 'F', $layout = 'default' ){
			
			$this->load->library(array('mpdf'));
			$this->load->helper(array('mpdf'));
			//echo _MPDF_PATH;
			// verificando se o tema atual possui a view
			if ( file_exists( THEMES_PATH . admin_theme_components_views_path() . get_class_name( get_class() ) . DS . 'tenders_management' . DS . 'tender_pdf' . DS . $layout . DS . 'tender_pdf.php' ) ){
				
				$view = $this->load->view( admin_theme_components_views_path() . get_class_name( get_class() ) . DS . 'tenders_management' . DS . 'tender_pdf' . DS . $layout . DS . 'tender_pdf', $data, TRUE);
				
			}
			// verificando se a view existe no diretório de views padrão
			else if ( file_exists( ADMIN_COMPONENTS_VIEWS_PATH . DS . get_class_name( get_class() ) . DS . 'tenders_management' . DS . 'tender_pdf' . DS . $layout . DS . 'tender_pdf.php' ) ){
				
				$view = $this->load->view( ADMIN_COMPONENTS_LOAD_VIEWS_PATH . DS . get_class_name( get_class() ) . DS . 'tenders_management' . DS . 'tender_pdf' . DS . $layout . DS . 'tender_pdf', $data, TRUE);
				
			}
			
			pdf($view, $filename, $destination);
			
		}
		
		/********************* Tenders ********************/
		/**************************************************/
		
		/**************************************************/
		/***************** Tenders search ****************/
		
		public function get_tenders_search_results( $where_condition = NULL, $or_where_condition = NULL, $limit = NULL, $offset = NULL, $return_type = 'get', $order_by = 't1.tender_code asc, t1.title asc, t1.id asc', $order_by_escape = TRUE ){
			
			$this->db->select('
				
				t1.*,
				
				t2.title as customer_title,
				t2.company_id,
				
				t3.trading_name,
				t3.company_name,
				t3.corporate_tax_register,
				t3.sic,
				t3.state_registration,
				t3.logo,
				t3.logo_thumb,
				t3.contacts,
				t3.phones as company_phones,
				t3.addresses as company_addresses,
				
				t4.name as contact_name,
				t4.thumb_local,
				t4.photo_local,
				
				t5.title as status_title,
				
			');
				
			$this->db->from('tb_vesm_tenders t1');
			$this->db->join('tb_customers t2', 't1.customer_id = t2.id', 'left');
			$this->db->join('tb_companies t3', 't2.company_id = t3.id', 'left');
			$this->db->join('tb_contacts t4', 't1.contact_id = t4.id', 'left');
			$this->db->join('tb_vesm_tenders_status t5', 't1.status_id = t5.id', 'left');
			$this->db->order_by($order_by, '', $order_by_escape);
			
			if ($where_condition){
				if(gettype($where_condition) === 'array'){
					foreach ($where_condition as $key => $value) {
						if(gettype($where_condition) === 'array' AND (strpos($key,'fake_index_') !== FALSE) ){
							$this->db->where($value);
						}
						else $this->db->where($key, $value);
					}
				}
				else $this->db->where($where_condition);
			}
			if ($or_where_condition){
				if(gettype($or_where_condition) === 'array'){
					foreach ($or_where_condition as $key => $value) {
						if(gettype($or_where_condition) === 'array' AND (strpos($key,'fake_index_') !== FALSE) ){
							$this->db->or_where($value);
						}
						else $this->db->or_where($key, $value);
					}
				}
				else $this->db->or_where($or_where_condition);
			}
			if ( $return_type === 'count_all_results' ){
				return $this->db->count_all_results();
			}
			if ($limit){
				$this->db->limit($limit, $offset?$offset:NULL);
			}
			
			return $this->db->get();
		}
		
		/***************** Tenders search ****************/
		/**************************************************/
		
		/**************************************************/
		/***************** Tenders status *****************/
		
		public function get_tenders_status($condition = NULL, $limit = NULL, $offset = NULL, $return_type = 'get', $order_by = 't1.title asc'){
			
			$this->db->select('t1.*');
			$this->db->from('tb_vesm_tenders_status t1');
			$this->db->order_by($order_by);
			
			if ($condition){
				$this->db->where($condition);
			}
			if ($limit){
				$this->db->limit($limit, $offset?$offset:NULL);
			}
			if ( $return_type === 'count_all_results' ){
				return $this->db->count_all_results();
			}
			
			return $this->db->get();
		}
		/***************** Tenders status *****************/
		/**************************************************/
		
		/**************************************************/
		/******************** Products ********************/
		
		public function get_products($condition = NULL, $limit = NULL, $offset = NULL, $return_type = 'get'){
			
			$this->db->select('
				
				t1.*,
				
				t2.title as provider_title,
				t2.company_id,
				
				t3.trading_name,
				t3.company_name,
				t3.corporate_tax_register,
				t3.sic,
				t3.state_registration,
				t3.logo,
				t3.logo_thumb,
				t3.contacts,
				t3.phones as company_phones,
				t3.addresses as company_addresses,
				
			');
			$this->db->from('tb_vesm_products t1');
			$this->db->join('tb_providers t2', 't1.provider_id = t2.id', 'left');
			$this->db->join('tb_companies t3', 't2.company_id = t3.id', 'left');
			$this->db->order_by('t1.title asc, t1.code asc, t1.id asc');
			
			if ($condition){
				$this->db->where($condition);
			}
			if ($limit){
				$this->db->limit($limit, $offset?$offset:NULL);
			}
			if ( $return_type === 'count_all_results' ){
				return $this->db->count_all_results();
			}
			
			return $this->db->get();
		}
		public function insert_product($data = NULL){
			if ($data != NULL){
				if ($this->db->insert('tb_vesm_products',$data)){
					// confirm the insertion for controller
					return $this->db->insert_id();
				}
				else {
					// case the insertion fails, return false
					return FALSE;
				}
			}
			else {
				redirect('admin');
			}
		}
		public function update_product($data = NULL, $condition = NULL){
			if ($data != NULL && $condition != NULL){
				if ($this->db->update('tb_vesm_products',$data,$condition)){
					// confirm update for controller
					return TRUE;
				}
				else {
					// case update fails, return false
					return FALSE;
				}
			}
			redirect('admin');
		}
		public function delete_product($condition = NULL){
			if ($condition != null){
				if ($this->db->delete('tb_vesm_products',$condition)){
					// confirm delete for controller
					return TRUE;
				}
				else {
					// case delete fails, return false
					return FALSE;
				}
			}
			redirect();
		}
		
		/******************** Products ********************/
		/**************************************************/
		
		/**************************************************/
		/******************* Warranties *******************/
		
		public function get_warranties($condition = NULL, $limit = NULL, $offset = NULL, $return_type = 'get'){
			
			$this->db->select('t1.*');
			$this->db->from('tb_vesm_warranties t1');
			$this->db->order_by('t1.title asc, t1.id asc');
			
			if ($condition){
				$this->db->where($condition);
			}
			if ($limit){
				$this->db->limit($limit, $offset?$offset:NULL);
			}
			if ( $return_type === 'count_all_results' ){
				return $this->db->count_all_results();
			}
			
			return $this->db->get();
		}
		public function insert_warranty($data = NULL){
			if ($data != NULL){
				if ($this->db->insert('tb_vesm_warranties',$data)){
					// confirm the insertion for controller
					return $this->db->insert_id();
				}
				else {
					// case the insertion fails, return false
					return FALSE;
				}
			}
			else {
				redirect('admin');
			}
		}
		public function update_warranty($data = NULL, $condition = NULL){
			if ($data != NULL && $condition != NULL){
				if ($this->db->update('tb_vesm_warranties',$data,$condition)){
					// confirm update for controller
					return TRUE;
				}
				else {
					// case update fails, return false
					return FALSE;
				}
			}
			redirect('admin');
		}
		public function delete_warranty($condition = NULL){
			if ($condition != null){
				if ($this->db->delete('tb_vesm_warranties',$condition)){
					// confirm delete for controller
					return TRUE;
				}
				else {
					// case delete fails, return false
					return FALSE;
				}
			}
			redirect();
		}
		
		/******************* Warranties *******************/
		/**************************************************/
		
		/**************************************************/
		/***************** Products search ****************/
		
		public function get_products_search_results( $where_condition = NULL, $or_where_condition = NULL, $limit = NULL, $offset = NULL, $return_type = 'get', $order_by = 't1.title asc, t1.code asc, t1.id asc', $order_by_escape = TRUE ){
			
			$this->db->select('
				
				t1.*,
				
				t2.title as provider_title,
				t2.company_id,
				
				t3.trading_name,
				t3.company_name,
				t3.corporate_tax_register,
				t3.sic,
				t3.state_registration,
				t3.logo,
				t3.logo_thumb,
				t3.contacts,
				t3.phones as company_phones,
				t3.addresses as company_addresses,
				
			');
				
			$this->db->from('tb_vesm_products t1');
			$this->db->join('tb_providers t2', 't1.provider_id = t2.id', 'left');
			$this->db->join('tb_companies t3', 't2.company_id = t3.id', 'left');
			$this->db->order_by($order_by, '', $order_by_escape);
			
			if ($where_condition){
				if(gettype($where_condition) === 'array'){
					foreach ($where_condition as $key => $value) {
						if(gettype($where_condition) === 'array' AND (strpos($key,'fake_index_') !== FALSE) ){
							$this->db->where($value);
						}
						else $this->db->where($key, $value);
					}
				}
				else $this->db->where($where_condition);
			}
			if ($or_where_condition){
				if(gettype($or_where_condition) === 'array'){
					foreach ($or_where_condition as $key => $value) {
						if(gettype($or_where_condition) === 'array' AND (strpos($key,'fake_index_') !== FALSE) ){
							$this->db->or_where($value);
						}
						else $this->db->or_where($key, $value);
					}
				}
				else $this->db->or_where($or_where_condition);
			}
			if ( $return_type === 'count_all_results' ){
				return $this->db->count_all_results();
			}
			if ($limit){
				$this->db->limit($limit, $offset?$offset:NULL);
			}
			
			return $this->db->get();
		}
		
		/***************** Products search ****************/
		/**************************************************/
		
	}

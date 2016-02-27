<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require(APPPATH.'controllers/admin/main.php');

class Places extends Main {

	public function __construct(){

		parent::__construct();

		$this->load->model(array('admin/places_model'));

		set_current_component();

	}

	public function index(){
		$this->places_management('countries_list');
	}

	/******************************************************************************/
	/******************************************************************************/
	/*********************************** Places ***********************************/

	public function places_management($action = NULL, $var1 = NULL, $var2 = NULL, $var3 = NULL, $var4 = NULL, $var5 = NULL, $var6 = NULL){

		$this->component_function = __FUNCTION__;
		$this->component_function_action = $action;

		if ( ! $action ) redirect('admin/'.$this->component_name . '/' . __FUNCTION__ . '/' . 'countries_list');

		$url = get_url('admin'.$this->uri->ruri_string());

		// verifica se o usuário atual possui privilégios para gerenciar locais
		if ( ! $this->users->check_privileges('places_management_places_management') ){
			msg(('access_denied'),'title');
			msg(('access_denied_places_management_places_management'),'error');
			redirect('admin');
		};

		/**************************************************/
		/***************** Countries list *****************/

		if ($action == 'countries_list'){

			$this->load->helper(array('pagination'));

			// $var1 = página atual
			// $var2 = itens por página
			if ( $var1 < 1 OR ! gettype($var1) == 'int' ) $var1 = 1;
			if ( $var2 < 1 OR ! gettype($var2) == 'int' ) $var2 = $this->mcm->filtered_system_params['admin_items_per_page'];
			$offset = ($var1-1)*$var2;

			if ($countries = $this->places_model->get_countries(NULL, $var2, $offset)){

				$data = array(
					'component_name' => $this->component_name,
					'countries' => $countries->result(),
					'pagination' => get_pagination('admin/'.$this->component_name . '/' . __FUNCTION__ . '/' . $action.'/%p%/%ipp%', $var1, $var2, $this->places_model->get_countries(NULL, NULL, NULL,'count_all_results')),
				);

				set_last_url($url);

				$this->_page(

					array(

						'component_view_folder' => $this->component_view_folder,
						'function' => __FUNCTION__,
						'action' => $action,
						'layout' => 'default',
						'view' => $action,
						'data' => $data,

					)

				);

			}
		}

		/***************** Countries list *****************/
		/**************************************************/

		/**************************************************/
		/************** Switch country status *************/

		else if (($action == 'fetch_publish' OR $action == 'fetch_unpublish') AND $var1){

			$update_data = array(
				'status' => $action == 'fetch_publish'?'1':'0',
			);

			if ($this->places_model->update_country($update_data, array('id' => $var1))){
				msg(('country_'.($action == 'fetch_publish'?'published':'unpublished')),'success');
				redirect_last_url();
			}
		}

		/************** Switch country status *************/
		/**************************************************/

		/**************************************************/
		/******************* Add country ******************/

		else if ($action == 'add_country'){

			$data = array(
				'component_name' => $this->component_name,
			);

			//validação dos campos
			$this->form_validation->set_rules('status',lang('status'),'trim|required|integer');
			$this->form_validation->set_rules('title',lang('title'),'trim|required');
			$this->form_validation->set_rules('alias',lang('alias'),'trim');

			if($this->input->post('submit_cancel')){
				redirect_last_url();
			}
			// se a validação dos campos for bem sucedida
			else if ($this->form_validation->run() AND ($this->input->post('submit') OR $this->input->post('submit_apply'))){
				$insert_data = elements(array(
					'status',
					'title',
					'alias',
				),$this->input->post());

				if ($insert_data['alias'] == ''){
					$insert_data['alias'] = url_title($insert_data['title'],'-',TRUE);
				}

				$return_id=$this->places_model->insert_country($insert_data);
				if ($return_id){
					msg(('country_added'),'success');
					if ($this->input->post('submit_apply')){
						redirect('admin/'.$this->component_name . '/' . __FUNCTION__.'/edit_country/'.$return_id);
					}
					else{
						redirect_last_url();
					}
				}

			}
			// caso contrário se a validação dos campos for negativa e mensagens de erro conter strings
			else if (!$this->form_validation->run() AND validation_errors() != ''){

				$data['post'] = $this->input->post();

				msg(('add_country_fail'),'title');
				msg(validation_errors('<div class="error">', '</div>'),'error');
			}

			$this->_page(

				array(

					'component_view_folder' => $this->component_view_folder,
					'function' => __FUNCTION__,
					'action' => $action,
					'layout' => 'default',
					'view' => $action,
					'data' => $data,

				)

			);

		}

		/******************* Add country ******************/
		/**************************************************/

		/**************************************************/
		/****************** Edit country ******************/

		else if ($action == 'edit_country' AND $var1 AND ($country = $this->places_model->get_countries(array('id'=>$var1), 1)->row())){

			// $var1 = id do país

			$data = array(
				'component_name' => $this->component_name,
				'country' => $country,
			);

			//validação dos campos
			$this->form_validation->set_rules('status',lang('status'),'trim|required|integer');
			$this->form_validation->set_rules('title',lang('title'),'trim|required');
			$this->form_validation->set_rules('alias',lang('alias'),'trim');

			if($this->input->post('submit_cancel')){
				redirect_last_url();
			}
			// se a validação dos campos for positiva
			else if ($this->form_validation->run() AND ($this->input->post('submit') OR $this->input->post('submit_apply'))){
				$update_data = elements(array(
					'status',
					'title',
					'alias',
				),$this->input->post());

				if ($update_data['alias'] == ''){
					$update_data['alias'] = url_title($update_data['title'],'-',TRUE);
				}

				if ($this->places_model->update_country($update_data, array('id' => $var1))){
					msg(('country_updated'),'success');

					if ($this->input->post('submit_apply')){
						redirect('admin'.$this->uri->ruri_string());
					}
					else{
						redirect_last_url();
					}
				}

			}
			// caso contrário se a validação dos campos for negativa e mensagens de erro conter strings
			else if (!$this->form_validation->run() AND validation_errors() != ''){

				$data['post'] = $this->input->post();

				msg(('update_country_fail'),'title');
				msg(validation_errors('<div class="error">', '</div>'),'error');
			}

			$this->_page(

				array(

					'component_view_folder' => $this->component_view_folder,
					'function' => __FUNCTION__,
					'action' => $action,
					'layout' => 'default',
					'view' => $action,
					'data' => $data,

				)

			);

		}

		/****************** Edit country ******************/
		/**************************************************/

		/**************************************************/
		/***************** Remove country *****************/

		else if ($action == 'remove_country' AND $var1 AND ($country = $this->places_model->get_countries(array('id' => $var1), 1)->row())){

			// $var1 = id do país

			if($this->input->post('submit_cancel')){
				redirect_last_url();
			}
			else if ($this->input->post('submit')){
				if ($this->places_model->delete_country(array('id'=>$var1))){
					msg(('country_deleted'),'success');
					redirect_last_url();
				}
				else{
					msg($this->lang->line('country_deleted_fail'),'error');
					redirect_last_url();
				}
			}
			else{
				$data=array(
					'component_name' => $this->component_name,
					'country'=>$country,
				);

				$this->_page(

					array(

						'component_view_folder' => $this->component_view_folder,
						'function' => __FUNCTION__,
						'action' => $action,
						'layout' => 'default',
						'view' => $action,
						'data' => $data,

					)

				);

			}

		}

		/***************** Remove country *****************/
		/**************************************************/

		/**************************************************/

		/**************************************************/
		/******************* States list ******************/

		else if ($action == 'states_list'){

			// $var1 = id do país
			if ( ! $var1 ){
				msg(('states_list_fail'),'title');
				msg(('no_country_selected'),'error');
				redirect_last_url();
			}

			$country = $this->places_model->get_countries(array( 'id' => $var1 ), 1)->row();

			$this->load->helper(array('pagination'));

			// $var2 = página atual
			// $var3 = itens por página
			if ( $var2 < 1 OR ! gettype($var2) == 'int' ) $var2 = 1;
			if ( $var3 < 1 OR ! gettype($var3) == 'int' ) $var3 = $this->mcm->filtered_system_params['admin_items_per_page'];
			$offset = ($var2-1)*$var3;

			$condition = array( 'country_id' => $var1 );

			if ($states = $this->places_model->get_states($condition, $var3, $offset)){

				$data = array(
					'component_name' => $this->component_name,
					'states' => $states->result(),
					'country' => $country,
					'pagination' => get_pagination('admin/'.$this->component_name . '/' . __FUNCTION__ . '/' . $action . '/' . $var1.'/%p%/%ipp%', $var2, $var3, $this->places_model->get_states($condition, NULL, NULL,'count_all_results')),
				);

				set_last_url($url);

				$this->_page(

					array(

						'component_view_folder' => $this->component_view_folder,
						'function' => __FUNCTION__,
						'action' => $action,
						'layout' => 'default',
						'view' => $action,
						'data' => $data,

					)

				);

			}
		}

		/******************* States list ******************/
		/**************************************************/

		/**************************************************/
		/*************** Switch state status **************/

		else if (($action == 'fetch_publish_state' OR $action == 'fetch_unpublish_state') AND $var1){

			$update_data = array(
				'status' => $action == 'fetch_publish_state'?'1':'0',
			);

			if ($this->places_model->update_state($update_data, array('id' => $var1))){
				msg(('state_'.($action == 'fetch_publish_state'?'published':'unpublished')),'success');
				redirect_last_url();
			}
		}

		/*************** Switch state status **************/
		/**************************************************/

		/**************************************************/
		/******************** Add state *******************/

		else if ($action == 'add_state'){

			// $var1 = id do país
			if ( ! $var1 ){
				msg(('add_state_fail'),'title');
				msg(('no_country_selected'),'error');
				redirect_last_url();
			}

			$country = $this->places_model->get_countries(array( 'id' => $var1 ), 1)->row();

			$data = array(
				'component_name' => $this->component_name,
				'country' => $country,
			);

			//validação dos campos
			$this->form_validation->set_rules('status',lang('status'),'trim|required|integer');
			$this->form_validation->set_rules('title',lang('title'),'trim|required');
			$this->form_validation->set_rules('alias',lang('alias'),'trim');
			$this->form_validation->set_rules('country_id',lang('country'),'trim|required|integer');

			if($this->input->post('submit_cancel')){
				redirect_last_url();
			}
			// se a validação dos campos for bem sucedida
			else if ($this->form_validation->run() AND ($this->input->post('submit') OR $this->input->post('submit_apply'))){
				$insert_data = elements(array(
					'status',
					'title',
					'acronym',
					'country_id',
				),$this->input->post());

				$return_id=$this->places_model->insert_state($insert_data);
				if ($return_id){
					msg(('state_added'),'success');
					if ($this->input->post('submit_apply')){
						redirect(get_url('admin/'.$this->component_name . '/' . __FUNCTION__.'/edit_state/'.$var1 . '/' . $return_id));
					}
					else{
						redirect_last_url();
					}
				}

			}
			// caso contrário se a validação dos campos for negativa e mensagens de erro conter strings
			else if (!$this->form_validation->run() AND validation_errors() != ''){

				$data['post'] = $this->input->post();

				msg(('add_state_fail'),'title');
				msg(validation_errors('<div class="error">', '</div>'),'error');
			}

			$this->_page(

				array(

					'component_view_folder' => $this->component_view_folder,
					'function' => __FUNCTION__,
					'action' => $action,
					'layout' => 'default',
					'view' => $action,
					'data' => $data,

				)

			);

		}

		/******************** Add state *******************/
		/**************************************************/

		/**************************************************/
		/******************* Edit state *******************/

		else if ($action == 'edit_state' AND ($state = $this->places_model->get_states(array('t1.id'=>$var2), 1)->row())){

			// $var1 = id do país
			if ( ! $var1 ){
				msg(('edit_state_fail'),'title');
				msg(('no_country_selected'),'error');
				redirect_last_url();
			}
			// $var2 = id do estado
			if ( ! $var2 ){
				msg(('edit_state_fail'),'title');
				msg(('no_state_selected'),'error');
				redirect_last_url();
			}

			$country = $this->places_model->get_countries(array( 'id' => $var1 ), 1)->row();

			$data = array(
				'component_name' => $this->component_name,
				'state' => $state,
				'country' => $country,
			);

			//validação dos campos
			$this->form_validation->set_rules('status',lang('status'),'trim|required|integer');
			$this->form_validation->set_rules('title',lang('title'),'trim|required');
			$this->form_validation->set_rules('acronym',lang('acronym'),'trim|required'.($this->input->post('acronym') AND $this->input->post('acronym') != $state->acronym?'|is_unique[tb_states.acronym]':''));
			$this->form_validation->set_rules('country_id',lang('country'),'trim|required|integer');

			if($this->input->post('submit_cancel')){
				redirect_last_url();
			}
			// se a validação dos campos for positiva
			else if ($this->form_validation->run() AND ($this->input->post('submit') OR $this->input->post('submit_apply'))){
				$update_data = elements(array(
					'status',
					'title',
					'acronym',
					'country_id',
				),$this->input->post());

				if ($this->places_model->update_state($update_data, array('id' => $var2))){
					msg(('state_updated'),'success');

					if ($this->input->post('submit_apply')){
						redirect(get_url('admin'.$this->uri->ruri_string()));
					}
					else{
						redirect_last_url();
					}
				}

			}
			// caso contrário se a validação dos campos for negativa e mensagens de erro conter strings
			else if (!$this->form_validation->run() AND validation_errors() != ''){

				$data['post'] = $this->input->post();

				msg(('update_state_fail'),'title');
				msg(validation_errors('<div class="error">', '</div>'),'error');
			}

			$this->_page(

				array(

					'component_view_folder' => $this->component_view_folder,
					'function' => __FUNCTION__,
					'action' => $action,
					'layout' => 'default',
					'view' => $action,
					'data' => $data,

				)

			);

		}

		/******************* Edit state *******************/
		/**************************************************/

		/**************************************************/
		/****************** Remove state ******************/

		else if ($action == 'remove_state' AND ($state = $this->places_model->get_states(array('t1.id' => $var2), 1)->row())){

			// $var1 = id do país
			if ( ! $var1 ){
				msg(('edit_state_fail'),'title');
				msg(('no_country_selected'),'error');
				redirect_last_url();
			}
			// $var2 = id do estado
			if ( ! $var2 ){
				msg(('edit_state_fail'),'title');
				msg(('no_state_selected'),'error');
				redirect_last_url();
			}

			$country = $this->places_model->get_countries(array( 'id' => $var1 ), 1)->row();

			if($this->input->post('submit_cancel')){
				redirect_last_url();
			}
			else if ($this->input->post('submit')){
				if ($this->places_model->delete_state(array('id'=>$var2))){
					msg(('state_deleted'),'success');
					redirect_last_url();
				}
				else{
					msg($this->lang->line('state_deleted_fail'),'error');
					redirect_last_url();
				}
			}
			else{
				$data=array(
					'component_name' => $this->component_name,
					'state'=>$state,
					'country' => $country,
				);

				$this->_page(

					array(

						'component_view_folder' => $this->component_view_folder,
						'function' => __FUNCTION__,
						'action' => $action,
						'layout' => 'default',
						'view' => $action,
						'data' => $data,

					)

				);

			}

		}

		/****************** Remove state ******************/
		/**************************************************/

		/**************************************************/

		/**************************************************/
		/******************* Cities list ******************/

		else if ($action == 'cities_list'){

			// $var1 = id do país
			if ( ! $var1 ){
				msg(('cities_list_fail'),'title');
				msg(('no_country_selected'),'error');
				redirect_last_url();
			}
			// $var2 = id do estado
			if ( ! $var2 ){
				msg(('cities_list_fail'),'title');
				msg(('no_state_selected'),'error');
				redirect_last_url();
			}

			$country = $this->places_model->get_countries(array( 'id' => $var1 ), 1)->row();
			$state = $this->places_model->get_states(array( 't1.id' => $var2 ), 1)->row();

			$this->load->helper(array('pagination'));

			// $var3 = página atual
			// $var4 = itens por página
			if ( $var3 < 1 OR ! gettype($var3) == 'int' ) $var3 = 1;
			if ( $var4 < 1 OR ! gettype($var4) == 'int' ) $var4 = $this->mcm->filtered_system_params['admin_items_per_page'];
			$offset = ($var3-1)*$var4;

			// $var1 = id do país
			$condition = array( 'country_id' => $var1 );
			$condition = array( 'state_id' => $var2 );

			if ($cities = $this->places_model->get_cities($condition, $var4, $offset)){

				$data = array(
					'component_name' => $this->component_name,
					'cities' => $cities->result(),
					'state' => $state,
					'country' => $country,
					'pagination' => get_pagination('admin/'.$this->component_name . '/' . __FUNCTION__ . '/' . $action . '/' . $var1 . '/' . $var2.'/%p%/%ipp%', $var3, $var4, $this->places_model->get_cities($condition, NULL, NULL,'count_all_results')),
				);

				set_last_url($url);

				$this->_page(

					array(

						'component_view_folder' => $this->component_view_folder,
						'function' => __FUNCTION__,
						'action' => $action,
						'layout' => 'default',
						'view' => $action,
						'data' => $data,

					)

				);

			}
		}

		/******************* Cities list ******************/
		/**************************************************/

		/**************************************************/
		/********************* Add city *******************/

		else if ($action == 'add_city'){

			// $var1 = id do país
			if ( ! $var1 ){
				msg(('cities_list_fail'),'title');
				msg(('no_country_selected'),'error');
				redirect_last_url();
			}
			// $var2 = id do estado
			if ( ! $var2 ){
				msg(('cities_list_fail'),'title');
				msg(('no_state_selected'),'error');
				redirect_last_url();
			}

			$country = $this->places_model->get_countries(array( 'id' => $var1 ), 1)->row();
			$state = $this->places_model->get_states(array( 't1.id' => $var2 ), 1)->row();

			$data = array(
				'component_name' => $this->component_name,
				'state' => $state,
				'country' => $country,
			);

			//validação dos campos
			$this->form_validation->set_rules('title',lang('title'),'trim|required');
			$this->form_validation->set_rules('state_id',lang('state'),'trim|required|integer');

			if($this->input->post('submit_cancel')){
				redirect_last_url();
			}
			// se a validação dos campos for bem sucedida
			else if ($this->form_validation->run() AND ($this->input->post('submit') OR $this->input->post('submit_apply'))){
				$insert_data = elements(array(
					'title',
					'state_id',
				),$this->input->post());

				$return_id=$this->places_model->insert_city($insert_data);
				if ($return_id){
					msg(('city_added'),'success');
					if ($this->input->post('submit_apply')){
						redirect(get_url('admin/'.$this->component_name . '/' . __FUNCTION__.'/edit_city/'.$var1 . '/' . $var2 . '/' . $return_id));
					}
					else{
						redirect_last_url();
					}
				}

			}
			// caso contrário se a validação dos campos for negativa e mensagens de erro conter strings
			else if (!$this->form_validation->run() AND validation_errors() != ''){

				$data['post'] = $this->input->post();

				msg(('add_city_fail'),'title');
				msg(validation_errors('<div class="error">', '</div>'),'error');
			}

			$this->_page(

				array(

					'component_view_folder' => $this->component_view_folder,
					'function' => __FUNCTION__,
					'action' => $action,
					'layout' => 'default',
					'view' => $action,
					'data' => $data,

				)

			);

		}

		/********************* Add city *******************/
		/**************************************************/

		/**************************************************/
		/******************** Edit city *******************/

		else if ($action == 'edit_city' AND ($city = $this->places_model->get_cities(array('t1.id'=>$var3), 1)->row())){

			// $var1 = id do país
			if ( ! $var1 ){
				msg(('cities_list_fail'),'title');
				msg(('no_country_selected'),'error');
				redirect_last_url();
			}
			// $var2 = id do estado
			if ( ! $var2 ){
				msg(('cities_list_fail'),'title');
				msg(('no_state_selected'),'error');
				redirect_last_url();
			}
			// $var3 = id da cidade
			if ( ! $var3 ){
				msg(('cities_list_fail'),'title');
				msg(('no_city_selected'),'error');
				redirect_last_url();
			}

			$country = $this->places_model->get_countries(array( 'id' => $var1 ), 1)->row();
			$state = $this->places_model->get_states(array( 't1.id' => $var2 ), 1)->row();

			$data = array(
				'component_name' => $this->component_name,
				'city' => $city,
				'state' => $state,
				'country' => $country,
			);

			//validação dos campos
			$this->form_validation->set_rules('title',lang('title'),'trim|required');
			$this->form_validation->set_rules('state_id',lang('state'),'trim|required|integer');

			if($this->input->post('submit_cancel')){
				redirect_last_url();
			}
			// se a validação dos campos for positiva
			else if ($this->form_validation->run() AND ($this->input->post('submit') OR $this->input->post('submit_apply'))){
				$update_data = elements(array(
					'title',
					'state_id',
				),$this->input->post());

				if ($this->places_model->update_city($update_data, array('id' => $var3))){
					msg(('city_updated'),'success');

					if ($this->input->post('submit_apply')){
						redirect(get_url('admin'.$this->uri->ruri_string()));
					}
					else{
						redirect_last_url();
					}
				}

			}
			// caso contrário se a validação dos campos for negativa e mensagens de erro conter strings
			else if (!$this->form_validation->run() AND validation_errors() != ''){

				$data['post'] = $this->input->post();

				msg(('update_city_fail'),'title');
				msg(validation_errors('<div class="error">', '</div>'),'error');
			}

			$this->_page(

				array(

					'component_view_folder' => $this->component_view_folder,
					'function' => __FUNCTION__,
					'action' => $action,
					'layout' => 'default',
					'view' => $action,
					'data' => $data,

				)

			);

		}

		/******************** Edit city *******************/
		/**************************************************/

		/**************************************************/
		/******************* Remove city ******************/

		else if ($action == 'remove_city' AND ($city = $this->places_model->get_cities(array('t1.id' => $var3), 1)->row())){

			// $var1 = id do país
			if ( ! $var1 ){
				msg(('cities_list_fail'),'title');
				msg(('no_country_selected'),'error');
				redirect_last_url();
			}
			// $var2 = id do estado
			if ( ! $var2 ){
				msg(('cities_list_fail'),'title');
				msg(('no_state_selected'),'error');
				redirect_last_url();
			}
			// $var3 = id da cidade
			if ( ! $var3 ){
				msg(('cities_list_fail'),'title');
				msg(('no_city_selected'),'error');
				redirect_last_url();
			}

			$country = $this->places_model->get_countries(array( 'id' => $var1 ), 1)->row();
			$state = $this->places_model->get_states(array( 't1.id' => $var2 ), 1)->row();

			if($this->input->post('submit_cancel')){
				redirect_last_url();
			}
			else if ($this->input->post('submit')){
				if ($this->places_model->delete_city(array('id'=>$var3))){
					msg(('city_deleted'),'success');
					redirect_last_url();
				}
				else{
					msg($this->lang->line('city_deleted_fail'),'error');
					redirect_last_url();
				}
			}
			else{
				$data=array(
					'component_name' => $this->component_name,
					'city' => $city,
					'state' => $state,
					'country' => $country,
				);

				$this->_page(

					array(

						'component_view_folder' => $this->component_view_folder,
						'function' => __FUNCTION__,
						'action' => $action,
						'layout' => 'default',
						'view' => $action,
						'data' => $data,

					)

				);

			}

		}

		/******************* Remove city ******************/
		/**************************************************/

		/**************************************************/

		/**************************************************/
		/**************** Neighborhoods list **************/

		else if ($action == 'neighborhoods_list'){

			// $var1 = id do país
			// $var2 = id do estado
			// $var3 = id da cidade
			if ( ! $var1 OR ! $var2 OR ! $var3 ){
				msg(('error'),'title');

				if ( ! $var1 ){
					msg(('no_country_selected'),'error');
				}
				if ( ! $var2 ){
					msg(('no_state_selected'),'error');
				}
				if ( ! $var3 ){
					msg(('no_city_selected'),'error');
				}
				redirect_last_url();

			}

			$country = $this->places_model->get_countries(array( 'id' => $var1 ), 1)->row();
			$state = $this->places_model->get_states(array( 't1.id' => $var2 ), 1)->row();
			$city = $this->places_model->get_cities(array( 't1.id' => $var3 ), 1)->row();

			$this->load->helper(array('pagination'));

			// $var4 = página atual
			// $var5 = itens por página
			if ( $var4 < 1 OR ! gettype($var4) == 'int' ) $var4 = 1;
			if ( $var5 < 1 OR ! gettype($var5) == 'int' ) $var5 = $this->mcm->filtered_system_params['admin_items_per_page'];
			$offset = ($var4-1)*$var5;

			// $var1 = id do país
			$condition = array( 'country_id' => $var1 );
			$condition = array( 'state_id' => $var2 );
			$condition = array( 'city_id' => $var3 );

			if ($neighborhoods = $this->places_model->get_neighborhoods($condition, $var5, $offset)){

				$data = array(
					'component_name' => $this->component_name,
					'neighborhoods' => $neighborhoods->result(),
					'city' => $city,
					'state' => $state,
					'country' => $country,
					'pagination' => get_pagination('admin/'.$this->component_name . '/' . __FUNCTION__ . '/' . $action . '/' . $var1 . '/' . $var2 . '/' . $var3.'/%p%/%ipp%', $var4, $var5, $this->places_model->get_neighborhoods($condition, NULL, NULL,'count_all_results')),
				);

				set_last_url($url);

				$this->_page(

					array(

						'component_view_folder' => $this->component_view_folder,
						'function' => __FUNCTION__,
						'action' => $action,
						'layout' => 'default',
						'view' => $action,
						'data' => $data,

					)

				);

			}
		}

		/**************** Neighborhoods list **************/
		/**************************************************/

		/**************************************************/
		/***************** Add neighborhood ***************/

		else if ($action == 'add_neighborhood'){

			// $var1 = id do país
			// $var2 = id do estado
			// $var3 = id da cidade
			if ( ! $var1 OR ! $var2 OR ! $var3 ){
				msg(('error'),'title');

				if ( ! $var1 ){
					msg(('no_country_selected'),'error');
				}
				if ( ! $var2 ){
					msg(('no_state_selected'),'error');
				}
				if ( ! $var3 ){
					msg(('no_city_selected'),'error');
				}
				redirect_last_url();

			}

			$country = $this->places_model->get_countries(array( 'id' => $var1 ), 1)->row();
			$state = $this->places_model->get_states(array( 't1.id' => $var2 ), 1)->row();
			$city = $this->places_model->get_cities(array( 't1.id' => $var3 ), 1)->row();

			$data = array(
				'component_name' => $this->component_name,
				'city' => $city,
				'state' => $state,
				'country' => $country,
			);

			//validação dos campos
			$this->form_validation->set_rules('title',lang('title'),'trim|required');
			$this->form_validation->set_rules('city_id',lang('state'),'trim|required|integer');

			if($this->input->post('submit_cancel')){
				redirect_last_url();
			}
			// se a validação dos campos for bem sucedida
			else if ($this->form_validation->run() AND ($this->input->post('submit') OR $this->input->post('submit_apply'))){
				$insert_data = elements(array(
					'title',
					'city_id',
				),$this->input->post());

				$return_id=$this->places_model->insert_neighborhood($insert_data);
				if ($return_id){
					msg(('neighborhood_added'),'success');
					if ($this->input->post('submit_apply')){
						redirect(get_url('admin/'.$this->component_name . '/' . __FUNCTION__.'/edit_neighborhood/'.$var1 . '/' . $var2 . '/' . $var3 . '/' . $return_id));
					}
					else{
						redirect_last_url();
					}
				}

			}
			// caso contrário se a validação dos campos for negativa e mensagens de erro conter strings
			else if (!$this->form_validation->run() AND validation_errors() != ''){

				$data['post'] = $this->input->post();

				msg(('add_neighborhood_fail'),'title');
				msg(validation_errors('<div class="error">', '</div>'),'error');
			}

			$this->_page(

				array(

					'component_view_folder' => $this->component_view_folder,
					'function' => __FUNCTION__,
					'action' => $action,
					'layout' => 'default',
					'view' => $action,
					'data' => $data,

				)

			);

		}

		/***************** Add neighborhood ***************/
		/**************************************************/

		/**************************************************/
		/**************** Edit neighborhood ***************/

		else if ($action == 'edit_neighborhood' AND ($neighborhood = $this->places_model->get_neighborhoods(array('t1.id'=>$var4), 1)->row())){

			// $var1 = id do país
			// $var2 = id do estado
			// $var3 = id da cidade
			// $var4 = id do bairro
			if ( ! $var1 OR ! $var2 OR ! $var3 OR ! $var4 ){
				msg(('error'),'title');

				if ( ! $var1 ){
					msg(('no_country_selected'),'error');
				}
				if ( ! $var2 ){
					msg(('no_state_selected'),'error');
				}
				if ( ! $var3 ){
					msg(('no_city_selected'),'error');
				}
				if ( ! $var4 ){
					msg(('no_neighborhood_selected'),'error');
				}
				redirect_last_url();

			}

			$country = $this->places_model->get_countries(array( 'id' => $var1 ), 1)->row();
			$state = $this->places_model->get_states(array( 't1.id' => $var2 ), 1)->row();
			$city = $this->places_model->get_cities(array( 't1.id' => $var3 ), 1)->row();

			$data = array(
				'component_name' => $this->component_name,
				'neighborhood' => $neighborhood,
				'city' => $city,
				'state' => $state,
				'country' => $country,
			);

			//validação dos campos
			$this->form_validation->set_rules('title',lang('title'),'trim|required');
			$this->form_validation->set_rules('city_id',lang('state'),'trim|required|integer');

			if($this->input->post('submit_cancel')){
				redirect_last_url();
			}
			// se a validação dos campos for positiva
			else if ($this->form_validation->run() AND ($this->input->post('submit') OR $this->input->post('submit_apply'))){
				$update_data = elements(array(
					'title',
					'city_id',
				),$this->input->post());

				if ($this->places_model->update_neighborhood($update_data, array('id' => $var4))){
					msg(('neighborhood_updated'),'success');

					if ($this->input->post('submit_apply')){
						redirect(get_url('admin'.$this->uri->ruri_string()));
					}
					else{
						redirect_last_url();
					}
				}

			}
			// caso contrário se a validação dos campos for negativa e mensagens de erro conter strings
			else if (!$this->form_validation->run() AND validation_errors() != ''){

				$data['post'] = $this->input->post();

				msg(('update_neighborhood_fail'),'title');
				msg(validation_errors('<div class="error">', '</div>'),'error');
			}

			$this->_page(

				array(

					'component_view_folder' => $this->component_view_folder,
					'function' => __FUNCTION__,
					'action' => $action,
					'layout' => 'default',
					'view' => $action,
					'data' => $data,

				)

			);

		}

		/**************** Edit neighborhood ***************/
		/**************************************************/

		/**************************************************/
		/*************** Remove neighborhood **************/

		else if ($action == 'remove_neighborhood' AND ($neighborhood = $this->places_model->get_neighborhoods(array('t1.id' => $var4), 1)->row())){

			// $var1 = id do país
			// $var2 = id do estado
			// $var3 = id da cidade
			// $var4 = id do bairro
			if ( ! $var1 OR ! $var2 OR ! $var3 OR ! $var4 ){
				msg(('error'),'title');

				if ( ! $var1 ){
					msg(('no_country_selected'),'error');
				}
				if ( ! $var2 ){
					msg(('no_state_selected'),'error');
				}
				if ( ! $var3 ){
					msg(('no_city_selected'),'error');
				}
				if ( ! $var4 ){
					msg(('no_neighborhood_selected'),'error');
				}
				redirect_last_url();

			}

			$country = $this->places_model->get_countries(array( 'id' => $var1 ), 1)->row();
			$state = $this->places_model->get_states(array( 't1.id' => $var2 ), 1)->row();
			$city = $this->places_model->get_cities(array( 't1.id' => $var3 ), 1)->row();

			if($this->input->post('submit_cancel')){
				redirect_last_url();
			}
			else if ($this->input->post('submit')){
				if ($this->places_model->delete_neighborhood(array('id'=>$var4))){
					msg(('neighborhood_deleted'),'success');
					redirect_last_url();
				}
				else{
					msg($this->lang->line('neighborhood_deleted_fail'),'error');
					redirect_last_url();
				}
			}
			else{
				$data=array(
					'component_name' => $this->component_name,
					'neighborhood' => $neighborhood,
					'city' => $city,
					'state' => $state,
					'country' => $country,
				);

				$this->_page(

					array(

						'component_view_folder' => $this->component_view_folder,
						'function' => __FUNCTION__,
						'action' => $action,
						'layout' => 'default',
						'view' => $action,
						'data' => $data,

					)

				);

			}

		}

		/*************** Remove neighborhood **************/
		/**************************************************/

		/**************************************************/

		/**************************************************/
		/***************** Public areas list **************/

		else if ($action == 'public_areas_list'){

			// $var1 = id do país
			// $var2 = id do estado
			// $var3 = id da cidade
			// $var4 = id do bairro
			if ( ! $var1 OR ! $var2 OR ! $var3 OR ! $var4 ){
				msg(('error'),'title');

				if ( ! $var1 ){
					msg(('no_country_selected'),'error');
				}
				if ( ! $var2 ){
					msg(('no_state_selected'),'error');
				}
				if ( ! $var3 ){
					msg(('no_city_selected'),'error');
				}
				if ( ! $var4 ){
					msg(('no_neighborhood_selected'),'error');
				}
				redirect_last_url();

			}

			$country = $this->places_model->get_countries(array( 'id' => $var1 ), 1)->row();
			$state = $this->places_model->get_states(array( 't1.id' => $var2 ), 1)->row();
			$city = $this->places_model->get_cities(array( 't1.id' => $var3 ), 1)->row();
			$neighborhood = $this->places_model->get_neighborhoods(array( 't1.id' => $var4 ), 1)->row();

			$this->load->helper(array('pagination'));

			// $var5 = página atual
			// $var6 = itens por página
			if ( $var5 < 1 OR ! gettype($var5) == 'int' ) $var5 = 1;
			if ( $var6 < 1 OR ! gettype($var6) == 'int' ) $var6 = $this->mcm->filtered_system_params['admin_items_per_page'];
			$offset = ($var5-1)*$var6;

			$condition = array( 'country_id' => $var1 );
			$condition = array( 'state_id' => $var2 );
			$condition = array( 'city_id' => $var3 );
			$condition = array( 'neighborhood_id' => $var4 );

			if ($public_areas = $this->places_model->get_public_areas($condition, $var6, $offset)){

				$data = array(
					'component_name' => $this->component_name,
					'public_areas' => $public_areas->result(),
					'neighborhood' => $neighborhood,
					'city' => $city,
					'state' => $state,
					'country' => $country,
					'pagination' => get_pagination('admin/'.$this->component_name . '/' . __FUNCTION__ . '/' . $action . '/' . $var1 . '/' . $var2 . '/' . $var3 . '/' . $var4.'/%p%/%ipp%', $var5, $var6, $this->places_model->get_public_areas($condition, NULL, NULL,'count_all_results')),
				);

				set_last_url($url);

				$this->_page(

					array(

						'component_view_folder' => $this->component_view_folder,
						'function' => __FUNCTION__,
						'action' => $action,
						'layout' => 'default',
						'view' => $action,
						'data' => $data,

					)

				);

			}
		}

		/***************** Public areas list **************/
		/**************************************************/

		/**************************************************/
		/****************** Add public area ***************/

		else if ($action == 'add_public_area'){

			// $var1 = id do país
			// $var2 = id do estado
			// $var3 = id da cidade
			// $var4 = id do bairro
			if ( ! $var1 OR ! $var2 OR ! $var3 OR ! $var4 ){
				msg(('error'),'title');

				if ( ! $var1 ){
					msg(('no_country_selected'),'error');
				}
				if ( ! $var2 ){
					msg(('no_state_selected'),'error');
				}
				if ( ! $var3 ){
					msg(('no_city_selected'),'error');
				}
				if ( ! $var4 ){
					msg(('no_neighborhood_selected'),'error');
				}
				redirect_last_url();

			}

			$country = $this->places_model->get_countries(array( 'id' => $var1 ), 1)->row();
			$state = $this->places_model->get_states(array( 't1.id' => $var2 ), 1)->row();
			$city = $this->places_model->get_cities(array( 't1.id' => $var3 ), 1)->row();
			$neighborhood = $this->places_model->get_neighborhoods(array( 't1.id' => $var4 ), 1)->row();

			$data = array(
				'component_name' => $this->component_name,
				'neighborhood' => $neighborhood,
				'city' => $city,
				'state' => $state,
				'country' => $country,
			);

			//validação dos campos
			$this->form_validation->set_rules('title',lang('title'),'trim|required');
			$this->form_validation->set_message('is_unique', lang('there_is_already_a_record_using_this_value_as'));
			$this->form_validation->set_rules('postal_code',lang('postal_code'),'trim|required|integer');
			$this->form_validation->set_rules('neighborhood_id',lang('neighborhood'),'trim|required|integer');
			$this->form_validation->set_rules('coordinates',lang('coordinates'),'trim');
			$this->form_validation->set_rules('map_url',lang('map_url'),'trim');

			if($this->input->post('submit_cancel')){
				redirect_last_url();
			}
			// se a validação dos campos for bem sucedida
			else if ($this->form_validation->run() AND ($this->input->post('submit') OR $this->input->post('submit_apply'))){
				$insert_data = elements(array(
					'title',
					'postal_code',
					'coordinates',
					'map_url',
					'neighborhood_id',
				),$this->input->post());

				$return_id=$this->places_model->insert_public_area($insert_data);
				if ($return_id){
					msg(('public_area_added'),'success');
					if ($this->input->post('submit_apply')){
						redirect(get_url('admin/'.$this->component_name . '/' . __FUNCTION__.'/edit_public_area/'.$var1 . '/' . $var2 . '/' . $var3 . '/' . $var4 . '/' . $return_id));
					}
					else{
						redirect_last_url();
					}
				}

			}
			// caso contrário se a validação dos campos for negativa e mensagens de erro conter strings
			else if (!$this->form_validation->run() AND validation_errors() != ''){

				$data['post'] = $this->input->post();

				msg(('add_public_area_fail'),'title');
				msg(validation_errors('<div class="error">', '</div>'),'error');
			}

			$this->_page(

				array(

					'component_view_folder' => $this->component_view_folder,
					'function' => __FUNCTION__,
					'action' => $action,
					'layout' => 'default',
					'view' => $action,
					'data' => $data,

				)

			);

		}

		/****************** Add public area ***************/
		/**************************************************/

		/**************************************************/
		/***************** Edit public area ***************/

		else if ($action == 'edit_public_area' AND ($public_area = $this->places_model->get_public_areas(array('t1.id'=>$var5), 1)->row())){

			// $var1 = id do país
			// $var2 = id do estado
			// $var3 = id da cidade
			// $var4 = id do bairro
			// $var5 = id do logadouro
			if ( ! $var1 OR ! $var2 OR ! $var3 OR ! $var4 OR ! $var5 ){
				msg(('error'),'title');

				if ( ! $var1 ){
					msg(('no_country_selected'),'error');
				}
				if ( ! $var2 ){
					msg(('no_state_selected'),'error');
				}
				if ( ! $var3 ){
					msg(('no_city_selected'),'error');
				}
				if ( ! $var4 ){
					msg(('no_neighborhood_selected'),'error');
				}
				if ( ! $var4 ){
					msg(('no_public_area_selected'),'error');
				}
				redirect_last_url();

			}

			$country = $this->places_model->get_countries(array( 'id' => $var1 ), 1)->row();
			$state = $this->places_model->get_states(array( 't1.id' => $var2 ), 1)->row();
			$city = $this->places_model->get_cities(array( 't1.id' => $var3 ), 1)->row();
			$neighborhood = $this->places_model->get_neighborhoods(array( 't1.id' => $var4 ), 1)->row();

			$data = array(
				'component_name' => $this->component_name,
				'public_area' => $public_area,
				'neighborhood' => $neighborhood,
				'city' => $city,
				'state' => $state,
				'country' => $country,
			);

			//validação dos campos
			$this->form_validation->set_rules('title',lang('title'),'trim|required');
			$this->form_validation->set_rules('postal_code',lang('postal_code'),'trim|required|integer');
			$this->form_validation->set_rules('neighborhood_id',lang('neighborhood'),'trim|required|integer');
			$this->form_validation->set_rules('coordinates',lang('coordinates'),'trim');
			$this->form_validation->set_rules('map_url',lang('map_url'),'trim');

			if($this->input->post('submit_cancel')){
				redirect_last_url();
			}
			// se a validação dos campos for positiva
			else if ( $this->form_validation->run() AND ($this->input->post('submit') OR $this->input->post('submit_apply')) ){
				$update_data = elements(array(
					'title',
					'postal_code',
					'coordinates',
					'map_url',
					'neighborhood_id',
				),$this->input->post());

				if ($this->places_model->update_public_area($update_data, array('id' => $var5))){
					msg(('public_area_updated'),'success');

					if ($this->input->post('submit_apply')){
						redirect(get_url('admin'.$this->uri->ruri_string()));
					}
					else{
						redirect_last_url();
					}
				}

			}
			// caso contrário se a validação dos campos for negativa e mensagens de erro conter strings
			else if ( ! $this->form_validation->run() AND validation_errors() != '' ){

				$data['post'] = $this->input->post();

				msg(('update_public_area_fail'),'title');
				msg(validation_errors('<div class="error">', '</div>'),'error');
			}

			$this->_page(

				array(

					'component_view_folder' => $this->component_view_folder,
					'function' => __FUNCTION__,
					'action' => $action,
					'layout' => 'default',
					'view' => $action,
					'data' => $data,

				)

			);

		}

		/***************** Edit public area ***************/
		/**************************************************/

		/**************************************************/
		/**************** Remove public area **************/

		else if ($action == 'remove_public_area' AND ($public_area = $this->places_model->get_public_areas(array('t1.id' => $var5), 1)->row())){

			// $var1 = id do país
			// $var2 = id do estado
			// $var3 = id da cidade
			// $var4 = id do bairro
			// $var5 = id do logadouro
			if ( ! $var1 OR ! $var2 OR ! $var3 OR ! $var4 OR ! $var5 ){
				msg(('error'),'title');

				if ( ! $var1 ){
					msg(('no_country_selected'),'error');
				}
				if ( ! $var2 ){
					msg(('no_state_selected'),'error');
				}
				if ( ! $var3 ){
					msg(('no_city_selected'),'error');
				}
				if ( ! $var4 ){
					msg(('no_neighborhood_selected'),'error');
				}
				if ( ! $var4 ){
					msg(('no_public_area_selected'),'error');
				}
				redirect_last_url();

			}

			$country = $this->places_model->get_countries(array( 'id' => $var1 ), 1)->row();
			$state = $this->places_model->get_states(array( 't1.id' => $var2 ), 1)->row();
			$city = $this->places_model->get_cities(array( 't1.id' => $var3 ), 1)->row();
			$neighborhood = $this->places_model->get_neighborhoods(array( 't1.id' => $var4 ), 1)->row();

			if($this->input->post('submit_cancel')){
				redirect_last_url();
			}
			else if ($this->input->post('submit')){
				if ($this->places_model->delete_public_area(array('id'=>$var5))){
					msg(('public_area_deleted'),'success');
					redirect_last_url();
				}
				else{
					msg($this->lang->line('public_area_deleted_fail'),'error');
					redirect_last_url();
				}
			}
			else{
				$data=array(
					'component_name' => $this->component_name,
					'public_area' => $public_area,
					'neighborhood' => $neighborhood,
					'city' => $city,
					'state' => $state,
					'country' => $country,
				);

				$this->_page(

					array(

						'component_view_folder' => $this->component_view_folder,
						'function' => __FUNCTION__,
						'action' => $action,
						'layout' => 'default',
						'view' => $action,
						'data' => $data,

					)

				);

			}

		}

		/**************** Remove public area **************/
		/**************************************************/

		else{
			show_404();
		}

	}

	/*********************************** Places ***********************************/
	/******************************************************************************/
	/******************************************************************************/

	/******************************************************************************/
	/******************************************************************************/
	/**************************** External components *****************************/

	// funcções utilizadas por outros componentes para obter dados
	// os campos aqui definidos são padronizados, e nunca definidos pelo componente requisitador
	// o componente requisitante deve obedecer estes padrões
	public function places_select($action = NULL, $var1 = NULL, $var2 = NULL, $var3 = NULL, $var4 = NULL, $var5 = NULL, $var6 = NULL){

		$this->component_function = __FUNCTION__;
		$this->component_function_action = $action;

		if ( ! $action ) $action = 'select_country';

		$url = get_url('admin'.$this->uri->ruri_string());

		if ( $this->session->envdata('reference_data_id') ){

			$this->session->set_envdata('select_on', TRUE);

			$reference_data_id = $this->session->envdata('reference_data_id');
			$temp_data = $this->main_model->get_temp_data(array('id' =>$reference_data_id))->row();
			$temp_data->data = json_decode($temp_data->data, TRUE);

			$reference = $temp_data->reference;

			if( $this->input->post('submit_ok') ){

				$data = array(
					'data' => array(
						'selection' => 'ok',
						'component_selection' => 'places',
					),
				);

				$data['data'] = json_encode(array_merge($data['data'], $temp_data->data));

				$this->main_model->update_temp_data($data, array('id' =>$reference_data_id));
				redirect($reference);

			}

			// se o usuário cancelar a escolha
			else if( $this->input->post('submit_cancel') ){

				$data = array(
					'data' => array(
						'selection' => 'canceled',
						'component_selection' => 'places',
					),
				);

				$data['data'] = json_encode(array_merge($data['data'], $temp_data->data));

				$this->main_model->update_temp_data($data, array('id' =>$reference_data_id));
				redirect($temp_data->reference);

			}

			$data = array(
				'component_name' => $this->component_name,
				'search_fields' => array(
					'postal_code' => '',
				),
			);

			/**************************************************/
			/********************* Search *********************/

			if ( $action == 'search' ){

				$this->load->helper(array('pagination'));

				// $var1 = página atual
				// $var2 = itens por página
				if ( $var1 < 1 OR ! gettype($var1) == 'int' ) $var1 = 1;
				if ( $var2 < 1 OR ! gettype($var2) == 'int' ) $var2 = $this->mcm->filtered_system_params['admin_items_per_page'];
				$offset = ($var1-1)*$var2;

				//validação dos campos
				$errors = FALSE;
				$errors_msg = '';
				$postal_code = '';
				if ( ( $this->input->post('submit_search') AND ( $this->input->post('postal_code', TRUE) OR $this->input->post('postal_code', TRUE) == 0) ) OR $this->input->get('pc') ){

					$postal_code = trim( $this->input->post('postal_code') ? $this->input->post('postal_code') : $this->input->get('pc') );

					if ( strlen($postal_code) == 0 ){
						$errors = TRUE;
						$errors_msg .= '<div class="error">'.lang('validation_error_postal_code_not_blank').'</div>';
					}
					if ( ! is_numeric($postal_code) OR (int)$postal_code < 0 ){
						$errors = TRUE;
						$errors_msg .= '<div class="error">'.lang('validation_error_postal_code_only_integer').'</div>';
					}
					if ( strlen($postal_code) < 3 ){
						$errors = TRUE;
						$errors_msg .= '<div class="error">'.sprintf(lang('validation_error_postal_code_min_lenght'), 3).'</div>';
					}

				}
				else if ( $this->input->post('submit_cancel_search', TRUE) ){
					redirect( 'admin/'.$this->component_name . '/' . __FUNCTION__.'/select_country' );
				}

				$data['search_fields']['postal_code'] = $postal_code;

				$this->form_validation->set_rules('postal_code',lang('postal_code'),'trim|integer|min_length[3]');

				if( ( $this->input->post() OR $this->input->get('pc') ) AND ! $errors){

					$condition = NULL;
					$or_condition = NULL;

					if( $postal_code ){

						//print_r($this->input->post());
						$query = $this->input->post('postal_code') ? $this->input->post('postal_code') : $this->input->get('pc');
						$or_condition['fake_index_1'] = '`t1`.`postal_code` LIKE \'%'.$query.'%\'';

						$public_areas = $this->places_model->get_search_results($condition, $or_condition, $var2, $offset);


						$data['public_areas'] = $public_areas->result();
						$data['pagination'] = get_pagination('admin/'.$this->component_name . '/' . __FUNCTION__ . '/' . $action.'/%p%/%ipp%?pc='.$query, $var1, $var2, $this->places_model->get_search_results($condition, $or_condition, NULL, NULL,'count_all_results'));

					}
					else if($this->input->post('submit_select')){

						$public_area = $this->places_model->get_public_areas(array('t1.id' => $this->input->post('public_area_id')));

						$selected_addresses = array();

						$selected_addresses[] = array(
							'country_id' => $public_area->row()->country_id,
							'country_alias' => $public_area->row()->country_alias,
							'country_title' => $public_area->row()->country_title,
							'state_id' => $public_area->row()->state_id,
							'state_acronym' => $public_area->row()->state_acronym,
							'state_title' => $public_area->row()->state_title,
							'city_id' => $public_area->row()->city_id,
							'city_title' => $public_area->row()->city_title,
							'neighborhood_id' => $public_area->row()->neighborhood_id,
							'neighborhood_title' => $public_area->row()->neighborhood_title,
							'public_area_id' => $public_area->row()->id,
							'public_area_title' => $public_area->row()->title,
							'postal_code' => $public_area->row()->postal_code,
						);

						$data = array(
							'data' => array(
								'component_selection' => 'places',
								'selected_addresses' => $selected_addresses,
								'selection' => 'selected',
							),
						);

						$temp_data->data['selected_addresses'] = array();

						$data['data'] = json_encode( array_merge( $temp_data->data, $data['data'] ) );

						$this->main_model->update_temp_data($data, array('id' =>$reference_data_id));
						redirect($temp_data->reference);
					}

				}
				else if ( $errors ){

					$data['post'] = $this->input->post();

					msg(('search_fail'),'title');
					msg($errors_msg,'error');
				}

				$this->_page(

					array(

						'component_view_folder' => $this->component_view_folder,
						'function' => __FUNCTION__,
						'action' => $action,
						'layout' => 'default',
						'view' => $action,
						'data' => $data,

					)

				);

			}

			/********************* Search *********************/
			/**************************************************/

			/**************************************************/

			/**************************************************/
			/***************** Select country *****************/

			else if ($action == 'select_country'){

				$this->load->helper(array('pagination'));

				// $var1 = página atual
				// $var2 = itens por página
				if ( $var1 < 1 OR ! gettype($var1) == 'int' ) $var1 = 1;
				if ( $var2 < 1 OR ! gettype($var2) == 'int' ) $var2 = $this->mcm->filtered_system_params['admin_items_per_page'];
				$offset = ($var1-1)*$var2;

				if ($countries = $this->places_model->get_countries(NULL, $var2, $offset)){

					//validação dos campos
					$this->form_validation->set_rules('country_id',lang('country_id'),'trim|integer|required');

					// se o usuário cancelar a escolha
					if($this->input->post('submit_cancel')){
						$this->main_model->delete_temp_data(array('id' =>$reference_data_id));
						redirect($temp_data->reference);
					}
					else if($this->input->post('submit_select')){

						$country = $this->places_model->get_countries(array('id' => $this->input->post('country_id')));

						$selected_addresses = array();

						$selected_addresses[] = array(
							'country_id' => $country->row()->id,
							'country_alias' => $country->row()->alias,
							'country_title' => $country->row()->title,
						);

						$data = array(
							'data' => array(
								'selected_addresses' => $selected_addresses,
								'selection' => 'selected',
								'component_selection' => 'places',
							),
						);

						$data['data'] = json_encode(array_merge($data['data'], $temp_data->data));

						$this->main_model->update_temp_data($data, array('id' =>$reference_data_id));
						redirect( 'admin/'.$this->component_name . '/' . __FUNCTION__.'/select_state/'.$country->row()->id );
					}

					$data['countries'] = $countries->result();
					$data['pagination'] = get_pagination('admin/'.$this->component_name . '/' . __FUNCTION__ . '/' . $action.'/%p%/%ipp%', $var1, $var2, $this->places_model->get_countries(NULL, NULL, NULL,'count_all_results'));

					$this->_page(

						array(

							'component_view_folder' => $this->component_view_folder,
							'function' => __FUNCTION__,
							'action' => $action,
							'layout' => 'default',
							'view' => $action,
							'data' => $data,

						)

					);

				}
			}

			/***************** Select country *****************/
			/**************************************************/

			/**************************************************/

			/**************************************************/
			/****************** Select state ******************/

			else if ($action == 'select_state'){

				// $var1 = id do país
				if ( ! $var1 ){
					msg(('states_list_fail'),'title');
					msg(('no_country_selected'),'error');
					redirect_last_url();
				}

				$country = $this->places_model->get_countries(array( 'id' => $var1 ), 1)->row();

				$this->load->helper(array('pagination'));

				// $var2 = página atual
				// $var3 = itens por página
				if ( $var2 < 1 OR ! gettype($var2) == 'int' ) $var2 = 1;
				if ( $var3 < 1 OR ! gettype($var3) == 'int' ) $var3 = $this->mcm->filtered_system_params['admin_items_per_page'];
				$offset = ($var2-1)*$var3;

				$condition = array( 'country_id' => $var1 );

				if ($states = $this->places_model->get_states($condition, $var3, $offset)){

					//validação dos campos
					$this->form_validation->set_rules('state_id',lang('state_id'),'trim|integer|required');

					// se o usuário cancelar a escolha
					if($this->input->post('submit_cancel')){
						$this->main_model->delete_temp_data(array('id' =>$reference_data_id));
						redirect($temp_data->reference);
					}
					else if($this->input->post('submit_select')){

						$state = $this->places_model->get_states(array('t1.id' => $this->input->post('state_id')));

						$selected_addresses = array();

						$selected_addresses[] = array(
							'state_id' => $state->row()->id,
							'state_acronym' => $state->row()->acronym,
							'state_title' => $state->row()->title,
						);

						$data = array(
							'data' => array(
								'selected_addresses' => $selected_addresses,
								'selection' => 'selected',
								'component_selection' => 'places',
							),
						);

						foreach ($data['data']['selected_addresses'] as $key => $address) {
							$data['data']['selected_addresses'][$key] = array_merge( $temp_data->data['selected_addresses'][$key] , $data['data']['selected_addresses'][$key] );
						}

						$data['data'] = json_encode(array_merge($temp_data->data, $data['data']));

						$this->main_model->update_temp_data($data, array('id' =>$reference_data_id));

						redirect( 'admin/'.$this->component_name . '/' . __FUNCTION__.'/select_city/'.$state->row()->country_id . '/' . $state->row()->id );
					}

					$data['states'] = $states->result();
					$data['country'] = $country;
					$data['pagination'] = get_pagination('admin/'.$this->component_name . '/' . __FUNCTION__ . '/' . $action . '/' . $var1.'/%p%/%ipp%', $var2, $var3, $this->places_model->get_states($condition, NULL, NULL,'count_all_results'));

					$this->_page(

						array(

							'component_view_folder' => $this->component_view_folder,
							'function' => __FUNCTION__,
							'action' => $action,
							'layout' => 'default',
							'view' => $action,
							'data' => $data,

						)

					);

				}
			}

			/****************** Select state ******************/
			/**************************************************/

			/**************************************************/

			/**************************************************/
			/******************* Select city ******************/

			else if ($action == 'select_city'){

				// $var1 = id do país
				if ( ! $var1 ){
					msg(('cities_list_fail'),'title');
					msg(('no_country_selected'),'error');
					redirect_last_url();
				}
				// $var2 = id do estado
				if ( ! $var2 ){
					msg(('cities_list_fail'),'title');
					msg(('no_state_selected'),'error');
					redirect_last_url();
				}

				$country = $this->places_model->get_countries(array( 'id' => $var1 ), 1)->row();
				$state = $this->places_model->get_states(array( 't1.id' => $var2 ), 1)->row();

				$this->load->helper(array('pagination'));

				// $var3 = página atual
				// $var4 = itens por página
				if ( $var3 < 1 OR ! gettype($var3) == 'int' ) $var3 = 1;
				if ( $var4 < 1 OR ! gettype($var4) == 'int' ) $var4 = $this->mcm->filtered_system_params['admin_items_per_page'];
				$offset = ($var3-1)*$var4;

				// $var1 = id do país
				$condition = array( 'country_id' => $var1 );
				$condition = array( 'state_id' => $var2 );

				if ($cities = $this->places_model->get_cities($condition, $var4, $offset)){

					//validação dos campos
					$this->form_validation->set_rules('city_id',lang('city_id'),'trim|integer|required');

					// se o usuário cancelar a escolha
					if($this->input->post('submit_cancel')){
						$this->main_model->delete_temp_data(array('id' =>$reference_data_id));
						redirect($temp_data->reference);
					}
					else if($this->input->post('submit_select')){

						$city = $this->places_model->get_cities(array('t1.id' => $this->input->post('city_id')));

						$selected_addresses = array();

						$selected_addresses[] = array(
							'city_id' => $city->row()->id,
							'city_title' => $city->row()->title,
						);

						$data = array(
							'data' => array(
								'selected_addresses' => $selected_addresses,
								'selection' => 'selected',
								'component_selection' => 'places',
							),
						);

						foreach ($data['data']['selected_addresses'] as $key => $address) {
							$data['data']['selected_addresses'][$key] = array_merge( $temp_data->data['selected_addresses'][$key] , $data['data']['selected_addresses'][$key] );
						}

						$data['data'] = json_encode(array_merge($temp_data->data, $data['data']));

						$this->main_model->update_temp_data($data, array('id' =>$reference_data_id));

						redirect( 'admin/'.$this->component_name . '/' . __FUNCTION__.'/select_neighborhood/'.$city->row()->country_id . '/' . $city->row()->state_id . '/' . $city->row()->id );
					}

					$data['cities'] = $cities->result();
					$data['state'] = $state;
					$data['country'] = $country;
					$data['pagination'] = get_pagination('admin/'.$this->component_name . '/' . __FUNCTION__ . '/' . $action . '/' . $var1 . '/' . $var2.'/%p%/%ipp%', $var3, $var4, $this->places_model->get_cities($condition, NULL, NULL,'count_all_results'));

					$this->_page(

						array(

							'component_view_folder' => $this->component_view_folder,
							'function' => __FUNCTION__,
							'action' => $action,
							'layout' => 'default',
							'view' => $action,
							'data' => $data,

						)

					);

				}
			}

			/******************* Select city ******************/
			/**************************************************/

			/**************************************************/

			/**************************************************/
			/*************** Select neighborhood **************/

			else if ($action == 'select_neighborhood'){

				// $var1 = id do país
				// $var2 = id do estado
				// $var3 = id da cidade
				if ( ! $var1 OR ! $var2 OR ! $var3 ){
					msg(('error'),'title');

					if ( ! $var1 ){
						msg(('no_country_selected'),'error');
					}
					if ( ! $var2 ){
						msg(('no_state_selected'),'error');
					}
					if ( ! $var3 ){
						msg(('no_city_selected'),'error');
					}
					redirect_last_url();

				}

				$country = $this->places_model->get_countries(array( 'id' => $var1 ), 1)->row();
				$state = $this->places_model->get_states(array( 't1.id' => $var2 ), 1)->row();
				$city = $this->places_model->get_cities(array( 't1.id' => $var3 ), 1)->row();

				$this->load->helper(array('pagination'));

				// $var4 = página atual
				// $var5 = itens por página
				if ( $var4 < 1 OR ! gettype($var4) == 'int' ) $var4 = 1;
				if ( $var5 < 1 OR ! gettype($var5) == 'int' ) $var5 = $this->mcm->filtered_system_params['admin_items_per_page'];
				$offset = ($var4-1)*$var5;

				// $var1 = id do país
				$condition = array( 'country_id' => $var1 );
				$condition = array( 'state_id' => $var2 );
				$condition = array( 'city_id' => $var3 );

				if ($neighborhoods = $this->places_model->get_neighborhoods($condition, $var5, $offset)){

					//validação dos campos
					$this->form_validation->set_rules('neighborhood_id',lang('neighborhood_id'),'trim|integer|required');

					// se o usuário cancelar a escolha
					if($this->input->post('submit_cancel')){
						$this->main_model->delete_temp_data(array('id' =>$reference_data_id));
						redirect($temp_data->reference);
					}
					else if($this->input->post('submit_select')){

						$neighborhood = $this->places_model->get_neighborhoods(array('t1.id' => $this->input->post('neighborhood_id')));

						$selected_addresses = array();

						$selected_addresses[] = array(
							'neighborhood_id' => $neighborhood->row()->id,
							'neighborhood_title' => $neighborhood->row()->title,
						);

						$data = array(
							'data' => array(
								'selected_addresses' => $selected_addresses,
								'selection' => 'selected',
								'component_selection' => 'places',
							),
						);

						foreach ($data['data']['selected_addresses'] as $key => $address) {
							$data['data']['selected_addresses'][$key] = array_merge( $temp_data->data['selected_addresses'][$key] , $data['data']['selected_addresses'][$key] );
						}

						$data['data'] = json_encode(array_merge($temp_data->data, $data['data']));

						$this->main_model->update_temp_data($data, array('id' =>$reference_data_id));

						redirect( 'admin/'.$this->component_name . '/' . __FUNCTION__.'/select_public_area/'.$neighborhood->row()->country_id . '/' . $neighborhood->row()->state_id . '/' . $neighborhood->row()->city_id . '/' . $neighborhood->row()->id );
					}

					$data['neighborhoods'] = $neighborhoods->result();
					$data['city'] = $city;
					$data['state'] = $state;
					$data['country'] = $country;
					$data['pagination'] = get_pagination('admin/'.$this->component_name . '/' . __FUNCTION__ . '/' . $action . '/' . $var1 . '/' . $var2 . '/' . $var3.'/%p%/%ipp%', $var4, $var5, $this->places_model->get_neighborhoods($condition, NULL, NULL,'count_all_results'));

					$this->_page(

						array(

							'component_view_folder' => $this->component_view_folder,
							'function' => __FUNCTION__,
							'action' => $action,
							'layout' => 'default',
							'view' => $action,
							'data' => $data,

						)

					);

				}
			}

			/*************** Select neighborhood **************/
			/**************************************************/

			/**************************************************/

			/**************************************************/
			/**************** Select public area **************/

			else if ($action == 'select_public_area'){

				// $var1 = id do país
				// $var2 = id do estado
				// $var3 = id da cidade
				// $var4 = id do bairro
				if ( ! $var1 OR ! $var2 OR ! $var3 OR ! $var4 ){
					msg(('error'),'title');

					if ( ! $var1 ){
						msg(('no_country_selected'),'error');
					}
					if ( ! $var2 ){
						msg(('no_state_selected'),'error');
					}
					if ( ! $var3 ){
						msg(('no_city_selected'),'error');
					}
					if ( ! $var4 ){
						msg(('no_neighborhood_selected'),'error');
					}
					redirect_last_url();

				}

				$country = $this->places_model->get_countries(array( 'id' => $var1 ), 1)->row();
				$state = $this->places_model->get_states(array( 't1.id' => $var2 ), 1)->row();
				$city = $this->places_model->get_cities(array( 't1.id' => $var3 ), 1)->row();
				$neighborhood = $this->places_model->get_neighborhoods(array( 't1.id' => $var4 ), 1)->row();

				$this->load->helper(array('pagination'));

				// $var5 = página atual
				// $var6 = itens por página
				if ( $var5 < 1 OR ! gettype($var5) == 'int' ) $var5 = 1;
				if ( $var6 < 1 OR ! gettype($var6) == 'int' ) $var6 = $this->mcm->filtered_system_params['admin_items_per_page'];
				$offset = ($var5-1)*$var6;

				$condition = array( 'country_id' => $var1 );
				$condition = array( 'state_id' => $var2 );
				$condition = array( 'city_id' => $var3 );
				$condition = array( 'neighborhood_id' => $var4 );

				if ($public_areas = $this->places_model->get_public_areas($condition, $var6, $offset)){

					//validação dos campos
					$this->form_validation->set_rules('public_area_id',lang('public_area_id'),'trim|integer|required');

					// se o usuário cancelar a escolha
					if($this->input->post('submit_cancel')){

						$this->main_model->delete_temp_data(array('id' =>$reference_data_id));

						redirect( $reference );

					}
					else if( $this->input->post('submit_select') ){

						$public_area = $this->places_model->get_public_areas(array('t1.id' => $this->input->post('public_area_id')));

						$selected_addresses = array();

						$selected_addresses[] = array(
							'public_area_id' => $public_area->row()->id,
							'public_area_title' => $public_area->row()->title,
							'postal_code' => $public_area->row()->postal_code,
						);

						$data = array(
							'data' => array(
								'selected_addresses' => $selected_addresses,
								'selection' => 'selected',
								'component_selection' => 'places',
							),
						);

						foreach ($data['data']['selected_addresses'] as $key => $address) {
							$data['data']['selected_addresses'][$key] = array_merge( $temp_data->data['selected_addresses'][$key] , $data['data']['selected_addresses'][$key] );
						}

						$data['data'] = json_encode(array_merge($temp_data->data, $data['data']));

						$this->main_model->update_temp_data($data, array('id' =>$reference_data_id));

						redirect( $reference );
					}

					$data['public_areas'] = $public_areas->result();
					$data['neighborhood'] = $neighborhood;
					$data['city'] = $city;
					$data['state'] = $state;
					$data['country'] = $country;
					$data['pagination'] = get_pagination('admin/'.$this->component_name . '/' . __FUNCTION__ . '/' . $action . '/' . $var1 . '/' . $var2 . '/' . $var3 . '/' . $var4.'/%p%/%ipp%', $var5, $var6, $this->places_model->get_public_areas($condition, NULL, NULL,'count_all_results'));

					$this->_page(

						array(

							'component_view_folder' => $this->component_view_folder,
							'function' => __FUNCTION__,
							'action' => $action,
							'layout' => 'default',
							'view' => $action,
							'data' => $data,

						)

					);

				}
			}

			/**************** Select public area **************/
			/**************************************************/

			else{
				show_404();
			}

		} else {
			msg(('no_reference_informed'),'error');
			redirect_last_url();
		}

	}

	/**************************** External components *****************************/
	/******************************************************************************/
	/******************************************************************************/

}

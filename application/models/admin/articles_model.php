<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

	class Articles_model extends CI_Model{

		private $categories_tree = '';
		private $next_has_children = FALSE;
		private $categories_tree_array = array();

		// Essa função sempre gera o html em formato de menu, sempre com um </li> no final,
		// mas no retorno da função get_menu_tree ele é retirado
		private function get_children_as_menu($parent_id, $level) {

			// pega todos os filhos de $parent_id
			$this->db->where('parent = '.$parent_id);
			$this->db->order_by('ordering asc, title asc, id asc');
			$query = $this->db->get('tb_articles_categories');

			// variável que determina se o item atual possui filhos
			$has_children = $this->get_num_childrens($parent_id);

			// se o item atual possui filhos, insere a primeira <ul>
			if ($has_children) $this->categories_tree .= "<ul>\n";

			// display each child
			foreach($query->result() as $row) {

				// verifica se este filho possui outros filhos
				$next_has_children = $this->get_num_childrens($row->id)?TRUE:FALSE;

				// indent and display the title of this child
				$this->categories_tree .= '<li class="level-'.$level.'">'.$row->title.($next_has_children?"\n":'</li>'."\n");

				// chama esta função novamente para mostrar os filhos deste filho
				$this->get_children_as_menu($row->id, $level+1);

			}
			if ($has_children) $this->categories_tree .= $next_has_children?"</ul>\n":"</ul>\n</li>\n";

		}

		// Essa função sempre gera uma lista para comboboxes em formato de array
		// O parâmetro $query serve para economizar consultas ao bando de dados, caso a função tenha que se "auto chamar" novamente
		private function get_children_as_list($parent_id, $level, $query = NULL) {

			// Caso a query não tenha sido informada, executa isto
			if (!$query){
				$this->db->select('t1.*, t2.title as parent_title, t2.alias as parent_alias');
				$this->db->from('tb_articles_categories t1');
				$this->db->join('tb_articles_categories t2', 't1.parent = t2.id', 'left');

				$this->db->order_by('ordering asc, title asc, id asc');
				$query = $this->db->get();
				$query = $query->result_array();
			}
			// atribui todos os filhos para a variável $menu_tree
			foreach($query as $row) {
				if ($row['parent'] == $parent_id) {
					// atribui cada valor da categoria em um array
					$this->categories_tree[$row['id']] = array(
						'id' => $row['id'],
						'alias' => $row['alias'],
						'title' => $row['title'],
						'ordering' => $row['ordering'],
						'parent' => $row['parent'],
						'parent_alias' => $row['parent_alias'],
						'parent_title' => $row['parent_title'],
						'status' => $row['status'],
						'indented_title' => str_repeat( '&nbsp;' , $level * 4 + 4) . lang( 'indented_symbol' ).$row['title'],
						'description' => $row['description'],
						'level' => $level,
					);

					// chama esta função novamente para mostrar os filhos deste filho
					$this->get_children_as_list($row['id'], $level+1, $query);
				}
			}
		}
		// Função que retorna o número de filhos de um item
		public function get_num_childrens($id){

			$this->db->where('parent = '.$id);
			$this->db->order_by('ordering asc, title asc, id asc');
			$this->db->from('tb_articles_categories');
			// retorna a quantidade de registros com item pai $id
			return $this->db->count_all_results();

		}
		public function get_categories_tree($parent_id, $level, $type = 'menu'){

			if ($type == 'menu'){
				$this->get_children_as_menu($parent_id, $level);
				// remove o último </li> da string
				return substr_replace($this->categories_tree,'',-6);
			}
			else if ($type == 'list'){
				$this->get_children_as_list($parent_id, $level);
				return $this->categories_tree;
			}
			else {
				return FALSE;
			}

		}
		public function get_category_path($category_id, $parent_limit = 0, $query = NULL){

			// Caso a query não tenha sido informada, executa isto
			if (!$query){
				$this->db->select('t1.*, t2.title as parent_title, t2.alias as parent_alias');
				$this->db->from('tb_articles_categories t1');
				$this->db->join('tb_articles_categories t2', 't1.parent = t2.id', 'left');

				$this->db->order_by('t1.ordering asc, t1.title asc, t1.id asc');
				$query = $this->db->get();
				$query = $query->result_array();

			}

			$path = array();

			foreach($query as $row) {
				if ($row['id'] == $category_id AND $row['parent'] != $parent_limit) {

					$path[] = array(
						'id' => $row['parent'],
						'title' => $row['parent_title'],
						'alias' => $row['parent_alias'],
					);

					$path = array_merge($this->get_category_path($row['parent'], $parent_limit, $query), $path);
				}
			}

			// return the path
			return $path;

		}
		public function get_categories_as_list_childrens_hidden($root_parent = 0, $parent_to_hide = NULL){

			if ($parent_to_hide){

				$this->db->select('t1.*, t2.title as parent_title, t2.alias as parent_alias');
				$this->db->from('tb_articles_categories t1');
				$this->db->join('tb_articles_categories t2', 't1.parent = t2.id', 'left');

				$this->db->order_by('ordering asc, title asc, id asc');
				$query = $this->db->get();
				$query = $query->result_array();

				// obtendo o array completo das categorias
				$this->get_children_as_list($root_parent,0,$query);
				$categories = $this->categories_tree;

				// zerando a variável categories_tree,
				// que continha o array completo com as categorias
				$this->categories_tree = array();

				// agora obtendo o array das categorias, com raiz $parent_to_hide
				$this->get_children_as_list($parent_to_hide,0,$query);
				$categories_to_hide = $this->categories_tree;

				foreach ($categories_to_hide as $key => $value) {
					if (array_key_exists($key,$categories)){
						unset($categories[$key]);
					}
				}

				return $categories;
			}

		}

		public function get_category( $id = NULL ){

			if ( $id != NULL ){

				$this->db->select('t1.*, t2.title as parent_category_title');
				$this->db->from('tb_articles_categories t1');
				$this->db->join('tb_articles_categories t2', 't1.parent = t2.id', 'left');
				$this->db->where('t1.id',$id);
				// limitando o resultando em apenas 1
				$this->db->limit(1);
				return $this->db->get();

			}
			else {

				return false;

			}

		}

		public function get_categories($condition = NULL){
			$this->db->select('t1.*, t2.title as parent_category_title');
			$this->db->from('tb_articles_categories t1');
			$this->db->join('tb_articles_categories t2', 't1.parent = t2.id', 'left');
			$this->db->order_by('title asc, id asc');
			$this->db->where($condition);
			return $this->db->get();
		}



		public function get_articles($cat_id = NULL,$user_id = NULL){
			$this->db->select('t1.*, t2.title as category_title, t3.username as created_by_username, t3.name as created_by_name, t4.username as modified_by_username, t4.name as modified_by_name, t5.id as access_user_id, t5.username as access_username, t5.name as access_user_name, t6.id as user_group_id, t6.alias as user_group_alias, t6.title as user_group_title');
			$this->db->from('tb_articles t1');
			$this->db->join('tb_articles_categories t2', 't1.category_id = t2.id', 'left');
			$this->db->join('tb_users t3', 't1.created_by_id = t3.id', 'left');
			$this->db->join('tb_users t4', 't1.modified_by_id = t4.id', 'left');
			$this->db->join('tb_users t5', 't1.access_id = t5.id', 'left');
			$this->db->join('tb_users_groups t6', 't1.access_id = t6.id', 'left');
			$this->db->order_by('title asc, id asc');
			if ($cat_id){
				$this->db->where("category_id", $cat_id);
			}
			if ($user_id){
				$this->db->where("created_by_id", $user_id);
			}
			return $this->db->get();
		}

		public function get_article($id = NULL){
			if ($id!=NULL){
				$this->db->select('t1.*, t2.title as category_title, t3.username as created_by_username, t3.name as created_by_name, t4.username as modified_by_username, t4.name as modified_by_name, t5.id as access_user_id, t5.username as access_username, t5.name as access_user_name, t6.id as user_group_id, t6.alias as user_group_alias, t6.title as user_group_title');
				$this->db->from('tb_articles t1');
				$this->db->join('tb_articles_categories t2', 't1.category_id = t2.id', 'left');
				$this->db->join('tb_users t3', 't1.created_by_id = t3.id', 'left');
				$this->db->join('tb_users t4', 't1.modified_by_id = t4.id', 'left');
				$this->db->join('tb_users t5', 't1.access_id = t5.id', 'left');
				$this->db->join('tb_users_groups t6', 't1.access_id = t6.id', 'left');
				$this->db->where('t1.id',$id);
				// limitando o resultando em apenas 1
				$this->db->limit(1);
				return $this->db->get();
			}
			else {
				return false;
			}
		}
		public function insert_article($data = NULL){
			if ($data != NULL){
				if ($this->db->insert('tb_articles',$data)){
					// confirm the insertion for controller
					$return_id = $this->db->insert_id();
					return $return_id;
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

		public function update($data = NULL,$condition = NULL){
			if ($data != NULL && $condition != NULL){
				if ($this->db->update('tb_articles',$data,$condition)){
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

		public function delete_article($condition = NULL){

			if ($condition != null){
				if ($this->db->delete('tb_articles',$condition)){
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


		public function insert_category($data = NULL){
			if ($data != NULL){
				if ($this->db->insert('tb_articles_categories',$data)){
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

		public function update_category($data = NULL,$condition = NULL){
			if ($data != NULL && $condition != NULL){
				if ($this->db->update('tb_articles_categories',$data,$condition)){
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

		public function delete_category($condition = NULL){
			if ($condition != null){
				if ($this->db->delete('tb_articles_categories',$condition)){
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


		/* General params */
		public function get_general_params(){
			
			$params = get_params_spec_from_xml(APPPATH.'controllers/admin/com_articles/general_params.xml');
			
			// carregando os layouts do tema atual
			$layouts_articles_list = dir_list_to_array( THEMES_PATH . site_theme_components_views_path() . get_class_name( get_class() ) . DS . 'index' . DS . 'list' );
			// carregando os layouts do diretório de views padrão
			$layouts_articles_list = array_merge( $layouts_articles_list, dir_list_to_array( VIEWS_PATH . SITE_COMPONENTS_VIEWS_PATH . get_class_name( get_class() ) . DS . 'index' . DS . 'list' ) );
			
			// carregando os layouts do tema atual
			$layouts_article_detail = dir_list_to_array( THEMES_PATH . site_theme_components_views_path() . get_class_name( get_class() ) . DS . 'index' . DS . 'detail' );
			// carregando os layouts do diretório de views padrão
			$layouts_article_detail = array_merge( $layouts_article_detail, dir_list_to_array( VIEWS_PATH . SITE_COMPONENTS_VIEWS_PATH . get_class_name( get_class() ) . DS . 'index' . DS . 'detail' ) );

			foreach ( $params['params_spec']['articles_list'] as $key => $element ) {

				if ( $element['name'] == 'layout_articles_list' ){

					$spec_options = array();

					if ( isset($params['params_spec']['articles_list'][$key]['options']) )
						$spec_options = $params['params_spec']['articles_list'][$key]['options'];

					$params['params_spec']['articles_list'][$key]['options'] = is_array($spec_options) ? $spec_options + $layouts_articles_list : $layouts_articles_list;

				}

			}

			foreach ( $params['params_spec']['detail_view'] as $key => $element ) {

				if ( $element['name'] == 'layout_article_detail' ){

					$spec_options = array();

					if ( isset($params['params_spec']['detail_view'][$key]['options']) )
						$spec_options = $params['params_spec']['detail_view'][$key]['options'];

					$params['params_spec']['detail_view'][$key]['options'] = is_array($spec_options) ? $spec_options + $layouts_article_detail : $layouts_article_detail;

				}

			}

			$after_content_params = $this->plugins->get_params_spec( NULL, 'after_content' );

			$params = array_merge_recursive( $params, $after_content_params );

			return $params;
		}



		public function get_article_params(){

			$params = get_params_spec_from_xml(APPPATH.'controllers/admin/com_articles/article_on_articles_list.xml');
			$params = array_merge_recursive($params, get_params_spec_from_xml(APPPATH.'controllers/admin/com_articles/article.xml'));

			// carregando os layouts do tema atual
			$layouts_articles_list = dir_list_to_array( THEMES_PATH . site_theme_components_views_path() . get_class_name( get_class() ) . DS . 'index' . DS . 'list' );
			// carregando os layouts do diretório de views padrão
			$layouts_articles_list = array_merge( $layouts_articles_list, dir_list_to_array( VIEWS_PATH . SITE_COMPONENTS_VIEWS_PATH . get_class_name( get_class() ) . DS . 'index' . DS . 'list' ) );

			// carregando os layouts do tema atual
			$layouts_article_detail = dir_list_to_array( THEMES_PATH . site_theme_components_views_path() . get_class_name( get_class() ) . DS . 'index' . DS . 'detail' );
			// carregando os layouts do diretório de views padrão
			$layouts_article_detail = array_merge( $layouts_article_detail, dir_list_to_array( VIEWS_PATH . SITE_COMPONENTS_VIEWS_PATH . get_class_name( get_class() ) . DS . 'index' . DS . 'detail' ) );

			foreach ( $params['params_spec']['articles_list'] as $key => $element ) {

				if ( $element['name'] == 'layout_articles_list' ){

					$spec_options = array();

					if ( isset($params['params_spec']['articles_list'][$key]['options']) )
						$spec_options = $params['params_spec']['articles_list'][$key]['options'];

					$params['params_spec']['articles_list'][$key]['options'] = is_array($spec_options) ? $spec_options + $layouts_articles_list : $layouts_articles_list;

				}

			}

			foreach ( $params['params_spec']['detail_view'] as $key => $element ) {

				if ( $element['name'] == 'layout_article_detail' ){

					$spec_options = array();

					if ( isset($params['params_spec']['detail_view'][$key]['options']) )
						$spec_options = $params['params_spec']['detail_view'][$key]['options'];

					$params['params_spec']['detail_view'][$key]['options'] = is_array($spec_options) ? $spec_options + $layouts_article_detail : $layouts_article_detail;

				}

			}

			// print_r($params);

			$after_content_params = $this->plugins->get_params_spec( NULL, 'after_content' );
			$params = array_merge_recursive( $params, $after_content_params );

			return $params;

		}


		/**************************************************/
		/***************** Contacts search ****************/

		public function get_articles_search_results($where_condition = NULL, $or_where_condition = NULL, $limit = NULL, $offset = NULL, $return_type = 'get', $order_by = 't1.title asc, t1.id asc', $order_by_escape = TRUE ){

			$this->db->select('

				t1.*');

			$this->db->from('tb_articles t1');
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
			if ( $limit ){
				$this->db->limit($limit, $offset?$offset:NULL);
			}

			return $this->db->get();
		}

		/***************** Contacts search ****************/
		/**************************************************/





		/* Article detail */

		public function menu_item_article_detail(){

			// Registrando a função
			$this->menu_item_component_model = __FUNCTION__;

			$params = get_params_spec_from_xml(APPPATH.'controllers/admin/com_articles/menu_item_article.xml');
			$params = array_merge_recursive($params, get_params_spec_from_xml(APPPATH.'controllers/admin/com_articles/article.xml'));
			$params = array_merge_recursive($params, get_params_spec_from_xml(APPPATH.'controllers/admin/com_articles/article_on_articles_list.xml'));

			// carregando os layouts do tema atual
			$layouts_article_detail = dir_list_to_array( THEMES_PATH . site_theme_components_views_path() . get_class_name( get_class() ) . DS . 'index' . DS . 'detail' );
			// carregando os layouts do diretório de views padrão
			$layouts_article_detail = array_merge( $layouts_article_detail, dir_list_to_array( VIEWS_PATH . SITE_COMPONENTS_VIEWS_PATH . get_class_name( get_class() ) . DS . 'index' . DS . 'detail' ) );
			//echo VIEWS_PATH . SITE_COMPONENTS_VIEWS_PATH . get_class_name( get_class() ) . DS . 'index' . DS . 'article_detail' ;
			$query = $this->get_articles()->result();
			foreach ($query as $article) {
				$articles_options[$article->id] = html_entity_decode( $article->title );
			}
			
			foreach ( $params['params_spec']['detail_view'] as $key => $element ) {

				if ( $element['name'] == 'layout_article_detail' ){

					$spec_options = array();

					if ( isset($params['params_spec']['detail_view'][$key]['options']) )
						$spec_options = $params['params_spec']['detail_view'][$key]['options'];

					$params['params_spec']['detail_view'][$key]['options'] = is_array($spec_options) ? $spec_options + $layouts_article_detail : $layouts_article_detail;

				}

			}

			foreach ( $params['params_spec']['detail_view'] as $key => $element ) {

				if ( $element['name'] == 'article_id' ){

					$spec_options = array();

					if ( isset($params['params_spec']['detail_view'][$key]['options']) )
						$spec_options = $params['params_spec']['detail_view'][$key]['options'];

					$params['params_spec']['detail_view'][$key]['options'] = is_array($spec_options) ? $spec_options + $articles_options : $articles_options;

				}

			}

			// print_r($params);

			$after_content_params = $this->plugins->get_params_spec( NULL, 'after_content' );
			$params = array_merge_recursive( $params, $after_content_params );

			return $params;
		}
		public function menu_item_get_link_article_detail($menu_item_id = NULL, $params = NULL){
			$article_id = $params['article_id'];
			return 'articles/index/article_detail/'.$menu_item_id . '/' . $article_id;
		}
		public function get_link_article_detail($menu_item_id = NULL, $article_id = NULL){
			return 'articles/index/article_detail/'.$menu_item_id . '/' . $article_id;
		}



		public function menu_item_articles_list( $menu_item = NULL ){

			$params = get_params_spec_from_xml( APPPATH . 'controllers/admin/com_articles/menu_item_articles_list.xml' );
			$params = array_merge_recursive($params, get_params_spec_from_xml( APPPATH . 'controllers/admin/com_articles/articles_list.xml') );
			$params = array_merge_recursive($params, get_params_spec_from_xml( APPPATH . 'controllers/admin/com_articles/article_on_articles_list.xml') );
			$params = array_merge_recursive($params, get_params_spec_from_xml( APPPATH . 'controllers/admin/com_articles/article.xml') );

			// obtendo a lista de layouts

			// carregando os layouts do tema atual
			$layouts_articles_list = dir_list_to_array( THEMES_PATH . site_theme_components_views_path() . get_class_name( get_class() ) . DS . 'index' . DS . 'list' );
			// carregando os layouts do diretório de views padrão
			$layouts_articles_list = array_merge( $layouts_articles_list, dir_list_to_array( VIEWS_PATH . SITE_COMPONENTS_VIEWS_PATH . get_class_name( get_class() ) . DS . 'index' . DS . 'list' ) );

			// carregando os layouts do tema atual
			$layouts_article_detail = dir_list_to_array( THEMES_PATH . site_theme_components_views_path() . get_class_name( get_class() ) . DS . 'index' . DS . 'detail' );
			// carregando os layouts do diretório de views padrão
			$layouts_article_detail = array_merge( $layouts_article_detail, dir_list_to_array( VIEWS_PATH . SITE_COMPONENTS_VIEWS_PATH . get_class_name( get_class() ) . DS . 'index' . DS . 'detail' ) );

			$categories = $this->get_categories_tree(0,0,'list');
			$categories_options = array(
				-1 => lang( 'all_articles' ),
				0 => lang( 'uncategorized' ),
			);
			
			if ( $categories ){
				
				foreach ($categories as $category) {
					$categories_options[$category['id']] = $category['indented_title'];
				}
				
			}
			
			//echo '<pre>' . print_r( $params, TRUE ) . '</pre>';
			
			
			foreach ( $params[ 'params_spec' ][ 'articles_list' ] as $key => $element ) {
				
				$element_name = isset( $element['name'] ) ? $element['name'] : FALSE;
				
				if (
					
					$element_name AND (
						
						$element_name == 'category_id' OR
						$element_name == 'layout_articles_list'
						
					)
					
				){
					
					$_new_params = array();
					$spec_options = array();
					
					switch ( $element_name ) {
						
						case 'layout_articles_list':
							
							$_new_params = $layouts_articles_list;
							break;
							
						case 'category_id':
							
							$_new_params = $categories_options;
							break;
							
					}
					
					if ( isset( $params['params_spec']['articles_list'][$key]['options']) )
						$spec_options = $params['params_spec']['articles_list'][$key]['options'];
					
					$params['params_spec']['articles_list'][$key]['options'] = is_array( $spec_options ) ? $spec_options + $_new_params : $_new_params;
					
					unset( $spec_options );
					unset( $_new_params );
					
				}
				
			}
			
			foreach ( $params['params_spec']['detail_view'] as $key => $element ) {
				
				if ( $element['name'] == 'layout_article_detail' ){
					
					$spec_options = array();
					
					if ( isset($params['params_spec']['detail_view'][$key]['options']) )
						$spec_options = $params['params_spec']['detail_view'][$key]['options'];
					
					$params['params_spec']['detail_view'][$key]['options'] = is_array($spec_options) ? $spec_options + $layouts_article_detail : $layouts_article_detail;
					
					break;
					
				}
				
			}
			
			$layout_params = $this->_get_articles_list_layout_params( $menu_item, $params[ 'params_spec_values' ] );
			$params = array_merge_recursive( $params, $layout_params );
			
			$after_content_params = $this->plugins->get_params_spec( NULL, 'after_content' );
			$params = array_merge_recursive( $params, $after_content_params );
			
			return $params;
			
		}

		private function _get_articles_list_layout_params( $menu_item, $params_spec_values ) {

			$articles_component_params = $this->mcm->get_component( 'articles' );
			$articles_component_params = $articles_component_params[ 'params' ];
			$menu_item_params = $menu_item[ 'params' ];

			$component_params_spec = get_params_spec_from_xml( APPPATH . 'controllers/admin/com_articles/general_params.xml' );

			foreach ( $component_params_spec['params_spec']['articles_list'] as $key => $element ) {

				if ( $element['name'] == 'articles_list_order' ){

					$order_by_options = array();

					if ( isset($component_params_spec['params_spec']['articles_list'][$key]['options']) ) {
						
						$order_by_options = $component_params_spec['params_spec']['articles_list'][$key]['options'];
						break;
						
					}
					
				}
				
			}
			


			
			$params_values = filter_params( $articles_component_params, $params_spec_values );
			$params_values = filter_params( $articles_component_params, $menu_item_params );

			$layout_params = array();

			if ( isset( $params_values[ 'layout_articles_list' ] ) AND $params_values[ 'layout_articles_list' ] != 'global' ) {

				$system_views_path = VIEWS_PATH . SITE_COMPONENTS_VIEWS_PATH . get_class_name( get_class() ) . DS . 'index' . DS . 'list' . DS;
				$theme_views_path = THEMES_PATH . site_theme_components_views_path() . get_class_name( get_class() ) . DS . 'index' . DS . 'list' . DS;

				if ( file_exists( $system_views_path . $params_values[ 'layout_articles_list' ] . DS . 'list_params.xml' ) ) {

					$layout_params = get_params_spec_from_xml( $system_views_path . $params_values[ 'layout_articles_list' ] . DS . 'list_params.xml' );

					if ( file_exists( $system_views_path . $params_values[ 'layout_articles_list' ] . DS . 'list_params.php' ) ) {

						include_once $system_views_path . $params_values[ 'layout_articles_list' ] . DS . 'list_params.php';

					}

				}
				else if ( file_exists( $theme_views_path . $params_values[ 'layout_articles_list' ] . DS . 'list_params.xml' ) ) {

					$layout_params = get_params_spec_from_xml( $theme_views_path . $params_values[ 'layout_articles_list' ] . DS . 'list_params.xml' );

					if ( file_exists( $theme_views_path . $params_values[ 'layout_articles_list' ] . DS . 'list_params.php' ) ) {

						include_once $theme_views_path . $params_values[ 'layout_articles_list' ] . DS . 'list_params.php';

					}

				}

				//echo '<pre>' . print_r( $layout_params, TRUE ) . '</pre>';

			}

			return $layout_params;

		}
		public function menu_item_get_link_articles_list( $menu_item_id = NULL, $params = NULL ){

			/**************************************************************/
			/**************** definindo o id da categoria *****************/

			$cat_id = $params[ 'category_id' ];

			/**************** definindo o id da categoria *****************/
			/**************************************************************/

			/**************************************************************/
			/********* definindo a quantidade de itens por página *********/

			$ipp = isset( $params[ 'site_items_per_page' ] ) ? $params[ 'site_items_per_page' ] : 'global';

			if ( $ipp == 'global' ){

				$ipp = '';

			}
			else {

				$ipp = '/' . $ipp;

			}

			/********* definindo a quantidade de itens por página *********/
			/**************************************************************/

			return 'articles/index/articles_list/'.$menu_item_id . '/' . $cat_id.'/0';

		}
		public function get_link_articles_list($menu_item_id = NULL, $cat_id = 0){
			return 'articles/index/articles_list/'.$menu_item_id . '/' . $cat_id.'/0';
		}


	}

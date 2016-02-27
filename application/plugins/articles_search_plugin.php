<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Articles_search_plugin extends Plugins_mdl{

	public function run( &$data, $params = NULL ){

		$return = FALSE;

		if ( is_object( $this->search ) ) {

			log_message( 'debug', '[Plugins] Articles search plugin initialized' );

			if ( ! $this->load->is_model_loaded( 'articles' ) ) {

				$this->load->model( 'articles_mdl', 'articles' );

			}

			// -------------------------------------------------
			// Parsing vars ------------------------------------

			$plugin_params =									$this->search->config( 'plugins_params' );
			$plugin_params =									isset( $plugin_params[ 'articles_search' ] ) ? $plugin_params[ 'articles_search' ] : array();

			// -------------

			$where_conditions =									( isset( $plugin_params[ 'where_conditions' ] ) ) ? $plugin_params[ 'where_conditions' ] : NULL;

			// -------------

			$article_id =										( isset( $plugin_params[ 'article_id' ] ) AND is_numeric( $plugin_params[ 'article_id' ] ) ) ? $plugin_params[ 'article_id' ] : NULL;

			// -------------

			$user_id =											( isset( $plugin_params[ 'user_id' ] ) AND is_numeric( $plugin_params[ 'user_id' ] ) ) ? $plugin_params[ 'user_id' ] : NULL;

			// -------------

			$_allowed_status =	array(

				'archived' => -1,
				'unpublished' => 0,
				'published' => 1,

			);
			$status =											( isset( $plugin_params[ 'status' ] ) AND ( is_numeric( $plugin_params[ 'status' ] ) AND in_array( $plugin_params[ 'status' ], $_allowed_status ) ) ) ? $plugin_params[ 'status' ] : ( ( isset( $plugin_params[ 'status' ] ) AND ( is_string( $plugin_params[ 'status' ] ) AND key_exists( $plugin_params[ 'status' ], $_allowed_status ) ) ) ? $_allowed_status[ $plugin_params[ 'status' ] ] : NULL );

			// -------------

			$random =											( isset( $plugin_params[ 'random' ] ) ) ? ( bool ) $plugin_params[ 'random' ] : FALSE;

			// -------------

			$filter_params =									( isset( $plugin_params[ 'filter_params' ] ) ) ? ( bool ) $plugin_params[ 'filter_params' ] : FALSE;
			$menu_item_id =										( isset( $plugin_params[ 'menu_item_id' ] ) AND is_numeric( $plugin_params[ 'menu_item_id' ] ) ) ? $plugin_params[ 'menu_item_id' ] : NULL;

			// -------------

			$category_id =										( isset( $plugin_params[ 'category_id' ] ) AND is_numeric( $plugin_params[ 'category_id' ] ) ) ? $plugin_params[ 'category_id' ] : NULL;
			$recursive_categories =								( isset( $plugin_params[ 'recursive_categories' ] ) ) ? ( bool ) $plugin_params[ 'recursive_categories' ] : TRUE;

			//if ( $this->search->config( 'plugins_params' ) )

			// -------------

			$order_by =											'id';
			$order_by =											( isset( $order_by[ 'articles_search' ] ) ) ? $order_by[ 'articles_search' ] : NULL;
			$order_by =											( isset( $plugin_params[ 'order_by' ] ) ) ? $plugin_params[ 'order_by' ] : $order_by;

			$order_by_direction =								$this->search->config( 'order_by_direction' );
			$order_by_direction =								( isset( $order_by_direction[ 'articles_search' ] ) ) ? $order_by_direction[ 'articles_search' ] : 'ASC';
			$order_by_direction =								( isset( $plugin_params[ 'order_by_direction' ] ) ) ? $plugin_params[ 'order_by_direction' ] : $order_by_direction;

			// Parsing vars ------------------------------------
			// -------------------------------------------------
			
			// db search params
			$dsp = array(
				
				'plugin_name' => 'articles_search',
				'table_name' => 'tb_articles t1',
				'random' => $random,
				'select_columns' => '
					
					t1.*,
					
					t2.alias as category_alias,
					t2.title as category_title,
					
					t3.username as created_by_username,
					t3.name as created_by_name,
					
					t4.username as modified_by_username,
					t4.name as modified_by_name,
					
					t5.id as access_user_id,
					t5.username as access_username,
					t5.name as access_user_name,
					
					t6.id as user_group_id,
					t6.alias as user_group_alias,
					t6.title as user_group_title,
					
					t7.id as parent_category_id,
					t7.alias as parent_category_alias,
					t7.title as parent_category_title,
					
					t8.username as publish_username,
					t8.name as publish_user_name
					
				',
				'tables_to_join' => array(
					
					array( 'tb_articles_categories t2', 't1.category_id = t2.id', 'left' ),
					array( 'tb_users t3', 't1.created_by_id = t3.id', 'left' ),
					array( 'tb_users t4', 't1.modified_by_id = t4.id', 'left' ),
					array( 'tb_users t5', 't1.access_id = t5.id', 'left' ),
					array( 'tb_users_groups t6', 't1.access_id = t6.id', 'left' ),
					array( 'tb_articles_categories t7', 't2.parent = t7.id', 'left' ),
					array( 'tb_users t8', 't1.publish_user_id = t8.id', 'left' ),

				),
				'search_columns' => '`t1`.`title`, `t2`.`title`, `t3`.`name`, `t4`.`name`, `t5`.`name`, `t8`.`name`, `t1`.`introtext`, `t1`.`fulltext`',

			);

			if ( $where_conditions ) {

				$dsp[ 'where_conditions' ][] = $where_conditions;

			}

			$default_condition = array();
			$default_condition_array = array();

			// -------------------------------------------------
			// Preserving original searh config ----------------

			$search_config = $this->search->config();

			// Preserving original searh config ----------------
			// -------------------------------------------------

			// -------------------------------------------------
			// Order by ----------------------------------------
			
			if ( $order_by ) {

				// order by complement
				$comp_ob = '';

				switch ( $order_by ) {

					case 'id':

						$order_by = 't1.id';
						break;

					case 'image':

						$order_by = 'if( `t1`.`image` = \'\' or `t1`.`image` is null, 1, 0 ), `t1`.`image`';
						$comp_ob = ', t1.ordering ' . $order_by_direction . ', t1.title ' . $order_by_direction;
						break;

					case 'title':

						$order_by = 't1.title';
						break;

					case 'alias':

						$order_by = 't1.alias';
						break;

					case 'category_id':

						$order_by = 't1.category_id';
						break;

					case 'category_title':

						$order_by = 't7.title';
						$comp_ob = ', t2.title ' . $order_by_direction . ', t2.ordering ' . $order_by_direction . ', t1.ordering ' . $order_by_direction . ', t1.title ' . $order_by_direction;
						break;

					case 'ordering':

						$order_by = 't1.ordering';
						break;

					case 'random':

						$order_by = 't1.id';
						$random = TRUE;

						break;

					case 'status':

						$order_by = 't1.status';
						break;

					case 'access_type':

						$order_by = 't1.access_type';
						break;

					case 'created_by_id':

						$order_by = 't1.created_by_id';
						break;

					case 'created_by_name':

						$order_by = 't3.name';
						break;

					case 'created_date':

						$order_by = 't1.created_date';
						break;

					case 'modified_by_id':

						$order_by = 't1.modified_by_id';
						break;

					case 'modified_date':

						$order_by = 't1.modified_date';
						break;

					case 'created_by_alias':

						$order_by = 't1.created_by_alias';
						break;

					default:

						$order_by = 't1.id';
						$data[ 'order_by' ] = 'id';
						
						break;

				}
				
			}
			else {
				
				$order_by = 't1.id';
				
			}
			//print_r($this->search->config( 'order_by' ));
			//echo $order_by;
			$search_config[ 'order_by' ][ 'articles_search' ] = $order_by;
			$search_config[ 'order_by_direction' ][ 'articles_search' ] = $order_by_direction;
			$search_config[ 'random' ] = $random;
				
			// Order by ----------------------------------------
			// -------------------------------------------------

			// -------------------------------------------------
			// Article filtering -------------------------------

			$article_condition = NULL;
			$default_condition_array[ 'article_condition' ] = NULL;

			if ( is_numeric( $article_id ) AND $article_id > 0 ) {

				$article_condition = '`t1`.`id` = ' . $article_id;
				$default_condition_array[ 'article_condition' ] = $article_condition;

			}

			// Article filtering -------------------------------
			// -------------------------------------------------

			// -------------------------------------------------
			// Category filtering ------------------------------

			$category_condition = NULL;
			$default_condition_array[ 'category_condition' ] = NULL;

			if ( is_numeric( $category_id ) AND $category_id != -1 ) {

				if ( $category_id > 0 ) {

					$cat_regx = array();

					// including the current category id
					$cat_regx[] = '^' . $category_id . '$';

					if ( $recursive_categories ) {

						$cats = $this->articles->get_categories_tree( array( 'parent_id' => $category_id, 'parent_field' => 'parent', ) );

					}	
					
					if ( isset( $cats ) AND is_array( $cats ) AND count( $cats ) > 0 ) {

						foreach ( $cats as $key => $cat ) {

							$cat_regx[] = '^' . $cat[ 'id' ] . '$';

						}

						$cat_regx = join( '|', $cat_regx );

						$category_condition = '`t1`.`category_id` REGEXP "' . $cat_regx . '"';

					}
					else {

						$category_condition = '`t1`.`category_id` = "' . $category_id . '"';

					}

					$default_condition_array[ 'category_condition' ] = $category_condition;

				}
				else {

					$category_condition = '`t1`.`category_id` = "' . $category_id . '"';
					$default_condition_array[ 'category_condition' ] = $category_condition;

				}

			}

			// Category filtering ------------------------------
			// -------------------------------------------------

			// -------------------------------------------------
			// User filtering ----------------------------------

			$user_condition = NULL;
			$default_condition_array[ 'user_condition' ] = NULL;

			if ( is_numeric( $user_id ) AND $user_id > 0 ) {

				$user_condition = '`t1`.`created_by_id` = "' . $user_id . '"';
				$default_condition_array[ 'user_condition' ] = $user_condition;

			}

			// User filtering ----------------------------------
			// -------------------------------------------------

			// -------------------------------------------------
			// Access type filtering ---------------------------

			$access_type_condition = '`t1`.`access_type` = "public"';
			$default_condition_array[ 'access_type_condition' ] = $access_type_condition;

			// Access type filtering ---------------------------
			// -------------------------------------------------

			// -------------------------------------------------
			// Status filtering --------------------------------

			$status_condition = NULL;
			$default_condition_array[ 'status_condition' ] = NULL;

			// if status is not set, it means that there is no status filter
			if ( ! isset( $status ) ) {

				// if environment is 'site', then we only show published articles
				// if there logged user, we remove this array key, and re-build $default_condition string
				if ( $this->mcm->environment === 'site' ) {

					$status_condition = '`t1`.`status` = "1"';

				}

				$default_condition_array[ 'status_condition' ] = $status_condition;

			}
			else if ( is_numeric( $status ) ) {

				$status_condition = '`t1`.`status` = "' . $status . '"';
				$default_condition_array[ 'status_condition' ] = $status_condition;

			}

			// Status filtering --------------------------------
			// -------------------------------------------------

			$default_condition_array = array_filter( $default_condition_array, 'strlen' );

			// -------------------------------------------------
			// Status filtering ---------------------------------
			
			if ( $this->users->is_logged_in() ){
				
				// if status is not set, it means that there is no status filter
				if ( ! isset( $status ) ) {
					
					$_status_condition = array(
						
						'^' . 1 . '$'
						
					);
					
					if ( $this->users->check_privileges( 'articles_management_can_view_archived_articles' ) ) {

						$_status_condition[] = '^' . -1 . '$';

					}
					if ( $this->users->check_privileges( 'articles_management_can_view_unpublished_articles' ) ) {

						$_status_condition[] = '^' . 0 . '$';

					}

					if ( count( $_status_condition ) > 1 ) {

						$_status_condition = join( '|', $_status_condition );

						$_status_condition = '`t1`.`status` REGEXP "' . $_status_condition . '"';

					}
					else {

						$_status_condition = '`t1`.`status` = "1"';

					}

					$default_condition_array[ 'status_condition' ] = $_status_condition;

				}

				// can view all articles, we can override the default conditions array, removing access type and status filters
				if ( $this->users->check_privileges( 'articles_management_can_view_all_articles' ) ){

					// if there's no status filter, we can disable this for this privilege
					if ( ! isset( $status ) ) {

						unset( $default_condition_array[ 'status_condition' ]  );

					}

					unset( $default_condition_array[ 'access_type_condition' ]  );

				}
				else {

					// -------------------------------------------------
					// Can view public articles and your own -----------

					// if user id is set, it means that there is user filter
					if ( isset( $user_id ) ) {

						$default_condition_array[ 'user_condition' ] = '`t1`.`created_by_id` = "' . $user_id . '"';

					}
					else {

						$_access_type_condition[] = '`t1`.`created_by_id` = "' . $this->users->user_data[ 'id' ] . '"'; // yep, current user filter here

					}

					$_access_type_condition[ 'at_default' ] = $default_condition_array[ 'access_type_condition' ];
					$_access_type_condition[ 'at_users' ] = '( `t1`.`access_type` = \'users\' AND `t1`.`access_id` LIKE \'%>' . $this->users->user_data[ 'id' ] . '<%\' )';
					$_access_type_condition[ 'at_users_groups' ] = '( `t1`.`access_type` = \'users_groups\' AND `t1`.`access_id` LIKE \'%>' . $this->users->user_data[ 'group_id' ] . '<%\' )';

					// Can view public articles and your own -----------
					// -------------------------------------------------

					if ( ! $this->users->check_privileges( 'articles_management_can_view_only_your_own' ) ){

						// getting accessible user groups
						$accessible_users_groups = $this->articles->view_articles_get_accessible_users_groups();

						if ( check_var( $accessible_users_groups ) ) {

							$users_groups[] = '(.*)>' . $this->users->user_data[ 'group_id' ] . '<(.*)';

							foreach ( $accessible_users_groups as $key => $users_group ) {

								$users_groups[] = '(.*)>' . $users_group[ 'id' ] . '<(.*)';

							}

							$users_groups = join( '|', $users_groups );
							$_access_type_condition[ 'at_users_groups' ] = '( `t1`.`access_type` = \'users_groups\' AND `t1`.`access_id` REGEXP "' . $users_groups . '" )';
							$_access_type_condition[ 'users_groups' ] = '`t3`.`group_id` REGEXP "' . $users_groups . '"';

						}

					}

					$default_condition_array[ 'access_type_condition' ] = '( ' . join( ' OR ' , $_access_type_condition ) . ' )';

				}

			}

			// Login filtering ---------------------------------
			// -------------------------------------------------

			$default_condition = check_var( $default_condition_array ) ? '( ' . join( ' AND ' , $default_condition_array ) . ' )' : NULL;
			$dsp[ 'where_conditions' ][] = $default_condition;

			$this->search->config( $search_config );

			$full_search_results = $this->search->db_search( $dsp );
			$full_search_results = $full_search_results ? $full_search_results->result_array() : $full_search_results;

			if ( $full_search_results ) {

				$default_search_results = array();

				$time_start = microtime(true);

				foreach ( $full_search_results as $key => & $search_result ) {

					$this->articles->parse( $search_result, $filter_params, $menu_item_id );

					$line = & $default_search_results[];

					$line[ 'id' ] = isset( $search_result[ 'id' ] ) ? $search_result[ 'id' ] : '';
					$line[ 'title' ] = isset( $search_result[ 'title' ] ) ? $search_result[ 'title' ] : '';
					$line[ 'image' ] = isset( $search_result[ 'image' ] ) ? $search_result[ 'image' ] : '';
					$line[ 'content' ] = $search_result[ 'fulltext' ] ? word_limiter( strip_tags( html_entity_decode( $search_result[ 'fulltext' ] ) ), 100, '...' ) : ( $search_result[ 'introtext' ] ? word_limiter( strip_tags( html_entity_decode( $search_result[ 'introtext' ] ) ), 20, '...' ) : '' );

				}

				// apply the string highlight to default results
				if ( $this->search->config( 'terms' ) ) {

					$keys = array(

						'title',
						'alias',
						'introtext',
						'fulltext',
						'content',

					);
					$default_search_results = $this->search->array_highlight( $default_search_results, $keys );

				}

				// apply the string highlight to full results
				if ( $this->search->config( 'terms' ) ) {

					$keys = array(

						'title',
						'alias',
						'introtext',
						'fulltext',
						'content',

					);
					$full_search_results = $this->search->array_highlight( $full_search_results, $keys );

				}

				$result = array(

					'results' => $default_search_results,
					'full_results' => $full_search_results,

				);

				$this->search->append_results( 'articles_search', $result );

				$return = TRUE;

			}

		}
		else {

			log_message( 'debug', '[Plugins] Articles search plugin could not be executed! Search library object not initialized!' );

			$return = FALSE;

		}

		//parent::_set_performed( 'articles_search' );

		$this->articles->append_articles( $return );

		return $return;

	}

	public function get_params_spec(){

	}

}

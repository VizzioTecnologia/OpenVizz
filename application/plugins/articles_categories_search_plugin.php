<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Articles_categories_search_plugin extends Plugins_mdl{

	public function run( &$data, $params = NULL ){

		$return = FALSE;

		if ( is_object( $this->search ) ) {

			log_message( 'debug', '[Plugins] Articles categories search plugin initialized' );

			if ( ! $this->load->is_model_loaded( 'articles' ) ) {

				$this->load->model( 'articles_mdl', 'articles' );

			}

			// -------------------------------------------------
			// Parsing vars ------------------------------------

			$plugin_params = $this->search->config( 'plugins_params' );
			$plugin_params = isset( $plugin_params[ 'articles_categories_search' ] ) ? $plugin_params[ 'articles_categories_search' ] : FALSE;

			// -------------

			$where_conditions =									( isset( $plugin_params[ 'where_conditions' ] ) ) ? $plugin_params[ 'where_conditions' ] : NULL;

			// -------------

			$category_id =										( isset( $plugin_params[ 'category_id' ] ) AND is_numeric( $plugin_params[ 'category_id' ] ) ) ? $plugin_params[ 'category_id' ] : NULL;

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

			$recursive_categories =								( isset( $plugin_params[ 'recursive_categories' ] ) ) ? ( bool ) $plugin_params[ 'recursive_categories' ] : TRUE;

			// Parsing vars ------------------------------------
			// -------------------------------------------------

			// db search params
			$dsp = array(

				'plugin_name' => 'articles_categories_search',
				'table_name' => 'tb_articles_categories t1',
				'random' => $random,
				'select_columns' => '

					t1.*,
					t2.title as parent_title,
					t2.alias as parent_alias

				',
				'tables_to_join' => array(

					array( 'tb_articles_categories t2', 't1.parent = t2.id', 'left' ),

				),
				'search_columns' => 't1.title, t1.alias, t1.description',

			);

			if ( $where_conditions ) {

				$dsp[ 'where_conditions' ][] = $where_conditions;

			}

			$default_condition = array();
			$default_condition_array = array();

			// -------------------------------------------------
			// Category filtering ------------------------------

			$category_condition = NULL;
			$default_condition_array[ 'category_condition' ] = NULL;

			if ( is_numeric( $category_id ) AND $category_id > 0 ) {

				$category_condition = '`t1`.`id` = ' . $category_id;
				$default_condition_array[ 'category_condition' ] = $category_condition;

			}

			// Category filtering ------------------------------
			// -------------------------------------------------

			// -------------------------------------------------
			// Status filtering --------------------------------

			$status_condition = NULL;
			$default_condition_array[ 'status_condition' ] = NULL;

			// if status is not set, it means that there is no status filter
			if ( ! isset( $status ) ) {

				// if environment is 'site', then we only show published categories
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

			$default_condition = check_var( $default_condition_array ) ? '( ' . join( ' AND ' , $default_condition_array ) . ' )' : NULL;
			$dsp[ 'where_conditions' ][] = $default_condition;

			$full_search_results = $this->search->db_search( $dsp );
			$full_search_results = $full_search_results ? $full_search_results->result_array() : $full_search_results;

			// -------------------------------------------------
			// Checking the get categories tree function use ---

			/*
			 * We can't get a categories tree when using pagination
			 * so, let's check this
			 */

			$get_tree = FALSE;

			if ( $recursive_categories ) {

				$order_by = $this->search->config( 'order_by' );
				if (

					isset( $order_by[ 'articles_categories_search' ] ) AND
					(

						trim( $order_by[ 'articles_categories_search' ] ) === 't1.parent DESC, t1.ordering DESC, t1.title DESC' OR
						trim( $order_by[ 'articles_categories_search' ] ) === 't1.parent ASC, t1.ordering ASC, t1.title ASC'

					) AND // if current order_by is ordering
					(

						! $this->search->config( 'ipp' ) OR
						! ( $this->search->count_all_results( 'articles_categories_search' ) > $this->search->config( 'ipp' ) )

					)

				) {

					$get_tree = TRUE;

				}

			}

			// Checking the get categories tree function use ---
			// -------------------------------------------------

			if ( $full_search_results ) {

				if ( $get_tree ) {

					$_tmp = $this->articles->get_categories_tree( array( 'array' => $full_search_results, ) );

					$full_search_results = check_var( $_tmp ) ? $_tmp : $full_search_results;

				}

				$default_search_results = array();

				foreach ( $full_search_results as $key => & $search_result ) {

					$this->articles->parse_category( $search_result );

					$line = & $default_search_results[];

					$line[ 'id' ] = isset( $search_result[ 'id' ] ) ? $search_result[ 'id' ] : '';
					$line[ 'title' ] = isset( $search_result[ 'title' ] ) ? $search_result[ 'title' ] : '';
					$line[ 'image' ] = isset( $search_result[ 'image' ] ) ? $search_result[ 'image' ] : '';
					$line[ 'content' ] = $search_result[ 'description' ] ? word_limiter( strip_tags( html_entity_decode( $search_result[ 'description' ] ) ), 20, '...' ) : '';

				}

				// apply the string highlight to default results
				if ( $this->search->config( 'terms' ) ) {

					$keys = array(

						'title',
						'alias',
						'description',
						'content',

					);
					$default_search_results = $this->search->array_highlight( $default_search_results, $keys );

				}

				// apply the string highlight to full results
				if ( $this->search->config( 'terms' ) ) {

					$keys = array(

						'title',
						'alias',
						'description',
						'content',

					);
					$full_search_results = $this->search->array_highlight( $full_search_results, $keys );

				}

				$result = array(

					'results' => $default_search_results,
					'full_results' => $full_search_results,

				);

				$this->search->append_results( 'articles_categories_search', $result );

				$return = TRUE;

			}

		}
		else {

			log_message( 'debug', '[Plugins] Articles categories search plugin could not be executed! Search library object not initialized!' );

			$return = FALSE;

		}

		//parent::_set_performed( 'articles_categories_search' );

		return $return;

	}

	public function get_params_spec(){

	}

}

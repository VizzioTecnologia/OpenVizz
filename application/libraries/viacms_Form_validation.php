<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * CodeIgniter
 *
 * An open source application development framework for PHP 5.1.6 or newer
 *
 * @package		CodeIgniter
 * @author		ExpressionEngine Dev Team
 * @copyright	Copyright (c) 2008 - 2014, EllisLab, Inc.
 * @license		http://codeigniter.com/user_guide/license.html
 * @link		http://codeigniter.com
 * @since		Version 1.0
 * @filesource
 */

// ------------------------------------------------------------------------

/**
 * Form Validation Class
 *
 * @package		CodeIgniter
 * @subpackage	Libraries
 * @category	Validation
 * @author		ExpressionEngine Dev Team
 * @link		http://codeigniter.com/user_guide/libraries/form_validation.html
 */
class Viacms_Form_validation extends CI_Form_validation {
	
	protected $_custom_messages = array();
	
	private $_custom_field_errors = array();
	protected $ci;
	
	// --------------------------------------------------------------------
	
	public function __construct(){
		
		parent::__construct();
		
		$this->ci =& get_instance();
		
	}
	
	// --------------------------------------------------------------------
	
	public function set_custom_message($field = '', $rule = '', $message = '' ){
		if(is_array($field)){
			foreach($field as $id){
				$this->_custom_messages[$id][$rule] =  $message;
			}
			return;
		}
		$this->_custom_messages[$field][$rule] =  $message;
		return;
	}
	protected function _execute($row, $rules, $postdata = NULL, $cycles = 0) {
		
		// If the $_POST data is an array we will run a recursive call
		if (is_array($postdata))
		{
			foreach ($postdata as $key => $val)
			{
				$this->_execute($row, $rules, $val, $cycles);
				$cycles++;
			}

			return;
		}

		// --------------------------------------------------------------------

		// If the field is blank, but NOT required, no further tests are necessary
		$callback = FALSE;
		if ( ! in_array('required', $rules) AND is_null($postdata))
		{
			// Before we bail out, does the rule contain a callback?
			if (preg_match("/(callback_\w+(\[.*?\])?)/", implode(' ', $rules), $match))
			{
				$callback = TRUE;
				$rules = (array('1' => $match[1]));
			}
			else
			{
				return;
			}
		}

		// --------------------------------------------------------------------

		// Isset Test. Typically this rule will only apply to checkboxes.
		if (is_null($postdata) AND $callback == FALSE)
		{
			if (in_array('isset', $rules, TRUE) OR in_array('required', $rules))
			{
				// Set the message type
				$type = (in_array('required', $rules)) ? 'required' : 'isset';
				if(array_key_exists($row['field'],$this->_custom_messages) && 
					array_key_exists($type,$this->_custom_messages[$row['field']])){
						$line = $this->_custom_messages[$row['field']][$type];
				}else{
					if ( ! isset($this->_error_messages[$type]))
					{
						if (FALSE === ($line = $this->CI->lang->line($type)))
						{
							$line = 'The field was not set';
						}
					}
					else
					{
						$line = $this->_error_messages[$type];
					}
				}

				// Build the error message
				$message = sprintf($line, $this->_translate_fieldname($row['label']));

				// Save the error message
				$this->_field_data[$row['field']]['error'] = $message;

				if ( ! isset($this->_error_array[$row['field']]))
				{
					$this->_error_array[$row['field']] = $message;
				}
			}

			return;
		}

		// --------------------------------------------------------------------

		// Cycle through each rule and run it
		foreach ($rules As $rule)
		{
			$_in_array = FALSE;

			// We set the $postdata variable with the current data in our master array so that
			// each cycle of the loop is dealing with the processed data from the last cycle
			if ($row['is_array'] == TRUE AND is_array($this->_field_data[$row['field']]['postdata']))
			{
				// We shouldn't need this safety, but just in case there isn't an array index
				// associated with this cycle we'll bail out
				if ( ! isset($this->_field_data[$row['field']]['postdata'][$cycles]))
				{
					continue;
				}

				$postdata = $this->_field_data[$row['field']]['postdata'][$cycles];
				$_in_array = TRUE;
			}
			else
			{
				$postdata = $this->_field_data[$row['field']]['postdata'];
			}

			// --------------------------------------------------------------------

			// Is the rule a callback?
			$callback = FALSE;
			if (substr($rule, 0, 9) == 'callback_')
			{
				$rule = substr($rule, 9);
				$callback = TRUE;
			}

			// Strip the parameter (if exists) from the rule
			// Rules can contain a parameter: max_length[5]
			$param = FALSE;
			if (preg_match("/(.*?)\[(.*)\]/", $rule, $match))
			{
				$rule   = $match[1];
				$param  = $match[2];
			}

			// Call the function that corresponds to the rule
			if ($callback === TRUE)
			{
				if ( ! method_exists($this->CI, $rule))
				{
					continue;
				}

				// Run the function and grab the result
				$result = $this->CI->$rule($postdata, $param);

				// Re-assign the result to the master data array
				if ($_in_array == TRUE)
				{
					$this->_field_data[$row['field']]['postdata'][$cycles] = (is_bool($result)) ? $postdata : $result;
				}
				else
				{
					$this->_field_data[$row['field']]['postdata'] = (is_bool($result)) ? $postdata : $result;
				}

				// If the field isn't required and we just processed a callback we'll move on...
				if ( ! in_array('required', $rules, TRUE) AND $result !== FALSE)
				{
					continue;
				}
			}
			else
			{
				if ( ! method_exists($this, $rule))
				{
					// If our own wrapper function doesn't exist we see if a native PHP function does.
					// Users can use any native PHP function call that has one param.
					if (function_exists($rule))
					{
						$result = $rule($postdata);

						if ($_in_array == TRUE)
						{
							$this->_field_data[$row['field']]['postdata'][$cycles] = (is_bool($result)) ? $postdata : $result;
						}
						else
						{
							$this->_field_data[$row['field']]['postdata'] = (is_bool($result)) ? $postdata : $result;
						}
					}
					else
					{
						log_message('debug', "Unable to find validation rule: ".$rule);
					}

					continue;
				}

				$result = $this->$rule($postdata, $param);

				if ($_in_array == TRUE)
				{
					$this->_field_data[$row['field']]['postdata'][$cycles] = (is_bool($result)) ? $postdata : $result;
				}
				else
				{
					$this->_field_data[$row['field']]['postdata'] = (is_bool($result)) ? $postdata : $result;
				}
			}

			// Did the rule test negatively?  If so, grab the error.
			if ($result === FALSE)
			{
				if(array_key_exists($row['field'],$this->_custom_messages) && 
					array_key_exists($rule,$this->_custom_messages[$row['field']])){
						$line = $this->_custom_messages[$row['field']][$rule];
				}else{
					if ( ! isset($this->_error_messages[$rule]))
					{
						if (FALSE === ($line = $this->CI->lang->line($rule)))
						{
							$line = 'Unable to access an error message corresponding to your field name.';
						}
					}
					else
					{
						$line = $this->_error_messages[$rule];
					}
				}

				// Is the parameter we are inserting into the error message the name
				// of another field?  If so we need to grab its "field label"
				if (isset($this->_field_data[$param]) AND isset($this->_field_data[$param]['label']))
				{
					$param = $this->_translate_fieldname($this->_field_data[$param]['label']);
				}

				// Build the error message
				$message = sprintf($line, $this->_translate_fieldname($row['label']), $param);

				// Save the error message
				$this->_field_data[$row['field']]['error'] = $message;

				if ( ! isset($this->_error_array[$row['field']]))
				{
					$this->_error_array[$row['field']] = $message;
				}

				return;
			}
		}
	}
	
	// --------------------------------------------------------------------
	
	function captcha( $str ) {
		
		// First, delete old captchas
		$expiration = number_format( time() - 300, 6, '.', '' ); // five minutes limit
		$this->CI->db->query( "DELETE FROM tb_captcha WHERE time < " . $expiration );
		
		// Then see if a captcha exists:
		$sql = "SELECT * FROM tb_captcha WHERE word = ? AND ip_address = ? AND time > ?";
		$binds = array( $str, $this->CI->input->ip_address(), $expiration );
		$query = $this->CI->db->query( $sql, $binds );
		$row = $query->row_array();
		
		if ( count( $row ) == 0 ) {
			
			$this->CI->form_validation->set_message( 'captcha', lang( 'validation_rule_captcha_is_invalid' ) );
			
			return FALSE;
			
		}
		
		$this->CI->db->query( "DELETE FROM tb_captcha WHERE time = '" . $row[ 'time' ] . "'" );
		
		return TRUE;
		
    }
	
	// --------------------------------------------------------------------
	
	function uppercase( $str ) {
		
		return strtoupper( $str );
		
    }
	
	// --------------------------------------------------------------------
	
	function lowercase( $str ) {
		
		return strtolower( $str );
		
    }
	
	// --------------------------------------------------------------------
	
	/**
	 * Constantes definidas para melhor legibilidade do código. O prefixo NN_ indica que
	 * seu uso está relacionado ao método público e estático normalizar_nome_ptbr().
	 */
	const NN_PONTO = '\.';
	const NN_PONTO_ESPACO = '. ';
	const NN_ESPACO = ' ';
	const NN_REGEX_MULTIPLOS_ESPACOS = '\s+';
	const NN_REGEX_NUMERO_ROMANO =
		'^M{0,4}(CM|CD|D?C{0,3})(XC|XL|L?X{0,3})(IX|IV|V?I{0,3})$';
	
	 /**
	 * Normaliza o nome próprio dado, aplicando a capitalização correta de acordo
	 * com as regras e exceções definidas no código.
	 * POR UMA DECISÃO DE PROJETO, FORAM UTILIZADAS FUNÇÕES MULTIBYTE (MB_) SEMPRE
	 * QUE POSSÍVEL, PARA GARANTIR SUA USABILIDADE EM STRINGS UNICODE.
	 * @param string $nome O nome a ser normalizado
	 * @return string O nome devidamente normalizado
	 */
	public function normalizar_nome_ptbr( $nome ) {
		
		/*
		 * A primeira tarefa da normalização é lidar com partes do nome que
		 * porventura estejam abreviadas,considerando-se para tanto a existência de
		 * pontos finais (p. ex. JOÃO A. DA SILVA, onde "A." é uma parte abreviada).
		 * Dado que mais à frente dividiremos o nome em partes tomando em
		 * consideração o caracter de espaço (" "), precisamos garantir que haja um
		 * espaço após o ponto. Fazemos isso substituindo todas as ocorrências do
		 * ponto por uma sequência de ponto e espaço.
		 */
		$nome = mb_ereg_replace(self::NN_PONTO, self::NN_PONTO_ESPACO, $nome);

		/*
		 * O procedimento anterior, ou mesmo a digitação errônea, podem ter
		 * introduzido espaços múltiplos entre as partes do nome, o que é totalmente
		 * indesejado. Para corrigir essa questão, utilizamos uma substituição
		 * baseada em expressão regular, a qual trocará todas as ocorrências de
		 * espaços múltiplos por espaços simples.
		 */
		$nome = mb_ereg_replace(self::NN_REGEX_MULTIPLOS_ESPACOS, self::NN_ESPACO,
			$nome);

		/*
		 * Isso feito, podemos fazer a capitalização "bruta", deixando cada parte do
		 * nome com a primeira letra maiúscula e as demais minúsculas. Assim,
		 * JOÃO DA SILVA => João Da Silva.
		 */
		$nome = mb_convert_case($nome, MB_CASE_TITLE, mb_detect_encoding($nome));

		/*
		 * Nesse ponto, dividimos o nome em partes, para trabalhar com cada uma
		 * delas separadamente.
		 */
		$partesNome = mb_split(self::NN_ESPACO, $nome);

		/*
		 * A seguir, são definidas as exceções à regra de capitalização. Como
		 * sabemos, alguns conectivos e preposições da língua portuguesa e de outras
		 * línguas jamais são utilizadas com a primeira letra maiúscula.
		 * Essa lista de exceções baseia-se na minha experiência pessoal, e pode ser
		 * adaptada, expandida ou mesmo reduzida conforme as necessidades de cada
		 * caso.
		 */
		$excecoes = array(
			'de', 'di', 'do', 'da', 'dos', 'das', 'dello', 'della',
			'dalla', 'dal', 'del', 'e', 'em', 'na', 'no', 'nas', 'nos', 'van', 'von',
			'y'
		);
		
		for($i = 0; $i < count($partesNome); ++$i) {
			
			/*
			 * Verificamos cada parte do nome contra a lista de exceções. Caso haja
			 * correspondência, a parte do nome em questão é convertida para letras
			 * minúsculas.
			 */
			foreach($excecoes as $excecao)
				if(mb_strtolower($partesNome[$i]) == mb_strtolower($excecao))
					$partesNome[$i] = $excecao;
			
			/*
			 * Uma situação rara em nomes de pessoas, mas bastante comum em nomes de
			 * logradouros, é a presença de numerais romanos, os quais, como é sabido,
			 * são utilizados em letras MAIÚSCULAS.
			 * No site
			 * http://htmlcoderhelper.com/how-do-you-match-only-valid-roman-numerals-with-a-regular-expression/,
			 * encontrei uma expressão regular para a identificação dos ditos
			 * numerais. Com isso, basta testar se há uma correspondência e, em caso
			 * positivo, passar a parte do nome para MAIÚSCULAS. Assim, o que antes
			 * era "Av. Papa João Xxiii" passa para "Av. Papa João XXIII".
			 */
			if(mb_ereg_match(self::NN_REGEX_NUMERO_ROMANO,
				mb_strtoupper($partesNome[$i])))
				$partesNome[$i] = mb_strtoupper($partesNome[$i]);
		}
		
		/*
		 * Finalmente, basta juntar novamente todas as partes do nome, colocando um
		 * espaço entre elas.
		 */
		
		$_return = implode( self::NN_ESPACO, $partesNome );
		
		return $_return != '' ? $_return : FALSE;
		
	}
	
	// --------------------------------------------------------------------
	
	function _domain_exists( $str, $record = 'ANY' ){
		
		return checkdnsrr( $str, $record );
		
	}
	
	function _valid_domain( $str ){
		
		if ( $str ) {
			
			if ( ( preg_match("/^([a-z\d](-*[a-z\d])*)(\.([a-z\d](-*[a-z\d])*))*$/i", $str) //valid chars check
				&& preg_match("/^.{1,253}$/", $str) //overall length check
				&& preg_match("/^[^\.]{1,63}(\.[^\.]{1,63})*$/", $str ) ) ) //length of each label )
			{
				
				return TRUE;
				
			}
			
			return FALSE;
			
		}
		
	}
	
	function valid_domain( $str ){
		
		if ( $str ) {
			
			if ( $this->_valid_domain( $str ) ) {
				
				return TRUE;
				
			}
			
			$this->CI->form_validation->set_message( 'valid_domain', lang( 'validation_rule_valid_domain_is_invalid' ) );
			return FALSE;
			
		}
		
	}
	
	function valid_domain_dns( $str ){
		
		if ( $str ) {
			
			if ( $this->_valid_domain( $str ) ) {
				
				if ( $this->_domain_exists( $str ) ) {
				
					return TRUE;
					
				}
				else {
					
					$this->CI->form_validation->set_message( 'valid_domain_dns', lang( 'validation_rule_valid_domain_dns_not_exists' ) );
					
					return FALSE;
					
				}
				
			}
			
			$this->CI->form_validation->set_message( 'valid_domain_dns', lang( 'validation_rule_valid_domain_dns_is_invalid' ) );
			return FALSE;
			
		}
		
	}
	
	function valid_email_dns( $str ){
		
		$this->ci->load->library( 'verify_email' );
		
		$email = $str;
		
		if ( $this->ci->verify_email->check( $email ) ) {
			
			return TRUE;
			
		} elseif ( $this->ci->verify_email->isValid( $email ) ) {
			
			$this->CI->form_validation->set_message( 'valid_email_dns', sprintf( lang( 'valid_email_dns_not_exists' ), $email ) );
			return FALSE;
			
		} else {
			
			$this->CI->form_validation->set_message( 'valid_email_dns', sprintf( lang( 'valid_email_dns_is_invalid' ), $email ) );
			return FALSE;
		    
		}
		
	}
	
	public function add_message($field, $message) {
		//this field was validated without error
		if(isset($this->_field_data[$field]) AND (!isset($this->_field_data[$field]['error']) OR !$this->_field_data[$field]['error']) )
			
			$this->_field_data[$field]['error'] = $message;
	}
	
	// --------------------------------------------------------------------

	
	// Substitui a função Matches padrão para suportar campos em formato de array
	public function matches($str, $field){
		
		$return = FALSE;
		
		$indexes = array();
		if (strpos($field, '[') !== FALSE AND preg_match_all('/\[(.*?)\]/', $field, $matches)){
			
			// Note: Due to a bug in current() that affects some versions
			// of PHP we can not pass function call directly into it
			$x = explode('[', $field);
			$indexes[] = current($x);
			
			for ($i = 0; $i < count($matches['0']); $i++)
			{
				if ($matches['1'][$i] != '')
				{
					$indexes[] = $matches['1'][$i];
				}
			}
			
			$is_array = TRUE;
		}
		
		if (!empty($indexes))
		{
			$it = new RecursiveArrayIterator($indexes);
			
			$search = $_POST;
			while ($it->valid() && $search !== FALSE)
			{
				if (isset($search[$it->current()]))
					$search = $search[$it->current()];
				else
					$search = FALSE;

				$it->next();
			}

			if ($search !== FALSE)
				$return = $search === $str;
		}
		else if (isset($_POST[$field]))
		{
			$return = $str === $_POST[$field];
		}
		
		return $return;
		
	}
	
	// Add a validation rule wich allow spaces no alphanumeric function
	public function alpha_dash_space( $str ){
		
		$this->CI->form_validation->set_message( 'alpha_dash_space', lang( 'validation_rule_alpha_dash_spaces' ) );
		
		return ( ! preg_match( '/^[a-z0-9 ._\-]+$/i', $str ) ) ? FALSE : TRUE;
		
	}

	

}
// END Form Validation Class

/* End of file Form_validation.php */
/* Location: ./system/libraries/Form_validation.php */

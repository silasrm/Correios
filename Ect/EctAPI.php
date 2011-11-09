<?php
/**
 * @brief	Biblioteca Correios
 * @details	Classes e interfaces para integração com a API do Correios
 * @package Correios
 * @subpackage Correios_Ect
 */

require_once 'Correios/Http/HTTPConnection.php';
require_once 'Correios/Http/HTTPCookieManager.php';

/**
 * @brief	Interface para definição de uma API do Correios
 * @author	João Batista Neto <neto.joaobatista@imasters.com.br>
 */
abstract class Correios_Ect_EctAPI {
	/**
	 * @var	ECT
	 */
	protected $ect;

	/**
	 * @var	HTTPConnection
	 */
	protected $httpConnection;

	/**
	 * @brief	Constroi o objeto que representa uma API do Correios
	 * @param	ECT $ect
	 */
	public function __construct( Correios_Ect_ECT $ect ) {
		$this->ect = $ect;
		$this->httpConnection = $ect->getHTTPConnection();
		$this->httpConnection->initialize( $this->getTargetHost() );
	}

	/**
	 * @brief	Recupera o host onde serão feitas as requisições
	 * @return	string
	 */
	public abstract function getTargetHost();
}
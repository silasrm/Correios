<?php
/**
 * @brief	Biblioteca Correios
 * @details	Classes e interfaces para integração com a API do Correios
 * @package Correios
 * @subpackage Correios_Ect
 */

require_once 'Correios/Http/HTTPConnection.php';
require_once 'Correios/Http/HTTPCookieManager.php';
require_once 'Correios/Ect/PrecosEPrazos.php';

/**
 * @brief	Interface para APIs dos Correios (ECT)
 * @author	Silas Ribas <silasrm@gmail.com>
 * @author	João Batista Neto <neto.joaobatista@imasters.com.br>
 */
class Correios_Ect
{
	/**
	 * @access private
	 * @var	Correios_Http_HTTPConnection
	 */
	private $httpConnection;

	/**
	 * @brief	Conexão HTTP
	 * @details	Recupera um objeto de conexão HTTP para ser utilizado
	 * nas chamadas às operações da API.
	 * @return	HTTPConnection
	 */
	public function getHTTPConnection() {
		$httpConnection = new Correios_Http_HTTPConnection();
		$httpConnection->setCookieManager( new Correios_Http_HTTPCookieManager() );

		return $httpConnection;
	}

	/**
	 * @brief	Objeto de integração para consultas de preços e prazos
	 * @return	Correios_Ect_PrecosEPrazos
	 */
	public function getPrecosEPrazos() {
		return new Correios_Ect_PrecosEPrazos( $this );
	}
}
<?php
/**
 * @brief	Protocolo HTTP
 * @details	Classes e interfaces relacionadas com o protocolo HTTP
 * @package Correios
 * @subpackage Correios_Http
 */

require_once 'Correios/Http/HTTPRequest.php';

/**
 * @author	João Batista Neto <neto.joaobatista@imasters.com.br>
 * @brief	Interface para definição de um autenticador HTTP.
 */
interface Correios_Http_HTTPAuthenticator {
	/**
	 * @brief	Autentica uma requisição HTTP.
	 * @param	HTTPRequest $httpRequest
	 */
	public function authenticate( Correios_Http_HTTPRequest $httpRequest );
}
<?php
/**
 * @brief	Biblioteca Correios para cálculo de preços e prazos
 * @details	Classes e interfaces para integração com a API do Correios
 * @package Correios
 * @subpackage Correios_Ect
 * @subpackage Correios_Ect_PrecosEPrazos
 */

/**
 * @brief	Informações sobre preço e prazo cobrados para um serviço do Correios
 * @author	Silas Ribas <silasrm@gmail.com>
 * @author	João Batista Neto <neto.joaobatista@imasters.com.br>
 */
class Correios_Ect_PrecosEPrazos_Servico
{
	/**
	 * Código do serviço
	 * @access public
	 * @var integer
	 */
	public $codigo;

	/**
	 * Valor do serviço adicional de mão própria
	 * @access public
	 * @var float
	 */
	public $valorMaoPropria;

	/**
	 * Valor do serviço adicional de aviso de recebimento
	 * @access public
	 * @var float
	 */
	public $valorAvisoRecebimento;

	/**
	 * Valor do serviço adicional de valor declarado
	 * @access public
	 * @var float
	 */
	public $valorValorDeclarado;

	/**
	 * Prazo de entrega para a encomenda
	 * @access public
	 * @var integer
	 */
	public $prazoEntrega;

	/**
	 * Informa se a localização possui entrega domiciliar
	 * @access public
	 * @var boolean
	 */
	public $entregaDomiciliar;

	/**
	 * Informa se existe entrega aos sábados
	 * @access public
	 * @var boolean
	 */
	public $entregaSabado;

	/**
	 * Guarda o nome do tipo
	 * @access public
	 * @var boolean
	 */
	public $tipo;

	/**
	 * Código do erro para o serviço, se não tiver ocorrido nenhum erro esse valor será 0
	 * @access public
	 * @var integer
	 */
	public $erro = 0;

	/**
	 * Mensagem de erro para o serviço, se não tiver ocorrido nenhum erro esse valor será NULL
	 * @access public
	 * @var string
	 */
	public $msgErro;

	/**
	 * Lista de tipos de servicos
	 * @access protected
	 * @var array
	 */
	protected $_tipos	= array(
		'E_SEDEX' => 'E-Sedex',
		'MALOTE' => 'Malote',
		'NORMAL' => 'Encomenda Normal',
		'PAC' => 'PAC',
		'PAC_CONTRATO' => 'PAC Contrato',
		'SEDEX' => 'Sedex',
		'SEDEX_A_COBRAR' => 'Sedex a cobrar',
		'SEDEX_10' => 'Sedex 10',
		'SEDEX_HOJE' => 'Sedex Hoje',
		'SEDEX_CONTRATO_1' => 'Sedex Contrato 1',
		'SEDEX_CONTRATO_2' => 'Sedex Contrato 2',
		'SEDEX_CONTRATO_3' => 'Sedex Contrato 3'
	  	);

  	/**
	 * Lista de constantes da interface Correios_Ect_PrecosEPrazos_ECTServicos
	 * @access protected
	 * @var array
	 */
	protected $_constants = array();

	/**
	 * Retorna o nome do serviço informado
	 *
	 * @param	string $tipo
	 * @return string|InvalidArgumentException Nome do serviço ou InvalidArgumentException quando for um serviço inválido.
	 */
  	public function getNome($tipo)
  	{
  		if (!in_array($tipo, $this->_getConstants())) {
  			throw new InvalidArgumentException('Tipo de serviço desconhecido.');
  		}

  		foreach ($this->_constants as $key => $value) {
  			if ($value == $tipo) {
  				return $this->_tipos[$key];
  			}
  		}

		throw new InvalidArgumentException('Tipo de serviço desconhecido.');
	}

	/**
	 * Retorna a lista de constantes da interface de serviços
	 *
	 * @return array
	 */
	protected function _getConstants()
	{
		if (count($this->_constants) == 0) {
			$reflection = new ReflectionClass('Correios_Ect_PrecosEPrazos_Servicos');
			$this->_constants = $reflection->getConstants();
		}

		return $this->_constants;
	}

	/**
	 * Retorna o tipo a partir do código
	 *
	 * @return array
	 */
	public function getTipoByCodigo($codigo)
	{
		$constantes = $this->_getConstants();

		$retorno = array();
		foreach($constantes as $key => $value)
		{
			if($value == $codigo)
			{
				$retorno['tipo'] = $key;
				$retorno['codigo'] = $codigo;
				$retorno['texto'] = $this->_tipos[$key];
			}
		}

		return $retorno;
	}
}
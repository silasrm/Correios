<?php
/**
 * @brief	Biblioteca Correios para cálculo de preços e prazos
 * @details	Classes e interfaces para integração com a API do Correios
 * @package Correios
 * @subpackage Correios_Ect
 * @subpackage Correios_Ect_PrecosEPrazos
 */

require_once 'Correios/Ect/PrecosEPrazos/Formatos.php';
require_once 'Correios/Ect/PrecosEPrazos/Servicos.php';
require_once 'Correios/Ect/Abstract.php';
require_once 'Correios/Ect/PrecosEPrazos/Servico.php';

/**
 * @brief	Cálculo de preços e prazos de entrega de encomendas
 * @author	Silas Ribas <silasrm@gmail.com>
 * @author	João Batista Neto <neto.joaobatista@imasters.com.br>
 */
class Correios_Ect_PrecosEPrazos extends Correios_Ect_Abstract {
	/**
	 * @access private
	 * @var string
	 */
	private $nCdEmpresa;

	/**
	 * @access private
	 * @var integer
	 */
	private $nCdFormato;

	/**
	 * @access private
	 * @var string
	 */
	private $nCdServico;

	/**
	 * @access private
	 * @var float
	 */
	private $nVlAltura;

	/**
	 * @access private
	 * @var float
	 */
	private $nVlComprimento;

	/**
	 * @access private
	 * @var float
	 */
	private $nVlDiametro;

	/**
	 * @access private
	 * @var float
	 */
	private $nVlLargura;

	/**
	 * @access private
	 * @var float
	 */
	private $nVlPeso;

	/**
	 * @access private
	 * @var float
	 */
	private $nVlValorDeclarado;

	/**
	 * @access private
	 * @var string
	 */
	private $sCdAvisoRecebimento;

	/**
	 * @access private
	 * @var string
	 */
	private $sCdMaoPropria;

	/**
	 * @access private
	 * @var string
	 */
	private $sCepOrigem;

	/**
	 * @access private
	 * @var string
	 */
	private $sCepDestino;

	/**
	 * @access private
	 * @var string
	 */
	private $sDsSenha;

	public function __construct( Correios_Ect $ect )
	{
		parent::__construct( $ect );

		$this->setNCdEmpresa( '' );
		$this->setNCdFormato( Correios_Ect_PrecosEPrazos_Formatos::FORMATO_CAIXA_PACOTE );
		$this->setNVlAltura( 0 );
		$this->setNVlComprimento( 0 );
		$this->setNVlDiametro( 0 );
		$this->setNVlLargura( 0 );
		$this->setNVlPeso( 0 );
		$this->setNVlValorDeclarado( 0 );
		$this->setSCdAvisoRecebimento( 'N' );
		$this->setSCdMaoPropria( 'N' );
		$this->setSDsSenha( '' );
	}

	/**
	 * @brief	Faz a chamada ao serviço de cálculo de preços e prazos.
	 * @return	Iterator lista de preços e prazos dos serviços.
	 */
	public function call()
	{
		$this->httpConnection->setParam( 'StrRetorno' , 'xml' );
		$xml = $this->httpConnection->execute( '/calculador/CalcPrecoPrazo.aspx' )->getContent();

		$dom = new DOMDocument();
		$dom->loadXML( $xml );

		$xpath = new DOMXPath( $dom );
		$servicos = new ArrayIterator();

		foreach ( $xpath->query( './/cServico' ) as $cServico ) {
			$ectServico = new Correios_Ect_PrecosEPrazos_Servico();

			foreach ( $xpath->query( './*' , $cServico ) as $node ){
				$name	= $node->nodeName;
				$value	= $node->nodeValue;

				switch ( $name ){
					case 'codigo':
					case 'erro':
					case 'prazoEntrega':
						 $value = (int) $value;
						 break;
					case 'valor':
					case 'valorMaoPropria':
					case 'valorAvisoRecebimento':
					case 'valorValorDeclarado':
						$value = (float) str_replace( ',' , '.' , $value ); break;
						break;
				}
				
				$propName = lcfirst($name);
				$ectServico->$propName = $value;
			}
			
			$ectServico->tipo = $this->getServicos()->getNome($ectServico->codigo);

			$servicos->append( $ectServico );
		}

		return $servicos;
	}

	/**
	 * @brief	Recupera o código administrativo junto à ECT.
	 * @details	O código está disponível no corpo do contrato
	 * 			firmado com os Correios.
	 * @return	string
	 */
	public function getNCdEmpresa()
	{
		return $this->nCdEmpresa;
	}

	/**
	 * @brief	Recupera o formato da encomenda (incluindo embalagem)
	 * @return	integer
	 */
	public function getNCdFormato()
	{
		return $this->nCdFormato;
	}

	/**
	 * @brief	Recupera o código do serviço
	 * @return	string
	 */
	public function getNCdServico()
	{
		return $this->nCdServico;
	}

	/**
	 * @brief	Recupera a altura da encomenda (incluindo embalagem), em centímetros.
	 * @return	float
	 */
	public function getNVlAltura()
	{
		return $this->nVlAltura;
	}

	/**
	 * @brief	Recupera o comprimento da encomenda (incluindo embalagem), em centímetros.
	 * @return	float
	 */
	public function getNVlComprimento()
	{
		return $this->nVlComprimento;
	}

	/**
	 * @brief	Recupera o diâmetro da encomenda (incluindo embalagem), em centímetros.
	 * @return	float
	 */
	public function getNVlDiametro()
	{
		return $this->nVlDiametro;
	}

	/**
	 * @brief	Recupera a largura da encomenda (incluindo embalagem), em centímetros.
	 * @return	float
	 */
	public function getNVlLargura()
	{
		return $this->nVlLargura;
	}

	/**
	 * @brief	Recupera o peso da encomenda, incluindo sua embalagem.
	 * @details	O peso deve ser informado em quilogramas.
	 * @return	float
	 */
	public function getNVlPeso()
	{
		return $this->nVlPeso;
	}

	/**
	 * @brief	Recupera o valor declarado
	 * @details	Indica se a encomenda será entregue com o serviço adicional
	 * 			valor declarado. Neste campo deve ser apresentado o valor
	 * 			declarado desejado, em Reais.
	 * @return	float
	 */
	public function getNVlValorDeclarado()
	{
		return $this->nVlValorDeclarado;
	}

	/**
	 * @brief	Serviço de aviso de recebimento
	 * @details	Indica se a encomenda será entregue com o serviço
	 * 			adicional aviso de recebimento.
	 * 			Valores possíveis: S ou N (S – Sim, N – Não)
	 * @return	string
	 */
	public function getSCdAvisoRecebimento()
	{
		return $this->sCdAvisoRecebimento;
	}

	/**
	 * @brief	Serviço mão própria.
	 * @details	Indica se a encomenda será entregue com o serviço
	 * 			adicional mão própria.
	 * 			Valores possíveis: S ou N (S – Sim, N – Não)
	 * @return	string
	 */
	public function getSCdMaoPropria()
	{
		return $this->sCdMaoPropria;
	}

	/**
	 * @brief	CEP de Origem sem hífen.Exemplo: 05311900
	 * @return	string
	 */
	public function getSCepOrigem()
	{
		return $this->sCepOrigem;
	}

	/**
	 * @brief	CEP de Destino sem hífen.Exemplo: 05311900
	 * @return	string
	 */
	public function getSCepDestino()
	{
		return $this->sCepDestino;
	}

	/**
	 * @brief	Senha para acesso ao serviço.
	 * @details	Senha para acesso ao serviço, associada ao seu código
	 * 			administrativo; A senha inicial corresponde aos 8 primeiros
	 * 			dígitos do CNPJ informado no contrato.
	 * 			A qualquer momento, é possível alterar a senha no endereço
	 * 			{@link: http://www.corporativo.correios.com.br/encomendas/servicosonline/recuperaSenha}}
	 * @return	string
	 */
	public function getSDsSenha()
	{
		return $this->sDsSenha;
	}

	/**
	 * @see		EctAPI::getTargetHost()
	 * @return	string
	 */
	public function getTargetHost()
	{
		return 'ws.correios.com.br';
	}

	/**
	 * @brief	Define o código administrativo junto à ECT.
	 * @details	O código está disponível no corpo do contrato
	 * 			firmado com os Correios.
	 * @param	string $nCdEmpresa
	 * @return Correios_Ect_PrecosEPrazos
	 */
	public function setNCdEmpresa( $nCdEmpresa )
	{
		$this->nCdEmpresa = $nCdEmpresa;
		$this->httpConnection->setParam( 'nCdEmpresa' , $nCdEmpresa );

		return $this;
	}

	/**
	 * @brief	Define o formato da encomenda (incluindo embalagem)
	 * @param	integer $nCdFormato
	 * @return Correios_Ect_PrecosEPrazos
	 */
	public function setNCdFormato( $nCdFormato )
	{
		$this->nCdFormato = $nCdFormato;
		$this->httpConnection->setParam( 'nCdFormato' , $nCdFormato );

		return $this;
	}

	/**
	 * @brief	Define o código do serviço
	 * @param	string $nCdServico
	 * @return Correios_Ect_PrecosEPrazos
	 */
	public function setNCdServico( $nCdServico )
	{
		if (is_array($nCdServico)) {
			$nCdServico = implode(',', $nCdServico);
		}

		$this->nCdServico = $nCdServico;
		$this->httpConnection->setParam( 'nCdServico' , $nCdServico );

		return $this;
	}

	/**
	 * @brief	Define a altura da encomenda (incluindo embalagem), em centímetros.
	 * @param	float $nVlAltura
	 * @return Correios_Ect_PrecosEPrazos
	 */
	public function setNVlAltura( $nVlAltura )
	{
		$this->nVlAltura = $nVlAltura;
		$this->httpConnection->setParam( 'nVlAltura' , $nVlAltura );

		return $this;
	}

	/**
	 * @brief	Define o comprimento da encomenda (incluindo embalagem), em centímetros.
	 * @param	float $nVlComprimento
	 * @return Correios_Ect_PrecosEPrazos
	 */
	public function setNVlComprimento( $nVlComprimento )
	{
		$this->nVlComprimento = $nVlComprimento;
		$this->httpConnection->setParam( 'nVlComprimento' , $nVlComprimento );

		return $this;
	}

	/**
	 * @brief	Define o diâmetro da encomenda (incluindo embalagem), em centímetros.
	 * @param	float $nVlDiametro
	 * @return Correios_Ect_PrecosEPrazos
	 */
	public function setNVlDiametro( $nVlDiametro )
	{
		$this->nVlDiametro = $nVlDiametro;
		$this->httpConnection->setParam( 'nVlDiametro' , $nVlDiametro );

		return $this;
	}

	/**
	 * @brief	Define a largura da encomenda (incluindo embalagem), em centímetros.
	 * @param	float $nVlLargura
	 * @return Correios_Ect_PrecosEPrazos
	 */
	public function setNVlLargura( $nVlLargura )
	{
		$this->nVlLargura = $nVlLargura;
		$this->httpConnection->setParam( 'nVlLargura' , $nVlLargura );

		return $this;
	}

	/**
	 * @brief	Define o peso da encomenda, incluindo sua embalagem.
	 * @details	O peso deve ser informado em quilogramas.
	 * @param	float $nVlPeso
	 * @return Correios_Ect_PrecosEPrazos
	 */
	public function setNVlPeso( $nVlPeso )
	{
		$this->nVlPeso = $nVlPeso;
		$this->httpConnection->setParam( 'nVlPeso' , $nVlPeso );

		return $this;
	}

	/**
	 * @brief	Define o valor do serviço valor declarado.
	 * @details	Indica se a encomenda será entregue com o serviço adicional
	 * 			valor declarado. Neste campo deve ser apresentado o valor
	 * 			declarado desejado, em Reais.
	 * @param	float $nVlValorDeclarado
	 * @return Correios_Ect_PrecosEPrazos
	 */
	public function setNVlValorDeclarado( $nVlValorDeclarado )
	{
		$this->nVlValorDeclarado = $nVlValorDeclarado;
		$this->httpConnection->setParam( 'nVlValorDeclarado' , $nVlValorDeclarado );

		return $this;
	}

	/**
	 * @brief	Serviço de aviso de recebimento
	 * @details	Indica se a encomenda será entregue com o serviço
	 * 			adicional aviso de recebimento.
	 * 			Valores possíveis: S ou N (S – Sim, N – Não)
	 * @param	string $sCdAvisoRecebimento
	 * @return Correios_Ect_PrecosEPrazos
	 */
	public function setSCdAvisoRecebimento( $sCdAvisoRecebimento )
	{
		$this->sCdAvisoRecebimento = $sCdAvisoRecebimento;
		$this->httpConnection->setParam( 'sCdAvisoRecebimento' , $sCdAvisoRecebimento );

		return $this;
	}

	/**
	 * @brief	Serviço mão própria.
	 * @details	Indica se a encomenda será entregue com o serviço
	 * 			adicional mão própria.
	 * 			Valores possíveis: S ou N (S – Sim, N – Não)
	 * @param	string $sCdMaoPropria
	 * @return Correios_Ect_PrecosEPrazos
	 */
	public function setSCdMaoPropria( $sCdMaoPropria )
	{
		$this->sCdMaoPropria = $sCdMaoPropria;
		$this->httpConnection->setParam( 'sCdMaoPropria' , $sCdMaoPropria );

		return $this;
	}

	/**
	 * @brief	CEP de Origem sem hífen.Exemplo: 05311900
	 * @param	string $sCepOrigem
	 * @return Correios_Ect_PrecosEPrazos
	 */
	public function setSCepOrigem( $sCepOrigem )
	{
		$this->sCepOrigem = $sCepOrigem;
		$this->httpConnection->setParam( 'sCepOrigem' , $sCepOrigem );

		return $this;
	}

	/**
	 * @brief	CEP de Destino sem hífen.Exemplo: 05311900
	 * @param	string $sCepDestino
	 * @return Correios_Ect_PrecosEPrazos
	 */
	public function setSCepDestino( $sCepDestino )
	{
		$this->sCepDestino = $sCepDestino;
		$this->httpConnection->setParam( 'sCepDestino' , $sCepDestino );

		return $this;
	}

	/**
	 * @brief	Senha para acesso ao serviço.
	 * @details	Senha para acesso ao serviço, associada ao seu código
	 * 			administrativo; A senha inicial corresponde aos 8 primeiros
	 * 			dígitos do CNPJ informado no contrato.
	 * 			A qualquer momento, é possível alterar a senha no endereço
	 * 			{@link: http://www.corporativo.correios.com.br/encomendas/servicosonline/recuperaSenha}}
	 * @param	string $sDsSenha
	 * @return Correios_Ect_PrecosEPrazos
	 */
	public function setSDsSenha( $sDsSenha )
	{
		$this->sDsSenha = $sDsSenha;
		$this->httpConnection->setParam( 'sDsSenha' , $sDsSenha );

		return $this;
	}

	/**
	 * @brief	Objeto de serviços
	 * @return	Correios_Ect_PrecosEPrazos_Servico
	 */
	public function getServicos()
	{
		return new Correios_Ect_PrecosEPrazos_Servico();
	}
}
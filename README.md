Atenção
========================
* Reescrita, reorganização e implementação de funcionalidades no código do projeto Correios do iMastersDev encontrado aqui https://github.com/iMastersDev/correios

TODO
========================
> @todo Refatorar metodos e propriedades, nomes confusos OK

> @todo Mover a classe Correios_Ect_ECT para Correios_Ect OK

> @todo Refatorar método Correios_Ect::prdt para Correios_Ect::getPrecosEPrazos OK

> @todo Mover as classes Correios_Ect_Prdt* para Correios_Ect_PrecosEPrazos* OK

> @todo Aplicar Fluente Interface em Correios_Ect_PrecosEPrazos OK


Cálculo de Preço e Prazo
========================

A biblioteca PHP para o Cálculo de preços e prazos do Correios facilita no cálculo de fretes de 1 ou de diversos serviços do Correios ao mesmo tempo.
Com a biblioteca o desenvolvedor utiliza uma interface simples, setando alguns parâmetros como altura, largura, comprimento, peso e indica o tipo de serviço que deseja calcular.
Todo o processo de integração é feito nos bastidores e o preço do frete e tempo de entrega é devolvido ao desenvolvedor para poder utilizar em sua aplicação sem o menor esforço.
O desenvolvedor pode calcular, de uma única vez, o preço do frete para os diversos serviços oferecidos pelo Correios, eliminando várias chamadas ao serviço e diminuindo o tempo de espera do cliente.

Como Usar ?
-----------

> A biblioteca pode fazer o cálculo de 1 serviço:

	<?php
	// Adicione a pasta onde encontra-se o projeto no include_path
	
	$ect = new Correios_Ect();
	$precosEPrazos = $ect->getPrecosEPrazos()
							->setNVlAltura( 10 )
							->setNVlComprimento( 20 )
							->setNVlLargura( 20 )
							->setNCdFormato( Correios_Ect_PrecosEPrazos_Formatos::FORMATO_CAIXA_PACOTE )
							->setNCdServico( Correios_Ect_PrecosEPrazos_Servicos::PAC )
							->setSCepOrigem( '09641030' )
							->setSCepDestino( '27511300' )
							->setNVlPeso( 10 );

	foreach ( $precosEPrazos->call() as $servico ) {
		printf( "O preço do frete do correios para o serviço %s é R$ %.02f<br />" , $precosEPrazos->getServicos()->getNome($servico->Codigo) , $servico->Valor );
	}

> Ou de vários ao mesmo tempo, eliminando-se assim o tempo de espera do cliente:
	
	<?php
	// Adicione a pasta onde encontra-se o projeto no include_path

	$ect = new Correios_Ect();
    $precosEPrazos = $ect->getPrecosEPrazos()
                            ->setNVlAltura( 10 )
                            ->setNVlComprimento( 20 )
                            ->setNVlLargura( 20 )
                            ->setNCdFormato( Correios_Ect_PrecosEPrazos_Formatos::FORMATO_CAIXA_PACOTE )
                            ->setNCdServico( array(
                                Correios_Ect_PrecosEPrazos_Servicos::PAC,
                                Correios_Ect_PrecosEPrazos_Servicos::SEDEX
                            ) ) //calculando apenas PAC e SEDEX
                            ->setSCepOrigem( '09641030' )
                            ->setSCepDestino( '27511300' )
                            ->setNVlPeso( 10 );

    foreach ( $precosEPrazos->call() as $servico ) {
        printf( "O preço do frete do correios para o serviço %s é R$ %.02f<br />" , $precosEPrazos->getServicos()->getNome($servico->Codigo) , $servico->Valor );
    }
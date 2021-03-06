<?php

set_include_path( implode( PATH_SEPARATOR
                            , array( realpath('.' . '/../../' )
                                    , get_include_path() ) ) );

require_once 'Correios/Ect.php';

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
	printf( "O preço do frete do correios para o serviço %s é R$ %.02f<br />" , $servico->tipo , $servico->valor );
}
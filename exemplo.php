<?php

set_include_path( implode( PATH_SEPARATOR
                            , array( realpath('.' . '/../../library' )
                                    , get_include_path() ) ) );

require_once 'Ect/ECT.php';

$ect = new Correios_Ect_ECT();
$prdt = $ect->prdt();
$prdt->setNVlAltura( 10 );
$prdt->setNVlComprimento( 20 );
$prdt->setNVlLargura( 20 );
$prdt->setNCdFormato( Correios_Ect_Prdt_ECTFormatos::FORMATO_CAIXA_PACOTE );
$prdt->setNCdServico( Correios_Ect_Prdt_ECTServicos::PAC );
$prdt->setSCepOrigem( '09641030' );
$prdt->setSCepDestino( '27511300' );
$prdt->setNVlPeso( 10 );

foreach ( $prdt->call() as $servico ) {
	printf( "O preço do frete do correios para o serviço %s é R$ %.02f<br />" , $prdt->getServicos()->getNome($servico->Codigo) , $servico->Valor );
}
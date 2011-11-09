<?php
/**
 * @brief	Biblioteca Correios para cálculo de preços e prazos
 * @details	Classes e interfaces para integração com a API do Correios
 * @package Correios
 * @subpackage Correios_Ect
 * @subpackage Correios_Ect_PrecosEPrazos
 */

/**
 * @brief	Tipos de formato de encomenda
 * @author	Silas Ribas <silasrm@gmail.com>
 * @author	João Batista Neto <neto.joaobatista@imasters.com.br>
 */
interface Correios_Ect_PrecosEPrazos_Formatos
{
	const FORMATO_CAIXA_PACOTE	= 1;
	const FORMATO_ROLO_PRISMA	= 2;
}
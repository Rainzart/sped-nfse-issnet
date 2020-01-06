<?php

error_reporting(E_ALL);
ini_set('display_errors', 'On');
require_once '../bootstrap.php';

use NFePHP\Common\Certificate;
use NFePHP\NFSeIssNet\Tools;
use NFePHP\NFSeIssNet\Rps;
use NFePHP\NFSeIssNet\Common\Soap\SoapFake;
use NFePHP\NFSeIssNet\Common\FakePretty;

try {

    $config = [
        'cnpj'  => '11006269000100',
        'im'    => '812005',
        'cmun'  => '4104808', //ira determinar as urls e outros dados
        'razao' => 'Empresa Test Ltda',
        'tpamb' => 2 //1-producao, 2-homologacao
    ];

    $configJson = json_encode($config);

    $content = file_get_contents('expired_certificate.pfx');
    $password = 'associacao';
    $cert = Certificate::readPfx($content, $password);

    $soap = new SoapFake();
    $soap->disableCertValidation(true);

    $tools = new Tools($configJson, $cert);
    $tools->loadSoapClass($soap);

    $arps = [];

    $std = new \stdClass();
    $std->version = '1.00'; //indica qual JsonSchema USAR na validação
    $std->IdentificacaoRps = new \stdClass();
    $std->IdentificacaoRps->Numero = 215; 
    $std->IdentificacaoRps->Serie = '10'; 
    $std->IdentificacaoRps->Tipo = 1; //1 - RPS 2-Nota Fiscal Conjugada (Mista) 3-Cupom
    $std->DataEmissao = '2009-07-24T10:00:00';
    $std->NaturezaOperacao = 1; // 1 – Tributação no município
    // 2 - Tributação fora do município
    // 3 - Isenção
    // 4 - Imune
    // 5 – Exigibilidade suspensa por decisão judicial
    // 6 – Exigibilidade suspensa por procedimento administrativo

    $std->RegimeEspecialTributacao = 1;    // 1 – Microempresa municipal
    // 2 - Estimativa
    // 3 – Sociedade de profissionais
    // 4 – Cooperativa
    // 5 – MEI – Simples Nacional
    // 6 – ME EPP – Simples Nacional

    $std->OptanteSimplesNacional = 2; //1 - SIM 2 - Não
    $std->IncentivadorCultural = 2; //1 - SIM 2 - Não
    $std->Status = 1;  // 1 – Normal  2 – Cancelado

    $std->Tomador = new \stdClass();
    $std->Tomador->Cnpj = "99999999000191";
    $std->Tomador->Cpf = "12345678901";
    $std->Tomador->RazaoSocial = "Fulano de Tal";

    $std->Tomador->Endereco = new \stdClass();
    $std->Tomador->Endereco->Endereco = 'Rua das Rosas';
    $std->Tomador->Endereco->Numero = '111';
    $std->Tomador->Endereco->Complemento = 'Sobre Loja';
    $std->Tomador->Endereco->Bairro = 'Centro';
    $std->Tomador->Endereco->CodigoMunicipio = 3106200;
    $std->Tomador->Endereco->Uf = 'MG';
    $std->Tomador->Endereco->Cep = 30160010;

    $std->Servico = new \stdClass();
    $std->Servico->ItemListaServico = '12';
    $std->Servico->CodigoCnae = '6311900';
    $std->Servico->CodigoTributacaoMunicipio = '45217023';
    $std->Servico->Discriminacao = 'Teste de RPS - Discriminação com vários caracteres especiais:  " ! @ # $ % Ǩ * ( ) - _ = + | \ , ; : / ? � � ';
    $std->Servico->CodigoMunicipio = 999;

    $std->Servico->Valores = new \stdClass();
    $std->Servico->Valores->ValorServicos = 1100;
    //$std->Servico->Valores->ValorDeducoes = 10.00;
    $std->Servico->Valores->ValorPis = 10;
    $std->Servico->Valores->ValorCofins = 20;
    $std->Servico->Valores->ValorInss = 30;
    $std->Servico->Valores->ValorIr = 40;
    $std->Servico->Valores->ValorCsll = 50;
    $std->Servico->Valores->IssRetido = 2;
    $std->Servico->Valores->BaseCalculo = 1000;
    $std->Servico->Valores->ValorIss = 10.00;
    //$std->Servico->Valores->OutrasRetencoes = 10.00;
    $std->Servico->Valores->Aliquota = 5.00;
    $std->Servico->Valores->ValorLiquidoNfse = 850;
    $std->Servico->Valores->DescontoIncondicionado = 0.00;
    $std->Servico->Valores->DescontoCondicionado = 0.00;

    /*
      $std->IntermediarioServico = new \stdClass();
      $std->IntermediarioServico->RazaoSocial = 'INSCRICAO DE TESTE SIATU - D AGUA -PAULINO S';
      $std->IntermediarioServico->Cnpj = '99999999000191';
      $std->IntermediarioServico->InscricaoMunicipal = '8041700010';

      $std->ConstrucaoCivil = new \stdClass();
      $std->ConstrucaoCivil->CodigoObra = '1234';
      $std->ConstrucaoCivil->Art = '1234';
     */


    $arps[] = new Rps($std);

    $lote = '1';
    $response = $tools->recepcionarLoteRps($arps, $lote);

    echo FakePretty::prettyPrint($response, '');
} catch (\Exception $e) {
    echo $e->getMessage();
}
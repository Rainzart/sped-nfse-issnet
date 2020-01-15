<?php
error_reporting(E_ALL);
ini_set('display_errors', 'On');
require_once '../bootstrap.php';

use NFePHP\Common\Certificate;
use NFePHP\NFSeIssNet\Tools;
use NFePHP\NFSeIssNet\Common\Soap\SoapFake;
use NFePHP\NFSeIssNet\Common\FakePretty;

try {
    
    $config = [
        'cnpj' => '02956773000171',
        'im' => '1998010',
        'cmun' => '4104808', //ira determinar as urls e outros dados
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

    $params = new \stdClass();
    $params->numero = 210;
    $params->serie = '1';
    $params->tipo = 1;
    
    $response = $tools->consultarNfseRps($params);
    
    echo FakePretty::prettyPrint($response, '');
 
} catch (\Exception $e) {
    echo $e->getMessage();
}

<?php

namespace NFePHP\NFSeIssNet\Common;

/**
 * Auxiar Tools Class for comunications with NFSe webserver in Nacional Standard
 *
 * @category  NFePHP
 * @package   NFePHP\NFSeIssNet
 * @copyright NFePHP Copyright (c) 2008-2018
 * @license   http://www.gnu.org/licenses/lgpl.txt LGPLv3+
 * @license   https://opensource.org/licenses/MIT MIT
 * @license   http://www.gnu.org/licenses/gpl.txt GPLv3+
 * @author    Roberto L. Machado <linux.rlm at gmail dot com>
 * @link      http://github.com/nfephp-org/sped-nfse-nacional for the canonical source repository
 */

use NFePHP\Common\Certificate;
use NFePHP\NFSeIssNet\RpsInterface;
use NFePHP\Common\DOMImproved as Dom;
use NFePHP\NFSeIssNet\Common\Signer;
use NFePHP\NFSeIssNet\Common\Soap\SoapInterface;
use NFePHP\NFSeIssNet\Common\Soap\SoapCurl;

class Tools
{
    public $lastRequest;
    
    protected $config;
    protected $prestador;
    protected $certificate;
    protected $wsobj;
    protected $soap;
    protected $environment;
    
    /**
     * Constructor
     * @param string $config
     * @param Certificate $cert
     */
    public function __construct($config, Certificate $cert)
    {
        $this->config = json_decode($config);
        $this->certificate = $cert;
        $this->buildPrestadorTag();
        $this->wsobj = $this->loadWsobj($this->config->cmun);
        $this->environment = 'homologacao';
        if ($this->config->tpamb === 1) {
            $this->environment = 'producao';
        }
    }
    
    /**
     * load webservice parameters
     * @param string $cmun
     * @return object
     * @throws \Exception
     */
    protected function loadWsobj($cmun)
    {
        $path = realpath(__DIR__ . "/../../storage/urls_webservices.json");
        $urls = json_decode(file_get_contents($path), true);
        if (empty($urls[$cmun])) {
            throw new \Exception("Não localizado parâmetros para esse municipio.");
        }
        return (object) $urls[$cmun];
    }


    /**
     * SOAP communication dependency injection
     * @param SoapInterface $soap
     */
    public function loadSoapClass(SoapInterface $soap)
    {
        $this->soap = $soap;
    }
    
    /**
     * Build tag Prestador
     */
    protected function buildPrestadorTag()
    {
        $this->prestador = "<Prestador>"
            . "<tc:CpfCnpj>";
        if (!empty($this->config->cnpj)) {
            $this->prestador .= "<tc:Cnpj>{$this->config->cnpj}</tc:Cnpj>";
        } else {
            $this->prestador .= "<tc:Cpf>{$this->config->cpf}</tc:Cpf>";
        }
        $this->prestador .= "</tc:CpfCnpj>"
            . "<tc:InscricaoMunicipal>{$this->config->im}</tc:InscricaoMunicipal>"
            . "</Prestador>";
    }

    /**
     * Sign XML passing in content
     * @param string $content
     * @param string $tagname
     * @param string $url_reference
     * @param array $transforms
     * @return string XML signed
     */
    public function sign($content, $tagname, $url_reference, $transforms)
    {
        $xml = Signer::sign(
            $this->certificate,
            $content,
            $tagname,
            $url_reference,
            $transforms
        );
        $dom = new Dom('1.0', 'UTF-8');
        $dom->preserveWhiteSpace = false;
        $dom->formatOutput = false;
        $dom->loadXML($xml);
        return $dom->saveXML($dom->documentElement);
    }
    
    /**
     * Send message to webservice
     * @param string $message
     * @param string $operation
     * @return string XML response from webservice
     */
    public function send($message, $operation)
    {
        $action = "{$this->wsobj->soapns}/$operation";
        $url = $this->wsobj->homologacao;
        if ($this->environment === 'producao') {
            $url = $this->wsobj->producao;
        }
        if (empty($url)) {
            throw new \Exception("Não está registrada a URL para o ambiente "
                . "de {$this->environment} desse municipio.");
        }
        $request = $this->createSoapRequest($message, $operation);
        $this->lastRequest = $request;
        
        if (empty($this->soap)) {
            $this->soap = new SoapCurl($this->certificate);
        }
        $msgSize = strlen($request);
        $parameters = [
            "Content-Type: text/xml;charset=UTF-8",
            "SOAPAction: \"$action\"",
            "Content-length: $msgSize"
        ];
        $response = (string) $this->soap->send(
            $operation,
            $url,
            $action,
            $request,
            $parameters
        );
        return $this->extractContentFromResponse($response, $operation);
    }
    
    /**
     * Extract xml response from CDATA outputXML tag
     * @param string $response Return from webservice
     * @return string XML extracted from response
     */
    protected function extractContentFromResponse($response, $operation)
    {
        //$dom = new Dom('1.0', 'UTF-8');
        //$dom->preserveWhiteSpace = false;
        //$dom->formatOutput = false;
        //$dom->loadXML($response);
        //if (!empty($dom->getElementsByTagName('outputXML')->item(0))) {
        //  $node = $dom->getElementsByTagName('outputXML')->item(0);
        //    return $node->textContent;
        //}
        return $response;
    }

    /**
     * Build SOAP request
     * @param string $message
     * @param string $operation
     * @return string XML SOAP request
     */
    protected function createSoapRequest($message, $operation)
    {
      
        $env = "<soap:Envelope "
            . "xmlns:soap=\"http://www.w3.org/2003/05/soap-envelope\" "
            . "xmlns:nfd=\"{$this->wsobj->soapns}\">"
            . "<soap:Header/>"
            . "<soap:Body>"
            . "<nfd:{$operation}>"
            . "<nfd:xml>{$message}</nfd:xml>"
            . "</nfd:{$operation}>"
            . "</soap:Body>"
            . "</soap:Envelope>";
            
        $dom = new Dom('1.0', 'UTF-8');
        $dom->preserveWhiteSpace = false;
        $dom->formatOutput = false;
        $dom->loadXML($env);
        return $dom->saveXML($dom->documentElement);
    }

    /**
     * Create tag Prestador and insert into RPS xml
     * @param RpsInterface $rps
     * @return string RPS XML (not signed)
     */
    protected function putPrestadorInRps(RpsInterface $rps)
    {
        $dom = new Dom('1.0', 'UTF-8');
        $dom->preserveWhiteSpace = false;
        $dom->formatOutput = false;
        $dom->loadXML($rps->render());
        $referenceNode = $dom->getElementsByTagName('Servico')->item(0);
        $node = $dom->createElement('Prestador');
        $dom->addChild(
            $node,
            "Cnpj",
            $this->config->cnpj,
            true
        );
        $dom->addChild(
            $node,
            "InscricaoMunicipal",
            $this->config->im,
            true
        );
        $dom->insertAfter($node, $referenceNode);
        return $dom->saveXML($dom->documentElement);
    }
}

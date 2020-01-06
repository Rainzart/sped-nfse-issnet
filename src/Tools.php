<?php

namespace NFePHP\NFSeIssNet;

/**
 * Class for comunications with NFSe webserver in Nacional Standard
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

use NFePHP\NFSeIssNet\Common\Tools as BaseTools;
use NFePHP\NFSeIssNet\RpsInterface;
use NFePHP\Common\Certificate;
use NFePHP\Common\Validator;
use NFePHP\NFSeIssNet\Common\Signer;

class Tools extends BaseTools
{
    
    const ERRO_EMISSAO = 1; //Erro na emissão;
    const SERVICO_NAO_PRESTADO = 2; //Serviço não prestado;
    const ERRO_ASSINATURA = 3; //Erro de Assinatura;
    const DULICIDADE = 4; //Duplicidade da Nota;
    const ERRO_PROCESSAMENTO = 5; //Erro de processamento;
    
    protected $tcnamespace = "http://www.issnetonline.com.br/webserviceabrasf/vsd/tipos_complexos.xsd";
    protected $tsnamespace = "http://www.issnetonline.com.br/webserviceabrasf/vsd/tipos_simples.xsd";
    
    protected $xsdpath;
    protected $cmun;


    public function __construct($config, Certificate $cert)
    {
        parent::__construct($config, $cert);
        $path = realpath(
            __DIR__ . '/../storage/schemes'
        );
        $this->xsdpath = $path;
        $this->cmun = $this->config->cmun;
        if ($this->config->tpamb == 2) {
            //em homolgação usa-se o municipio 999
            $this->cmun = '999';
        }
    }
    
    /**
     * Solicita o cancelamento de NFSe (SINCRONO)
     * @param integer $numero NFSe
     * @param integer $codigo codigo de cancelamento
     * @param string $motivo
     * @return string
     */
    public function cancelarNfse($numero, $codigo, $motivo = null)
    {
        
        //devido a falhas no xsd não permitir justificativa
        $motivo = null;
        $transforms = ["http://www.w3.org/2000/09/xmldsig#enveloped-signature"];
        $xsd = "servico_cancelar_nfse_envio.xsd";
        $operation = 'CancelarNfse';
        $pedido = "<Pedido xmlns:tc=\"{$this->tcnamespace}\">"
            . "<tc:InfPedidoCancelamento>"
            . "<tc:IdentificacaoNfse>"
            . "<tc:Numero>{$numero}</tc:Numero>"
            . "<tc:Cnpj>{$this->config->cnpj}</tc:Cnpj>"
            . "<tc:InscricaoMunicipal>{$this->config->im}</tc:InscricaoMunicipal>"
            . "<tc:CodigoMunicipio>{$this->cmun}</tc:CodigoMunicipio>"
            . "</tc:IdentificacaoNfse>"
            . "<tc:CodigoCancelamento>{$codigo}</tc:CodigoCancelamento>";
        if (!empty($motivo)) {
            $pedido .= "<tc:MotivoCancelamentoNfse>{$motivo}</tc:MotivoCancelamentoNfse>";
        }
        $pedido .= "</tc:InfPedidoCancelamento>"
            . "</Pedido>";
        
        $pedido = $this->sign(
            $pedido,
            'InfPedidoCancelamento',
            "http://www.w3.org/TR/2000/REC-xhtml1-20000126/",
            $transforms
        );
        $content = "<p1:CancelarNfseEnvio "
            . "xmlns:p1=\"{$this->wsobj->msgns}/{$xsd}\" "
            . "xmlns:tc=\"{$this->tcnamespace}\" "
            . "xmlns:ts=\"{$this->tsnamespace}\">"
            . $pedido
            . "</p1:CancelarNfseEnvio>";
        
        $content = $this->canonize($content);
        Validator::isValid($content, "$this->xsdpath/{$xsd}");
        return $this->send($content, $operation);
    }

    /**
     * Consultar NFSe por RPS (SINCRONO)
     * @param integer $numero
     * @param string $serie
     * @param integer $tipo
     * @return string
     */
    public function consultarNfsePorRps($numero, $serie, $tipo)
    {
        $xsd = "servico_consultar_nfse_rps_envio.xsd";
        $operation = "ConsultarNfsePorRps";
        $content = "<ConsultarNfseRpsEnvio "
            . "xmlns=\"{$this->wsobj->msgns}/{$xsd}\" "
            . "xmlns:tc=\"{$this->tcnamespace}\" "
            . "xmlns:ts=\"{$this->tsnamespace}\">"
            . "<IdentificacaoRps>"
            . "<tc:Numero>$numero</tc:Numero>"
            . "<tc:Serie>$serie</tc:Serie>"
            . "<tc:Tipo>$tipo</tc:Tipo>"
            . "</IdentificacaoRps>"
            . $this->prestador
            . "</ConsultarNfseRpsEnvio>";
        Validator::isValid($content, "$this->xsdpath/{$xsd}");
        return $this->send($content, $operation);
    }

    public function consultarDadosCadastrais()
    {
        $xsd = "servico_consultar_dados_cadastrais_envio.xsd";
        $transforms = [
            "http://www.w3.org/2000/09/xmldsig#enveloped-signature",
            "http://www.w3.org/TR/2001/REC-xml-c14n-20010315"
        ];
        $operation = "ConsultarDadosCadastrais";
        $content = "<ConsultarDadosCadastraisEnvio "
            . "xmlns:tc=\"{$this->tcnamespace}\" "
            . "xmlns:ts=\"{$this->tsnamespace}\" "
            . "xmlns=\"{$this->wsobj->msgns}/{$xsd}\">"
            . $this->prestador
            . "</ConsultarDadosCadastraisEnvio>";
            
        //$content = $this->sign($content, 'ConsultarDadosCadastraisEnvio', '', $transforms);
        //Validator::isValid($content, "$this->xsdpath/{$xsd}");
        return $this->send($content, $operation);
    }

    
    /**
     * Consulta Lote RPS (SINCRONO) após envio com recepcionarLoteRps() (ASSINCRONO)
     * complemento do processo de envio assincono.
     * Que deve ser usado quando temos mais de um RPS sendo enviado
     * por vez.
     * @param string $protocolo
     * @return string
     */
    public function consultarLoteRps($protocolo)
    {
        $xsd = "servico_consultar_lote_rps_envio.xsd";
        $operation = 'ConsultarLoteRps';
        $content = "<ConsultarLoteRpsEnvio "
            . "xmlns=\"{$this->wsobj->msgns}/{$xsd}\" "
            . "xmlns:tc=\"{$this->tcnamespace}\" "
            . "xmlns:ts=\"{$this->tsnamespace}\">"
            . $this->prestador
            . "<Protocolo>$protocolo</Protocolo>"
            . "</ConsultarLoteRpsEnvio>";
        Validator::isValid($content, "$this->xsdpath/{$xsd}");
        return $this->send($content, $operation);
    }
    
    /**
     * Consulta NFSe emitidas em um periodo e por tomador (SINCRONO)
     * @param string $dini
     * @param string $dfim
     * @param string $tomadorCnpj
     * @param string $tomadorCpf
     * @param string $tomadorIM
     * @return string
     */
    public function consultarNfse($dini, $dfim, $tomadorCnpj = null, $tomadorCpf = null, $tomadorIM = null)
    {
        $xsd = "servico_consultar_nfse_envio.xsd";
        $operation = 'ConsultarNfse';
        $content = "<ConsultarNfseEnvio "
            . "xmlns=\"{$this->wsobj->msgns}/{$xsd}\" "
            . "xmlns:tc=\"{$this->tcnamespace}\" "
            . "xmlns:ts=\"{$this->tsnamespace}\">"
            . $this->prestador
            . "<PeriodoEmissao>"
            . "<DataInicial>$dini</DataInicial>"
            . "<DataFinal>$dfim</DataFinal>"
            . "</PeriodoEmissao>";
            
        if ($tomadorCnpj || $tomadorCpf) {
            $content .= "<Tomador>"
            . "<tc:CpfCnpj>";
            if (isset($tomadorCnpj)) {
                $content .= "<tc:Cnpj>$tomadorCnpj</tc:Cnpj>";
            } else {
                $content .= "<tc:Cpf>$tomadorCpf</tc:Cpf>";
            }
            $content .= "</tc:CpfCnpj>";
            if (isset($tomadorIM)) {
                $content .= "<tc:InscricaoMunicipal>$tomadorIM</tc:InscricaoMunicipal>";
            }
            $content .= "</Tomador>";
        }
        $content .= "</ConsultarNfseEnvio>";
        Validator::isValid($content, "$this->xsdpath/{$xsd}");
        return $this->send($content, $operation);
    }
    
    /**
     * Consulta
     * @param integer $numero
     * @param integer $codigo_tributacao
     * @return string
     */
    public function consultarVisualizacaoNfse($numero, $codigo_tributacao)
    {
        $xsd = "servico_consultar_url_visualizacao_nfse_envio.xsd";
        $operation = 'ConsultarUrlVisualizacaoNfse';
        
        $content = "<ConsultarUrlVisualizacaoNfseEnvio "
            . "xmlns=\"{$this->wsobj->msgns}/{$xsd}\" "
            . "xmlns:tc=\"{$this->tcnamespace}\">"
            . $this->prestador
            . "<Numero>{$numero}</Numero>"
            . "<CodigoTributacaoMunicipio>{$codigo_tributacao}</CodigoTributacaoMunicipio>"
            . "</ConsultarUrlVisualizacaoNfseEnvio>";
        Validator::isValid($content, "$this->xsdpath/{$xsd}");
        return $this->send($content, $operation);
    }
    
    /**
     * Consulta
     * @param integer $numero
     * @param integer $codigo_tributacao
     * @return string
     */
    public function consultarVisualizacaoNfseSerie($numero, $codigo_tributacao, $codigo_serie)
    {
        $xsd = "servico_consultar_url_visualizacao_nfse_serie_envio.xsd";
        $operation = 'ConsultarUrlVisualizacaoNfseSerie';
        $content = "<ConsultarUrlVisualizacaoNfseSerieEnvio "
            . "xmlns=\"{$this->wsobj->msgns}/{$xsd}\" "
            . "xmlns:tc=\"{$this->tcnamespace}\">"
            . $this->prestador
            . "<Numero>{$numero}</Numero>"
            . "<CodigoTributacaoMunicipio>{$codigo_tributacao}</CodigoTributacaoMunicipio>"
            . "<CodigoSerie>{$codigo_serie}</CodigoSerie>"
            . "</ConsultarUrlVisualizacaoNfseSerieEnvio>";
        Validator::isValid($content, "$this->xsdpath/{$xsd}");
        return $this->send($content, $operation);
    }
    
    /**
     * Envia LOTE de RPS para emissão de NFSe (ASSINCRONO)
     * @param array $arps Array contendo de 1 a 50 RPS::class
     * @param string $lote Número do lote de envio
     * @return string
     * @throws \Exception
     */
    public function recepcionarLoteRps($arps, $lote)
    {
        $xsd = "servico_enviar_lote_rps_envio.xsd";
        $transforms = [
            "http://www.w3.org/2000/09/xmldsig#enveloped-signature"
        ];
        $operation = 'EnviarLoteRps';
        $no_of_rps_in_lot = count($arps);
        if ($no_of_rps_in_lot > 50) {
            throw new \Exception('O limite é de 50 RPS por lote enviado.');
        }
        $rpstxt = '';
        foreach ($arps as $rps) {
            $rps->config($this->config);
            $rpstxt .= $rps->render();
        }
                
        $content = "<EnviarLoteRpsEnvio "
            . "xmlns=\"{$this->wsobj->msgns}/{$xsd}\" "
            . "xmlns:tc=\"{$this->tcnamespace}\">"
            . "<LoteRps>"
            . "<tc:NumeroLote>$lote</tc:NumeroLote>"
            . "<tc:CpfCnpj>";
        if (!empty($this->config->cnpj)) {
            $content .= "<tc:Cnpj>{$this->config->cnpj}</tc:Cnpj>";
        } else {
            $content .= "<tc:Cpf>{$this->config->cpf}</tc:Cpf>";
        }
        $content .= "</tc:CpfCnpj>"
            . "<tc:InscricaoMunicipal>{$this->config->im}</tc:InscricaoMunicipal>"
            . "<tc:QuantidadeRps>$no_of_rps_in_lot</tc:QuantidadeRps>"
            . "<tc:ListaRps>"
            . $rpstxt
            . "</tc:ListaRps>"
            . "</LoteRps>"
            . "</EnviarLoteRpsEnvio>";
        $content = $this->canonize($content);
        //header("Content-type: text/xml");
        //echo $content;
        //die;
            
        $content = $this->sign($content, 'LoteRps', '', $transforms);
        //Validator::isValid($content, "$this->xsdpath/{$xsd}");
        return $this->send($content, $operation);
    }
    
    
    protected function canonize($content)
    {
        $dom = new \DOMDocument('1.0', 'utf-8');
        $dom->formatOutput = false;
        $dom->preserveWhiteSpace = false;
        $dom->loadXML($content);
        return $dom->C14N(false, false, null, null);
    }
}

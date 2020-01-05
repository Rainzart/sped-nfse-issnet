<?php

namespace NFePHP\NFSeIssNet\Common;

/**
 * Class for RPS XML convertion
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

use stdClass;
use NFePHP\Common\DOMImproved as Dom;
use DOMNode;
use DOMElement;

class Factory
{
    /**
     * @var stdClass
     */
    protected $std;
    /**
     * @var Dom
     */
    protected $dom;
    /**
     * @var DOMNode
     */
    protected $rps;

    /**
     * Constructor
     * @param stdClass $std
     */
    public function __construct(stdClass $std)
    {
        $namespace = "http://www.issnetonline.com.br/webserviceabrasf/vsd/servico_enviar_lote_rps_envio.xsd";
        $tcnamespace = "http://www.issnetonline.com.br/webserviceabrasf/vsd/tipos_complexos.xsd";
        
        $this->std = $std;
        $this->dom = new Dom('1.0', 'UTF-8');
        $this->dom->preserveWhiteSpace = false;
        $this->dom->formatOutput = false;
        $this->rps = $this->dom->createElement('tc:Rps');
        $this->rps->setAttribute('xmlns:tc', $tcnamespace);
    }
    
    /**
     * Builder, converts sdtClass Rps in XML Rps
     * NOTE: without Prestador Tag
     * @return string RPS in XML string format
     */
    public function render()
    {
        $infRps = $this->dom->createElement('tc:InfRps');
        
        $this->addIdentificacao($infRps);
        
        $this->dom->addChild(
            $infRps,
            "tc:DataEmissao",
            $this->std->dataemissao,
            true
        );
        $this->dom->addChild(
            $infRps,
            "tc:NaturezaOperacao",
            $this->std->naturezaoperacao,
            true
        );
        $this->dom->addChild(
            $infRps,
            "tc:OptanteSimplesNacional",
            $this->std->optantesimplesnacional,
            true
        );
        $this->dom->addChild(
            $infRps,
            "tc:IncentivadorCultural",
            $this->std->incentivadorcultural,
            false
        );
        $this->dom->addChild(
            $infRps,
            "tc:Status",
            $this->std->status,
            true
        );
        $this->dom->addChild(
            $infRps,
            "tc:RegimeEspecialTributacao",
            $this->std->regimeespecialtributacao,
            true
        );
        
        $this->addServico($infRps);
        $this->addTomador($infRps);
        $this->addIntermediario($infRps);
        $this->addConstrucao($infRps);
        
        $this->rps->appendChild($infRps);
        $this->dom->appendChild($this->rps);
        return $this->dom->saveXML();
    }
    
    /**
     * Includes Identificacao TAG in parent NODE
     * @param DOMNode $parent
     */
    protected function addIdentificacao(&$parent)
    {
        $id = $this->std->identificacaorps;
        $node = $this->dom->createElement('tc:IdentificacaoRps');
        $this->dom->addChild(
            $node,
            "tc:Numero",
            $id->numero,
            true
        );
        $this->dom->addChild(
            $node,
            "tc:Serie",
            $id->serie,
            true
        );
        $this->dom->addChild(
            $node,
            "tc:Tipo",
            $id->tipo,
            true
        );
        $parent->appendChild($node);
    }
    
    /**
     * Includes Servico TAG in parent NODE
     * @param DOMNode $parent
     */
    protected function addServico(&$parent)
    {
        $serv = $this->std->servico;
        $val = $this->std->servico->valores;
        $node = $this->dom->createElement('tc:Servico');
        $valnode = $this->dom->createElement('tc:Valores');
        $this->dom->addChild(
            $valnode,
            "tc:ValorServicos",
            number_format($val->valorservicos, 2, '.', ''),
            true
        );
        $this->dom->addChild(
            $valnode,
            "tc:ValorDeducoes",
            isset($val->valordeducoes)
                ? number_format($val->valordeducoes, 2, '.', '')
                : null,
            false
        );
        $this->dom->addChild(
            $valnode,
            "tc:ValorPis",
            isset($val->valorpis)
                ? number_format($val->valorpis, 2, '.', '')
                : null,
            false
        );
        $this->dom->addChild(
            $valnode,
            "tc:ValorCofins",
            isset($val->valorcofins)
                ? number_format($val->valorcofins, 2, '.', '')
                : null,
            false
        );
        $this->dom->addChild(
            $valnode,
            "tc:ValorInss",
            isset($val->valorinss)
                ? number_format($val->valorinss, 2, '.', '')
                : null,
            false
        );
        $this->dom->addChild(
            $valnode,
            "tc:ValorIr",
            isset($val->valorir)
                ? number_format($val->valorir, 2, '.', '')
                : null,
            false
        );
        $this->dom->addChild(
            $valnode,
            "tc:ValorCsll",
            isset($val->valorcsll)
                ? number_format($val->valorcsll, 2, '.', '')
                : null,
            false
        );
        $this->dom->addChild(
            $valnode,
            "tc:IssRetido",
            isset($val->issretido) ? $val->issretido : null,
            false
        );
        $this->dom->addChild(
            $valnode,
            "tc:ValorIss",
            isset($val->valoriss)
                ? number_format($val->valoriss, 2, '.', '')
                : null,
            false
        );
        $this->dom->addChild(
            $valnode,
            "tc:OutrasRetencoes",
            isset($val->outrasretencoes)
                ? number_format($val->outrasretencoes, 2, '.', '')
                : null,
            false
        );
        $this->dom->addChild(
            $valnode,
            "tc:BaseCalculo",
            isset($val->basecalculo)
                ? number_format($val->basecalculo, 2, '.', '')
                : null,
            false
        );
        $this->dom->addChild(
            $valnode,
            "tc:Aliquota",
            isset($val->aliquota) ? $val->aliquota : null,
            false
        );
        $this->dom->addChild(
            $valnode,
            "tc:ValorLiquidoNfse",
            isset($val->valorliquidonfse) ? $val->valorliquidonfse : null,
            false
        );
        $this->dom->addChild(
            $valnode,
            "tc:DescontoIncondicionado",
            isset($val->descontoincondicionado)
                ? number_format($val->descontoincondicionado, 2, '.', '')
                : null,
            false
        );
        $this->dom->addChild(
            $valnode,
            "tc:DescontoCondicionado",
            isset($val->descontocondicionado)
                ? number_format($val->descontocondicionado, 2, '.', '')
                : null,
            false
        );
        $this->dom->addChild(
            $valnode,
            "tc:ValorDeducoes",
            isset($val->valordeducoes)
                ? number_format($val->valordeducoes, 2, '.', '')
                : null,
            false
        );
        $node->appendChild($valnode);
        
        $this->dom->addChild(
            $node,
            "tc:ItemListaServico",
            $serv->itemlistaservico,
            true
        );
        $this->dom->addChild(
            $node,
            "tc:CodigoCnae",
            $serv->codigocnae,
            true
        );
        $this->dom->addChild(
            $node,
            "tc:CodigoTributacaoMunicipio",
            $serv->codigotributacaomunicipio,
            true
        );
        $this->dom->addChild(
            $node,
            "tc:Discriminacao",
            $serv->discriminacao,
            true
        );
        $this->dom->addChild(
            $node,
            "tc:MunicipioPrestacaoServico",
            $serv->codigomunicipio,
            true
        );
        $parent->appendChild($node);
    }
    
    /**
     * Includes Tomador TAG in parent NODE
     * @param DOMNode $parent
     */
    protected function addTomador(&$parent)
    {
        if (!isset($this->std->tomador)) {
            return;
        }
        $tom = $this->std->tomador;
        $end = $this->std->tomador->endereco;
        
        $node = $this->dom->createElement('tc:Tomador');
        $ide = $this->dom->createElement('tc:IdentificacaoTomador');
        $cpfcnpj = $this->dom->createElement('tc:CpfCnpj');
        if (isset($tom->cnpj)) {
            $this->dom->addChild(
                $cpfcnpj,
                "tc:Cnpj",
                $tom->cnpj,
                true
            );
        } else {
            $this->dom->addChild(
                $cpfcnpj,
                "tc:Cpf",
                $tom->cpf,
                true
            );
        }
        $ide->appendChild($cpfcnpj);
        $this->dom->addChild(
            $ide,
            "tc:InscricaoMunicipal",
            isset($tom->inscricaomunicipal) ? $tom->inscricaomunicipal : null,
            false
        );
        $node->appendChild($ide);
        $this->dom->addChild(
            $node,
            "tc:RazaoSocial",
            $tom->razaosocial,
            true
        );
        $endereco = $this->dom->createElement('tc:Endereco');
        $this->dom->addChild(
            $endereco,
            "tc:Endereco",
            $end->endereco,
            true
        );
        $this->dom->addChild(
            $endereco,
            "tc:Numero",
            $end->numero,
            true
        );
        $this->dom->addChild(
            $endereco,
            "tc:Complemento",
            isset($end->complemento) ? $end->complemento : null,
            false
        );
        $this->dom->addChild(
            $endereco,
            "tc:Bairro",
            $end->bairro,
            true
        );
        $this->dom->addChild(
            $endereco,
            "tc:Cidade",
            $end->codigomunicipio,
            true
        );
        $this->dom->addChild(
            $endereco,
            "tc:Estado",
            $end->uf,
            true
        );
        $this->dom->addChild(
            $endereco,
            "tc:Cep",
            $end->cep,
            true
        );
        $node->appendChild($endereco);
        $parent->appendChild($node);
    }
    
    /**
     * Includes Intermediario TAG in parent NODE
     * @param DOMNode $parent
     */
    protected function addIntermediario(&$parent)
    {
        if (!isset($this->std->intermediarioservico)) {
            return;
        }
        $int = $this->std->intermediarioservico;
        $node = $this->dom->createElement('tc:IntermediarioServico');
        $this->dom->addChild(
            $node,
            "tc:RazaoSocial",
            $int->razaosocial,
            true
        );
        $cpfcnpj = $this->dom->createElement('tc:CpfCnpj');
        if (isset($int->cnpj)) {
            $this->dom->addChild(
                $cpfcnpj,
                "tc:Cnpj",
                $int->cnpj,
                true
            );
        } else {
            $this->dom->addChild(
                $cpfcnpj,
                "tc:Cpf",
                $int->cpf,
                true
            );
        }
        $node->appendChild($cpfcnpj);
        $this->dom->addChild(
            $node,
            "tc:InscricaoMunicipal",
            $int->inscricaomunicipal,
            false
        );
        $parent->appendChild($node);
    }
    
    /**
     * Includes Construcao TAG in parent NODE
     * @param DOMNode $parent
     */
    protected function addConstrucao(&$parent)
    {
        if (!isset($this->std->construcaocivil)) {
            return;
        }
        $obra = $this->std->construcaocivil;
        $node = $this->dom->createElement('tc:ConstrucaoCivil');
        $this->dom->addChild(
            $node,
            "tc:CodigoObra",
            $obra->codigoobra,
            true
        );
        $this->dom->addChild(
            $node,
            "tc:Art",
            $obra->art,
            true
        );
        $parent->appendChild($node);
    }
}

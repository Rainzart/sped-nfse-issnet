# sped-nfse-issnet

API para integraçao para geração de NFSe (Prefeituras) com o modelo ISS.NET

[![Build Status][ico-travis]][link-travis]
[![Coverage Status][ico-scrutinizer]][link-scrutinizer]
[![Quality Score][ico-code-quality]][link-code-quality]

[![Latest Stable Version][ico-stable]][link-packagist]
[![Latest Version on Packagist][ico-version]][link-packagist]
[![License][ico-license]][link-packagist]
[![Total Downloads][ico-downloads]][link-downloads]

[![Issues][ico-issues]][link-issues]
[![Forks][ico-forks]][link-forks]
[![Stars][ico-stars]][link-stars]


# BETHA TESTS

## Importante

> O provedor ISS.NET não tem ambiente de homologação disponível livremente, para usar o ambiente de testes é necessário solicitar esse acesso pela NotaControl pelo email: atendimento@notacontrol.com.br. O suporte desse provedor NotaControl é pago, então não espere muita ajuda e/ou informações deles.
> Esse acesso ao ambiente de homologação é limitado a 50 RPS.

> Já para acessar o ambiente de produção é necessário solicitar esse acesso a sua Prefeitura que irá passar a você os parâmetros para a emissão dos RPS, como serie, faixa de numeros, codigos de serviço e etc.

> Para o cancelamento de NFSe em Cascavel, impreterivelmente, o pedido deve ser justificado e informado o número da NF a qual substituiu ou substituirá a cancelada ou seja para cancelar uma nota no ISS.NET você precisa dizer e enviar qual é a numeração da NFSe (já autorizada) que vai substituir essa que quer cancelar. Deixando mais claro "NÃO EXISTE SIMPLES CANCELAMENTO". o cancelamento é sempre feito por substituição.


## Municípios atendidos por esse provedor

|n|Município|UF|IBGE|
|:---:|:---|:---:|:---:|
|1|Anapolis|GO|5201108|
|2|Aparecida de Goiania|GO|5201405|
|3|Mantena|MG|3139607|
|4|Anastacio|MS|5000708|
|5|Aquidauana|MS|5001102|
|6|Bonito|MS|5002209|
|7|Caarapo|MS|5002407|
|8|Dourados|MS|5003702|
|9|Iguatemi|MS|5004304|
|10|Inocencio|MS|5004403|
|11|Ivinhema|MS|5004700|
|12|Jardim|MS|5005004|
|13|Miranda|MS|5005608|
|14|Navirai|MS|5005707|
|15|Nova Alvorada do Sul|MS|5006002|
|16|Paranaiba|MS|5006309|
|17|Ponta Pora|MS|5006606|
|18|Ribas do Rio Pardo|MS|5007109|
|19|Rio Brilhante|MS|5007208|
|20|Sao Gabriel do Oeste|MS|5007695|
|21|Selviria|MS|5007802|
|22|Sidrolandia|MS|5007901|
|23|Alta Floresta|MT|5100250|
|24|Cuiaba|MT|5103403|
|25|Juara|MT|5105101|
|26|Paranatinga|MT|5106307|
|27|Barcarena|PA|1501303|
|28|Cascavel|PR|4104808|
|29|Barra Mansa|RJ|3300407|
|30|Duque de Caxias|RJ|3301702|
|31|Itatiaia|RJ|3302254|
|32|Cruz Alta|RS|4306106|
|33|Novo Hamburgo|RS|4313409|
|34|Santa Maria|RS|4316907|
|35|Andradina|SP|3502101|
|36|Birigui|SP|3506508|
|37|Lorena|SP|3527207|
|38|Jacarei|SP|3524402|
|39|Praia Grande|SP|3541000|
|40|Sao Vicente|SP|3551009|
|41|Serrana|SP|3551504|


## Dependências

- PHP >= 7.1
- ext-curl
- ext-soap
- ext-zlib
- ext-dom
- ext-openssl
- ext-json
- ext-simplexml
- ext-libxml

### Outras Libs

- nfephp-org/sped-common
- justinrainbow/json-schema


## Contribuindo
Este é um projeto totalmente *OpenSource*, para usa-lo e modifica-lo você não paga absolutamente nada. Porém para continuarmos a mante-lo é necessário qua alguma contribuição seja feita, seja auxiliando na codificação, na documentação ou na realização de testes e identificação de falhas e BUGs.

**Este pacote está listado no [Packgist](https://packagist.org/)**

*Durante a fase de desenvolvimento e testes este pacote deve ser instalado com:*
```bash
composer require nfephp-org/sped-nfse-issnet:dev-master
```

*Ou ainda,*
```bash
composer require nfephp-org/sped-nfse-issnet:dev-master --prefer-dist
```

*Ou ainda alterando o composer.json do seu aplicativo inserindo:*
```json
"require": {
    "nfephp-org/sped-nfse-issnet" : "dev-master"
}
```

> NOTA: Ao utilizar este pacote ainda na fase de desenvolvimento não se esqueça de alterar o composer.json da sua aplicação para aceitar pacotes em desenvolvimento, alterando a propriedade "minimum-stability" de "stable" para "dev".
> ```json
> "minimum-stability": "dev",
> "prefer-stable": true
> ```

*Após os stable realeases estarem disponíveis, este pacote poderá ser instalado com:*
```bash
composer require nfephp-org/sped-nfse-issnet
```
Ou ainda alterando o composer.json do seu aplicativo inserindo:
```json
"require": {
    "nfephp-org/sped-sped-nfse-issnet" : "^1.0"
}
```

## Forma de uso
vide a pasta *Examples*

## Log de mudanças e versões
Acompanhe o [CHANGELOG](CHANGELOG.md) para maiores informações sobre as alterações recentes.

## Testing

Todos os testes são desenvolvidos para operar com o PHPUNIT

## Security

Caso você encontre algum problema relativo a segurança, por favor envie um email diretamente aos mantenedores do pacote ao invés de abrir um ISSUE.

## Credits

Roberto L. Machado (owner and developer)
Chrystian Toigo (admin and developer)

## License

Este pacote está diponibilizado sob LGPLv3 ou MIT License (MIT). Leia  [Arquivo de Licença](LICENSE.md) para maiores informações.

[ico-stable]: https://poser.pugx.org/nfephp-org/sped-nfse-issnet/version
[ico-stars]: https://img.shields.io/github/stars/nfephp-org/sped-nfse-issnet.svg?style=flat-square
[ico-forks]: https://img.shields.io/github/forks/nfephp-org/sped-nfse-issnet.svg?style=flat-square
[ico-issues]: https://img.shields.io/github/issues/nfephp-org/sped-nfse-issnet.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/nfephp-org/sped-nfse-issnet/master.svg?style=flat-square
[ico-scrutinizer]: https://img.shields.io/scrutinizer/coverage/g/nfephp-org/sped-nfse-issnet.svg?style=flat-square
[ico-code-quality]: https://img.shields.io/scrutinizer/g/nfephp-org/sped-nfse-issnet.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/nfephp-org/sped-nfse-issnet.svg?style=flat-square
[ico-version]: https://img.shields.io/packagist/v/nfephp-org/sped-nfse-issnet.svg?style=flat-square
[ico-license]: https://poser.pugx.org/nfephp-org/nfephp/license.svg?style=flat-square
[ico-gitter]: https://img.shields.io/badge/GITTER-4%20users%20online-green.svg?style=flat-square


[link-packagist]: https://packagist.org/packages/nfephp-org/sped-nfse-issnet
[link-travis]: https://travis-ci.org/nfephp-org/sped-nfse-issnet
[link-scrutinizer]: https://scrutinizer-ci.com/g/nfephp-org/sped-nfse-issnet/code-structure
[link-code-quality]: https://scrutinizer-ci.com/g/nfephp-org/sped-nfse-issnet
[link-downloads]: https://packagist.org/packages/nfephp-org/sped-nfse-issnet
[link-author]: https://github.com/nfephp-org
[link-issues]: https://github.com/nfephp-org/sped-nfse-issnet/issues
[link-forks]: https://github.com/nfephp-org/sped-nfse-issnet/network
[link-stars]: https://github.com/nfephp-org/sped-nfse-issnet/stargazers
[link-gitter]: https://gitter.im/nfephp-org/sped-nfse-issnet?utm_source=badge&utm_medium=badge&utm_campaign=pr-badge&utm_content=badge

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


# EM DESENVONVIMENTO NÃO FUNCIONAL

## Prefeituras (até o momento localidas 12 prefeituras usando esse modelo) 

- Anapolis (GO)
- Barcarena (PA)
- Cascavel (PR)
- Barra MAnsa (RJ)
- Dique de Caxias (RJ)
- Itatiaia (RJ)
- Lorena (SP)
- Praia Grande (SP)
- São Vicente (SP)
- Jacarei (SP)
- Novo Hamburgo (RS)
- Santa Maria (RS)

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

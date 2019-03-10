# Desafio Superlófica

https://github.com/Superlogica/vagas/issues/2

## Dependências
Baixe as dependências com o composer:

```sh
composer.phar install
```

## SDK-PJBank
Arquivos: 
https://github.com/pjbank/pjbank-php-sdk

Gerando library para CodeIgniter.

No arquivo application/config/config.php, adicionar as variáveis de configuração:

```sh
$config['credencialPJBank'] = "SUA_CREDENCIAL_PJBANK";
$config['chavePJBank'] = "SUA_CHAVE_PJBANK";
```

em application/libraries, estão os arquivos da pasta src.

## Banco de Dados (MySQL)

Baixe e importe o SQL (estrutura e script para popular)

https://github.com/joaosanches/desafio-superlogica/tree/master/SQL


## Iniciando Aplicação
Inicie sua aplicação startando o banco de dados e o servidor PHP

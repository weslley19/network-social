## Projeto Network Social
Exclusivo para fins de aprendizado utilizando a linguagem PHP na versão 7.4

O objetivo deste projeto é servir como base para a disciplina de Projeto de Ciência da Computação na Estácio.

## Instalação
Você pode clonar este repositório ou baixar o .zip

Rode o seguinte comando no *prompt/terminal* para clonar o projeto:
> git clone <span>https://github.com/weslley19/network-social.git</span>

Ao descompactar, é necessário rodar o **composer** pra instalar as dependências e gerar o *autoload*.

Vá até a pasta do projeto, pelo *prompt/terminal* e execute:
> composer install

Depois é só aguardar.

## Configuração
Todos os arquivos de **configuração** e aplicação estão dentro da pasta *src*.

As configurações de Banco de Dados e URL estão no arquivo *src/Config.php*

É importante configurar corretamente a constante *BASE_DIR* que é a URL do projeto:
> const BASE_DIR = '/**PastaDoProjeto**/public';

## Uso
Você deve acessar a pasta *public* do projeto.

## Modelo de MODEL

```php
<?php

namespace src\models;

use \core\Model;

class Usuario extends Model 
{

}
```

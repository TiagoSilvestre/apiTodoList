Ol�, nessas duas pastas, backEnd e frontEnd, se encontram a api e o aplicativo.


- BackEnd:

Na pasta raiz se encontra o arquivo apitodolist.sql para a cria��o do banco de dados
E a configura��o do db est� em: \backEnd\bootstrap\config.php
A pasta publica do servidor � a \api


- FrontEnd:

Deve-se configurar a url da api no arquivo functions.js, linha 1: var urlBase = 'path/to/server/api'
A pasta publica para requisi��es do servidor � a \api
Executar o index.html
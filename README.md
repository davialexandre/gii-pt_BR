## Descrição

O Gii é uma ferramenta de geração automática de código para o Yii Framework. Ela está fundamentada nos Code Generators, um conjunto de classes e templates responsável por gerar o código e arquivos para uma funcionalidade específica. No Yii, ja temos generators para criar facilmente Models, Forms, Modules, Controllers e CRUDs.

Esse projeto tem a finalidade localizar os Generators originais, inclusos no Yii, para português brasileiro.

## Instalação e Configuração

Crie o diretório protected/gii e copie os arquivos desse repositório para ele. Feito isso, edite o arquivo protected/config/main.php e configure a propriedade generatorPaths, do módulo Gii, com o path alias do diretório recém criado:

	'gii'=>array(
		'class'=>'system.gii.GiiModule',
		...
	        'generatorPaths' => array(
        	        'application.gii',
	        )
	),

Pronto! Agora, os novos Generators estarão disponíveis no Gii.

## Generators Disponíveis

### CRUD
A versão inicial desse generator foi criada pelo @g0nc1n.

Esse é o primeiro Generator localizado para o português brasileiro. Trata-se do CRUD (Create, Read, Update, Delete) Generator original do Yii Framework, adaptado para criar Actions em português.

Com base em um Model as seguintes actions são criadas:

- inserir 
- exibir
- atualizar
- gerenciar

A idéia é criar urls que façam mais sentido em português. Assim, em vez de:

http://example.com/cliente/create

Temos:

http://example.com/cliente/inserir

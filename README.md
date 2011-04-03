Esta é uma versão personalizada do gerador de CRUD do Gii, baseado na versão 1.1.7 do Yii Framework.

Para instalar, substitua os arquivos da pasta <yii-home>/framework/gii/generators/crud por esses. *NÃO* sobrescreva a pasta "crud", renomeie-a e extraia os arquivos para uma pasta "crud" vazia.

O que eu fiz:

* Traduzi os nomes das actions e das respectivas views para o Português;

Modificando o método generateActiveField() do CrudCode.php:
* Alterei a geração de campos do formulário: campos do tipo 'date' geram um CJuiDatepicker e os campos do tipo 'datetime', um CMaskedTextfield com a máscara apropriada;
* Campos associados a uma relação do tipo CActiveDirectory::BELONGS_TO são gerados como dropdown, preenchido com o conteúdo do modelo relacionado.

Submeti essas minhas ideias no fórum do Yii (em inglês):
http://www.yiiframework.com/forum/index.php?/topic/17747-gii-generated-files-should-be-localized-and-more-intelligent/

USE COM MODERAÇÃO. NÃO HÁ GARANTIAS ;)

Fausto Gonçalves Cintra (@g0nc1n)
goncin ARROBA gmail PONTO com

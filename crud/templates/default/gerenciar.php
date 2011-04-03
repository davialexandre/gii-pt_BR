<?php
/**
 * The following variables are available in this template:
 * - $this: the CrudCode object
 */
?>
<?php
echo "<?php\n";
$label=$this->pluralize($this->class2name($this->modelClass));
echo "\$this->breadcrumbs=array(
	'$label'=>array('index'),
	'Gerenciar',
);\n";
?>

$this->menu=array(
	array('label'=>'Listar <?php echo $this->modelClass; ?>', 'url'=>array('index')),
	array('label'=>'Inserir <?php echo $this->modelClass; ?>', 'url'=>array('inserir')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('<?php echo $this->class2id($this->modelClass); ?>-grid', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Gerenciar <?php echo $this->pluralize($this->class2name($this->modelClass)); ?></h1>

<p>
Opcionalmente, você pode informar um operador de comparação (<b>&lt;</b>, <b>&lt;=</b>, <b>&gt;</b>, <b>&gt;=</b>, <b>&lt;&gt;</b>
or <b>=</b>) no início de cada valor de busca para especificar como a comparação deve ser feita.
</p>

<?php echo "<?php echo CHtml::link('Busca Avançada','#',array('class'=>'search-button')); ?>"; ?>

<div class="search-form" style="display:none">
<?php echo "<?php \$this->renderPartial('_busca',array(
	'model'=>\$model,
)); ?>\n"; ?>
</div><!-- search-form -->

<?php echo "<?php"; ?> $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'<?php echo $this->class2id($this->modelClass); ?>-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
<?php
$count=0;
foreach($this->tableSchema->columns as $column)
{
	if(++$count==7)
		echo "\t\t/*\n";
	echo "\t\t'".$column->name."',\n";
}
if($count>=7)
	echo "\t\t*/\n";
?>
		array(
			'class'=>'CButtonColumn',
			'viewButtonUrl' => 'Yii::app()->controller->createUrl("exibir",array("id"=>$data->primaryKey))',
			'updateButtonUrl' => 'Yii::app()->controller->createUrl("atualizar",array("id"=>$data->primaryKey))',
			'deleteButtonUrl' => 'Yii::app()->controller->createUrl("excluir",array("id"=>$data->primaryKey))',
		),
	),
)); ?>

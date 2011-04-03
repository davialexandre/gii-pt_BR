<?php
/**
 * The following variables are available in this template:
 * - $this: the CrudCode object
 */
?>
<?php
echo "<?php\n";
$nameColumn=$this->guessNameColumn($this->tableSchema->columns);
$label=$this->pluralize($this->class2name($this->modelClass));
echo "\$this->breadcrumbs=array(
	'$label'=>array('index'),
	\$model->{$nameColumn},
);\n";
?>

$this->menu=array(
	array('label'=>'Listar <?php echo $this->modelClass; ?>', 'url'=>array('index')),
	array('label'=>'Inserir <?php echo $this->modelClass; ?>', 'url'=>array('inseir')),
	array('label'=>'Atualizar <?php echo $this->modelClass; ?>', 'url'=>array('atualizar', 'id'=>$model-><?php echo $this->tableSchema->primaryKey; ?>)),
	array('label'=>'Excluir <?php echo $this->modelClass; ?>', 'url'=>'#', 'linkOptions'=>array('submit'=>array('excluir','id'=>$model-><?php echo $this->tableSchema->primaryKey; ?>),'confirm'=>'Deseja realmente excluir este item?')),
	array('label'=>'Gerenciar <?php echo $this->modelClass; ?>', 'url'=>array('gerenciar')),
);
?>

<h1>Exibir <?php echo $this->modelClass." #<?php echo \$model->{$this->tableSchema->primaryKey}; ?>"; ?></h1>

<?php echo "<?php"; ?> $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
<?php
foreach($this->tableSchema->columns as $column)
	echo "\t\t'".$column->name."',\n";
?>
	),
)); ?>

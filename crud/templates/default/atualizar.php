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
	\$model->{$nameColumn}=>array('exibir','id'=>\$model->{$this->tableSchema->primaryKey}),
	'Atualizar',
);\n";
?>

$this->menu=array(
	array('label'=>'Listar <?php echo $this->modelClass; ?>', 'url'=>array('index')),
	array('label'=>'Inserir <?php echo $this->modelClass; ?>', 'url'=>array('inserir')),
	array('label'=>'Exibir <?php echo $this->modelClass; ?>', 'url'=>array('exibir', 'id'=>$model-><?php echo $this->tableSchema->primaryKey; ?>)),
	array('label'=>'Gerenciar <?php echo $this->modelClass; ?>', 'url'=>array('gerenciar')),
);
?>

<h1>Atualizar <?php echo $this->modelClass." <?php echo \$model->{$this->tableSchema->primaryKey}; ?>"; ?></h1>

<?php echo "<?php echo \$this->renderPartial('_form', array('model'=>\$model)); ?>"; ?>

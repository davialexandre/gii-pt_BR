<?php

class CrudCode extends CCodeModel
{
	public $model;
	public $controller;
	public $baseControllerClass='Controller';

	private $_modelClass;
	private $_table;

	public function rules()
	{
		return array_merge(parent::rules(), array(
			array('model, controller', 'filter', 'filter'=>'trim'),
			array('model, controller, baseControllerClass', 'required'),
			array('model', 'match', 'pattern'=>'/^\w+[\w+\\.]*$/', 'message'=>'{attribute} should only contain word characters and dots.'),
			array('controller', 'match', 'pattern'=>'/^\w+[\w+\\/]*$/', 'message'=>'{attribute} should only contain word characters and slashes.'),
			array('baseControllerClass', 'match', 'pattern'=>'/^[a-zA-Z_]\w*$/', 'message'=>'{attribute} should only contain word characters.'),
			array('baseControllerClass', 'validateReservedWord', 'skipOnError'=>true),
			array('model', 'validateModel'),
			array('baseControllerClass', 'sticky'),
		));
	}

	public function attributeLabels()
	{
		return array_merge(parent::attributeLabels(), array(
			'model'=>'Model Class',
			'controller'=>'Controller ID',
			'baseControllerClass'=>'Base Controller Class',
		));
	}

	public function requiredTemplates()
	{
		return array(
			'controller.php',
		);
	}

	public function init()
	{
		if(Yii::app()->db===null)
			throw new CHttpException(500,'An active "db" connection is required to run this generator.');
		parent::init();
	}

	public function successMessage()
	{
		$link=CHtml::link('try it now', Yii::app()->createUrl($this->controller), array('target'=>'_blank'));
		return "The controller has been generated successfully. You may $link.";
	}

	public function validateModel($attribute,$params)
	{
		if($this->hasErrors('model'))
			return;
		$class=@Yii::import($this->model,true);
		if(!is_string($class) || !$this->classExists($class))
			$this->addError('model', "Class '{$this->model}' does not exist or has syntax error.");
		else if(!is_subclass_of($class,'CActiveRecord'))
			$this->addError('model', "'{$this->model}' must extend from CActiveRecord.");
		else
		{
			$table=CActiveRecord::model($class)->tableSchema;
			if($table->primaryKey===null)
				$this->addError('model',"Table '{$table->name}' does not have a primary key.");
			else if(is_array($table->primaryKey))
				$this->addError('model',"Table '{$table->name}' has a composite primary key which is not supported by crud generator.");
			else
			{
				$this->_modelClass=$class;
				$this->_table=$table;
			}
		}
	}

	public function prepare()
	{
		$this->files=array();
		$templatePath=$this->templatePath;
		$controllerTemplateFile=$templatePath.DIRECTORY_SEPARATOR.'controller.php';

		$this->files[]=new CCodeFile(
			$this->controllerFile,
			$this->render($controllerTemplateFile)
		);

		$files=scandir($templatePath);
		foreach($files as $file)
		{
			if(is_file($templatePath.'/'.$file) && CFileHelper::getExtension($file)==='php' && $file!=='controller.php')
			{
				$this->files[]=new CCodeFile(
					$this->viewPath.DIRECTORY_SEPARATOR.$file,
					$this->render($templatePath.'/'.$file)
				);
			}
		}
	}

	public function getModelClass()
	{
		return $this->_modelClass;
	}

	public function getControllerClass()
	{
		if(($pos=strrpos($this->controller,'/'))!==false)
			return ucfirst(substr($this->controller,$pos+1)).'Controller';
		else
			return ucfirst($this->controller).'Controller';
	}

	public function getModule()
	{
		if(($pos=strpos($this->controller,'/'))!==false)
		{
			$id=substr($this->controller,0,$pos);
			if(($module=Yii::app()->getModule($id))!==null)
				return $module;
		}
		return Yii::app();
	}

	public function getControllerID()
	{
		if($this->getModule()!==Yii::app())
			$id=substr($this->controller,strpos($this->controller,'/')+1);
		else
			$id=$this->controller;
		if(($pos=strrpos($id,'/'))!==false)
			$id[$pos+1]=strtolower($id[$pos+1]);
		else
			$id[0]=strtolower($id[0]);
		return $id;
	}

	public function getUniqueControllerID()
	{
		$id=$this->controller;
		if(($pos=strrpos($id,'/'))!==false)
			$id[$pos+1]=strtolower($id[$pos+1]);
		else
			$id[0]=strtolower($id[0]);
		return $id;
	}

	public function getControllerFile()
	{
		$module=$this->getModule();
		$id=$this->getControllerID();
		if(($pos=strrpos($id,'/'))!==false)
			$id[$pos+1]=strtoupper($id[$pos+1]);
		else
			$id[0]=strtoupper($id[0]);
		return $module->getControllerPath().'/'.$id.'Controller.php';
	}

	public function getViewPath()
	{
		return $this->getModule()->getViewPath().'/'.$this->getControllerID();
	}

	public function getTableSchema()
	{
		return $this->_table;
	}

	public function generateInputLabel($modelClass,$column)
	{
		return "CHtml::activeLabelEx(\$model,'{$column->name}')";
	}

	public function generateInputField($modelClass,$column)
	{
		if($column->type==='boolean')
			return "CHtml::activeCheckBox(\$model,'{$column->name}')";
		else if(stripos($column->dbType,'text')!==false)
			return "CHtml::activeTextArea(\$model,'{$column->name}',array('rows'=>6, 'cols'=>50))";
		else
		{
			if(preg_match('/^(password|pass|passwd|passcode)$/i',$column->name))
				$inputField='activePasswordField';
			else
				$inputField='activeTextField';

			if($column->type!=='string' || $column->size===null)
				return "CHtml::{$inputField}(\$model,'{$column->name}')";
			else
			{
				if(($size=$maxLength=$column->size)>60)
					$size=60;
				return "CHtml::{$inputField}(\$model,'{$column->name}',array('size'=>$size,'maxlength'=>$maxLength))";
			}
		}
	}

	public function generateActiveLabel($modelClass,$column)
	{
		return "\$form->labelEx(\$model,'{$column->name}')";
	}

  public function generateActiveField($modelClass, $column)
  {

    /* MY CODE BEGINS HERE */
    foreach (CActiveRecord::model($modelClass)->relations() as $relation)
      if ($relation[0] == CActiveRecord::BELONGS_TO && $column->name == $relation[2]) {
        $relSchema = CActiveRecord::model($relation[1])->tableSchema;
        $descrField = $relSchema->primaryKey;
        foreach ($relSchema->columns as $col)
          if (preg_match('/^(descr_|nome_)/i', $col->name)) {
            $descrField = $col->name;
            break;
          }

        return "\$form->dropDownList(\$model, '{$column->name}',
					CHtml::listData({$relation[1]}::model()->findAll(), '{$relSchema->primaryKey}', '$descrField'),
					array('prompt'=>''));";
      }

    if ($column->type === 'boolean')
      return "\$form->checkBox(\$model,'{$column->name}')";
    else if ($column->dbType == 'date') {
      $dayNamesMin = 'array("Dm", "Sg", "Tr", "Qr", "Qt", "Sx", "Sb")';
      $dayNamesShort = 'array("Dom", "Seg", "Ter", "Qua", "Qui", "Sex", "Sáb")';
      $dayNames = 'array("Domingo", "Segunda-feira", "Terça-feira", "Quarta-feira", "Quinta-feira", "Sexta-feira",
      "Sábado")';

      $monthNamesShort = 'array("Jan", "Fev", "Mar", "Abr", "Mai", "Jun", "Jul", "Ago", "Set", "Out", "Nov", "Dez")';
      $monthNames = 'array("Janeiro", "Fevereiro", "Março", "Abril", "Maio", "Junho", "Julho", "Agosto", "Setembro",
      "Outubro", "Novembro", "Dezembro")';

      return "\$this->widget('zii.widgets.jui.CJuiDatePicker', array('model'=>\$model,
        'attribute'=>'{$column->name}', 'options'=>array('showAnim'=>'fold', 'dateFormat'=>'dd/mm/yy',
        'dayNamesMin' => $dayNamesMin, 'dayNamesShort' => $dayNamesShort, 'dayNames' => $dayNames,
        'monthNamesShort' => $monthNamesShort, 'monthNames' => $monthNames)), true)";
    }
    else if ($column->dbType == 'datetime')
      return "\$form->widget('CMaskedTextField',array('model'=>\$model, 'attribute'=>'{$column->name}', 'mask'=>'99/99/9999 99:99', 'placeholder'=>' ', ), true)";
    else
      /* MY CODE ENDS HERE */

      if (stripos($column->dbType, 'text') !== false)
        return "\$form->textArea(\$model,'{$column->name}',array('rows'=>6, 'cols'=>50))";
      else
      {
        if (preg_match('/^(password|pass|passwd|passcode)$/i', $column->name))
          $inputField = 'passwordField';
        else
          $inputField = 'textField';

        if ($column->type !== 'string' || $column->size === null)
          return "\$form->{$inputField}(\$model,'{$column->name}')";
        else
        {
          if (($size = $maxLength = $column->size) > 60)
            $size = 60;
          return "\$form->{$inputField}(\$model,'{$column->name}',array('size'=>$size,'maxlength'=>$maxLength))";
        }
      }
  }

	public function guessNameColumn($columns)
	{
		foreach($columns as $column)
		{
			if(!strcasecmp($column->name,'name'))
				return $column->name;
		}
		foreach($columns as $column)
		{
			if(!strcasecmp($column->name,'title'))
				return $column->name;
		}
		foreach($columns as $column)
		{
			if($column->isPrimaryKey)
				return $column->name;
		}
		return 'id';
	}
}

<?php
/* @var $this DetailinputinventorytakingsController */
/* @var $model Detailinputinventorytakings */
/* @var $form CActiveForm */
?>

<div class="wide form">

<?php 
	$myscript=<<<EOS
	
	
EOS;
	Yii::app()->clientScript->registerScript('myscript',$myscript, CClientScript::POS_READY);
?>

<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

	<?php 
		echo $form->hiddenField($model, 'iditem');
		echo $form->hiddenField($model, 'idwarehouse');
	?>
	<div class="row">
		<?php echo $form->label($model,'iditem'); ?>
		<?php 
			//echo $form->textField($model,'iditem',array('size'=>21,'maxlength'=>21)); 
			$this->widget('zii.widgets.jui.CJuiAutoComplete', array(
					'name'=>'itemname',
					'sourceUrl'=> Yii::app()->createUrl('LookUp/getItemName'),
					'htmlOptions'=>array(
							'style'=>'height:20px;',
					),
			));
		?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'qty'); ?>
		<?php echo $form->textField($model,'qty'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'idwarehouse'); ?>
		<?php 
			//echo $form->textField($model,'idwarehouse'); 
			$this->widget('zii.widgets.jui.CJuiAutoComplete', array(
					'name'=>'whcode',
					'sourceUrl'=> Yii::app()->createUrl('LookUp/getWareHouse'),
					'htmlOptions'=>array(
							'style'=>'height:20px;',
					),
			));
		?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Search'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->
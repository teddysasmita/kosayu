<?php
/* @var $this ItemsController */
/* @var $model Items */
/* @var $form CActiveForm */
?>

<div class="wide form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

	<div class="row">
		<?php echo $form->label($model,'id'); ?>
		<?php echo $form->textField($model,'id',array('size'=>21,'maxlength'=>21)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'idatetime'); ?>
		<?php echo $form->textField($model,'idatetime'); ?>
	</div>
	
	<div class="row">
		<?php echo $form->label($model,'iditem'); ?>
		<?php 
			$this->widget('zii.widgets.jui.CJuiAutoComplete',array(
				'name'=>'Sellingprices[iditem]',
				'sourceUrl'=>Yii::app()->createUrl('LookUp/getItem2'),
				// additional javascript options for the autocomplete plugin
				'options'=>array(
						'minLength'=>'2',
				),
				'htmlOptions'=>array(
					'id'=>'Sellingprices_iditem',
						'style'=>'height:20px;',
				),
			));
			//echo $form->textField($model,'iditem',array('size'=>12,'maxlength'=>12)); 
		
		?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'normalprice'); ?>
		<?php echo $form->textField($model,'normalprice',array('size'=>60,'maxlength'=>255)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'minprice'); ?>
		<?php echo $form->textField($model,'minprice',array('size'=>50,'maxlength'=>50)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'userlog'); ?>
		<?php echo $form->textField($model,'userlog',array('size'=>21,'maxlength'=>21)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'datetimelog'); ?>
		<?php echo $form->textField($model,'datetimelog',array('size'=>19,'maxlength'=>19)); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Search'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->
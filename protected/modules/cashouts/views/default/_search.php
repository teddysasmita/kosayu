<?php
/* @var $this CashoutsController */
/* @var $model Cashouts */
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
		<?php echo $form->label($model,'regnum'); ?>
		<?php echo $form->textField($model,'regnum'); ?>
	</div>
	
	<div class="row">
		<?php echo $form->label($model,'idexpense'); ?>
		<?php echo $form->textField($model,'idexpense'); ?>
	</div>
	
	<div class="row">
		<?php echo $form->label($model,'idacctcredit'); ?>
		<?php echo $form->textField($model,'idacctcredit'); ?>
	</div>
	
	<div class="row">
		<?php echo $form->label($model,'amount'); ?>
		<?php echo $form->textField($model,'amount'); ?>
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
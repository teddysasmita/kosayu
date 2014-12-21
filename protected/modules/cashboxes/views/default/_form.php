<?php
/* @var $this CashboxesController */
/* @var $model Cashboxes */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'cashboxes-form',
	'enableAjaxValidation'=>true,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<?php 
       echo $form->hiddenField($model,'id');
       echo $form->hiddenField($model,'userlog');
       echo $form->hiddenField($model,'datetimelog');
             
   ?>
	<div class="row">
		<?php echo $form->labelEx($model,'name'); ?>
		<?php echo $form->textField($model,'name',array('size'=>50,'maxlength'=>200)); ?>
		<?php echo $form->error($model,'name'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'accountnum'); ?>
		<?php echo $form->textField($model,'accountnum',array('size'=>30,'maxlength'=>30)); ?>
		<?php echo $form->error($model,'accountnum'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'remark'); ?>
		<?php echo $form->textArea($model,'remark',array('rows'=>5,'cols'=>40)); ?>
		<?php echo $form->error($model,'remark'); ?>
	</div>

	
	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->
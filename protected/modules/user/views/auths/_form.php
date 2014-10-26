<?php
/* @var $this AuthsController */
/* @var $model Auths */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'auths-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'iduser'); ?>
		<?php echo $form->textField($model,'iduser',array('size'=>21,'maxlength'=>21)); ?>
		<?php echo $form->error($model,'iduser'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'idpriv'); ?>
		<?php echo $form->textField($model,'idpriv',array('size'=>6,'maxlength'=>6)); ?>
		<?php echo $form->error($model,'idpriv'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'seclevel'); ?>
		<?php echo $form->textField($model,'seclevel'); ?>
		<?php echo $form->error($model,'seclevel'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->
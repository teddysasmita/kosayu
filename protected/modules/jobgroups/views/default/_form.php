<?php
/* @var $this JobgroupsController */
/* @var $model Jobgroups */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'jobgroups-_form-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// See class documentation of CActiveForm for details on this,
	// you need to use the performAjaxValidation()-method described there.
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); 
		echo $form->hiddenField($model, 'id');
	?>

	<div class="row">
		<?php echo $form->labelEx($model,'name'); ?>
		<?php echo $form->textField($model,'name'); ?>
		<?php echo $form->error($model,'name'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'wager'); ?>
		<?php echo $form->dropDownList($model, 'wager', array('0'=>'Tidak', '1'=>'Tetap', '2'=>'Kehadiran')); ?>
		<?php echo $form->error($model,'wager'); ?>		
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'bonus'); ?>
		<?php echo $form->dropDownList($model, 'bonus', array('0'=>'Tidak', '1'=>'Ya')); ?>
		<?php echo $form->error($model,'bonus'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'thr'); ?>
		<?php echo $form->dropDownList($model, 'bonus', array('0'=>'Tidak', '1'=>'Ya')); ?>
		<?php echo $form->error($model,'thr'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'cashier'); ?>
		<?php echo $form->dropDownList($model, 'bonus', array('0'=>'Tidak', '1'=>'Ya')); ?>
		<?php echo $form->error($model,'cashier'); ?>
	</div>


	<div class="row buttons">
		<?php echo CHtml::submitButton('Submit'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->
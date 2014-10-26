<?php
/* @var $this UsersController */
/* @var $model Users */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'users-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<?php
        echo $form->hiddenField($model, 'id');
        echo $form->hiddenField($model, 'userlog');
        echo $form->hiddenField($model, 'datetimelog');
      ?>
      
      <div class="row">
		<?php echo $form->labelEx($model,'loginname'); ?>
		<?php echo $form->textField($model,'loginname',array('size'=>20,'maxlength'=>20)); ?>
		<?php echo $form->error($model,'loginname'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'fullname'); ?>
		<?php echo $form->textField($model,'fullname',array('size'=>40,'maxlength'=>100)); ?>
		<?php echo $form->error($model,'fullname'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'passkey'); ?>
		<?php echo $form->passwordField($model,'passkey',array('size'=>40,'maxlength'=>100)); ?>
		<?php echo $form->error($model,'passkey'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'active'); ?>
		<?php 
               echo $form->dropDownList($model, 'active', array('0'=>'Tidak Aktif',
                 '1'=>'Aktif'), array('empty'=>'Harap Pilih'));
            ?>
		<?php echo $form->error($model,'active'); ?>
	</div>

	
	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->

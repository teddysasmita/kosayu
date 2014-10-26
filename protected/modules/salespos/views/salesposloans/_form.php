<?php
/* @var $this SalesposloansController */
/* @var $model Salesposloans */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'salesposloans-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>true,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<?php 
		echo $form->hiddenField($model, 'id');
		echo $form->hiddenField($model, 'userlog');
		echo $form->hiddenField($model, 'datetimelog');
		
	?>
	
	<div class="row">
		<?php echo $form->labelEx($model,'idcompany'); ?>
		<?php
			$data=Yii::app()->db->createCommand()->select ('id, name')->from('salesposbanks')
				->queryAll();
			$data=CHtml::listdata($data, 'id', 'name'); 
			echo $form->dropDownList($model,'idcompany', $data, array('empty'=>'Harap Pilih')); 
		?>
		<?php echo $form->error($model,'idcompany'); ?>
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($model,'productname'); ?>
		<?php echo $form->textField($model,'productname',array('size'=>50,'maxlength'=>100)); ?>
		<?php echo $form->error($model,'productname'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->
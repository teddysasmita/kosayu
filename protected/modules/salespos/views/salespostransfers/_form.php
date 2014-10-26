<?php
/* @var $this SalespostransfersController */
/* @var $model Salespostransfers */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'salespostransfers-form',
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
		<?php echo $form->labelEx($model,'holdername'); ?>
		<?php echo $form->textField($model,'holdername',array('size'=>60,'maxlength'=>100)); ?>
		<?php echo $form->error($model,'holdername'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'acctno'); ?>
		<?php echo $form->textField($model,'acctno',array('size'=>50,'maxlength'=>50)); ?>
		<?php echo $form->error($model,'acctno'); ?>
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($model,'idbank'); ?>
		<?php 
			$data=Yii::app()->db->createCommand()->select('id, name')->from('salesposbanks')
				->queryAll();
			$data=CHtml::listData($data, 'id', 'name');
			echo $form->dropDownList($model,'idbank',$data, array('empty'=>'Harap Pilih')); 
		?>
		<?php echo $form->error($model,'idbank'); ?>
	</div>
	
	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->
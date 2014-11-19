<?php
/* @var $this SalesposcardsController */
/* @var $model Salesposcards */
/* @var $form CActiveForm */
?>

<div class="form">

<?php 

	$bankscript=<<<EOS
	$('#bankname').change(
		function() {
			$.getJSON('index.php?r=LookUp/getBankID', {'name':$('#bankname').val()},
				function(data){
					$('#Salesposcards_idbank').val(data);
				}
			);
		}
	);
EOS;
	Yii::app()->clientScript->registerScript('bankscript', $bankscript, CClientScript::POS_READY);

?>


<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'salesposcards-form',
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
		echo $form->hiddenField($model, 'idbank');
	?>

	<div class="row">
		<?php echo $form->labelEx($model,'name'); ?>
		<?php echo $form->textField($model,'name',array('size'=>50,'maxlength'=>100)); ?>
		<?php echo $form->error($model,'name'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'surchargeamount'); ?>
		<?php echo $form->textField($model,'surchargeamount',array('size'=>50)); ?>
		<?php echo $form->error($model,'surchargeamount'); ?>
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($model,'surchargepct'); ?>
		<?php echo $form->textField($model,'surchargepct',array('size'=>50)); ?>
		<?php echo $form->error($model,'surchargepct'); ?>
	</div>
	
	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->
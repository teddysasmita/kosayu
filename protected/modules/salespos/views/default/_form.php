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
		<?php echo $form->labelEx($model,'idbank'); ?>
		<?php 
			$this->widget('zii.widgets.jui.CJuiAutoComplete', array(
				'name'=>'bankname',
				'sourceUrl'=> Yii::app()->createUrl('LookUp/getBankName'),
				'htmlOptions'=>array(
					'style'=>'height:20px;',
					'id'=>'bankname',
				),
				'value'=>lookup::BankNameFromID($model->idbank),
			));
		?>
		<?php echo $form->error($model,'idbank'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'kind'); ?>
		<?php echo $form->dropDownList($model,'kind',array('KK'=>'Kartu Kredit',
			'KD'=>'Kartu Debit'), array('empty'=>'Harap Pilih'));
		?>
		<?php echo $form->error($model,'kind'); ?>
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($model,'company'); ?>
		<?php echo $form->dropDownList($model,'company',array('Visa'=>'Visa',
			'Master Card'=>'Master Card', 'Alto'=>'Alto'), array('empty'=>'Harap Pilih'));
		?>
		<?php echo $form->error($model,'company'); ?>
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
<?php
/* @var $this FinancepaymentsController */
/* @var $model Financepayments */
/* @var $form CActiveForm */
?>

<div class="form">

<?php 
	$fpscript=<<<EOS
	$('#Financepayments_method').change(function(event){
		var method=$('#Financepayments_method');
		var remark=$('#Financepayments_remark');
		if (method[0].value == '1') {	
			remark[0].value='Tunai';
		} else if (method[0].value == '2') {	
			remark[0].value='';
			alert('Masukkan nomor rekening, nama pemegang rekening dan nama bank di Catatan');	
		} else if (method[0].value == '3') {
			remark[0].value='';
			alert('Masukkan nomor cek dan nama bank penerbit di Catatan');
		};
	});
EOS;
	Yii::app()->clientScript->registerScript('fpscript', $fpscript, 
			CClientScript::POS_READY );

	$form=$this->beginWidget('CActiveForm', array(
	'id'=>'financepayments-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>true,
	)); 
?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<?php 
		echo $form->hiddenField($model, 'id');
		echo $form->hiddenField($model, 'userlog');
		echo $form->hiddenField($model, 'datetimelog');
		echo $form->hiddenField($model, 'receipient');
		echo $form->hiddenField($model, 'amount');
		echo $form->hiddenField($model, 'idatetime');
	?>

	<div class="row">
		<?php echo $form->labelEx($model,'idatetime'); ?>
		<?php echo CHtml::label($model->idatetime, false); ?>
		<?php echo $form->error($model,'idatetime'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'receipient'); ?>
		<?php echo CHtml::label($model->receipient, false); ?>
		<?php echo $form->error($model,'receipient'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'method'); ?>
		<?php echo $form->dropDownList($model, 'method', 
				array('0'=>'Belum dilaksanakan', '1'=>'Tunai', '2'=>'Transfer',
					'3'=>'Cek/Giro')); ?>
		<?php echo $form->error($model,'method'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'duedate'); ?>
		<?php 
			$this->widget('zii.widgets.jui.CJuiDatePicker',array(
				'name'=>'Financespayments[duedate]',
                     // additional javascript options for the date picker plugin
				'options'=>array(
					'showAnim'=>'fold',
					'dateFormat'=>'yy/mm/dd',
					'defaultdate'=>$model->idatetime
                 ),
                 'htmlOptions'=>array(
                    'style'=>'height:20px;',
                 ),
                 'value'=>$model->idatetime,
               )); 
		?>
		<?php echo $form->error($model,'duedate'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'amount'); ?>
		<?php echo CHtml::label(number_format($model->amount), false,
			array('class'=>'money')); ?>
		<?php echo $form->error($model,'amount'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'remark');?>
		<?php echo $form->textArea($model,'remark',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'remark'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->
			
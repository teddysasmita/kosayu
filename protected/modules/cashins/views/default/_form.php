<?php
/* @var $this CashinsController */
/* @var $model Cashins */
/* @var $form CActiveForm */
?>

<div class="form">
    
<?php

$cashinScript=<<<EOS
	$('#Cashins_idcash').change(
		function() {
			$.getJSON('index.php?r=LookUp/getCashboxName',{ id: $('#Cashins_idcash').val() },
               	function(data) {
				 	$('#cashname').html(data);
               })
	});

	$('#Cashins_idacctcredit').change(
		function() {
			$.getJSON('index.php?r=LookUp/getAccountName',{ id: $('#Cashins_idacctcredit').val() },
               	function(data) {
				 	$('#acctcreditname').html(data);
               })
	});
EOS;
Yii::app()->clientScript->registerScript('cashinscript', $cashinScript, CClientScript::POS_READY);

?>

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'cashins-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>true,
        'htmlOptions'=>array('enctype'=>'multipart/form-data'),
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<?php
             echo $form->hiddenField($model, 'id');
             echo $form->hiddenField($model, 'userlog');
             echo $form->hiddenField($model, 'datetimelog');
             echo $form->hiddenField($model, 'regnum');
             //echo $form->hiddenField($model, 'iditem');
              
          ?>

	<div class="row">
		<?php echo $form->labelEx($model,'idatetime'); ?>
            <?php
               $this->widget('zii.widgets.jui.CJuiDatePicker',array(
                  'name'=>'Cashins[idatetime]',
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
		<?php echo $form->error($model,'idatetime'); ?>
	</div>
	
	
	<div class="row">
		<?php echo $form->labelEx($model,'idcash'); ?>
		<?php 
			$this->widget('zii.widgets.jui.CJuiAutoComplete', array(
				'name'=>'Cashins[idcash]',
				'sourceUrl'=> Yii::app()->createUrl('LookUp/getCashboxes'),
				'htmlOptions'=>array(
						'style'=>'height:20px;',
				),
				'value'=>$model->idcash,
			));
		?>
		<?php echo $form->error($model,'idcash'); ?>
	</div>
	
	<div class="row">
		<?php echo CHtml::label('', false);
			echo CHtml::tag('span', array('id'=>'cashname', 'class'=>'money'), 
				lookup::CashboxNameFromID($model->idcash)); 
		?>
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($model,'amount'); ?>
		<?php 
         	echo $form->textField($model, 'amount' );
     	?>
		<?php echo $form->error($model,'amount'); ?>
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($model,'idacctcredit'); ?>
		<?php 
			$this->widget('zii.widgets.jui.CJuiAutoComplete', array(
				'name'=>'Cashins[idacctcredit]',
				'sourceUrl'=> Yii::app()->createUrl('LookUp/getCashInCredit'),
				'htmlOptions'=>array(
						'style'=>'height:20px;',
				),
				'value'=>$model->idacctcredit,
			));
		?>
		<?php echo $form->error($model,'idacctcredit'); ?>
	</div>
	
	<div class="row">
		<?php echo CHtml::label('', false);
			echo CHtml::tag('span', array('id'=>'acctcreditname', 'class'=>'error'), 
				lookup::AccountNameFromID($model->idacctcredit)); 
		?>
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($model,'remark'); ?>
		<?php 
         echo $form->textArea($model, 'remark', array('cols'=>'50', 'rows'=>4 ));
     	?>
		<?php echo $form->error($model,'remark'); ?>
	</div>	
	
	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->

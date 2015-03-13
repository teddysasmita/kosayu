<?php
/* @var $this CashoutsController */
/* @var $model Cashouts */
/* @var $form CActiveForm */
?>

<div class="form">
    
<?php

$cashoutScript=<<<EOS
	$('#Cashouts_idexpense').change(
		function() {
			$.getJSON('index.php?r=LookUp/getExpenseName',{ id: $('#Cashouts_idexpense').val() },
               	function(data) {
				 	$('#expensename').html(data);
               })
	});

	$('#Cashouts_idacctcredit').change(
		function() {
			$.getJSON('index.php?r=LookUp/getCashboxName',{ id: $('#Cashouts_idacctcredit').val() },
               	function(data) {
				 	$('#acctcreditname').html(data);
               })
	});
EOS;
Yii::app()->clientScript->registerScript('cashoutscript', $cashoutScript, CClientScript::POS_READY);

?>

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'cashouts-form',
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
                  'name'=>'Cashouts[idatetime]',
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
		<?php echo $form->labelEx($model,'idexpense'); ?>
		<?php 
			$this->widget('zii.widgets.jui.CJuiAutoComplete', array(
				'name'=>'Cashouts[idexpense]',
				'sourceUrl'=> Yii::app()->createUrl('LookUp/getExpenses'),
				'htmlOptions'=>array(
						'style'=>'height:20px;',
				),
				'value'=>$model->idexpense,
			));
		?>
		<?php echo $form->error($model,'idexpense'); ?>
	</div>
	
	<div class="row">
		<?php echo CHtml::label('', false);
			echo CHtml::tag('span', array('id'=>'expensename', 'class'=>'money'), 
				lookup::ExpenseNameFromID($model->idexpense)); 
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
		<?php echo $form->labelEx($model,'periodcount'); ?>
		<?php 
         	echo $form->textField($model, 'periodcount' );
     	?>
		<?php echo $form->error($model,'periodcount'); ?>
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($model,'idacctcredit'); ?>
		<?php 
			$this->widget('zii.widgets.jui.CJuiAutoComplete', array(
				'name'=>'Cashouts[idacctcredit]',
				'sourceUrl'=> Yii::app()->createUrl('LookUp/getCashboxes'),
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
				lookup::CashboxNameFromID($model->idacctcredit)); 
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

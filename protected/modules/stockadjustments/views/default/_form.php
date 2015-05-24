<?php
/* @var $this ItemsController */
/* @var $model Items */
/* @var $form CActiveForm */
?>

<div class="form">
    
<?php

$itemScript=<<<EOS
	$('#Stockadjustments_itembatch').change(
		function() {
			$.getJSON('index.php?r=LookUp/getItemFromBatchcode',{ batchcode: $('#Stockadjustments_iditem').val() },
               	function(data) {
				 	$('#iditem').html(data.name);
               })
	});
EOS;
Yii::app()->clientScript->registerScript('itemscript', $itemScript, CClientScript::POS_READY);

?>

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'stockadjustments-form',
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
             echo $form->hiddenField($model, 'oldamount');
             echo $form->hiddenField($model, 'regnum');
             //echo $form->hiddenField($model, 'iditem');
              
          ?>

	<div class="row">
		<?php echo $form->labelEx($model,'idatetime'); ?>
            <?php
               $this->widget('zii.widgets.jui.CJuiDatePicker',array(
                  'name'=>'Stockadjustments[idatetime]',
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
		<?php echo $form->labelEx($model,'itembatch'); ?>
		<?php 
			$this->widget('zii.widgets.jui.CJuiAutoComplete', array(
				'name'=>'Stockadjustments[itembatch]',
				'sourceUrl'=> Yii::app()->createUrl('LookUp/getBatchcode'),
				'htmlOptions'=>array(
						'style'=>'height:20px;',
				),
				'value'=>$model->itembatch,
			));
		?>
		<?php echo $form->error($model,'itembatch'); ?>
	</div>
	
	<div class="row">
		<?php echo CHtml::label('Nama Barang', false); ?>
		<?php 
         	echo CHtml::tag('span', array('id'=>'iditem', 'class'=>'money'), 
         		lookup::ItemNameFromItemCode($model->itembatch) );
     	?>
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($model,'oldamount'); ?>
		<?php 
         	echo CHtml::tag('span', array('id'=>'oldamount'), number_format($model->oldamount) );
     	?>
		<?php echo $form->error($model,'oldamount'); ?>
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($model,'newamount'); ?>
		<?php 
         echo $form->textField($model, 'newamount' );
     	?>
		<?php echo $form->error($model,'newamount'); ?>
	</div>	
	
	<div class="row">
		<?php echo $form->labelEx($model,'remark'); ?>
		<?php 
         echo <?php echo $form->textArea($model,'remark',array('rows'=>6, 'cols'=>50)); ?>
     	?>
		<?php echo $form->error($model,'remark'); ?>
	</div>	
	
	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->

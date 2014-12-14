<?php
/* @var $this ItemsController */
/* @var $model Items */
/* @var $form CActiveForm */
?>

<div class="form">
    
<?php

$itemScript=<<<EOS
	$('#Sellingprices_batchcode').change(
		function() {
			$.getJSON('index.php?r=LookUp/getItemFromBatchcode',{ batchcode: $('#Sellingprices_batchcode').val() },
               	function(data) {
				 	$('#batchcode').html(data.name);
                  $('#Sellingprices_batchcode').val(data.iditem);
               })
	});
EOS;
Yii::app()->clientScript->registerScript('itemscript', $itemScript, CClientScript::POS_READY);

?>

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'sellingprices-form',
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
             //echo $form->hiddenField($model, 'iditem');
              
          ?>

	<div class="row">
		<?php echo $form->labelEx($model,'idatetime'); ?>
            <?php
               $this->widget('zii.widgets.jui.CJuiDatePicker',array(
                  'name'=>'Sellingprices[idatetime]',
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
		<?php echo $form->labelEx($model,'iditem'); ?>
		<?php 
			$this->widget('zii.widgets.jui.CJuiAutoComplete', array(
				'name'=>'Sellingprices[iditem]',
				'sourceUrl'=> Yii::app()->createUrl('LookUp/getBatchcode'),
				'htmlOptions'=>array(
						'style'=>'height:20px;',
				),
			));
		?>
		<?php echo $form->error($model,'iditem'); ?>
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($model,'batchcode'); ?>
		<?php 
         	echo CHtml::tag('span', array('id'=>'batchcode', 'class'=>'money'), 
         		lookup::ItemNameFromItemCode($model->batchcode) );
     	?>
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($model,'normalprice'); ?>
		<?php 
         	echo $form->textField($model, 'normalprice' );
     	?>
		<?php echo $form->error($model,'normalprice'); ?>
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($model,'minprice'); ?>
		<?php 
         echo $form->textField($model, 'minprice' );
     	?>
		<?php echo $form->error($model,'minprice'); ?>
	</div>	
	
	<div class="row">
		<?php echo $form->labelEx($model,'approvalby'); ?>
		<?php 
         echo $form->dropDownList($model, 'approvalby', array('Pak Made'=>'Pak Made', 'Bu Anita'=>'Bu Anita',
         		'Pak Herry'=>'Pak Herry', 'Cik San'=>'Cik San'
         ),
			array('empty'=>'Harap Pilih') );
     	?>
		<?php echo $form->error($model,'approvalby'); ?>
	</div>	
	
	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->

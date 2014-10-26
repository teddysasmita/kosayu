<?php
/* @var $this InventorytakingsController */
/* @var $model Inventorytakings */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'inventorytakings-form',
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
		<?php echo $form->labelEx($model,'operationlabel'); ?>
		<?php echo $form->textField($model,'operationlabel',array('size'=>40,'maxlength'=>50)); ?>
		<?php echo $form->error($model,'operationlabel'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'idatetime'); ?>
		<?php 
			$this->widget('zii.widgets.jui.CJuiDatePicker',array(
				'name'=>'Inventorytakings[idatetime]',
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
		<?php echo $form->labelEx($model,'status'); ?>
		<?php echo $form->dropDownList($model, 'status', 
				array('0'=>'Tidak Aktif', '1'=>'Aktif')); ?>
		<?php echo $form->error($model,'status'); ?>
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($model,'remark'); ?>
		<?php echo $form->textArea($model,'remark',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'remark'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->
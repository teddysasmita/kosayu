<?php
/* @var $this EmployeesController */
/* @var $model Employees */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'employees-_form-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// See class documentation of CActiveForm for details on this,
	// you need to use the performAjaxValidation()-method described there.
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); 
		echo $form->hiddenField($model, 'id');
	?>

	<div class="row">
		<?php echo $form->labelEx($model,'firstname'); ?>
		<?php echo $form->textField($model,'firstname'); ?>
		<?php echo $form->error($model,'firstname'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'lastname'); ?>
		<?php echo $form->textField($model,'lastname'); ?>
		<?php echo $form->error($model,'lastname'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'address'); ?>
		<?php echo $form->textField($model,'address'); ?>
		<?php echo $form->error($model,'address'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'idnumber'); ?>
		<?php echo $form->textField($model,'idnumber'); ?>
		<?php echo $form->error($model,'idnumber'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'idjobgroup'); ?>
		<?php 
			$jobdata = Yii::app()->db->createCommand()
				->select('id, name')->from('jobgroups')
				->queryAll();
			$jobdata = CHtml::listData($jobdata, 'id', 'name');
			echo $form->dropDownList(model,'idjobgroup',
				$jobdata, array('empty'=>'Harap Pilih')); 
		?>
		<?php echo $form->error($model,'idjobgroup'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'startdate'); ?>
		<?php $this->widget('zii.widgets.jui.CJuiDatePicker',array(
                  'name'=>'startdate',
                     // additional javascript options for the date picker plugin
                  'options'=>array(
                     'showAnim'=>'fold',
                     'dateFormat'=>'yy/mm/dd',
                     'defaultdate'=>idmaker::getDateTime()
                  ),
                  'htmlOptions'=>array(
                     'style'=>'height:20px;',
                  ),
               )); 
		 ?>
		<?php echo $form->error($model,'startdate'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'phone'); ?>
		<?php echo $form->textField($model,'phone'); ?>
		<?php echo $form->error($model,'phone'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'email'); ?>
		<?php echo $form->textField($model,'email'); ?>
		<?php echo $form->error($model,'email'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'enddate'); ?>
		<?php $this->widget('zii.widgets.jui.CJuiDatePicker',array(
                  'name'=>'enddate',
                     // additional javascript options for the date picker plugin
                  'options'=>array(
                     'showAnim'=>'fold',
                     'dateFormat'=>'yy/mm/dd',
                     'defaultdate'=>idmaker::getDateTime()
                  ),
                  'htmlOptions'=>array(
                     'style'=>'height:20px;',
                  ),
               )); 
		 ?>
		<?php echo $form->error($model,'enddate'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'active'); ?>
		<?php echo $form->dropDownList($model,'active', 
				array('Ya'=>'1', 'Tidak'=>'0')); 
		?>
		<?php echo $form->error($model,'active'); ?>
	</div>


	<div class="row buttons">
		<?php echo CHtml::submitButton('Submit'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->
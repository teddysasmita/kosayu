   <?php
/* @var $this AuthItemController */
/* @var $model AuthItem */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'auth-item-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>
      <?php
         echo $form->hiddenfield($model, 'userid', array('value'=>$userid));
      ?>
      
	<div class="row">
		<?php echo $form->labelEx($model,'Select Task'); ?>
		<?php 
			$this->widget('zii.widgets.jui.CJuiAutoComplete',array(
				'name'=>'AuthAssignment[itemname]',
				'sourceUrl'=>Yii::app()->createUrl('LookUp/getUserTask'),
				// additional javascript options for the autocomplete plugin
				'options'=>array(
						'minLength'=>'2',
				),
				'htmlOptions'=>array(
						'style'=>'height:20px;',
				),
			));
		?>
		<?php echo $form->error($model,'itemname'); ?>
	</div>

      
	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->
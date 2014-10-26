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
         echo $form->hiddenfield($model, 'parent', array('value'=>$parent));
      ?>
      
	<div class="row">
		<?php echo $form->labelEx($model,'Select Operation'); ?>
        <?php 
			$this->widget('zii.widgets.jui.CJuiAutoComplete',array(
				'name'=>'authitemchild[child]',
				'sourceUrl'=>Yii::app()->createUrl('LookUp/getUserOperation'),
				// additional javascript options for the autocomplete plugin
				'options'=>array(
						'minLength'=>'2',
				),
				'htmlOptions'=>array(
						'style'=>'height:20px;',
				),
			));
		?>
		<?php echo $form->error($model,'child'); ?>
	</div>

      
	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->
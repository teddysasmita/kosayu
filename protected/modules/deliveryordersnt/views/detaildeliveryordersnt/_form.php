<?php
/* @var $this DetaildeliveryordersntController */
/* @var $model Detaildeliveryordersnt */
/* @var $form CActiveForm */
?>

<div class="form">
   
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'detaildeliveryordersnt-form',
	'enableAjaxValidation'=>true,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<?php
         echo $form->hiddenField($model,'iddetail');
         echo $form->hiddenField($model,'id');
         echo $form->hiddenField($model,'userlog');
         echo $form->hiddenField($model,'datetimelog');
      ?>
      
	<div class="row">
		<?php echo $form->labelEx($model,'itemname'); ?>
		<?php 
			$this->widget("zii.widgets.jui.CJuiAutoComplete", array(
				'name'=>'Detaildeliveryordersnt[itemname]',
				'sourceUrl'=>Yii::app()->createUrl('LookUp/getItemName'),
				'value'=>$model->itemname,
				'htmlOptions'=>array('size'=>50)
			));
        ?>    
		<?php echo $form->error($model,'itemname'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'qty'); ?>
		<?php echo $form->textField($model,'qty'); ?>
		<?php echo $form->error($model,'qty'); ?>
	</div>


	<div class="row buttons">
		<?php 
            echo CHtml::submitButton($mode); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->

<?php
/* @var $this DetailpaysalariesController */
/* @var $model Detailpaysalaries */
/* @var $form CActiveForm */
?> 

<div class="form">

<?php
 $form=$this->beginWidget('CActiveForm', array(
	'id'=>'detailpaysalaries-form',
	'enableAjaxValidation'=>true,
   ));
 ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>
        
        <?php 
         echo $form->hiddenField($model,'iddetail');
         echo $form->hiddenField($model,'id');
         echo $form->hiddenField($model,'userlog');
         echo $form->hiddenField($model,'datetimelog');
         echo CHtml::hiddenField('command');
        ?>

	<div class="row">
		<?php echo $form->labelEx($model,'name'); ?>
		<?php echo CHtml::tag('span', array('id'=>'componentname', 'class'=>money),
				$model->name ); ?>
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($model,'amount'); ?>
		<?php echo $form->textField($model,'amount'); ?>
		<?php echo $form->error($model,'amount'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($mode); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->
<?php
/* @var $this ItemtipgroupsController */
/* @var $model Itemtipgroups */
/* @var $form CActiveForm */
?>

<div class="wide form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

	<div class="row">
		<?php echo $form->label($model,'id'); ?>
		<?php echo $form->textField($model,'id',array('size'=>21,'maxlength'=>21)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'name'); ?>
		<?php echo $form->textField($model,'name',array('size'=>12,'maxlength'=>50)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'pct'); ?>
		<?php echo $form->textField($model,'pct',array('size'=>3,'maxlength'=>3)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'userlog'); ?>
		<?php echo $form->textField($model,'userlog',array('size'=>21,'maxlength'=>21)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'datetimelog'); ?>
		<?php echo $form->textField($model,'datetimelog',array('size'=>19,'maxlength'=>19)); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Search'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->
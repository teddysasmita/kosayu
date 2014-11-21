<?php
/* @var $this TippaymentsController */
/* @var $model Tippayments */
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
		<?php echo $form->label($model,'regnum'); ?>
		<?php echo $form->textField($model,'regnum',array('size'=>12,'maxlength'=>12)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'idatetime'); ?>
		<?php echo $form->textField($model,'idatetime',array('size'=>19,'maxlength'=>19)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'idsticker'); ?>
		<?php echo $form->textField($model,'idsticker',array('size'=>50,'maxlength'=>50)); ?>
	</div>
   
    <div class="row">
		<?php echo $form->label($model,'ddatetime'); ?>
		<?php echo $form->textField($model,'ddatetime',array('size'=>19,'maxlength'=>19)); ?>
	</div>
   
   <div class="row">
		<?php echo $form->label($model,'receiver'); ?>
		<?php echo $form->textField($model,'receiver',array('size'=>40,'maxlength'=>100)); ?>
	</div>
   
      <div class="row">
		<?php echo $form->label($model,'idwarehouse'); ?>
		<?php echo $form->textField($model,'idwarehouse',array('size'=>21,'maxlength'=>21)); ?>
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
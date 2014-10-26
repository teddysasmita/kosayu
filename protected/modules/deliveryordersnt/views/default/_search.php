<?php
/* @var $this DeliveryordersntController */
/* @var $model Deliveryordersnt */
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
		<?php echo $form->label($model,'idatetime'); ?>
		<?php echo $form->textField($model,'idatetime',array('size'=>19,'maxlength'=>19)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'receivername'); ?>
		<?php echo $form->textField($model,'receivername',array('size'=>50,'maxlength'=>100)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'receiveraddress'); ?>
		<?php echo $form->textField($model,'receiveraddress', array('size'=>50,'maxlength'=>200)); ?>
	</div>
	
	<div class="row">
		<?php echo $form->label($model,'receiverphone'); ?>
		<?php echo $form->textField($model,'receiverphone', array('size'=>50,'maxlength'=>50)); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Search'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->
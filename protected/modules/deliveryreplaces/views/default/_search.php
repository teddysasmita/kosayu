<?php
/* @var $this PurchasesordersController */
/* @var $model Purchasesorders */
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
		<?php echo $form->label($model,'deliverynum'); ?>
		<?php echo $form->textField($model,'deliverynum',array('size'=>12,'maxlength'=>12)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'receivername'); ?>
		<?php echo $form->textField($model,'receivername'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'receiveraddress'); ?>
		<?php echo $form->textField($model,'receiveraddress'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'receiverphone'); ?>
		<?php echo $form->textField($model,'receiverphone'); ?>
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
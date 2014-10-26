<?php
/* @var $this ItemtransfersController */
/* @var $model Itemtransfers */
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
		<?php echo $form->label($model,'idwhsource'); ?>
		<?php 
			$data=Yii::app()->db->createCommand()->select('id, code')->from('warehouses')->queryAll();
			$data=CHtml::listData($data, 'id', 'code');
			echo $form->dropDownList($model,'idwhsource',$data, array('empty'=>'Harap Pilih')); 
		?>
	</div>
	
	<div class="row">
		<?php echo $form->label($model,'idwhdest'); ?>
		<?php 
			$data=Yii::app()->db->createCommand()->select('id, code')->from('warehouses')->queryAll();
			$data=CHtml::listData($data, 'id', 'code');
			echo $form->dropDownList($model,'idwhdest',$data, array('empty'=>'Harap Pilih')); 
		?>
	</div>


	<div class="row">
		<?php echo $form->label($model,'userlog'); ?>
		<?php 
			$data=Yii::app()->authdb->createCommand()->select('id, loginname')->from('users')->queryAll();
			$data=CHtml::listData($data, 'id', 'loginname');
			echo $form->dropDownList($model,'userlog',$data, array('empty'=>'Harap Pilih')); 
		?>
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
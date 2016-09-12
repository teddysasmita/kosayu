<?php
/* @var $this StickertoguidesController */
/* @var $data Stickertoguides */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('regnum')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->regnum), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('stickernum')); ?>:</b>
	<?php echo CHtml::encode($data->stickernum); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('stickerdate')); ?>:</b>
	<?php echo CHtml::encode($data->stickerdate); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('userlog')); ?>:</b>
	<?php echo CHtml::encode($data->userlog); ?>
	<br />

	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('datetimelog')); ?>:</b>
	<?php echo CHtml::encode($data->datetimelog); ?>
	<br />

	*/ ?>

</div>
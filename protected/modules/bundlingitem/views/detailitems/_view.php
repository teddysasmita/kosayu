<?php
/* @var $this DetailitemsController */
/* @var $data Detailitems */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('iddetail')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->iddetail), array('view', 'id'=>$data->iddetail)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::encode($data->id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('iditem')); ?>:</b>
	<?php echo CHtml::encode($data->iditem); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('qty')); ?>:</b>
	<?php echo CHtml::encode($data->qty); ?>
	<br />


</div>
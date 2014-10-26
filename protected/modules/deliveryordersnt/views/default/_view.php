<?php
/* @var $this DeliveryordersntController */
/* @var $data Deliveryordersnt */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('regnum')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->regnum), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('idatetime')); ?>:</b>
	<?php echo CHtml::encode($data->idatetime); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('receivername')); ?>:</b>
	<?php echo CHtml::encode($data->receivername); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('drivername')); ?>:</b>
	<?php echo CHtml::encode($data->drivername); ?>
	<br />
	
	<b><?php echo CHtml::encode($data->getAttributeLabel('vehicleinfo')); ?>:</b>
	<?php echo CHtml::encode($data->vehicleinfo); ?>
	<br />

</div>
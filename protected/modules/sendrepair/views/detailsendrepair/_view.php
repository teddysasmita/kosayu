<?php
/* @var $this DetailsendrepairsController */
/* @var $data Detailsendrepairs */
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
	<?php echo CHtml::encode(number_format($data->qty)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('buyprice')); ?>:</b>
	<?php echo CHtml::encode(number_format($data->buyprice)); ?>
	<br />
	
	<b><?php echo CHtml::encode($data->getAttributeLabel('idwarehouse')); ?>:</b>
	<?php echo CHtml::encode(lookup::WarehouseNameFromWarehouseID($data->idwarehouse)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('remark')); ?>:</b>
	<?php echo CHtml::encode($data->remark); ?>
	<br />

	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('userlog')); ?>:</b>
	<?php echo CHtml::encode($data->userlog); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('datetimelog')); ?>:</b>
	<?php echo CHtml::encode($data->datetimelog); ?>
	<br />

	*/ ?>

</div>
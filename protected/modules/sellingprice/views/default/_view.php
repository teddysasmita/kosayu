<?php
/* @var $this SellingpricesController */
/* @var $data Sellingprices */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('regnum')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->regnum), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('idatetime')); ?>:</b>
	<?php echo CHtml::encode($data->idatetime); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('iditem')); ?>:</b>
	<?php echo CHtml::encode(lookup::ItemNameFromItemID($data->iditem)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('normalprice')); ?>:</b>
	<?php echo CHtml::encode(number_format($data->normalprice)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('minprice')); ?>:</b>
	<?php echo CHtml::encode(number_format($data->minprice)); ?>
	<br />
	
	<b><?php echo CHtml::encode($data->getAttributeLabel('userlog')); ?>:</b>
	<?php echo CHtml::encode(lookup::UserNameFromUserID($data->userlog)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('datetimelog')); ?>:</b>
	<?php echo CHtml::encode($data->datetimelog); ?>
	<br />

</div>
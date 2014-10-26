<?php
/* @var $this InventorycostingsController */
/* @var $data Inventorycostings */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('regnum')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->regnum), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('idatetime')); ?>:</b>
	<?php echo CHtml::encode($data->idatetime); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('idinventorytaking')); ?>:</b>
	<?php echo CHtml::encode(lookup::InventoryTakingLabelFromID($data->idinventorytaking)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('iditem')); ?>:</b>
	<?php echo CHtml::encode(lookup::ItemNameFromItemID($data->iditem)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('cost')); ?>:</b>
	<?php echo CHtml::encode(number_format($data->cost)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('userlog')); ?>:</b>
	<?php echo CHtml::encode(lookup::UserNameFromUserID($data->userlog)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('datetimelog')); ?>:</b>
	<?php echo CHtml::encode($data->datetimelog); ?>
	<br />


</div>
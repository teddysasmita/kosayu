<?php
/* @var $this ItembatchController */
/* @var $data Itembatch */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('batchcode')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->batchcode), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('iditem')); ?>:</b>
	<?php echo CHtml::encode(lookup::ItemNameFromItemID($data->iditem)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('buyprice')); ?>:</b>
	<?php echo CHtml::encode($data->buyprice); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('sellprice')); ?>:</b>
	<?php echo CHtml::encode($data->sellprice); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('userlog')); ?>:</b>
	<?php echo CHtml::encode(lookup::UserNameFromUserID($data->userlog)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('datetimelog')); ?>:</b>
	<?php echo CHtml::encode($data->datetimelog); ?>
	<br />

</div>
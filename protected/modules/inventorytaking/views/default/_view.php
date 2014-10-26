<?php
/* @var $this InventorytakingsController */
/* @var $data Inventorytakings */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('operationlabel')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->operationlabel), 
			array('view', 'id'=>$data->id)); ?>
	<br />
	
	<b><?php echo CHtml::encode($data->getAttributeLabel('remark')); ?>:</b>
	<?php echo CHtml::encode($data->remark); ?>
	<br />
	
	<b><?php echo CHtml::encode($data->getAttributeLabel('status')); ?>:</b>
	<?php echo CHtml::encode(lookup::activeStatus($data->status)); ?>
	<br />
		

	<b><?php echo CHtml::encode($data->getAttributeLabel('userlog')); ?>:</b>
	<?php echo CHtml::encode(lookup::UserNameFromUserID($data->userlog)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('datetimelog')); ?>:</b>
	<?php echo CHtml::encode($data->datetimelog); ?>
	<br />


</div>
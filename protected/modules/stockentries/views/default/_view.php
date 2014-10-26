<?php
/* @var $this PurchasesordersController */
/* @var $data Purchasesorders */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('regnum')); ?>:</b>
      <?php echo CHtml::link(CHtml::encode($data->regnum), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('idatetime')); ?>:</b>
	<?php echo CHtml::encode($data->idatetime); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('transid')); ?>:</b>
	<?php echo CHtml::encode($data->transid); ?>
	<br />
	
	<b><?php echo CHtml::encode($data->getAttributeLabel('transname')); ?>:</b>
	<?php echo CHtml::encode(Action::getTransName($data->transname)); ?>
	<br />
	
	<b><?php echo CHtml::encode($data->getAttributeLabel('transinfo')); ?>:</b>
	<?php echo CHtml::encode($data->transname); ?>
	<br />
	  
      <b><?php echo CHtml::encode($data->getAttributeLabel('idwarehouse')); ?>:</b>
	<?php echo CHtml::encode(lookup::WarehouseNameFromWarehouseID($data->idwarehouse)); ?>
	<br />

      <b><?php echo CHtml::encode($data->getAttributeLabel('userlog')); ?>:</b>
	<?php echo CHtml::encode(lookup::UserNameFromUserID($data->userlog)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('datetimelog')); ?>:</b>
	<?php echo CHtml::encode($data->datetimelog); ?>
	<br />
      
	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('status')); ?>:</b>
	<?php echo CHtml::encode($data->status); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('remark')); ?>:</b>
	<?php echo CHtml::encode($data->remark); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('userlog')); ?>:</b>
	<?php echo CHtml::encode($data->userlog); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('datetimelog')); ?>:</b>
	<?php echo CHtml::encode($data->datetimelog); ?>
	<br />

	*/ ?>

</div>
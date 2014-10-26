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

	<b><?php echo CHtml::encode($data->getAttributeLabel('invnum')); ?>:</b>
	<?php echo CHtml::encode($data->invnum); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('receivername')); ?>:</b>
	<?php echo CHtml::encode($data->receivername); ?>
	<br />
	
	<b><?php echo CHtml::encode($data->getAttributeLabel('receiveraddress')); ?>:</b>
	<?php echo CHtml::encode($data->receiveraddress); ?>
	<br />
	
	<b><?php echo CHtml::encode($data->getAttributeLabel('receiverphone')); ?>:</b>
	<?php echo CHtml::encode($data->receiverphone); ?>
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
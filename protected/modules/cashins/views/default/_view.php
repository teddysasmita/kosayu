<?php
/* @var $this CashinsController */
/* @var $data Cashins */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('regnum')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->regnum), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('idatetime')); ?>:</b>
	<?php echo CHtml::encode($data->idatetime); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('idacctcredit')); ?>:</b>
	<?php echo CHtml::encode(lookup::CashboxNameFromID($data->idacctcredit)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('idcash')); ?>:</b>
	<?php echo CHtml::encode(lookup::ExpenseNameFromID($data->idcash)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('amount')); ?>:</b>
	<?php echo CHtml::encode(number_format($data->amount)); ?>
	<br />
	
	<b><?php echo CHtml::encode($data->getAttributeLabel('userlog')); ?>:</b>
	<?php echo CHtml::encode(lookup::UserNameFromUserID($data->userlog)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('datetimelog')); ?>:</b>
	<?php echo CHtml::encode($data->datetimelog); ?>
	<br />

</div>
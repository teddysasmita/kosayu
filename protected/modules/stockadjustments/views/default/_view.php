<?php
/* @var $this StockadjustmentsController */
/* @var $data Stockadjustments */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('regnum')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->regnum), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('idatetime')); ?>:</b>
	<?php echo CHtml::encode($data->idatetime); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('itembatch')); ?>:</b>
	<?php echo CHtml::encode($data->itembatch); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('oldamount')); ?>:</b>
	<?php echo CHtml::encode(number_format($data->oldamount)); ?>
	<br />
	
	<b><?php echo CHtml::encode($data->getAttributeLabel('amount')); ?>:</b>
	<?php echo CHtml::encode(number_format($data->amount)); ?>
	<br />
	
	<b><?php echo CHtml::encode($data->getAttributeLabel('kind')); ?>:</b>
	<?php echo CHtml::encode(lookup::getAdjustmentType($data->kind)); ?>
	<br />
	
	<b><?php echo CHtml::encode($data->getAttributeLabel('userlog')); ?>:</b>
	<?php echo CHtml::encode(lookup::UserNameFromUserID($data->userlog)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('datetimelog')); ?>:</b>
	<?php echo CHtml::encode($data->datetimelog); ?>
	<br />

</div>
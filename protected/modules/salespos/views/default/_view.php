<?php
/* @var $this SalesposcardsController */
/* @var $data Salesposcards */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('name')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->name), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('idbank')); ?>:</b>
	<?php echo CHtml::encode($data->idbank); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('company')); ?>:</b>
	<?php echo CHtml::encode($data->company); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('surchargeamount')); ?>:</b>
	<?php echo CHtml::encode($data->surchargeamount); ?>
	<br />
	
	<b><?php echo CHtml::encode($data->getAttributeLabel('surchargepct')); ?>:</b>
	<?php echo CHtml::encode($data->surchargepct); ?>
	<br />
	
	<b><?php echo CHtml::encode($data->getAttributeLabel('userlog')); ?>:</b>
	<?php echo CHtml::encode(lookup::UserNameFromUserID($data->userlog)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('datetimelog')); ?>:</b>
	<?php echo CHtml::encode($data->datetimelog); ?>
	<br />


</div>
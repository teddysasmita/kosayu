<?php
/* @var $this SalespostransfersController */
/* @var $data Salespostransfers */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('holdername')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->holdername), array('view', 'id'=>$data->id)); ?>
	<br />
	
	<b><?php echo CHtml::encode($data->getAttributeLabel('acctno')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->acctno), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('userlog')); ?>:</b>
	<?php echo CHtml::encode(lookup::UserNameFromUserID($data->userlog)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('datetimelog')); ?>:</b>
	<?php echo CHtml::encode($data->datetimelog); ?>
	<br />


</div>
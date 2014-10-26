<?php
/* @var $this SalesposbanksController */
/* @var $data Salesposbanks */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('productname')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->productname), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('userlog')); ?>:</b>
	<?php echo CHtml::encode(lookup::UserNameFromUserID($data->userlog)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('datetimelog')); ?>:</b>
	<?php echo CHtml::encode($data->datetimelog); ?>
	<br />


</div>
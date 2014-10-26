<?php
/* @var $this RequestdisplaysController */
/* @var $data Requestdisplays */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('regnum')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->regnum), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('idatetime')); ?>:</b>
	<?php echo CHtml::encode($data->idatetime); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('idsales')); ?>:</b>
	<?php echo CHtml::encode(lookup::SalesNameFromID($data->idsales)); ?>
	<br />

</div>
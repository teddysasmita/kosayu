<?php
/* @var $this InputinventorytakingsController */
/* @var $data Inputinventorytakings */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('regnum')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->regnum), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('idatetime')); ?>:</b>
	<?php echo CHtml::encode($data->idatetime); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('idinventorytaking')); ?>:</b>
	<?php echo CHtml::encode($data->idinventorytaking); ?>
	<br />

</div>
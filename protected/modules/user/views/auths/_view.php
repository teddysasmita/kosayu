<?php
/* @var $this AuthsController */
/* @var $data Auths */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('iduser')); ?>:</b>
	<?php echo CHtml::encode($data->iduser); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('idpriv')); ?>:</b>
	<?php echo CHtml::encode($data->idpriv); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('seclevel')); ?>:</b>
	<?php echo CHtml::encode($data->seclevel); ?>
	<br />


</div>
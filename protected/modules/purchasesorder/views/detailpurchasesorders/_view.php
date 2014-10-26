<?php
/* @var $this DetailpurchasesordersController */
/* @var $data Detailpurchasesorders */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('iddetail')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->iddetail), array('view', 'id'=>$data->iddetail)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::encode($data->id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('iditem')); ?>:</b>
	<?php echo CHtml::encode($data->iditem); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('idunit')); ?>:</b>
	<?php echo CHtml::encode($data->idunit); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('qty')); ?>:</b>
	<?php echo CHtml::encode(number_format($data->qty)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('discount')); ?>:</b>
	<?php echo CHtml::encode(number_format($data->discount)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('price')); ?>:</b>
	<?php echo CHtml::encode(number_format($data->price)); ?>
	<br />
   
   <b><?php echo CHtml::encode($data->getAttributeLabel('cost1')); ?>:</b>
	<?php echo CHtml::encode(number_format($data->cost1)); ?>
	<br />
   
   <b><?php echo CHtml::encode($data->getAttributeLabel('cost2')); ?>:</b>
	<?php echo CHtml::encode(number_format($data->cost2)); ?>
	<br />

	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('userlog')); ?>:</b>
	<?php echo CHtml::encode($data->userlog); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('datetimelog')); ?>:</b>
	<?php echo CHtml::encode($data->datetimelog); ?>
	<br />

	*/ ?>

</div>
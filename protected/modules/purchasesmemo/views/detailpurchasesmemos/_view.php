<?php
/* @var $this DetailpurchasesmemosController */
/* @var $data Detailpurchasesmemos */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('iddetail')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->iddetail), array('view', 'id'=>$data->iddetail)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::encode($data->id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('iditem')); ?>:</b>
	<?php echo CHtml::encode(lookup::ItemNameFromItemID($data->iditem)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('qty')); ?>:</b>
	<?php echo CHtml::encode($data->qty); ?>
	<br />
   
   <b><?php echo CHtml::encode($data->getAttributeLabel('prevprice')); ?>:</b>
	<?php echo CHtml::encode(number_format($data->prevprice)); ?>
	<br />
   
   <b><?php echo CHtml::encode($data->getAttributeLabel('price')); ?>:</b>
	<?php echo CHtml::encode(number_format($data->price)); ?>
	<br />
   
   <b><?php echo CHtml::encode($data->getAttributeLabel('prevprice')); ?>:</b>
	<?php echo CHtml::encode(number_format($data->prevprice)); ?>
	<br />
   
   <b><?php echo CHtml::encode($data->getAttributeLabel('price')); ?>:</b>
	<?php echo CHtml::encode(number_format($data->price)); ?>
	<br />
   
   <b><?php echo CHtml::encode($data->getAttributeLabel('prevcost1')); ?>:</b>
	<?php echo CHtml::encode(number_format($data->prevcost1)); ?>
	<br />
   
   <b><?php echo CHtml::encode($data->getAttributeLabel('cost1')); ?>:</b>
	<?php echo CHtml::encode(number_format($data->cost1)); ?>
	<br />
   
   <b><?php echo CHtml::encode($data->getAttributeLabel('prevcost2')); ?>:</b>
	<?php echo CHtml::encode(number_format($data->prevcost2)); ?>
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
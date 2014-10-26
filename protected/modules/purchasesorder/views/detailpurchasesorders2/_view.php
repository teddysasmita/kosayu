<?php
/* @var $this Detailpurchasesorders2Controller */
/* @var $data Detailpurchasesorders2 */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('iddetail')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->iddetail), array('view', 'id'=>$data->iddetail)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::encode($data->id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('vouchername')); ?>:</b>
	<?php echo CHtml::encode($data->vouchername); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('vouchervalue')); ?>:</b>
	<?php echo CHtml::encode($data->vouchervalue); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('userlog')); ?>:</b>
	<?php echo CHtml::encode($data->userlog); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('datetimelog')); ?>:</b>
	<?php echo CHtml::encode($data->datetimelog); ?>
	<br />


</div>
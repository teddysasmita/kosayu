<?php
/* @var $this SalesordersController */
/* @var $data Salesorders */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('idatetime')); ?>:</b>
	<?php echo CHtml::encode($data->idatetime); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('idcustomer')); ?>:</b>
	<?php
         $sql="select concat(firstname,' ', lastname) as name from customers where id='$data->idcustomer'";
         echo CHtml::encode(Yii::app()->db->createCommand($sql)->queryScalar()); 
      ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('total')); ?>:</b>
	<?php echo CHtml::encode(number_format($data->total)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('discount')); ?>:</b>
	<?php echo CHtml::encode(number_format($data->discount)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('status')); ?>:</b>
	<?php echo CHtml::encode($data->status); ?>
	<br />


</div>
<?php
/* @var $this StockentriesController */
/* @var $data Stockentries */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('regnum')); ?>:</b>
	<?php echo CHtml::encode($data->regnum); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('idatetime')); ?>:</b>
	<?php echo CHtml::encode($data->idatetime); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('idsupplier')); ?>:</b>
	<?php
      $sql="select concat(firstname,' ', lastname) as name from suppliers where id='$data->idsupplier'";
      echo CHtml::encode(Yii::app()->db->createCommand($sql)->queryScalar()); 
      ?>
      <br />

</div>
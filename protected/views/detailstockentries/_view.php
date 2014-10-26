<?php
/* @var $this DetailstockentriesController */
/* @var $data Detailstockentries */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('iditem')); ?>:</b>
	<?php
         $sql="select name from items where id='$data->iditem'";
         echo CHtml::encode(Yii::app()->db->createCommand($sql)->queryScalar()); 
      ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('qty')); ?>:</b>
	<?php echo CHtml::encode($data->qty); ?>
	<br />


</div>
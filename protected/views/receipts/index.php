<?php
/* @var $this ReceiptsController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Receipts',
);

$this->menu=array(
	array('label'=>'Create Receipts', 'url'=>array('create')),
	array('label'=>'Manage Receipts', 'url'=>array('admin')),
);
?>

<h1>Receipts</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>

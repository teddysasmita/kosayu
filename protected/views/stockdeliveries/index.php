<?php
/* @var $this StockdeliveriesController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Stockdeliveries',
);

$this->menu=array(
	array('label'=>'Create Stockdeliveries', 'url'=>array('create')),
	array('label'=>'Manage Stockdeliveries', 'url'=>array('admin')),
);
?>

<h1>Stockdeliveries</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>

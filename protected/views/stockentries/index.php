<?php
/* @var $this StockentriesController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Stockentries',
);

$this->menu=array(
	array('label'=>'Create Stockentries', 'url'=>array('create')),
	array('label'=>'Manage Stockentries', 'url'=>array('admin')),
);
?>

<h1>Stockentries</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>

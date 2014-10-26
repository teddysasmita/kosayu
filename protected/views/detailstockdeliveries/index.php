<?php
/* @var $this DetailstockdeliveriesController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Detailstockdeliveries',
);

$this->menu=array(
	array('label'=>'Create Detailstockdeliveries', 'url'=>array('create')),
	array('label'=>'Manage Detailstockdeliveries', 'url'=>array('admin')),
);
?>

<h1>Detailstockdeliveries</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>

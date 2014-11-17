<?php
/* @var $this DetailconsignedordersController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Detailconsignedorders',
);

$this->menu=array(
	array('label'=>'Create Detailconsignedorders', 'url'=>array('create')),
	array('label'=>'Manage Detailconsignedorders', 'url'=>array('admin')),
);
?>

<h1>Detailconsignedorders</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>

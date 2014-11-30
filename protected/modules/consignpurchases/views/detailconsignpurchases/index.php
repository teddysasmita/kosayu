<?php
/* @var $this DetailconsignpurchasesController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Detailconsignpurchases',
);

$this->menu=array(
	array('label'=>'Create Detailconsignpurchases', 'url'=>array('create')),
	array('label'=>'Manage Detailconsignpurchases', 'url'=>array('admin')),
);
?>

<h1>Detailconsignpurchases</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>

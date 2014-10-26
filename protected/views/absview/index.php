<?php
/* @var $this SalesordersController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	//'Salesorders',
);

$this->menu=array(
	array('label'=>'Create Salesorders', 'url'=>array('create')),
	array('label'=>'Manage Salesorders', 'url'=>array('admin')),
);
?>

<h1>Salesorders</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>

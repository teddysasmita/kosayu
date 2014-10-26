<?php
/* @var $this DetailitemsController */
/* @var $model Detailitems */

$this->breadcrumbs=array(
	'Detailitems'=>array('index'),
	$model->iddetail,
);

$this->menu=array(
	array('label'=>'List Detailitems', 'url'=>array('index')),
	array('label'=>'Create Detailitems', 'url'=>array('create')),
	array('label'=>'Update Detailitems', 'url'=>array('update', 'id'=>$model->iddetail)),
	array('label'=>'Delete Detailitems', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->iddetail),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Detailitems', 'url'=>array('admin')),
);
?>

<h1>View Detailitems #<?php echo $model->iddetail; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'iddetail',
		'id',
		'iditem',
		'qty',
	),
)); ?>

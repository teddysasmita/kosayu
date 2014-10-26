<?php
/* @var $this DetailstockdeliveriesController */
/* @var $model Detailstockdeliveries */

$this->breadcrumbs=array(
	'View Stockdeliveries '.$model->id=>array('stockdeliveries/view', 'id'=>$model->id),
      'Update Stockdeliveries '.$model->id=>array('stockdeliveries/update', 'id'=>$model->id),
	$model->iddetail,
);

$this->menu=array(
	array('label'=>'List Detailstockdeliveries', 'url'=>array('index')),
	array('label'=>'Create Detailstockdeliveries', 'url'=>array('create')),
	array('label'=>'Update Detailstockdeliveries', 'url'=>array('update', 'id'=>$model->iddetail)),
	array('label'=>'Delete Detailstockdeliveries', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->iddetail),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Detailstockdeliveries', 'url'=>array('admin')),
);
?>

<h1>View Detailstockdeliveries #<?php echo $model->iddetail; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'iddetail',
		'id',
		'iditem',
		'qty',
	),
)); ?>

<?php
/* @var $this DetailstockentriesController */
/* @var $model Detailstockentries */

$this->breadcrumbs=array(
	'View Stockentries '.$model->id=>array('stockentries/view', 'id'=>$model->id),
      'Update Stockentries '.$model->id=>array('stockentries/update', 'id'=>$model->id),
	$model->iddetail,
);

$this->menu=array(
	array('label'=>'List Detailstockentries', 'url'=>array('index')),
	array('label'=>'Create Detailstockentries', 'url'=>array('create')),
	array('label'=>'Update Detailstockentries', 'url'=>array('update', 'id'=>$model->iddetail)),
	array('label'=>'Delete Detailstockentries', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->iddetail),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Detailstockentries', 'url'=>array('admin')),
);
?>

<h1>View Detailstockentries #<?php echo $model->iddetail; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'iddetail',
		'id',
		'iditem',
		'qty',
	),
)); ?>

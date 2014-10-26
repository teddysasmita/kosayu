<?php
/* @var $this PrivsController */
/* @var $model Privs */

$this->breadcrumbs=array(
	'Privs'=>array('index'),
	$model->name,
);

$this->menu=array(
	array('label'=>'List Privs', 'url'=>array('index')),
	array('label'=>'Create Privs', 'url'=>array('create')),
	array('label'=>'Update Privs', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete Privs', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Privs', 'url'=>array('admin')),
);
?>

<h1>View Privs #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'name',
		'idapp',
	),
)); ?>

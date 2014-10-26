<?php
/* @var $this UserhostsController */
/* @var $model Userhosts */

$this->breadcrumbs=array(
	'Userhosts'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List Userhosts', 'url'=>array('index')),
	array('label'=>'Create Userhosts', 'url'=>array('create')),
	array('label'=>'Update Userhosts', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete Userhosts', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Userhosts', 'url'=>array('admin')),
        array('label'=>'History', 'url'=>array('history', 'id'=>$model->id)),
);
?>

<h1>View Userhosts #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'iduser',
		'allowedip',
		'userlog',
		'datetimelog',
	),
)); ?>

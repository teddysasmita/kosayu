<?php
/* @var $this PrivsController */
/* @var $model Privs */

$this->breadcrumbs=array(
	'Privs'=>array('index'),
	$model->name=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List Privs', 'url'=>array('index')),
	array('label'=>'Create Privs', 'url'=>array('create')),
	array('label'=>'View Privs', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage Privs', 'url'=>array('admin')),
);
?>

<h1>Update Privs <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
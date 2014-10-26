<?php
/* @var $this PrivsController */
/* @var $model Privs */

$this->breadcrumbs=array(
	'Privs'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Privs', 'url'=>array('index')),
	array('label'=>'Manage Privs', 'url'=>array('admin')),
);
?>

<h1>Create Privs</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
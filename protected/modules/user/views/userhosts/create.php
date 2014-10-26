<?php
/* @var $this UserhostsController */
/* @var $model Userhosts */

$this->breadcrumbs=array(
	'Userhosts'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Userhosts', 'url'=>array('index')),
	array('label'=>'Manage Userhosts', 'url'=>array('admin')),
);
?>

<h1>Create Userhosts</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>
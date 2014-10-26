<?php
/* @var $this DetailitemsController */
/* @var $model Detailitems */

$this->breadcrumbs=array(
	'Detailitems'=>array('index'),
	$model->iddetail=>array('view','id'=>$model->iddetail),
	'Update',
);

$this->menu=array(
	array('label'=>'List Detailitems', 'url'=>array('index')),
	array('label'=>'Create Detailitems', 'url'=>array('create')),
	array('label'=>'View Detailitems', 'url'=>array('view', 'id'=>$model->iddetail)),
	array('label'=>'Manage Detailitems', 'url'=>array('admin')),
);
?>

<h1>Update Detailitems <?php echo $model->iddetail; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model, 'mode'=>'Update')); ?>
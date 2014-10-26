<?php
/* @var $this UserhostsController */
/* @var $model Userhosts */

$this->breadcrumbs=array(
	'Userhosts'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List Userhosts', 'url'=>array('index')),
	array('label'=>'Create Userhosts', 'url'=>array('create')),
	array('label'=>'View Userhosts', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage Userhosts', 'url'=>array('admin')),
);
?>

<h1>Update Userhosts <?php echo $model->id; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>
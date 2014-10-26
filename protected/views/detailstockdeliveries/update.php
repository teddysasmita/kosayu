<?php
/* @var $this DetailstockdeliveriesController */
/* @var $model Detailstockdeliveries */

$this->breadcrumbs=array(
	 'View Stockdeliveries '.$model->id=>array('stockdeliveries/view', 'id'=>$model->id),
      'Update Stockdeliveries '.$model->id=>array('stockdeliveries/update', 'id'=>$model->id),
	'Update',
);


$this->menu=array(
	array('label'=>'List Detailstockdeliveries', 'url'=>array('index')),
	array('label'=>'Create Detailstockdeliveries', 'url'=>array('create')),
	array('label'=>'View Detailstockdeliveries', 'url'=>array('view', 'id'=>$model->iddetail)),
	array('label'=>'Manage Detailstockdeliveries', 'url'=>array('admin')),
);
?>

<h1>Update Detailstockdeliveries <?php echo $model->iddetail; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>
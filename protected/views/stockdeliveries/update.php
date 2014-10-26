<?php
/* @var $this StockdeliveriesController */
/* @var $model Stockdeliveries */

$this->breadcrumbs=array(
	'Stockdeliveries'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	/*
       array('label'=>'List Stockdeliveries', 'url'=>array('index')),
	array('label'=>'Create Stockdeliveries', 'url'=>array('create')),
	array('label'=>'View Stockdeliveries', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage Stockdeliveries', 'url'=>array('admin')),
       */
    array('label'=>'Add Detail', 'url'=>array('detailstockdeliveries/create', 'id'=>$model->id, 
      'command'=>'update'),
          'linkOptions'=>array('id'=>'adddetail')),
);
?>

<h1>Update Stockdeliveries <?php echo $model->id; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model, 'command'=>'update')); ?>
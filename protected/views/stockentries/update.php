<?php
/* @var $this StockentriesController */
/* @var $model Stockentries */

$this->breadcrumbs=array(
	'Stockentries'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	/*
       array('label'=>'List Stockentries', 'url'=>array('index')),
	array('label'=>'Create Stockentries', 'url'=>array('create')),
	array('label'=>'View Stockentries', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage Stockentries', 'url'=>array('admin')),
      */
    
    array('label'=>'Add Detail', 'url'=>array('detailstockentries/create', 'id'=>$model->id, 
      'command'=>'update'),
          'linkOptions'=>array('id'=>'adddetail')),
);
?>

<h1>Update Stockentries <?php echo $model->id; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model, 'command'=>'update')); ?>
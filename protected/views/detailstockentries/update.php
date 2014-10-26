<?php
/* @var $this DetailstockentriesController */
/* @var $model Detailstockentries */

$this->breadcrumbs=array(
	'View Stockentries '.$model->id=>array('stockentries/view', 'id'=>$model->id),
      'Update Stockentries '.$model->id=>array('stockentries/update', 'id'=>$model->id),
	'Update',
);


$this->menu=array(
	array('label'=>'List Detailstockentries', 'url'=>array('index')),
	array('label'=>'Create Detailstockentries', 'url'=>array('create')),
	array('label'=>'View Detailstockentries', 'url'=>array('view', 'id'=>$model->iddetail)),
	array('label'=>'Manage Detailstockentries', 'url'=>array('admin')),
);
?>

<h1>Update Detailstockentries <?php echo $model->iddetail; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model, 'mode'=>'Update')); ?>
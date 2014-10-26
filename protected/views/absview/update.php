<?php
/* @var $this SalesordersController */
/* @var $model Salesorders */

$this->breadcrumbs=array(
	'Salesorders'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	//array('label'=>'List Salesorders', 'url'=>array('index')),
	//array('label'=>'Create Salesorders', 'url'=>array('create')),
	//array('label'=>'View Salesorders', 'url'=>array('view', 'id'=>$model->id)),
	//array('label'=>'Manage Salesorders', 'url'=>array('admin')),
    array('label'=>'Add Detail', 'url'=>array('detailsalesorders/create', 'id'=>$model->id, 
      'command'=>'update'),
          'linkOptions'=>array('id'=>'adddetail')),     
);
?>

<h1>Update Salesorders <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model, 'command'=>'update')); ?>
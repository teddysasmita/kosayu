<?php
/* @var $this DetailsalesinvoicesController */
/* @var $model Detailsalesinvoices */

$this->breadcrumbs=array(
	'View Salesinvoices '.$model->id=>array('salesinvoices/view', 'id'=>$model->id),
      'Update Salesinvoices '.$model->id=>array('salesinvoices/update', 'id'=>$model->id),
	'Update',
);


$this->menu=array(
	array('label'=>'List Detailsalesinvoices', 'url'=>array('index')),
	array('label'=>'Create Detailsalesinvoices', 'url'=>array('create')),
	array('label'=>'View Detailsalesinvoices', 'url'=>array('view', 'id'=>$model->iddetail)),
	array('label'=>'Manage Detailsalesinvoices', 'url'=>array('admin')),
);
?>

<h1>Update Detailsalesinvoices <?php echo $model->iddetail; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model, 'mode'=>'Update')); ?>
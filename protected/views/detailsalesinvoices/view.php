<?php
/* @var $this DetailsalesinvoicesController */
/* @var $model Detailsalesinvoices */

$this->breadcrumbs=array(
	'View Salesinvoices '.$model->id=>array('salesinvoices/view', 'id'=>$model->id),
      'Update Salesinvoices '.$model->id=>array('salesinvoices/update', 'id'=>$model->id),
	$model->iddetail,
);

$this->menu=array(
	array('label'=>'List Detailsalesinvoices', 'url'=>array('index')),
	array('label'=>'Create Detailsalesinvoices', 'url'=>array('create')),
	array('label'=>'Update Detailsalesinvoices', 'url'=>array('update', 'id'=>$model->iddetail)),
	array('label'=>'Delete Detailsalesinvoices', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','iddetail'=>$model->iddetail),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Detailsalesinvoices', 'url'=>array('admin')),
);
?>

<h1>View Detailsalesinvoices #<?php echo $model->iddetail; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'iddetail',
		'id',
		'iditem',
		'qty',
		'discount',
		'price',
	),
)); ?>

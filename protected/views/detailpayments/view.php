<?php
/* @var $this DetailpaymentsController */
/* @var $model Detailpayments */

$this->breadcrumbs=array(
      'View Receipts '.$model->id=>array('receipts/view', 'id'=>$model->id),
      'Update Receipts '.$model->id=>array('receipts/update', 'id'=>$model->id),
	$model->iddetail,
);

$this->menu=array(
	array('label'=>'List Detailpayments', 'url'=>array('index')),
	array('label'=>'Create Detailpayments', 'url'=>array('create')),
	array('label'=>'Update Detailpayments', 'url'=>array('update', 'id'=>$model->iddetail)),
	array('label'=>'Delete Detailpayments', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->iddetail),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Detailpayments', 'url'=>array('admin')),
);
?>

<h1>View Detailpayments #<?php echo $model->iddetail; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'iddetail',
		'id',
		'discount',
		'price',
		'idinvoice',
	),
)); ?>

<?php
/* @var $this DetailreceiptsController */
/* @var $model Detailreceipts */

$this->breadcrumbs=array(
	'View Receipts '.$model->id=>array('receipts/view', 'id'=>$model->id),
      'Update Receipts '.$model->id=>array('receipts/update', 'id'=>$model->id),
	$model->iddetail,
);

$this->menu=array(
	array('label'=>'List Detailreceipts', 'url'=>array('index')),
	array('label'=>'Create Detailreceipts', 'url'=>array('create')),
	array('label'=>'Update Detailreceipts', 'url'=>array('update', 'id'=>$model->iddetail)),
	array('label'=>'Delete Detailreceipts', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->iddetail),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Detailreceipts', 'url'=>array('admin')),
);
?>

<h1>View Detailreceipts #<?php echo $model->iddetail; ?></h1>

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

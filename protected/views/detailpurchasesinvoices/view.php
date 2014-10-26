<?php
/* @var $this DetailpurchasesinvoicesController */
/* @var $model Detailpurchasesinvoices */

$this->breadcrumbs=array(
      'View Purchasesinvoices '.$model->id=>array('purchasesinvoices/view', 'id'=>$model->id),
      'Update Purchasesinvoices '.$model->id=>array('purchasesinvoices/update', 'id'=>$model->id),
	$model->iddetail
);

$this->menu=array(
	array('label'=>'List Detailpurchasesinvoices', 'url'=>array('index')),
	array('label'=>'Create Detailpurchasesinvoices', 'url'=>array('create')),
	array('label'=>'Update Detailpurchasesinvoices', 'url'=>array('update', 'id'=>$model->iddetail)),
	array('label'=>'Delete Detailpurchasesinvoices', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->iddetail),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Detailpurchasesinvoices', 'url'=>array('admin')),
);
?>

<h1>View Detailpurchasesinvoices #<?php echo $model->iddetail; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'iddetail',
		'id',
		'iditem',
		'qty',
		'discount',
		'price',
		'idorder',
		'iddetailorder',
	),
)); ?>

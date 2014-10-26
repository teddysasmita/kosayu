<?php
/* @var $this PurchasesinvoicesController */
/* @var $model Purchasesinvoices */

$this->breadcrumbs=array(
	'Purchasesinvoices'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List Purchasesinvoices', 'url'=>array('index')),
	array('label'=>'Create Purchasesinvoices', 'url'=>array('create')),
	array('label'=>'Update Purchasesinvoices', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete Purchasesinvoices', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Purchasesinvoices', 'url'=>array('admin')),
);
?>

<h1>View Purchasesinvoices #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'idatetime',
            array(
                'label'=>'Pelanggan',
                'value'=>lookup::SupplierNameFromSupplierID($model->idsupplier)
            ),
		array(
                'label'=>'Total',
                'type'=>'number',
                'name'=>'total'
            ),
            array(
                'label'=>'Diskon',
                'type'=>'number',
                'name'=>'discount'
            ),
		'status',
	),
)); ?>

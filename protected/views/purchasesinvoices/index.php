<?php
/* @var $this PurchasesinvoicesController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Purchasesinvoices',
);

$this->menu=array(
	array('label'=>'Create Purchasesinvoices', 'url'=>array('create')),
	array('label'=>'Manage Purchasesinvoices', 'url'=>array('admin')),
);
?>

<h1>Purchasesinvoices</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>

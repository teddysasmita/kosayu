<?php
/* @var $this DetailpurchasesinvoicesController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Detailpurchasesinvoices',
);

$this->menu=array(
	array('label'=>'Create Detailpurchasesinvoices', 'url'=>array('create')),
	array('label'=>'Manage Detailpurchasesinvoices', 'url'=>array('admin')),
);
?>

<h1>Detailpurchasesinvoices</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>

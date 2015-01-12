<?php
/* @var $this DetailpurchasespaymentsController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Detailpurchasespayments',
);

$this->menu=array(
	array('label'=>'Create Detailpurchasespayments', 'url'=>array('create')),
	array('label'=>'Manage Detailpurchasespayments', 'url'=>array('admin')),
);
?>

<h1>Detailpurchasespayments</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>

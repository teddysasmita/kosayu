<?php
/* @var $this DetailpaymentsController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Detailpayments',
);

$this->menu=array(
	array('label'=>'Create Detailpayments', 'url'=>array('create')),
	array('label'=>'Manage Detailpayments', 'url'=>array('admin')),
);
?>

<h1>Detailpayments</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>

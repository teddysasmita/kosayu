<?php
/* @var $this DetailpurchasesreceiptsController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Detailpurchasesreceipts',
);

$this->menu=array(
	array('label'=>'Create Detailpurchasesreceipts', 'url'=>array('create')),
	array('label'=>'Manage Detailpurchasesreceipts', 'url'=>array('admin')),
);
?>

<h1>Detailpurchasesreceipts</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>

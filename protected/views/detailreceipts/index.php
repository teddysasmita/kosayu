<?php
/* @var $this DetailreceiptsController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Detailreceipts',
);

$this->menu=array(
	array('label'=>'Create Detailreceipts', 'url'=>array('create')),
	array('label'=>'Manage Detailreceipts', 'url'=>array('admin')),
);
?>

<h1>Detailreceipts</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>

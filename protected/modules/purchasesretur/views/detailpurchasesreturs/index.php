<?php
/* @var $this DetailpurchasesretursController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Detailpurchasesreturs',
);

$this->menu=array(
	array('label'=>'Create Detailpurchasesreturs', 'url'=>array('create')),
	array('label'=>'Manage Detailpurchasesreturs', 'url'=>array('admin')),
);
?>

<h1>Detailpurchasesreturs</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>

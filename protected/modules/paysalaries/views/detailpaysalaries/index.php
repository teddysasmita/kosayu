<?php
/* @var $this DetailpurchasesController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Detailpurchases',
);

$this->menu=array(
	array('label'=>'Create Detailpurchases', 'url'=>array('create')),
	array('label'=>'Manage Detailpurchases', 'url'=>array('admin')),
);
?>

<h1>Detailpurchases</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>

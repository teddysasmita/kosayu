<?php
/* @var $this DetailpurchasesordersController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Detailpurchasesorders',
);

$this->menu=array(
	array('label'=>'Create Detailpurchasesorders', 'url'=>array('create')),
	array('label'=>'Manage Detailpurchasesorders', 'url'=>array('admin')),
);
?>

<h1>Detailpurchasesorders</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>

<?php
/* @var $this DetaildeliveryordersController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Detaildeliveryorders',
);

$this->menu=array(
	array('label'=>'Create Detaildeliveryorders', 'url'=>array('create')),
	array('label'=>'Manage Detaildeliveryorders', 'url'=>array('admin')),
);
?>

<h1>Detil Pengiriman Barang</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>

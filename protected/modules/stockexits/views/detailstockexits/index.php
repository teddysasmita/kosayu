<?php
/* @var $this DetailstockexitsController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Detailstockexits',
);

$this->menu=array(
	array('label'=>'Create Detailstockexits', 'url'=>array('create')),
	array('label'=>'Manage Detailstockexits', 'url'=>array('admin')),
);
?>

<h1>Pengeluaran Barang</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>

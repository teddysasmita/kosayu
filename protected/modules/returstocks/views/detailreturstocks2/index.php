<?php
/* @var $this Detailreturstocks2Controller */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Detailreturstocks2',
);

$this->menu=array(
	array('label'=>'Create Detailreturstocks2', 'url'=>array('create')),
	array('label'=>'Manage Detailreturstocks2', 'url'=>array('admin')),
);
?>

<h1>Pengembalian Barang ke Pemasok</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>

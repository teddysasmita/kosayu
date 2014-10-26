<?php
/* @var $this DetailsalesretursController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Daftar'=>array('index'),
);

$this->menu=array(
	array('label'=>'Tambah Data', 'url'=>array('create')),
	array('label'=>'Pencarian Data', 'url'=>array('admin')),
);
?>

<h1>Detil Retur Penjualan</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>

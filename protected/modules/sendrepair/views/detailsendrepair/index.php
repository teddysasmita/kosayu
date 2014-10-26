<?php
/* @var $this DetailsendrepairsController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Proses'=>array('/site/proses'),
	'Daftar',
);

$this->menu=array(
	array('label'=>'Tambah Data', 'url'=>array('create')),
	array('label'=>'Pencarian Data', 'url'=>array('admin')),
);
?>

<h1>Detil Pengiriman Barang untuk Perbaikan</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>

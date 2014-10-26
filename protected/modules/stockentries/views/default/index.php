<?php
/* @var $this StockentriesController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
   'Proses'=>array('/site/proses'),
	'Daftar',
);

$this->menu=array(
   array('label'=>'Tambah Data', 'url'=>array('create')),
   array('label'=>'Pencarian Data', 'url'=>array('admin')),
   array('label'=>'Data yang telah dihapus', 'url'=>array('deleted')),
	array('label'=>'Nomor Seri Barang Masuk', 'url'=>array('serial')),
	array('label'=>'Nomor Seri Barang dari Scan dgn Laptop', 'url'=>array('serialScan')),
);
?>

<h1>Penerimaan Barang</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>

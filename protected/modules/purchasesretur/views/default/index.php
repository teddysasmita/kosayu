<?php
/* @var $this PurchasesretursController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
   'Proses'=>array('/site/proses'),
	'Daftar',
);

$this->menu=array(
   array('label'=>'Tambah Data', 'url'=>array('create')),
   array('label'=>'Pencarian Data', 'url'=>array('admin')),
   array('label'=>'Data yang telah dihapus', 'url'=>array('deleted')),
	array('label'=>'Laporan', 'url'=>array('getreport')),
);
?>

<h1>Retur Barang ke Pemasok</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>

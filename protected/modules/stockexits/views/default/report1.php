<?php
/* @var $this SalesposreportController */
/* @var $model Salesposreport */

$this->breadcrumbs=array(
	'Proses'=>array('/site/proses'),
	'Daftar'=>array('index'),
	'Tambah Data',
);

$this->menu=array(
	//array('label'=>'Pencarian Data', 'url'=>array('admin')),
);
?>

<h1>Laporan Pengeluaran Barang</h1>

<?php $this->renderPartial('_formreport1'); ?>
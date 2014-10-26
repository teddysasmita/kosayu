<?php
/* @var $this SalesposreportController */
/* @var $model Salesposreport */

$this->breadcrumbs=array(
	'Master Data'=>array('/site/masterdata'),
	'Daftar'=>array('index'),
	'Tambah Data',
);

$this->menu=array(
	//array('label'=>'Pencarian Data', 'url'=>array('admin')),
);
?>

<h1>Laporan Penjualan</h1>

<?php $this->renderPartial('_form'); ?>
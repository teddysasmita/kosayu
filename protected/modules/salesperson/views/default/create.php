<?php
/* @var $this SalespersonsController */
/* @var $model Salespersons */

$this->breadcrumbs=array(
	'Daftar'=>array('index'),
	'Tambah Data',
);

$this->menu=array(
	array('label'=>'Pencarian Data', 'url'=>array('admin')),
);
?>

<h1>Master Data Tenaga Penjualan</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>
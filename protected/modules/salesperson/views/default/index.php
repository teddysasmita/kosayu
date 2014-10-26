<?php
/* @var $this SalespersonsController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Daftar',
);

$this->menu=array(
	array('label'=>'Tambah Data', 'url'=>array('create')),
	array('label'=>'Pencarian Data', 'url'=>array('admin')),
);
?>

<h1>Master Data Tenaga Penjualan</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>

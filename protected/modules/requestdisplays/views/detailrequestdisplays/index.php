<?php
/* @var $this DetailrequestdisplaysController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Daftar'=>array('index'),
);

$this->menu=array(
	array('label'=>'Tambah Data', 'url'=>array('create')),
	array('label'=>'Pencarian Data', 'url'=>array('admin')),
);
?>

<h1>Detil Permintaan Barang Display</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>

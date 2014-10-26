<?php
/* @var $this DetailitemtransfersController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Daftar'=>array('index'),
);

$this->menu=array(
	array('label'=>'Tambah Data', 'url'=>array('create')),
	array('label'=>'Pencarian Data', 'url'=>array('admin')),
);
?>

<h1>Detil Pemindahan Barang</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>

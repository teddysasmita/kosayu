<?php
/* @var $this WarehousesController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Master Data'=>array('/site/masterdata'),
	'Daftar'
);

$this->menu=array(
	array('label'=>'Tambah Data', 'url'=>array('create')),
	array('label'=>'Pencarian Data', 'url'=>array('admin')),
);
?>

<h1>Gudang</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>

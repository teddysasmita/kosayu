<?php
/* @var $this SuppliersController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Master Data'=>array('/site/masterdata'),
	'Daftar'
);

$this->menu=array(
	array('label'=>'Tambah Data', 'url'=>array('create')),
	array('label'=>'Pencarian Data', 'url'=>array('admin')),
   array('label'=>'Data-data yang telah terhapus', 'url'=>array('deleted')),
);
?>

<h1>Pemasok</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>

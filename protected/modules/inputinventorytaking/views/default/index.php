<?php
/* @var $this InputinventorytakingsController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
   'Proses'=>array('/site/proses'),
	'Daftar',
);

$this->menu=array(
	array('label'=>'Tambah Data', 'url'=>array('create')),
	array('label'=>'Pencarian Data', 'url'=>array('admin')),
    array('label'=>'Data-data yang telah terhapus', 'url'=>array('deleted')),
	array('label'=>'Lihat Aktifitas User', 'url'=>array('viewuser')),
	array('label'=>'Pencarian Detil Data', 'url'=>array('detailinputinventorytakings/admin')),
        
);
?>

<h1>Input Stok Opname</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>

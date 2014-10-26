<?php
/* @var $this InventorycostingsController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
    'Master Data'=>array('/site/proses'),
    'Daftar',
);

$this->menu=array(
	array('label'=>'Tambah Data', 'url'=>array('create')),
	array('label'=>'Cari Data', 'url'=>array('admin')),
   array('label'=>'Data-data yang telah terhapus', 'url'=>array('deleted')),
   
);
?>

<h1>Penentuan Harga Pokok Opname</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>

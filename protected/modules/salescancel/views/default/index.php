<?php
/* @var $this SalescancelController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
    'Proses'=>array('/site/proses'),
    'Daftar',
);

$this->menu=array(
	array('label'=>'Tambah Data', 'url'=>array('create')),
	array('label'=>'Cari Data', 'url'=>array('admin')),
   array('label'=>'Terhapus', 'url'=>array('deleted')),
   
);
?>

<h1>Pembatalan Penjualan</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>

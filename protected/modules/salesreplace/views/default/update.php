<?php
/* @var $this SalesreplaceController */
/* @var $model Salesreplace */

$this->breadcrumbs=array(
    'Proses'=>array('/site/proses'),
    'Daftar Pembatalan Penjualan'=>array('index'),
    'Lihat Data'=>array('view','id'=>$model->id),
    'Ubah Data',
);

$this->menu=array(
	array('label'=>'Tambah Data', 'url'=>array('create')),
	array('label'=>'Lihat Data', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Cari Data', 'url'=>array('admin')),
);
?>

<h1>Ganti Barang Penjualan</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model, 'command'=>'update')); ?>
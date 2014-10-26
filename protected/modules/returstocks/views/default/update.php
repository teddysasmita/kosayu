<?php
/* @var $this ReturstocksController */
/* @var $model Returstocks */

$this->breadcrumbs=array(
      'Proses'=>array('/site/proses'),
	'Daftar'=>array('index'),
	'Lihat Data'=>array('view','id'=>$model->id),
	'Ubah Data',
);

$this->menu=array(
	/*
	array('label'=>'List Returstocks', 'url'=>array('index')),
	array('label'=>'Create Returstocks', 'url'=>array('create')),
	array('label'=>'View Returstocks', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage Returstocks', 'url'=>array('admin')),
    */
	array('label'=>'Tambah Detil', 'url'=>array('detailreturstocks/create', 
       'id'=>$model->id, 'command'=>'update', 'regnum'=>$model->regnum),
          'linkOptions'=>array('id'=>'adddetail')),
);
?>

<h1>Pengembalian Barang ke Pemasok</h1>

<?php $this->renderPartial('_form', array('model'=>$model, 'command'=>'update')); ?>
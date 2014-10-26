<?php
/* @var $this SendrepairsController */
/* @var $model Sendrepairs */

$this->breadcrumbs=array(
      'Proses'=>array('/site/proses'),
	'Daftar'=>array('index'),
	'Lihat Data'=>array('view','id'=>$model->id),
	'Ubah Data',
);

$this->menu=array(
	/*
	array('label'=>'List Sendrepairs', 'url'=>array('index')),
	array('label'=>'Create Sendrepairs', 'url'=>array('create')),
	array('label'=>'View Sendrepairs', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage Sendrepairs', 'url'=>array('admin')),
    */
	array('label'=>'Tambah Detil', 'url'=>array('detailsendrepairs/create', 
       'id'=>$model->id, 'command'=>'update', 'regnum'=>$model->regnum),
          'linkOptions'=>array('id'=>'adddetail')),
);
?>

<h1>Pengiriman Barang untuk Perbaikan</h1>

<?php $this->renderPartial('_form', array('model'=>$model, 'command'=>'update', 'allitems'=>$allitems)); ?>
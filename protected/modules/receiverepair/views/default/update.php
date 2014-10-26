<?php
/* @var $this ReceiverepairsController */
/* @var $model Receiverepairs */

$this->breadcrumbs=array(
      'Proses'=>array('/site/proses'),
	'Daftar'=>array('index'),
	'Lihat Data'=>array('view','id'=>$model->id),
	'Ubah Data',
);

$this->menu=array(
	/*
	array('label'=>'List Receiverepairs', 'url'=>array('index')),
	array('label'=>'Create Receiverepairs', 'url'=>array('create')),
	array('label'=>'View Receiverepairs', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage Receiverepairs', 'url'=>array('admin')),
    */
	array('label'=>'Tambah Detil', 'url'=>array('detailreceiverepairs/create', 
       'id'=>$model->id, 'command'=>'update', 'regnum'=>$model->regnum),
          'linkOptions'=>array('id'=>'adddetail')),
);
?>

<h1>Penerimaan Barang dari Perbaikan</h1>

<?php $this->renderPartial('_form', array('model'=>$model, 'command'=>'update', 'allitems'=>$allitems)); ?>
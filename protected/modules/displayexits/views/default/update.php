<?php
/* @var $this DisplayentriesController */
/* @var $model Displayentries */

$this->breadcrumbs=array(
      'Proses'=>array('/site/proses'),
	'Daftar'=>array('index'),
	'Lihat Data'=>array('view','id'=>$model->id),
	'Ubah Data',
);

$this->menu=array(
	//array('label'=>'List Displayentries', 'url'=>array('index')),
	//array('label'=>'Create Displayentries', 'url'=>array('create')),
	//array('label'=>'View Displayentries', 'url'=>array('view', 'id'=>$model->id)),
	//array('label'=>'Manage Displayentries', 'url'=>array('admin')),
    array('label'=>'Tambah Detil', 'url'=>array('detaildisplayentries/create', 
       'id'=>$model->id, 'command'=>'update', 'regnum'=>$model->regnum),
          'linkOptions'=>array('id'=>'adddetail')), 
);
?>

<h1>Barang Masuk Display</h1>

<?php $this->renderPartial('_form', array('model'=>$model, 'command'=>'update')); ?>
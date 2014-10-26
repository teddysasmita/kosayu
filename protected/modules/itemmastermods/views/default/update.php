<?php
/* @var $this RetrievalreplacesController */
/* @var $model Retrievalreplaces */

$this->breadcrumbs=array(
      'Proses'=>array('/site/proses'),
	'Daftar'=>array('index'),
	'Lihat Data'=>array('view','id'=>$model->id),
	'Ubah Data',
);

$this->menu=array(
	//array('label'=>'List Retrievalreplaces', 'url'=>array('index')),
	//array('label'=>'Create Retrievalreplaces', 'url'=>array('create')),
	//array('label'=>'View Retrievalreplaces', 'url'=>array('view', 'id'=>$model->id)),
	//array('label'=>'Manage Retrievalreplaces', 'url'=>array('admin')),
    array('label'=>'Tambah Detil', 'url'=>array('detailretrievalreplaces/create', 
       'id'=>$model->id, 'command'=>'update', 'regnum'=>$model->regnum),
          'linkOptions'=>array('id'=>'adddetail')), 
);
?>

<h1>Penukaran Pengambilan Barang</h1>

<?php $this->renderPartial('_form', array('model'=>$model, 'command'=>'update', 'error'=>$error)); ?>
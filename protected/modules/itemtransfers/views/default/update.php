<?php
/* @var $this ItemtransfersController */
/* @var $model Itemtransfers */

$this->breadcrumbs=array(
      'Proses'=>array('/site/proses'),
	'Daftar'=>array('index'),
	'Lihat Data'=>array('view','id'=>$model->id),
	'Ubah Data',
);

$this->menu=array(
	//array('label'=>'List Itemtransfers', 'url'=>array('index')),
	//array('label'=>'Create Itemtransfers', 'url'=>array('create')),
	//array('label'=>'View Itemtransfers', 'url'=>array('view', 'id'=>$model->id)),
	//array('label'=>'Manage Itemtransfers', 'url'=>array('admin')),
    array('label'=>'Tambah Detil', 'url'=>array('detailitemtransfers/create', 
       'id'=>$model->id, 'idwhsource'=>$model->idwhsource, 'command'=>'update', 'regnum'=>$model->regnum),
          'linkOptions'=>array('id'=>'adddetail')), 
);
?>

<h1>Pemindahan Barang</h1>

<?php $this->renderPartial('_form', array('model'=>$model, 'command'=>'update')); ?>
<?php
/* @var $this PurchasesstockentriesController */
/* @var $model Purchasesstockentries */

$this->breadcrumbs=array(
      'Proses'=>array('/site/proses'),
	'Daftar'=>array('index'),
	'Lihat Data'=>array('view','id'=>$model->id),
	'Ubah Data',
);

$this->menu=array(
	/*
	array('label'=>'List Purchasesstockentries', 'url'=>array('index')),
	array('label'=>'Create Purchasesstockentries', 'url'=>array('create')),
	array('label'=>'View Purchasesstockentries', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage Purchasesstockentries', 'url'=>array('admin')),
    */
	array('label'=>'Tambah Detil', 'url'=>array('detailpurchasesstockentries/create', 
       'id'=>$model->id, 'command'=>'update', 'regnum'=>$model->regnum),
          'linkOptions'=>array('id'=>'adddetail')),
);
?>

<h1>Penerimaan Barang</h1>

<?php $this->renderPartial('_form', array('model'=>$model, 'command'=>'update')); ?>
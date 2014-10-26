<?php
/* @var $this PurchasesreceiptsController */
/* @var $model Purchasesreceipts */

$this->breadcrumbs=array(
      'Proses'=>array('/site/proses'),
	'Daftar'=>array('index'),
	'Lihat Data'=>array('view','id'=>$model->id),
	'Ubah Data',
);

$this->menu=array(
	/*
	array('label'=>'List Purchasesreceipts', 'url'=>array('index')),
	array('label'=>'Create Purchasesreceipts', 'url'=>array('create')),
	array('label'=>'View Purchasesreceipts', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage Purchasesreceipts', 'url'=>array('admin')),
    array('label'=>'Tambah Detil', 'url'=>array('detailpurchasesreceipts/create', 
       'id'=>$model->id, 'command'=>'update', 'regnum'=>$model->regnum),
          'linkOptions'=>array('id'=>'adddetail')),
    */       
);
?>

<h1>Penerimaan Barang dari Pemasok</h1>

<?php $this->renderPartial('_form', array('model'=>$model, 'command'=>'update')); ?>
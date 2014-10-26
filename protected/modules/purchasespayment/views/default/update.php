<?php
/* @var $this PurchasespaymentsController */
/* @var $model Purchasespayments */

$this->breadcrumbs=array(
      'Proses'=>array('/site/proses'),
	'Daftar'=>array('index'),
	'Lihat Data'=>array('view','id'=>$model->id),
	'Ubah Data',
);

$this->menu=array(
	/*array('label'=>'List Purchasespayments', 'url'=>array('index')),
	array('label'=>'Create Purchasespayments', 'url'=>array('create')),
	array('label'=>'View Purchasespayments', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage Purchasespayments', 'url'=>array('admin')),
   array('label'=>'Tambah Detil', 'url'=>array('detailstockentries/create', 
      'id'=>$model->id, 'command'=>'update', 'regnum'=>$model->regnum),
      'linkOptions'=>array('id'=>'adddetail')), 
    */
);
?>

<h1>Pembayaran pada Pemasok</h1>

<?php $this->renderPartial('_form', array('model'=>$model, 'command'=>'update')); ?>
<?php
/* @var $this ConsignpaymentsController */
/* @var $model Consignpayments */

$this->breadcrumbs=array(
      'Proses'=>array('/site/proses'),
	'Daftar'=>array('index'),
	'Lihat Data'=>array('view','id'=>$model->id),
	'Ubah Data',
);

$this->menu=array(
	/*array('label'=>'List Consignpayments', 'url'=>array('index')),
	array('label'=>'Create Consignpayments', 'url'=>array('create')),
	array('label'=>'View Consignpayments', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage Consignpayments', 'url'=>array('admin')),
   array('label'=>'Tambah Detil', 'url'=>array('detailstockentries/create', 
      'id'=>$model->id, 'command'=>'update', 'regnum'=>$model->regnum),
      'linkOptions'=>array('id'=>'adddetail')), 
    */
);
?>

<h1>Pembayaran Konsinyasi</h1>

<?php $this->renderPartial('_form', array('model'=>$model, 'command'=>'update')); ?>
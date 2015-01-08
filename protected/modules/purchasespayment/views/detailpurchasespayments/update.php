<?php
/* @var $this DetailpurchasespaymentsController */
/* @var $model Detailpurchasespayments */

$master=Yii::app()->session['master'];
if($master=='create')
   $this->breadcrumbs=array(
      'Proses'=>array('/site/proses'),
      'Daftar'=>array('default/index'),
      'Tambah Data'=>array('default/create'),
      'Ubah Detil'); 
else if ($master=='update')
   $this->breadcrumbs=array(
      'Proses'=>array('/site/proses'),
      'Daftar'=>array('default/index'),
      'Lihat Data'=>array('default/view','id'=>$model->id),
      'Ubah Data'=>array('default/update','id'=>$model->id),
      'Ubah Detil');

$this->menu=array(
	//array('label'=>'List Detailpurchasespayments', 'url'=>array('index')),
	//array('label'=>'Create Detailpurchasespayments', 'url'=>array('create')),
	//array('label'=>'View Detailpurchasespayments', 'url'=>array('view', 'id'=>$model->iddetail)),
	//array('label'=>'Manage Detailpurchasespayments', 'url'=>array('admin')), 
);
?>

<h1>Detil Pembayaran Pada Pemasok</h1>

<?php $this->renderPartial('_form', array('model'=>$model, 'mode'=>'Update')); ?>
<?php
/* @var $this DetailconsignpurchasesController */
/* @var $model Detailconsignpurchases */

$master=Yii::app()->session['master'];
if($master=='create')
   $this->breadcrumbs=array(
      'Proses'=>array('/site/proses'),
      'Daftar'=>array('default/index'),
      'Tambah Data'=>array('default/create','id'=>$model->id),
      'Ubah Detil'); 
else if ($master=='update')
   $this->breadcrumbs=array(
      'Proses'=>array('/site/proses'),
      'Daftar'=>array('default/index'),
      'Lihat Data'=>array('default/view','id'=>$model->id),
      'Ubah Data'=>array('default/update','id'=>$model->id),
      'Ubah Detil');

$this->menu=array(
	//array('label'=>'List Detailconsignpurchases', 'url'=>array('index')),
	//array('label'=>'Create Detailconsignpurchases', 'url'=>array('create')),
	//array('label'=>'View Detailconsignpurchases', 'url'=>array('view', 'id'=>$model->iddetail)),
	//array('label'=>'Manage Detailconsignpurchases', 'url'=>array('admin')), 
);
?>

<h1>Pembelian Konsinyasi dari Pemasok</h1>

<?php $this->renderPartial('_form', array('model'=>$model, 'mode'=>'Update')); ?>
<?php
/* @var $this DetaildeliveryordersController */
/* @var $model Detaildeliveryorders */

$master=Yii::app()->session['master'];
if($master=='create')
   $this->breadcrumbs=array(
      'Proses'=>array('/site/proses'),
      'Daftar'=>array('default/index'),
      'Tambah Data'=>array('default/create','id'=>$model->id),
      'Tambah Detil'); 
else if ($master=='update')
   $this->breadcrumbs=array(
      'Proses'=>array('/site/proses'),
      'Ubah Data'=>array('default/update','id'=>$model->id),
      'Daftar'=>array('default/index'),
      'Tambah Detil');

/*$this->menu=array(
	array('label'=>'List Detaildeliveryorders', 'url'=>array('index')),
	array('label'=>'Manage Detaildeliveryorders', 'url'=>array('admin')),
);*/
?>

<h1>Detil Pengiriman Barang </h1>

<?php $this->renderPartial('_form', array('model'=>$model, 'mode'=>'Create', 'error'=>$error)); ?>
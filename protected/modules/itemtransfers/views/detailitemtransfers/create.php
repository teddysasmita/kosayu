<?php
/* @var $this DetailitemtransfersController */
/* @var $model Detailitemtransfers */

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
	array('label'=>'List Detailitemtransfers', 'url'=>array('index')),
	array('label'=>'Manage Detailitemtransfers', 'url'=>array('admin')),
);*/
?>

<h1>Detil Pemindahan Barang </h1>

<?php $this->renderPartial('_form', array('model'=>$model, 'mode'=>'Create', 'error'=>$error)); ?>
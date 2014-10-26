<?php
/* @var $this DetaildeliveryordersController */
/* @var $model Detaildeliveryorders */

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
	//array('label'=>'List Detaildeliveryorders', 'url'=>array('index')),
	//array('label'=>'Create Detaildeliveryorders', 'url'=>array('create')),
	//array('label'=>'View Detaildeliveryorders', 'url'=>array('view', 'id'=>$model->iddetail)),
	//array('label'=>'Manage Detaildeliveryorders', 'url'=>array('admin')), 
);
?>

<h1>Detil Pengiriman Barang </h1>

<?php $this->renderPartial('_form', array('model'=>$model, 'mode'=>'Update')); ?>
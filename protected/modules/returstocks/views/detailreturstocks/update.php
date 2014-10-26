<?php
/* @var $this DetailreturstocksController */
/* @var $model Detailreturstocks */

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
	//array('label'=>'List Detailreturstocks', 'url'=>array('index')),
	//array('label'=>'Create Detailreturstocks', 'url'=>array('create')),
	//array('label'=>'View Detailreturstocks', 'url'=>array('view', 'id'=>$model->iddetail)),
	//array('label'=>'Manage Detailreturstocks', 'url'=>array('admin')), 
);
?>

<h1>Detil Pengembalian Barang ke Pemasok</h1>

<?php $this->renderPartial('_form', array('model'=>$model, 'mode'=>'Update')); ?>
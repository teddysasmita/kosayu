<?php
/* @var $this DetailstockdamageController */
/* @var $model Detailstockdamage */

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
	//array('label'=>'List Detailstockdamage', 'url'=>array('index')),
	//array('label'=>'Create Detailstockdamage', 'url'=>array('create')),
	//array('label'=>'View Detailstockdamage', 'url'=>array('view', 'id'=>$model->iddetail)),
	//array('label'=>'Manage Detailstockdamage', 'url'=>array('admin')), 
);
?>

<h1>Detil Barang Rusak</h1>

<?php $this->renderPartial('_form', array('model'=>$model, 'idwh'=>$idwh, 'mode'=>'Update')); ?>
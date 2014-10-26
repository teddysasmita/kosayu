<?php
/* @var $this DetailacquisitionsController */
/* @var $model Detailacquisitions */

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
	//array('label'=>'List Detailacquisitions', 'url'=>array('index')),
	//array('label'=>'Create Detailacquisitions', 'url'=>array('create')),
	//array('label'=>'View Detailacquisitions', 'url'=>array('view', 'id'=>$model->iddetail)),
	//array('label'=>'Manage Detailacquisitions', 'url'=>array('admin')), 
);
?>

<h1>Detil Akuisisi Barang dan Nomor Seri</h1>

<?php $this->renderPartial('_form', array('model'=>$model, 'mode'=>'Update')); ?>
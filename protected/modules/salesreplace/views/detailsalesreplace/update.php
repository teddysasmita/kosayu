<?php
/* @var $this DetailsalesreplaceController */
/* @var $model Detailsalesreplace */

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
	//array('label'=>'List Detailsalesreplace', 'url'=>array('index')),
	//array('label'=>'Create Detailsalesreplace', 'url'=>array('create')),
	//array('label'=>'View Detailsalesreplace', 'url'=>array('view', 'id'=>$model->iddetail)),
	//array('label'=>'Manage Detailsalesreplace', 'url'=>array('admin')), 
);
?>

<h1>Detil Perubahan Penjualan</h1>

<?php $this->renderPartial('_form', array('model'=>$model, 'mode'=>'Update')); ?>
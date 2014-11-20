<?php
/* @var $this DetailitemtipgroupsController */
/* @var $model Detailitemtipgroups */

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
	//array('label'=>'List Detailitemtipgroups', 'url'=>array('index')),
	//array('label'=>'Create Detailitemtipgroups', 'url'=>array('create')),
	//array('label'=>'View Detailitemtipgroups', 'url'=>array('view', 'id'=>$model->iddetail)),
	//array('label'=>'Manage Detailitemtipgroups', 'url'=>array('admin')), 
);
?>

<h1>Kelompok Komisi Barang</h1>

<?php $this->renderPartial('_form', array('model'=>$model, 'mode'=>'Update')); ?>
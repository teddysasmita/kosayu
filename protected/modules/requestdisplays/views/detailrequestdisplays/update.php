<?php
/* @var $this DetailrequestdisplaysController */
/* @var $model Detailrequestdisplays */

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
	//array('label'=>'List Detailrequestdisplays', 'url'=>array('index')),
	//array('label'=>'Create Detailrequestdisplays', 'url'=>array('create')),
	//array('label'=>'View Detailrequestdisplays', 'url'=>array('view', 'id'=>$model->iddetail)),
	//array('label'=>'Manage Detailrequestdisplays', 'url'=>array('admin')), 
);
?>

<h1>Detil Permintaan Barang Display</h1>

<?php $this->renderPartial('_form', array('model'=>$model, 'mode'=>'Update', 'error'=>$error)); ?>
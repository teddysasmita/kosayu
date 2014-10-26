<?php
/* @var $this DetailstockentriesController */
/* @var $model Detailstockentries */

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
	//array('label'=>'List Detailstockentries', 'url'=>array('index')),
	//array('label'=>'Create Detailstockentries', 'url'=>array('create')),
	//array('label'=>'View Detailstockentries', 'url'=>array('view', 'id'=>$model->iddetail)),
	//array('label'=>'Manage Detailstockentries', 'url'=>array('admin')), 
);
?>

<h1>Penerimaan Barang dari Pemasok</h1>

<?php $this->renderPartial('_form', array('model'=>$model, 'mode'=>'Update', 'idwh'=>$idwh, 
		'transname'=>$transname, 'transid'=>$transid, 'error'=>$error)); ?>
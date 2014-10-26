<?php
/* @var $this DetailpurchasesretursController */
/* @var $model Detailpurchasesreturs */

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
      'Lihat Data'=>array('view','id'=>$model->id),
      'Ubah Data'=>array('default/update','id'=>$model->id),
      'Ubah Detil');

$this->menu=array(
	//array('label'=>'List Detailpurchasesreturs', 'url'=>array('index')),
	//array('label'=>'Create Detailpurchasesreturs', 'url'=>array('create')),
	//array('label'=>'View Detailpurchasesreturs', 'url'=>array('view', 'id'=>$model->iddetail)),
	//array('label'=>'Manage Detailpurchasesreturs', 'url'=>array('admin')), 
);
?>

<h1>Detil Retur Pembelian</h1>

<?php $this->renderPartial('_form', array('model'=>$model, 'mode'=>'Update')); ?>
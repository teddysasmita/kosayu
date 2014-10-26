<?php
/* @var $this DetailpurchasesreceiptsController */
/* @var $model Detailpurchasesreceipts */

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
	//array('label'=>'List Detailpurchasesreceipts', 'url'=>array('index')),
	//array('label'=>'Create Detailpurchasesreceipts', 'url'=>array('create')),
	//array('label'=>'View Detailpurchasesreceipts', 'url'=>array('view', 'id'=>$model->iddetail)),
	//array('label'=>'Manage Detailpurchasesreceipts', 'url'=>array('admin')), 
);
?>

<h1>Penerimaan Barang dari Pemasok</h1>

<?php $this->renderPartial('_form', array('model'=>$model, 'mode'=>'Update')); ?>
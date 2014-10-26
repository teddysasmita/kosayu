<?php
/* @var $this PurchasesretursController */
/* @var $model Purchasesreturs */

$this->breadcrumbs=array(
      'Proses'=>array('/site/proses'),
	'Daftar'=>array('index'),
	'Lihat Data'=>array('view','id'=>$model->id),
	'Ubah Data',
);

$this->menu=array(
	/*array('label'=>'List Purchasesreturs', 'url'=>array('index')),
	array('label'=>'Create Purchasesreturs', 'url'=>array('create')),
	array('label'=>'View Purchasesreturs', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage Purchasesreturs', 'url'=>array('admin')),
   array('label'=>'Tambah Detil', 'url'=>array('detailstockentries/create', 
      'id'=>$model->id, 'command'=>'update', 'regnum'=>$model->regnum),
      'linkOptions'=>array('id'=>'adddetail')), 
    */
);
?>

<h1>Retur Pembelian</h1>

<?php $this->renderPartial('_form', array('model'=>$model, 'command'=>'update')); ?>
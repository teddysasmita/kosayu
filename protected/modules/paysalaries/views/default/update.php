<?php
/* @var $this PurchasesController */
/* @var $model Purchases */

$this->breadcrumbs=array(
      'Proses'=>array('/site/proses'),
	'Daftar'=>array('index'),
	'Lihat Data'=>array('view','id'=>$model->id),
	'Ubah Data',
);

$this->menu=array(
	//array('label'=>'List Purchases', 'url'=>array('index')),
	//array('label'=>'Create Purchases', 'url'=>array('create')),
	//array('label'=>'View Purchases', 'url'=>array('view', 'id'=>$model->id)),
	//array('label'=>'Manage Purchases', 'url'=>array('admin')),
    array('label'=>'Tambah Detil', 'url'=>array('detailpurchases/create', 
       'id'=>$model->id, 'command'=>'update', 'regnum'=>$model->regnum),
          'linkOptions'=>array('id'=>'adddetail')), 
   array('label'=>'Tambah Detil2', 'url'=>array('detailpurchases2/create', 
      'id'=>$model->id, 'command'=>'update', 'regnum'=>$model->regnum),
          'linkOptions'=>array('id'=>'adddetail2')) 
);
?>

<h1>Pembelian dari Pemasok</h1>

<?php $this->renderPartial('_form', array('model'=>$model, 'command'=>'update')); ?>
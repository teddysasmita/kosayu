<?php
/* @var $this ConsignconsignpurchasesController */
/* @var $model Consignconsignpurchases */

$this->breadcrumbs=array(
      'Proses'=>array('/site/proses'),
	'Daftar'=>array('index'),
	'Lihat Data'=>array('view','id'=>$model->id),
	'Ubah Data',
);

$this->menu=array(
	//array('label'=>'List Consignconsignpurchases', 'url'=>array('index')),
	//array('label'=>'Create Consignconsignpurchases', 'url'=>array('create')),
	//array('label'=>'View Consignconsignpurchases', 'url'=>array('view', 'id'=>$model->id)),
	//array('label'=>'Manage Consignconsignpurchases', 'url'=>array('admin')),
    array('label'=>'Tambah Detil', 'url'=>array('detailconsignpurchases/create', 
       'id'=>$model->id, 'command'=>'update', 'regnum'=>$model->regnum),
          'linkOptions'=>array('id'=>'adddetail')), 
);
?>

<h1>Pembelian Konsinyasi dari Pemasok</h1>

<?php $this->renderPartial('_form', array('model'=>$model, 'command'=>'update')); ?>
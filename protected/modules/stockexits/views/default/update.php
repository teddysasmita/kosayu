<?php
/* @var $this StockexitsController */
/* @var $model Stockexits */

$this->breadcrumbs=array(
      'Proses'=>array('/site/proses'),
	'Daftar'=>array('index'),
	'Lihat Data'=>array('view','id'=>$model->id),
	'Ubah Data',
);

$this->menu=array(
	//array('label'=>'List Stockexits', 'url'=>array('index')),
	//array('label'=>'Create Stockexits', 'url'=>array('create')),
	//array('label'=>'View Stockexits', 'url'=>array('view', 'id'=>$model->id)),
	//array('label'=>'Manage Stockexits', 'url'=>array('admin')),
    array('label'=>'Tambah Detil', 'url'=>array('detailstockexits/create', 
       'id'=>$model->id, 'command'=>'update', 'regnum'=>$model->regnum),
          'linkOptions'=>array('id'=>'adddetail')), 
);
?>

<h1>Pengeluaran Barang</h1>

<?php $this->renderPartial('_form', array('model'=>$model, 'command'=>'update')); ?>